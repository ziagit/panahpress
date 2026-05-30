<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as AppFacade;

class WeatherController extends Controller
{
    public function show(Request $request, WeatherService $weatherService): JsonResponse
    {
        $locale = $request->route('locale') ?: app()->getLocale() ?: 'en';
        AppFacade::setLocale($locale);

        $latitude = $request->filled('lat') ? (float) $request->query('lat') : null;
        $longitude = $request->filled('lon') ? (float) $request->query('lon') : null;
        $timezone = $request->query('timezone');
        $label = ($latitude !== null && $longitude !== null)
            ? __('messages.current_location')
            : null;

        return response()
            ->json([
                'data' => $weatherService->current($locale, $latitude, $longitude, $label, $timezone),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
