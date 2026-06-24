<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title_en', 'title_fa', 'content_en', 'content_fa', 'slug', 'published_at', 'user_id', 'category_id', 'image', 'video_url'])]
class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lessThanOrEqualTo(now());
    }

    public function isPendingApproval(): bool
    {
        return $this->published_at === null;
    }

    public function isScheduled(): bool
    {
        return $this->published_at !== null && $this->published_at->greaterThan(now());
    }

    public function canBeEditedByAuthor(): bool
    {
        if (! $this->created_at) {
            return true;
        }

        return now()->lt($this->created_at->copy()->addDay());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function title(string $locale = null): string
    {
        $locale = $locale ?: AppFacade::getLocale();

        return $this->{'title_'.$locale} ?: $this->title_en;
    }

    public function content(string $locale = null): string
    {
        $locale = $locale ?: AppFacade::getLocale();

        return $this->{'content_'.$locale} ?: $this->content_en;
    }

    public function plainContent(string $locale = null): string
    {
        $content = $this->content($locale) ?: '';

        return html_entity_decode(trim(strip_tags($content)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function videoEmbedUrl(string $locale = null): ?string
    {
        $url = $this->normalizeVideoSource((string) ($this->video_url ?: ''));

        if ($url === '') {
            $url = $this->extractVideoSourceFromContent($this->content($locale) ?: '');
        }

        if ($this->isYoutubeVideoSource($locale)) {
            return null;
        }

        if ($url === '') {
            return null;
        }

        return $url;
    }

    public function isYoutubeVideoSource(string $locale = null): bool
    {
        $source = trim((string) ($this->video_url ?: ''));

        if ($source === '') {
            $source = $this->extractVideoSourceFromContent($this->content($locale) ?: '');
        }

        return (bool) preg_match('#(?:youtube\.com|youtu\.be)#i', $source);
    }

    public function videoWatchUrl(string $locale = null): ?string
    {
        $source = trim((string) ($this->video_url ?: ''));

        if ($source === '') {
            $source = $this->extractVideoSourceFromContent($this->content($locale) ?: '');
        }

        $videoId = $this->extractYoutubeId($source);

        return $videoId ? 'https://www.youtube.com/watch?v='.$videoId : ($source ?: null);
    }

    public function videoThumbnailUrl(string $locale = null): ?string
    {
        $source = trim((string) ($this->video_url ?: ''));

        if ($source === '') {
            $source = $this->extractVideoSourceFromContent($this->content($locale) ?: '');
        }

        $videoId = $this->extractYoutubeId($source);

        if ($videoId) {
            return 'https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg';
        }

        $vimeoId = $this->extractVimeoId($source);

        return $vimeoId ? 'https://vumbnail.com/'.$vimeoId.'.jpg' : null;
    }

    public function renderedContent(string $locale = null): string
    {
        $content = $this->content($locale) ?: '';

        if ($content === '') {
            return '';
        }

        if ($this->isYoutubeVideoSource($locale)) {
            return preg_replace(
                '/<iframe\b[^>]*src=(["\'])(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)[^>]*>.*?<\/iframe>/is',
                '',
                $content
            ) ?: $content;
        }

        return preg_replace_callback(
            '/<iframe\b([^>]*?)src=(["\'])([^"\']+)\2([^>]*)>(.*?)<\/iframe>/is',
            function (array $matches): string {
                $src = $this->normalizeVideoSource($matches[3]);

                if ($src === '') {
                    return $matches[0];
                }

                if ($this->isVideoSource($src)) {
                    return '';
                }

                return '<iframe'.$matches[1].'src="'.$src.'"'.$matches[4].'>'.$matches[5].'</iframe>';
            },
            $content
        ) ?: $content;
    }

    protected function extractVideoSourceFromContent(string $content): string
    {
        if ($content === '') {
            return '';
        }

        if (preg_match('/<iframe\b[^>]*src=(["\'])([^"\']+)\1/i', $content, $matches)) {
            $normalized = $this->normalizeVideoSource($matches[2]);

            if ($this->isVideoSource($normalized)) {
                return $normalized;
            }
        }

        if (preg_match('/https?:\/\/[^\s"<>\']+/i', html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8'), $matches)) {
            $normalized = $this->normalizeVideoSource($matches[0]);

            if ($this->isVideoSource($normalized)) {
                return $normalized;
            }
        }

        return '';
    }

    protected function isVideoSource(string $value): bool
    {
        return str_contains($value, 'youtube.com/embed/')
            || str_contains($value, 'youtube-nocookie.com/embed/')
            || str_contains($value, 'player.vimeo.com/video/');
    }

    protected function normalizeVideoSource(string $value): string
    {
        $value = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if ($value === '') {
            return '';
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $value, $matches)) {
            $value = $matches[1];
        }

        if (preg_match('/<a[^>]+href=["\']([^"\']+)["\']/i', $value, $matches)) {
            $value = $matches[1];
        }

        if (str_contains($value, 'youtube.com') || str_contains($value, 'youtu.be')) {
            $videoId = $this->extractYoutubeId($value);

            if ($videoId) {
                return 'https://www.youtube-nocookie.com/embed/'.$videoId.'?rel=0&modestbranding=1';
            }

            if (str_contains($value, '/embed/')) {
                return $value;
            }
        }

        if (str_contains($value, 'vimeo.com/')) {
            if (str_contains($value, '/video/')) {
                return preg_replace('#vimeo\.com/video/#', 'player.vimeo.com/video/', $value) ?: $value;
            }

            $path = trim((string) parse_url($value, PHP_URL_PATH), '/');
            $id = basename($path);

            return $id ? 'https://player.vimeo.com/video/'.$id : $value;
        }

        return $value;
    }

    protected function extractYoutubeId(string $value): ?string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('/[?&]v=([A-Za-z0-9_-]{6,})/i', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('#youtu\.be/([A-Za-z0-9_-]{6,})#i', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('#youtube\.com/embed/([A-Za-z0-9_-]{6,})#i', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('#youtube\.com/shorts/([A-Za-z0-9_-]{6,})#i', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('#youtube\.com/live/([A-Za-z0-9_-]{6,})#i', $value, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function extractVimeoId(string $value): ?string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('#vimeo\.com/(?:video/)?([0-9]+)#i', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('#player\.vimeo\.com/video/([0-9]+)#i', $value, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function imageUrl(int $index = 0): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/'.$this->image);
        }

        return asset('images/home/placeholder-01.svg');
    }
}
