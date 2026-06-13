<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPublishingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_created_post_stays_pending_until_admin_approves_and_is_hidden_publicly(): void
    {
        $author = User::factory()->create([
            'role' => 'author',
        ]);
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name_en' => 'News',
            'name_fa' => 'خبر',
            'slug' => 'news',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($author)
            ->post(route('admin.posts.store', ['locale' => 'en']), [
                'title_en' => 'Pending story',
                'title_fa' => 'داستان در انتظار',
                'content_en' => 'English content',
                'content_fa' => 'محتوای فارسی',
                'category_id' => $category->id,
                'published_at' => now()->addWeek()->toDateString(),
            ])
            ->assertRedirect();

        $post = Post::query()->firstOrFail();

        $this->assertNull($post->published_at);

        $this->get(route('posts.show', ['locale' => 'en', 'post' => $post->slug]))
            ->assertNotFound();

        $this->actingAs($admin)
            ->patch(route('admin.posts.approve', ['locale' => 'en', 'post' => $post]), [])
            ->assertRedirect();

        $post->refresh();

        $this->assertNotNull($post->published_at);

        $this->get(route('posts.show', ['locale' => 'en', 'post' => $post->slug]))
            ->assertOk();

        $this->actingAs($admin)
            ->patch(route('admin.posts.disapprove', ['locale' => 'en', 'post' => $post]), [])
            ->assertRedirect();

        $post->refresh();

        $this->assertNull($post->published_at);

        $this->get(route('posts.show', ['locale' => 'en', 'post' => $post->slug]))
            ->assertNotFound();
    }

    public function test_admin_created_post_is_published_by_default(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name_en' => 'News',
            'name_fa' => 'خبر',
            'slug' => 'news',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.posts.store', ['locale' => 'en']), [
                'title_en' => 'Live story',
                'title_fa' => 'خبر منتشر',
                'content_en' => 'English content',
                'content_fa' => 'محتوای فارسی',
                'category_id' => $category->id,
            ])
            ->assertRedirect();

        $post = Post::query()->firstOrFail();

        $this->assertNotNull($post->published_at);

        $this->get(route('posts.show', ['locale' => 'en', 'post' => $post->slug]))
            ->assertOk();
    }

    public function test_author_cannot_edit_or_delete_their_post_after_one_day(): void
    {
        $author = User::factory()->create([
            'role' => 'author',
        ]);

        $post = Post::create([
            'title_en' => 'Old story',
            'title_fa' => 'خبر قدیمی',
            'content_en' => 'English content',
            'content_fa' => 'محتوای فارسی',
            'slug' => 'old-story',
            'user_id' => $author->id,
            'category_id' => null,
            'published_at' => null,
        ]);

        $post->newQuery()->whereKey($post->id)->update([
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $this->actingAs($author)
            ->get(route('admin.posts.edit', ['locale' => 'en', 'post' => $post]))
            ->assertForbidden();

        $this->actingAs($author)
            ->delete(route('admin.posts.destroy', ['locale' => 'en', 'post' => $post]))
            ->assertForbidden();
    }
}
