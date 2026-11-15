<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MenuPageTest extends MenuTestCase
{
    private const CATEGORY_IMAGES = [
        'breakfast' => 'breakfast.webp',
        'lunch' => 'lunch.webp',
        'mains' => 'mains.webp',
        'salads' => 'salads.webp',
        'pizza' => 'pizza.webp',
        'sharing' => 'sharing.webp',
    ];

    private ?string $temporaryPublicPath = null;

    private ?string $originalPublicPath = null;

    protected function setUp(): void
    {
        parent::setUp();

        $exitCode = Artisan::call('db:seed', [
            '--class' => \Database\Seeders\MenuSeeder::class,
        ]);

        $this->assertSame(0, $exitCode, Artisan::output());
    }

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

    public function test_menu_page_renders_all_categories_items_descriptions_and_prices(): void
    {
        $response = $this->get('/menu')->assertOk();
        $categories = MenuCategory::with('availableItems')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $this->assertCount(6, $categories);

        $availableItemCount = 0;

        foreach ($categories as $category) {
            $response->assertSee($category->name);

            foreach ($category->availableItems as $item) {
                $response->assertSee($item->name);
                $response->assertSee($item->description);
                $response->assertSee($item->display_price);
                $availableItemCount++;
            }
        }

        $this->assertSame(
            $availableItemCount,
            substr_count($response->getContent(), 'class="card menu-item-card h-100 border-0"')
        );
        $response->assertDontSee('menu-item-image', false);
    }

    public function test_menu_page_references_existing_category_images_with_alt_text(): void
    {
        $this->useTemporaryPublicPath();
        $imageDirectory = $this->temporaryPublicPath . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'menu-categories';
        File::ensureDirectoryExists($imageDirectory);

        foreach (self::CATEGORY_IMAGES as $filename) {
            $this->assertNotFalse(file_put_contents($imageDirectory . DIRECTORY_SEPARATOR . $filename, 'test'));
        }

        $response = $this->get('/menu')->assertOk();

        foreach (self::CATEGORY_IMAGES as $slug => $filename) {
            $category = MenuCategory::where('slug', $slug)->firstOrFail();

            $response->assertSee('src="' . asset('images/menu-categories/' . $filename) . '"', false);
            $response->assertSee('alt="' . $category->name . ' dishes at Aquila"', false);
        }

        $this->assertSame(6, substr_count($response->getContent(), 'class="menu-category-image"'));
    }

    public function test_menu_page_still_renders_when_category_images_are_missing(): void
    {
        $this->useTemporaryPublicPath();

        $response = $this->get('/menu')->assertOk();

        $this->assertSame(0, substr_count($response->getContent(), 'class="menu-category-image"'));
        $response->assertSee('Breakfast');
        $response->assertSee('Eggs Your Way');
        $response->assertSee('Two eggs served with toasted sourdough and butter.');
        $response->assertSee('$18');
    }

    private function useTemporaryPublicPath(): void
    {
        $this->originalPublicPath = $this->app->publicPath();
        $this->temporaryPublicPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'aquila-menu-' . Str::uuid();
        File::ensureDirectoryExists($this->temporaryPublicPath);
        $this->app->usePublicPath($this->temporaryPublicPath);
    }
}
