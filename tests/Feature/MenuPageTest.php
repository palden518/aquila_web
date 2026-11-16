<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuPageTest extends MenuTestCase
{
    private const REPRESENTATIVE_ITEM_PHOTOS = [
        'eggs-your-way' => [
            'name' => 'Eggs Your Way',
            'path' => 'images/menu-items/eggs-your-way.webp',
            'alt' => 'Illustrative two eggs served with toasted sourdough and butter.',
        ],
        'avocado-toast' => [
            'name' => 'Avocado Toast',
            'path' => 'images/menu-categories/breakfast.webp',
            'alt' => 'Illustrative breakfast plate with avocado toast, poached eggs, grilled tomato and coffee.',
        ],
        'breakfast-toastie' => [
            'name' => 'Breakfast Toastie',
            'path' => 'images/menu-items/breakfast-toastie.webp',
            'alt' => 'Illustrative breakfast toastie with egg and melted cheese.',
        ],
        'big-breakfast' => [
            'name' => 'Big Breakfast',
            'path' => 'images/menu-items/big-breakfast.webp',
            'alt' => 'Illustrative big breakfast with eggs, bacon, sausage, mushrooms, tomato and toast.',
        ],
        'pancakes' => [
            'name' => 'Pancakes',
            'path' => 'images/menu-items/pancakes.webp',
            'alt' => 'Illustrative pancakes with seasonal fruit, cream and maple syrup.',
        ],
        'granola-bowl' => [
            'name' => 'Granola Bowl',
            'path' => 'images/menu-items/granola-bowl.webp',
            'alt' => 'Illustrative granola bowl with yoghurt, fresh fruit and honey.',
        ],
        'aquila-beef-burger' => [
            'name' => 'Aquila Beef Burger',
            'path' => 'images/menu-items/aquila-beef-burger.webp',
            'alt' => 'Illustrative beef burger with lettuce, tomato, cheese, onion and chips.',
        ],
        'chicken-parmigiana' => [
            'name' => 'Chicken Parmigiana',
            'path' => 'images/menu-items/chicken-parmigiana.webp',
            'alt' => 'Illustrative crumbed chicken parmigiana with tomato sauce, cheese, chips and salad.',
        ],
        'fish-and-chips' => [
            'name' => 'Fish and Chips',
            'path' => 'images/menu-items/fish-and-chips.webp',
            'alt' => 'Illustrative battered fish and chips with garden salad and tartare sauce.',
        ],
        'steak-sandwich' => [
            'name' => 'Steak Sandwich',
            'path' => 'images/menu-items/steak-sandwich.webp',
            'alt' => 'Illustrative grilled steak sandwich with onion, lettuce, tomato and chips.',
        ],
        'chicken-wrap' => [
            'name' => 'Chicken Wrap',
            'path' => 'images/menu-items/chicken-wrap.webp',
            'alt' => 'Illustrative grilled chicken wrap with salad, cheese and dressing.',
        ],
        'salt-and-pepper-squid' => [
            'name' => 'Salt and Pepper Squid',
            'path' => 'images/menu-items/salt-and-pepper-squid.webp',
            'alt' => 'Illustrative crispy salt and pepper squid with salad, lemon and dipping sauce.',
        ],
        'grilled-chicken' => [
            'name' => 'Grilled Chicken',
            'path' => 'images/menu-categories/mains.webp',
            'alt' => 'Illustrative plate of grilled chicken with pasta and vegetables.',
        ],
        'chefs-seasonal-risotto' => [
            'name' => "Chef's Seasonal Risotto",
            'path' => 'images/menu-items/chefs-seasonal-risotto.webp',
            'alt' => "Illustrative chef's seasonal risotto.",
        ],
        'grilled-salmon' => [
            'name' => 'Grilled Salmon',
            'path' => 'images/menu-items/grilled-salmon.webp',
            'alt' => 'Illustrative salmon fillet with seasonal vegetables and lemon butter.',
        ],
        'pasta-of-the-day' => [
            'name' => 'Pasta of the Day',
            'path' => 'images/menu-items/pasta-of-the-day.webp',
            'alt' => "Illustrative pasta of the day with the chef's daily sauce.",
        ],
        'lamb-shoulder' => [
            'name' => 'Lamb Shoulder',
            'path' => 'images/menu-items/lamb-shoulder.webp',
            'alt' => 'Illustrative slow-cooked lamb shoulder with vegetables and red wine sauce.',
        ],
        'chicken-caesar-salad' => [
            'name' => 'Chicken Caesar Salad',
            'path' => 'images/menu-items/chicken-caesar-salad.webp',
            'alt' => 'Illustrative chicken Caesar salad with lettuce, parmesan, croutons and Caesar dressing.',
        ],
        'mediterranean-salad' => [
            'name' => 'Mediterranean Salad',
            'path' => 'images/menu-categories/salads.webp',
            'alt' => 'Illustrative salad with leafy greens, avocado, tomatoes, feta and seeds.',
        ],
        'pumpkin-and-quinoa-salad' => [
            'name' => 'Pumpkin and Quinoa Salad',
            'path' => 'images/menu-items/pumpkin-and-quinoa-salad.webp',
            'alt' => 'Illustrative pumpkin and quinoa salad with leafy greens, feta and seeds.',
        ],
        'garden-salad' => [
            'name' => 'Garden Salad',
            'path' => 'images/menu-items/garden-salad.webp',
            'alt' => 'Illustrative garden salad with mixed leaves, cucumber, tomato and onion.',
        ],
        'margherita' => [
            'name' => 'Margherita',
            'path' => 'images/menu-items/margherita.webp',
            'alt' => 'Illustrative Margherita pizza with tomato, mozzarella and fresh basil.',
        ],
        'hawaiian' => [
            'name' => 'Hawaiian',
            'path' => 'images/menu-items/hawaiian.webp',
            'alt' => 'Illustrative Hawaiian pizza with tomato, mozzarella, ham and pineapple.',
        ],
        'chicken-and-bacon' => [
            'name' => 'Chicken and Bacon',
            'path' => 'images/menu-items/chicken-and-bacon.webp',
            'alt' => 'Illustrative chicken and bacon pizza with onion, mozzarella and barbecue sauce.',
        ],
        'vegetarian' => [
            'name' => 'Vegetarian',
            'path' => 'images/menu-items/vegetarian.webp',
            'alt' => 'Illustrative vegetarian pizza with mushrooms, capsicum, onion, olives and mozzarella.',
        ],
        'antipasto-board' => [
            'name' => 'Antipasto Board',
            'path' => 'images/menu-categories/sharing.webp',
            'alt' => 'Illustrative antipasto board with cured meats, cheeses, bread, olives and vegetables.',
        ],
        'garlic-bread' => [
            'name' => 'Garlic Bread',
            'path' => 'images/menu-items/garlic-bread.webp',
            'alt' => 'Illustrative toasted garlic bread with garlic butter and herbs.',
        ],
        'seasoned-wedges' => [
            'name' => 'Seasoned Wedges',
            'path' => 'images/menu-items/seasoned-wedges.webp',
            'alt' => 'Illustrative seasoned potato wedges served with sour cream.',
        ],
        'calamari-plate' => [
            'name' => 'Calamari Plate',
            'path' => 'images/menu-items/calamari-plate.webp',
            'alt' => 'Illustrative crispy calamari with lemon and dipping sauce.',
        ],
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
        $representativePhotoCount = count(self::REPRESENTATIVE_ITEM_PHOTOS);
        $this->assertSame($availableItemCount, $representativePhotoCount);
        $this->assertSame($representativePhotoCount, substr_count($response->getContent(), 'class="menu-item-image"'));
        $this->assertSame(
            $availableItemCount - $representativePhotoCount,
            substr_count($response->getContent(), 'class="menu-item-image-fallback"')
        );
        $response->assertDontSee('menu-category-image', false);
        $response->assertDontSee('class="featured-menu-image"', false);
    }

    public function test_menu_page_maps_representative_photos_by_item_slug(): void
    {
        $this->useTemporaryPublicPath();
        foreach (self::REPRESENTATIVE_ITEM_PHOTOS as $photo) {
            $imagePath = $this->temporaryPublicPath . DIRECTORY_SEPARATOR
                . str_replace('/', DIRECTORY_SEPARATOR, $photo['path']);
            File::ensureDirectoryExists(dirname($imagePath));
            $this->assertNotFalse(file_put_contents($imagePath, 'test'));
        }

        $response = $this->get('/menu')->assertOk();

        foreach (self::REPRESENTATIVE_ITEM_PHOTOS as $slug => $photo) {
            $item = MenuItem::where('slug', $slug)->firstOrFail();
            $this->assertSame($photo['name'], $item->name);
            $card = $this->menuItemCard($response->getContent(), $photo['name']);

            $this->assertStringContainsString(
                'src="' . asset($photo['path']) . '"',
                $card
            );
            $this->assertStringContainsString('alt="' . e($photo['alt']) . '"', $card);
            $this->assertStringContainsString('Illustrative photo', $card);
            $this->assertStringContainsString('loading="lazy"', $card);
        }

        $this->assertSame(
            count(self::REPRESENTATIVE_ITEM_PHOTOS),
            substr_count($response->getContent(), 'class="menu-item-image"')
        );
        $response->assertDontSee('menu-category-image', false);
    }

    public function test_menu_page_renders_stored_item_urls_and_uploaded_photos(): void
    {
        $item = MenuItem::where('slug', 'avocado-toast')->firstOrFail();
        $item->update(['image' => 'https://images.example.test/avocado-toast.webp']);

        $response = $this->get('/menu')->assertOk();
        $card = $this->menuItemCard($response->getContent(), $item->name);
        $this->assertStringContainsString('src="https://images.example.test/avocado-toast.webp"', $card);
        $this->assertStringContainsString('alt="Photo of Avocado Toast"', $card);

        Storage::fake('public');
        Storage::disk('public')->put('menu-items/avocado-toast.webp', 'test');
        $item->update(['image' => 'menu-items/avocado-toast.webp']);

        $response = $this->get('/menu')->assertOk();
        $card = $this->menuItemCard($response->getContent(), $item->name);
        $this->assertStringContainsString(
            'src="' . Storage::disk('public')->url('menu-items/avocado-toast.webp') . '"',
            $card
        );
        $this->assertStringContainsString('alt="Photo of Avocado Toast"', $card);
    }

    public function test_menu_page_falls_back_when_item_photos_are_missing(): void
    {
        $this->useTemporaryPublicPath();

        $response = $this->get('/menu')->assertOk();
        $availableItemCount = MenuItem::where('is_available', true)->count();
        $fallbackCard = $this->menuItemCard($response->getContent(), 'Eggs Your Way');

        $this->assertSame(
            $availableItemCount,
            substr_count($response->getContent(), 'class="menu-item-image-fallback"')
        );
        $this->assertSame(0, substr_count($response->getContent(), 'class="menu-item-image"'));
        $this->assertStringContainsString(
            'aria-label="Photo not yet available for Eggs Your Way"',
            $fallbackCard
        );
        $this->assertStringNotContainsString('tabindex=', $fallbackCard);
        $response->assertDontSee('menu-category-image', false);
        $response->assertSee('Breakfast');
        $response->assertSee('Eggs Your Way');
        $response->assertSee('Two eggs served with toasted sourdough and butter.');
        $response->assertSee('$18');
    }

    private function menuItemCard(string $html, string $itemName): string
    {
        $headingPosition = strpos($html, e($itemName));
        $this->assertNotFalse($headingPosition, "The {$itemName} menu card should be present.");

        $cardStart = strrpos(substr($html, 0, $headingPosition), '<article class="card menu-item-card');
        $cardEnd = strpos($html, '</article>', $headingPosition);
        $this->assertNotFalse($cardStart, "The {$itemName} menu card should have an opening article.");
        $this->assertNotFalse($cardEnd, "The {$itemName} menu card should have a closing article.");

        return substr($html, $cardStart, $cardEnd + strlen('</article>') - $cardStart);
    }

    private function useTemporaryPublicPath(): void
    {
        $this->originalPublicPath = $this->app->publicPath();
        $this->temporaryPublicPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'aquila-menu-' . Str::uuid();
        File::ensureDirectoryExists($this->temporaryPublicPath);
        $this->app->usePublicPath($this->temporaryPublicPath);
    }
}
