<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_sections_and_category_pages_only_include_their_category_tree(): void
    {
        $this->mock(WeatherService::class, fn ($mock) => $mock->shouldReceive('current')->andReturn([]));
        $categories = [];
        foreach (['sports', 'world', 'health', 'art-culture', 'business', 'opinions', 'politics'] as $slug) {
            $categories[$slug] = Category::create(['slug' => $slug, 'name_en' => $slug, 'name_fa' => $slug, 'is_active' => true]);
        }
        $child = Category::create(['slug' => 'economy', 'name_en' => 'Economy', 'name_fa' => 'اقتصاد', 'is_active' => true, 'parent_id' => $categories['business']->id]);
        foreach (array_merge(array_values($categories), [$child]) as $category) {
            Post::create(['slug' => 'story-'.$category->slug, 'title_en' => 'Story '.$category->slug, 'title_fa' => 'خبر '.$category->slug, 'content_en' => 'Content', 'content_fa' => 'متن', 'category_id' => $category->id, 'published_at' => now()->subMinute()]);
        }
        Post::create(['slug' => 'draft-sports', 'title_en' => 'Draft', 'title_fa' => 'پیش نویس', 'content_en' => 'Draft', 'content_fa' => 'متن', 'category_id' => $categories['sports']->id]);
        foreach (['en', 'fa'] as $locale) {
            $response = $this->get('/'.$locale)->assertOk();
            $columns = $response->viewData('editorialColumns');
            $this->assertCount(4, $columns);
            foreach ($columns as $index => $column) {
                $this->assertSame('story-'.['sports', 'world', 'health', 'art-culture'][$index], $column['lead']['slug']);
                $this->assertCount(0, $column['items']);
            }
            $this->assertSame('story-opinions', $response->viewData('opinionLead')['slug']);
            $businessSlugs = collect([$response->viewData('businessLead')])->merge($response->viewData('businessStories'))->pluck('slug')->all();
            $this->assertEqualsCanonicalizing(['story-business', 'story-economy'], $businessSlugs);
            $this->get('/'.$locale.'/categories/business')->assertOk()->assertViewHas('posts', fn ($posts) => $posts->count() === 2);
            $this->get('/'.$locale.'?category=sports')->assertOk()->assertViewHas('editorialColumns', fn ($columns) => $columns->count() === 1 && $columns->first()['lead']['slug'] === 'story-sports');
        }
        Post::query()->delete();
        $this->get('/en')->assertOk()->assertViewHas('editorialColumns', fn ($columns) => $columns->isEmpty())->assertViewHas('businessLead', null)->assertViewHas('opinionLead', null);
    }
    public function test_category_lists_are_ordered_and_exclude_unpublished_and_other_categories(): void
    {
        $this->mock(WeatherService::class, fn ($mock) => $mock->shouldReceive('current')->andReturn([]));
        $sports = Category::create(['slug' => 'sports', 'name_en' => 'Sports', 'name_fa' => 'ورزش', 'is_active' => true]);
        $politics = Category::create(['slug' => 'politics', 'name_en' => 'Politics', 'name_fa' => 'سیاست', 'is_active' => true]);
        foreach (range(1, 6) as $i) {
            Post::create(['slug' => 'sports-'.$i, 'title_en' => 'Sports '.$i, 'title_fa' => 'ورزش '.$i, 'content_en' => 'Content', 'content_fa' => 'متن', 'category_id' => $sports->id, 'published_at' => now()->subMinutes($i)]);
        }
        foreach (['political' => [$politics->id, now()], 'scheduled' => [$sports->id, now()->addDay()], 'draft' => [$sports->id, null]] as $slug => [$categoryId, $date]) {
            Post::create(['slug' => $slug, 'title_en' => $slug, 'title_fa' => $slug, 'content_en' => 'Content', 'content_fa' => 'متن', 'category_id' => $categoryId, 'published_at' => $date]);
        }
        foreach (['en', 'fa'] as $locale) {
            $response = $this->get('/'.$locale)->assertOk();
            $column = $response->viewData('editorialColumns')->first();
            $this->assertSame('sports-1', $column['lead']['slug']);
            $this->assertSame(['sports-2', 'sports-3', 'sports-4'], $column['items']->pluck('slug')->all());
            $this->get('/'.$locale.'/categories/sports')->assertOk()->assertViewHas('posts', fn ($posts) => $posts->pluck('slug')->all() === ['sports-1', 'sports-2', 'sports-3', 'sports-4', 'sports-5', 'sports-6']);
            $this->get('/'.$locale.'/posts/scheduled')->assertNotFound();
            $this->get('/'.$locale.'/posts/draft')->assertNotFound();
        }
    }

}
