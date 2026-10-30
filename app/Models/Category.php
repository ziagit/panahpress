<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App as AppFacade;

#[Fillable(['parent_id', 'name_en', 'name_fa', 'slug', 'is_active', 'sort_order'])]
class Category extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_en');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /** Include active descendants while guarding against cyclic category data. */
    public function subtreeIds(?\Illuminate\Support\Collection $categories = null): array
    {
        $categories ??= static::active()->get();
        $ids = [$this->id];
        do {
            $previous = count($ids);
            foreach ($categories as $category) {
                if (in_array($category->parent_id, $ids) && ! in_array($category->id, $ids)) {
                    $ids[] = $category->id;
                }
            }
        } while (count($ids) > $previous);

        return $ids;
    }

    public function name(string $locale = null): string
    {
        $locale = $locale ?: AppFacade::getLocale();

        return $this->{'name_'.$locale} ?: $this->name_en;
    }
}
