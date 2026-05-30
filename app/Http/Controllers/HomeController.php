<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App as AppFacade;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    protected function setLocale(Request $request): string
    {
        $locale = $request->route('locale') ?: 'en';
        AppFacade::setLocale($locale);
        Carbon::setLocale($locale);

        return $locale;
    }

    public function index(Request $request, WeatherService $weatherService)
    {
        $locale = $this->setLocale($request);
        $categorySlug = $request->query('category');
        $selectedCategory = null;

        if ($categorySlug) {
            $selectedCategory = Category::active()->where('slug', $categorySlug)->first();
        }

        $postsQuery = Post::with(['category', 'user'])
            ->published()
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory->id))
            ->orderByRaw('COALESCE(published_at, created_at) desc');

        $posts = $postsQuery->get();
        $storyBank = $this->buildStoryBank($posts, $locale);
        $videoStories = $this->buildVideoStories($locale);

        $featuredLead = $storyBank->first();
        $heroRail = $storyBank->slice(1, 4)->values();
        $featuredCards = $storyBank->slice(5, 4)->values();
        $businessLead = $storyBank->slice(9, 1)->first() ?? $storyBank->first();
        $businessStories = $storyBank->slice(10, 3)->values();

        $sportsLead = $storyBank->slice(13, 1)->first() ?? $storyBank->first();
        $worldLead = $storyBank->slice(17, 1)->first() ?? $storyBank->skip(1)->first();
        $healthLead = $storyBank->slice(21, 1)->first() ?? $storyBank->skip(2)->first();
        $cultureLead = $storyBank->slice(25, 1)->first() ?? $storyBank->skip(3)->first();

        $editorialColumns = collect([
            [
                'title' => __('messages.sports'),
                'lead' => $sportsLead,
                'items' => $this->uniqueStorySlice($storyBank, 14, 6, $sportsLead['id'] ?? null),
            ],
            [
                'title' => __('messages.world'),
                'lead' => $worldLead,
                'items' => $this->uniqueStorySlice($storyBank, 18, 6, $worldLead['id'] ?? null),
            ],
            [
                'title' => __('messages.health'),
                'lead' => $healthLead,
                'items' => $this->uniqueStorySlice($storyBank, 22, 6, $healthLead['id'] ?? null),
            ],
            [
                'title' => __('messages.art_culture'),
                'lead' => $cultureLead,
                'items' => $this->uniqueStorySlice($storyBank, 26, 6, $cultureLead['id'] ?? null),
            ],
        ]);

        $opinionLead = $storyBank->slice(37, 1)->first() ?? $storyBank->first();
        $opinionStories = $storyBank->slice(38, 3)->values();
        $opinionSidebarLead = $storyBank->slice(41, 1)->first() ?? $storyBank->skip(1)->first();
        $readMoreStories = $storyBank->slice(42, 5)->values();

        $utilityStories = $storyBank->take(3)->values();
        $weather = $weatherService->current($locale);

        return view('home', compact(
            'locale',
            'selectedCategory',
            'featuredLead',
            'heroRail',
            'featuredCards',
            'businessLead',
            'businessStories',
            'editorialColumns',
            'videoStories',
            'opinionLead',
            'opinionStories',
            'opinionSidebarLead',
            'readMoreStories',
            'utilityStories',
            'weather'
        ));
    }

    public function show(Request $request, $locale, Post $post)
    {
        AppFacade::setLocale($locale);

        $relatedArticles = collect();

        if ($post->category_id) {
            $relatedArticles = Post::with('category')
                ->published()
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->orderByRaw('COALESCE(published_at, created_at) desc')
                ->take(8)
                ->get();
        }

        // Keep the sidebar small and general, while the bottom block is category-aware.
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->orderByRaw('COALESCE(published_at, created_at) desc')
            ->take(5)
            ->get();

        return view('post.show', compact('post', 'locale', 'relatedPosts', 'relatedArticles'));
    }

    private function buildStoryBank(Collection $posts, string $locale): Collection
    {
        $stories = $posts->map(fn (Post $post, int $index) => $this->decorateStory($post, $index, $locale))->values();

        if ($stories->isEmpty()) {
            $stories = $this->fallbackStories($locale);
        }

        $seed = $stories->values();

        while ($stories->count() < 48) {
            $stories = $stories->concat($seed)->values();
        }

        return $stories->values();
    }

    private function decorateStory(Post $post, int $index, string $locale): array
    {
        $content = trim(strip_tags($post->content($locale) ?: ''));

        return [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title($locale),
            'category' => $post->category?->name($locale) ?? 'General',
            'excerpt' => Str::limit($content, 120),
            'summary' => Str::limit($content, 165),
            'date' => $post->published_at?->translatedFormat('M d, Y') ?? now()->translatedFormat('M d, Y'),
            'url' => route('posts.show', ['locale' => $locale, 'post' => $post->slug]),
            'image' => $this->resolveImageUrl($post->image, $index),
            'video_url' => $this->normalizeEmbedUrl($post->video_url ?: ($this->extractVideoUrl($post->content($locale) ?: '') ?? '')),
            'avatar' => $this->avatarUrl($index),
            'author' => $post->user?->name ?? 'Staff Reporter',
            'author_initials' => $this->initials($post->user?->name ?? 'Staff Reporter'),
        ];
    }

    private function fallbackStories(string $locale): Collection
    {
        $items = [
            ['title' => 'China won battle for key UN Afghanistan role, report finds', 'category' => 'Politics'],
            ['title' => 'Women in Ghor say loss of aid has crippled small businesses', 'category' => 'Economy'],
            ['title' => 'Afghanistan recall Farooqi from England\'s Vitality Blast', 'category' => 'Sports'],
            ['title' => 'Shiite clerics say Taliban have increased pressure on Jafari followers', 'category' => 'Human Rights'],
            ['title' => 'Taliban defense minister says some countries seek to destabilize Afghanistan', 'category' => 'Security'],
            ['title' => 'In Herat, child laborers work among drug users, crime', 'category' => 'Economy'],
            ['title' => '11 million face crisis-level hunger in Afghanistan, report finds', 'category' => 'Economy'],
            ['title' => 'Afghanistan\'s economy expands, but incomes continue to fall', 'category' => 'Economy'],
            ['title' => 'Trump says Iran deal, Strait of Hormuz reopening are near', 'category' => 'World'],
            ['title' => 'UN says new $1.8 billion US aid will expand humanitarian operations', 'category' => 'World'],
            ['title' => 'Afghanistan launches new polio campaign amid continuing access challenges', 'category' => 'Health'],
            ['title' => 'Asha Bhosle, Indian playback singing icon, passes away at 92', 'category' => 'Arts & Culture'],
        ];

        return collect($items)->map(function (array $item, int $index) use ($locale) {
            $date = now()->subDays($index + 1)->translatedFormat('M d, Y');

            return [
                'id' => $index + 1,
                'slug' => 'fallback-story-'.$index,
                'title' => $item['title'],
                'category' => $item['category'],
                'excerpt' => Str::limit('An editorial placeholder article used when the content library is empty.', 120),
                'summary' => 'An editorial placeholder article used when the content library is empty.',
                'date' => $date,
                'url' => route('home', ['locale' => $locale]).'#',
                'image' => $this->resolveImageUrl(null, $index),
                'avatar' => $this->avatarUrl($index),
                'author' => 'Staff Reporter',
                'author_initials' => 'SR',
            ];
        });
    }

    private function resolveImageUrl(?string $image, int $index): string
    {
        if ($image) {
            $storagePath = storage_path('app/public/'.$image);

            if (file_exists($storagePath)) {
                return asset('storage/'.$image);
            }
        }

        $fallbacks = [
            'placeholder-01.svg',
            'placeholder-02.svg',
            'placeholder-03.svg',
            'placeholder-04.svg',
            'placeholder-05.svg',
            'placeholder-06.svg',
            'placeholder-07.svg',
            'placeholder-08.svg',
        ];

        return asset('images/home/'.$fallbacks[$index % count($fallbacks)]);
    }

    private function avatarUrl(int $index): string
    {
        $avatars = [
            'avatar-01.svg',
            'avatar-02.svg',
            'avatar-03.svg',
            'avatar-04.svg',
        ];

        return asset('images/home/'.$avatars[$index % count($avatars)]);
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = collect($parts)->filter()->take(2)->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('');

        return $initials ?: 'SR';
    }

    private function extractVideoUrl(string $content): ?string
    {
        if (! trim($content)) {
            return null;
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $content, $matches)) {
            return $this->normalizeEmbedUrl($matches[1]);
        }

        if (preg_match('/https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|vimeo\.com\/(?:video\/)?)([A-Za-z0-9_\-]+)/i', $content, $matches)) {
            return $this->normalizeEmbedUrl($matches[0]);
        }

        return null;
    }

    private function normalizeEmbedUrl(string $url): string
    {
        if ($url === '') {
            return $url;
        }

        if (str_contains($url, 'youtube.com/watch?v=')) {
            $videoId = parse_url($url, PHP_URL_QUERY);
            parse_str((string) $videoId, $query);

            return isset($query['v']) ? 'https://www.youtube.com/embed/'.$query['v'] : $url;
        }

        if (str_contains($url, 'youtu.be/')) {
            $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

            return $path ? 'https://www.youtube.com/embed/'.$path : $url;
        }

        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }

        if (str_contains($url, 'vimeo.com/')) {
            if (str_contains($url, '/video/')) {
                return preg_replace('#vimeo\.com/video/#', 'player.vimeo.com/video/', $url) ?: $url;
            }

            $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
            $id = basename($path);

            return $id ? 'https://player.vimeo.com/video/'.$id : $url;
        }

        return $url;
    }

    private function uniqueStorySlice(Collection $storyBank, int $start, int $length, ?int $excludeId = null): Collection
    {
        return $storyBank
            ->slice($start, $length)
            ->filter(fn (array $story) => $excludeId === null || (int) data_get($story, 'id') !== $excludeId)
            ->unique('id')
            ->values()
            ->take(3)
            ->values();
    }

    private function buildVideoStories(string $locale): Collection
    {
        $videoCategory = Category::active()->where('slug', 'videos')->first();

        if (! $videoCategory) {
            return collect();
        }

        $videoPosts = Post::with(['category', 'user'])
            ->published()
            ->where('category_id', $videoCategory->id)
            ->orderByRaw('COALESCE(published_at, created_at) desc')
            ->get()
            ->filter(fn (Post $post) => (bool) ($post->video_url ?: $this->extractVideoUrl($post->content($locale) ?: '')))
            ->values();

        $stories = $videoPosts
            ->map(fn (Post $post, int $index) => $this->decorateStory($post, $index, $locale))
            ->values()
            ->take(8)
            ->values();

        if ($stories->isNotEmpty()) {
            return $stories;
        }

        return $this->fallbackVideoStories($locale);
    }

    private function fallbackVideoStories(string $locale): Collection
    {
        $items = [
            [
                'title' => 'سمیع سادات: طالبان ثبات نیاورده‌اند؛ جبهه متحد به مبارزه ادامه می‌دهد',
                'video_url' => 'https://www.youtube.com/watch?v=L1FGKggNk34',
            ],
            [
                'title' => 'علی رادمهر: فرهنگ هزاره فراتر از مرزها گسترش یافته است',
                'video_url' => 'https://www.youtube.com/watch?v=LDg4tMYYAQc',
            ],
            [
                'title' => 'روز فرهنگ هزاره، با برگزاری برنامه‌ای ویژه تجلیل کرد.',
                'video_url' => 'https://www.youtube.com/watch?v=A2tBgDG9do4',
            ],
            [
                'title' => 'قاری عیسی محمدی در ویدیوی تازه از چهره‌های حکومت پیشین جمهوریت انتقاد کرد.',
                'video_url' => 'https://www.youtube.com/watch?v=RMoQHz471a8',
            ],
            [
                'title' => 'عزت‌الله قربان‌زاده: از شاگردی تا ایجاد منبع درآمد برای چند خانواده',
                'video_url' => 'https://www.youtube.com/watch?v=o5RSt23u0E8',
            ],
            [
                'title' => 'محمد طاهر رزمجو',
                'video_url' => 'https://www.youtube.com/watch?v=CL3QbjUOEOA',
            ],
            [
                'title' => 'یک عضو تاجیک‌تبار طالبان با لحنی تند از تبعیض و بی‌عدالتی در ساختار قدرت این گروه انتقاد کرده',
                'video_url' => 'https://www.youtube.com/watch?v=ElUYQGzNH9Y',
            ],
            [
                'title' => 'نسخه‌ای از معاهده دیورند در کتابخانه بریتانیا',
                'video_url' => 'https://www.youtube.com/watch?v=mEeJeMxzkgU',
            ],
        ];

        return collect($items)->map(function (array $item, int $index) use ($locale) {
            $videoId = trim((string) parse_url($item['video_url'], PHP_URL_QUERY));
            parse_str($videoId, $query);
            $youtubeId = $query['v'] ?? '';

            return [
                'id' => 9000 + $index,
                'slug' => 'video-fallback-'.$index,
                'title' => $item['title'],
                'category' => __('messages.live_tv'),
                'excerpt' => '',
                'summary' => '',
                'date' => now()->subDays($index)->translatedFormat('M d, Y'),
                'url' => route('posts.show', ['locale' => $locale, 'post' => 'video-fallback-'.$index]),
                'image' => $youtubeId ? 'https://i.ytimg.com/vi/'.$youtubeId.'/hqdefault.jpg' : $this->resolveImageUrl(null, $index),
                'video_url' => 'https://www.youtube.com/embed/'.$youtubeId,
                'avatar' => $this->avatarUrl($index),
                'author' => 'Panah Press',
                'author_initials' => 'PP',
            ];
        })->take(8)->values();
    }
}
