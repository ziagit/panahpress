<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function current(
        string $locale = 'en',
        ?float $latitude = null,
        ?float $longitude = null,
        ?string $label = null,
        ?string $timezone = null
    ): array
    {
        $cacheKey = 'weather.current.'.implode('.', [
            $locale,
            $latitude !== null ? round($latitude, 3) : 'default',
            $longitude !== null ? round($longitude, 3) : 'default',
        ]);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($locale, $latitude, $longitude, $label, $timezone) {
            return $this->fetchCurrent($locale, $latitude, $longitude, $label, $timezone);
        });
    }

    private function fetchCurrent(
        string $locale,
        ?float $latitude = null,
        ?float $longitude = null,
        ?string $label = null,
        ?string $timezone = null
    ): array
    {
        $config = config('services.weather', []);
        $city = $label ?: ($config['city'] ?? 'Sindh');
        $latitude = $latitude ?? (float) ($config['latitude'] ?? 24.8607);
        $longitude = $longitude ?? (float) ($config['longitude'] ?? 67.0011);
        $timezone = $timezone ?? ($config['timezone'] ?? 'Asia/Karachi');
        $endpoint = $config['endpoint'] ?? 'https://api.open-meteo.com/v1/forecast';

        try {
            $response = Http::timeout(5)
                ->retry(2, 200)
                ->acceptJson()
                ->get($endpoint, [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'current' => 'temperature_2m,weather_code',
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min',
                    'timezone' => $timezone,
                    'forecast_days' => 3,
                    'temperature_unit' => 'celsius',
                ]);

            if ($response->successful()) {
                return $this->normalizeResponse($response->json(), $city, $timezone, $locale);
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return $this->fallbackResponse($city, $timezone, $locale);
    }

    private function normalizeResponse(array $payload, string $city, string $timezone, string $locale): array
    {
        $currentTemp = data_get($payload, 'current.temperature_2m');
        $currentCode = data_get($payload, 'current.weather_code', data_get($payload, 'current.weathercode'));
        $currentTime = data_get($payload, 'current.time');
        $dailyDates = data_get($payload, 'daily.time', []);
        $dailyHighs = data_get($payload, 'daily.temperature_2m_max', []);
        $dailyLows = data_get($payload, 'daily.temperature_2m_min', []);
        $dailyCodes = data_get($payload, 'daily.weather_code', []);

        $days = [];
        foreach ([0, 1] as $index) {
            if (! isset($dailyDates[$index])) {
                continue;
            }

            $dayDate = CarbonImmutable::parse($dailyDates[$index], $timezone)->locale($locale);

            $days[] = [
                'label' => $dayDate->translatedFormat('D'),
                'high' => $this->formatTemperature(data_get($dailyHighs, $index)),
                'low' => $this->formatTemperature(data_get($dailyLows, $index)),
                'icon' => $this->iconForCode((int) data_get($dailyCodes, $index, $currentCode)),
            ];
        }

        return [
            'city' => $city,
            'temperature' => $this->formatTemperature($currentTemp),
            'icon' => $this->iconForCode((int) $currentCode),
            'days' => $days,
            'updated_at' => $currentTime ? CarbonImmutable::parse($currentTime, $timezone)->locale($locale)->translatedFormat('M d, H:i') : null,
            'source' => 'Open-Meteo',
        ];
    }

    private function fallbackResponse(string $city, string $timezone, string $locale): array
    {
        $now = CarbonImmutable::now($timezone)->locale($locale);
        $tomorrow = $now->addDay();
        $dayAfter = $now->addDays(2);

        return [
            'city' => $city,
            'temperature' => '+31°C',
            'icon' => '☀',
            'days' => [
                [
                    'label' => $tomorrow->translatedFormat('D'),
                    'high' => '+38°C',
                    'low' => '28°C',
                    'icon' => '☀',
                ],
                [
                    'label' => $dayAfter->translatedFormat('D'),
                    'high' => '+35°C',
                    'low' => '29°C',
                    'icon' => '☀',
                ],
            ],
            'updated_at' => $now->translatedFormat('M d, H:i'),
            'source' => 'Fallback',
        ];
    }

    private function formatTemperature(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        $rounded = round((float) $value);
        $prefix = $rounded > 0 ? '+' : '';

        return $prefix.$rounded.'°C';
    }

    private function iconForCode(int $code): string
    {
        return match (true) {
            in_array($code, [0], true) => '☀',
            in_array($code, [1, 2], true) => '⛅',
            in_array($code, [3], true) => '☁',
            in_array($code, [45, 48], true) => '🌫',
            in_array($code, [51, 53, 55, 56, 57, 61, 63, 65, 80, 81, 82], true) => '🌧',
            in_array($code, [71, 73, 75, 77, 85, 86], true) => '❄',
            in_array($code, [95, 96, 99], true) => '⛈',
            default => '☀',
        };
    }
}
