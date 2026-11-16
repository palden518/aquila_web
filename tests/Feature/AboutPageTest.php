<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AboutPageTest extends MenuTestCase
{
    private const ABOUT_IMAGES = [
        'images/about page/dining.webp' => 'Restaurant dining area with tables and large windows',
        'images/about page/outdoor.webp' => 'Dining area with tables beside large windows and hanging plants',
    ];

    private ?string $temporaryPublicPath = null;

    private ?string $originalPublicPath = null;

    protected function tearDown(): void
    {
        if ($this->originalPublicPath !== null) {
            $this->app->usePublicPath($this->originalPublicPath);
        }

        if ($this->temporaryPublicPath !== null) {
            File::deleteDirectory($this->temporaryPublicPath);
        }

        parent::tearDown();
    }

    public function test_about_page_renders_with_content_and_main_landmark(): void
    {
        $response = $this->get(route('about'))
            ->assertOk()
            ->assertViewIs('about');

        $response->assertSee('<a class="about-skip-link" href="#main-content">Skip to main content</a>', false);
        $response->assertSee('<main id="main-content">', false);
        $response->assertSee('<h1 class="hero-heading">About The Aquila</h1>', false);
        $response->assertSee('What makes us', false);
        $response->assertSee('Italian and Australian', false);
        $response->assertSee('aria-label="Toggle navigation"', false);
        $response->assertSee(asset('css/about.css'), false);
        $response->assertDontSee('15k');
        $response->assertDontSee('Two decades');
        $response->assertDontSee('over twenty years');
        $response->assertDontSee('open 7 days');
        $response->assertDontSee('images.unsplash.com');
    }

    public function test_about_statistics_are_hidden_until_content_is_approved_and_complete(): void
    {
        Setting::setMany([
            'about_stat_1_value' => '15k',
            'about_stat_1_label' => 'Guests served yearly',
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('15k');

        Setting::set('about_content_approved', 'true');
        Setting::set('about_stat_1_label', null);

        $this->get(route('about'))
            ->assertOk()
            ->assertDontSee('15k');

        Setting::set('about_stat_1_label', 'Guests served yearly');

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('15k')
            ->assertSee('Guests served yearly');
    }

    public function test_about_top_navigation_links_to_the_about_route_on_every_public_page(): void
    {
        foreach (['home', 'menu', 'specials', 'events', 'contact', 'about'] as $viewName) {
            $blade = File::get(resource_path("views/{$viewName}.blade.php"));
            $navStart = strpos($blade, '<nav');
            $navEnd = strpos($blade, '</nav>', $navStart);

            $this->assertNotFalse($navStart, "The {$viewName} page should have a top navigation.");
            $this->assertNotFalse($navEnd, "The {$viewName} page top navigation should close.");

            $navigation = substr($blade, $navStart, $navEnd - $navStart);
            $this->assertStringContainsString(
                "href=\"{{ route('about') }}\"",
                $navigation,
                "The About link on the {$viewName} page should target the About route."
            );
        }

        foreach (['home', 'menu'] as $routeName) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertSee('<a class="nav-link" href="' . route('about') . '">About</a>', false);
        }
    }

    public function test_about_images_use_local_sources_and_descriptive_non_venue_alt_text(): void
    {
        $this->useTemporaryPublicPath();

        foreach (array_keys(self::ABOUT_IMAGES) as $imagePath) {
            $fullPath = $this->temporaryPublicPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
            File::ensureDirectoryExists(dirname($fullPath));
            $this->assertNotFalse(file_put_contents($fullPath, 'test'));
        }

        $response = $this->get(route('about'))->assertOk();

        foreach (self::ABOUT_IMAGES as $imagePath => $alt) {
            $response->assertSee('src="' . asset($imagePath) . '"', false);
            $response->assertSee('alt="' . $alt . '"', false);
        }

        $this->assertSame(2, substr_count($response->getContent(), 'class="about-image"'));
    }

    public function test_about_page_renders_when_optional_photographs_are_missing(): void
    {
        $this->useTemporaryPublicPath();

        $response = $this->get(route('about'))->assertOk();

        $this->assertSame(0, substr_count($response->getContent(), 'class="about-image"'));
        $response->assertSee('<main id="main-content">', false);
        $response->assertSee('About The Aquila');
    }

    private function useTemporaryPublicPath(): void
    {
        $this->originalPublicPath = $this->app->publicPath();
        $this->temporaryPublicPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'aquila-about-' . Str::uuid();
        File::ensureDirectoryExists($this->temporaryPublicPath);
        $this->app->usePublicPath($this->temporaryPublicPath);
    }
}
