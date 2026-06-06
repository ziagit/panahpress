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
        return $query;
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

    public function imageUrl(int $index = 0): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/'.$this->image);
        }

        return asset('images/home/placeholder-01.svg');
    }
}
