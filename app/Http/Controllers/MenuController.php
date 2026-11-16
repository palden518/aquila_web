<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    private const REPRESENTATIVE_ITEM_PHOTOS = [
        'eggs-your-way' => [
            'path' => 'images/menu-items/eggs-your-way.webp',
            'alt' => 'Illustrative two eggs served with toasted sourdough and butter.',
        ],
        'avocado-toast' => [
            'path' => 'images/menu-categories/breakfast.webp',
            'alt' => 'Illustrative breakfast plate with avocado toast, poached eggs, grilled tomato and coffee.',
        ],
        'breakfast-toastie' => [
            'path' => 'images/menu-items/breakfast-toastie.webp',
            'alt' => 'Illustrative breakfast toastie with egg and melted cheese.',
        ],
        'big-breakfast' => [
            'path' => 'images/menu-items/big-breakfast.webp',
            'alt' => 'Illustrative big breakfast with eggs, bacon, sausage, mushrooms, tomato and toast.',
        ],
        'pancakes' => [
            'path' => 'images/menu-items/pancakes.webp',
            'alt' => 'Illustrative pancakes with seasonal fruit, cream and maple syrup.',
        ],
        'granola-bowl' => [
            'path' => 'images/menu-items/granola-bowl.webp',
            'alt' => 'Illustrative granola bowl with yoghurt, fresh fruit and honey.',
        ],
        'aquila-beef-burger' => [
            'path' => 'images/menu-items/aquila-beef-burger.webp',
            'alt' => 'Illustrative beef burger with lettuce, tomato, cheese, onion and chips.',
        ],
        'chicken-parmigiana' => [
            'path' => 'images/menu-items/chicken-parmigiana.webp',
            'alt' => 'Illustrative crumbed chicken parmigiana with tomato sauce, cheese, chips and salad.',
        ],
        'fish-and-chips' => [
            'path' => 'images/menu-items/fish-and-chips.webp',
            'alt' => 'Illustrative battered fish and chips with garden salad and tartare sauce.',
        ],
        'steak-sandwich' => [
            'path' => 'images/menu-items/steak-sandwich.webp',
            'alt' => 'Illustrative grilled steak sandwich with onion, lettuce, tomato and chips.',
        ],
        'chicken-wrap' => [
            'path' => 'images/menu-items/chicken-wrap.webp',
            'alt' => 'Illustrative grilled chicken wrap with salad, cheese and dressing.',
        ],
        'salt-and-pepper-squid' => [
            'path' => 'images/menu-items/salt-and-pepper-squid.webp',
            'alt' => 'Illustrative crispy salt and pepper squid with salad, lemon and dipping sauce.',
        ],
        'grilled-chicken' => [
            'path' => 'images/menu-categories/mains.webp',
            'alt' => 'Illustrative plate of grilled chicken with pasta and vegetables.',
        ],
        'chefs-seasonal-risotto' => [
            'path' => 'images/menu-items/chefs-seasonal-risotto.webp',
            'alt' => "Illustrative chef's seasonal risotto.",
        ],
        'grilled-salmon' => [
            'path' => 'images/menu-items/grilled-salmon.webp',
            'alt' => 'Illustrative salmon fillet with seasonal vegetables and lemon butter.',
        ],
        'pasta-of-the-day' => [
            'path' => 'images/menu-items/pasta-of-the-day.webp',
            'alt' => "Illustrative pasta of the day with the chef's daily sauce.",
        ],
        'lamb-shoulder' => [
            'path' => 'images/menu-items/lamb-shoulder.webp',
            'alt' => 'Illustrative slow-cooked lamb shoulder with vegetables and red wine sauce.',
        ],
        'chicken-caesar-salad' => [
            'path' => 'images/menu-items/chicken-caesar-salad.webp',
            'alt' => 'Illustrative chicken Caesar salad with lettuce, parmesan, croutons and Caesar dressing.',
        ],
        'mediterranean-salad' => [
            'path' => 'images/menu-categories/salads.webp',
            'alt' => 'Illustrative salad with leafy greens, avocado, tomatoes, feta and seeds.',
        ],
        'pumpkin-and-quinoa-salad' => [
            'path' => 'images/menu-items/pumpkin-and-quinoa-salad.webp',
            'alt' => 'Illustrative pumpkin and quinoa salad with leafy greens, feta and seeds.',
        ],
        'garden-salad' => [
            'path' => 'images/menu-items/garden-salad.webp',
            'alt' => 'Illustrative garden salad with mixed leaves, cucumber, tomato and onion.',
        ],
        'margherita' => [
            'path' => 'images/menu-items/margherita.webp',
            'alt' => 'Illustrative Margherita pizza with tomato, mozzarella and fresh basil.',
        ],
        'hawaiian' => [
            'path' => 'images/menu-items/hawaiian.webp',
            'alt' => 'Illustrative Hawaiian pizza with tomato, mozzarella, ham and pineapple.',
        ],
        'chicken-and-bacon' => [
            'path' => 'images/menu-items/chicken-and-bacon.webp',
            'alt' => 'Illustrative chicken and bacon pizza with onion, mozzarella and barbecue sauce.',
        ],
        'vegetarian' => [
            'path' => 'images/menu-items/vegetarian.webp',
            'alt' => 'Illustrative vegetarian pizza with mushrooms, capsicum, onion, olives and mozzarella.',
        ],
        'garlic-bread' => [
            'path' => 'images/menu-items/garlic-bread.webp',
            'alt' => 'Illustrative toasted garlic bread with garlic butter and herbs.',
        ],
        'seasoned-wedges' => [
            'path' => 'images/menu-items/seasoned-wedges.webp',
            'alt' => 'Illustrative seasoned potato wedges served with sour cream.',
        ],
        'antipasto-board' => [
            'path' => 'images/menu-categories/sharing.webp',
            'alt' => 'Illustrative antipasto board with cured meats, cheeses, bread, olives and vegetables.',
        ],
        'calamari-plate' => [
            'path' => 'images/menu-items/calamari-plate.webp',
            'alt' => 'Illustrative crispy calamari with lemon and dipping sauce.',
        ],
    ];

    public function index()
    {
        $categories = MenuCategory::query()
            ->with(['availableItems'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        foreach ($categories as $category) {
            foreach ($category->availableItems as $item) {
                $item->setAttribute('menu_photo', $this->menuPhotoFor($item));
            }
        }

        $featured = [
            'title' => Setting::get('featured_dish_title', "Home Dinner"),
            'description' => Setting::get('featured_dish_description', "Ask our team about today's featured meal, prepared using fresh seasonal ingredients."),
            'image' => Setting::get('featured_dish_image'),
        ];

        return view('menu', compact('categories', 'featured'));
    }

    private function menuPhotoFor(MenuItem $item): ?array
    {
        $storedImage = trim((string) $item->image);

        if ($storedImage !== '' && filter_var($storedImage, FILTER_VALIDATE_URL)) {
            return [
                'src' => $storedImage,
                'alt' => 'Photo of ' . $item->name,
                'caption' => null,
            ];
        }

        if ($storedImage !== '' && Storage::disk('public')->exists($storedImage)) {
            return [
                'src' => Storage::disk('public')->url($storedImage),
                'alt' => 'Photo of ' . $item->name,
                'caption' => null,
            ];
        }

        $photo = self::REPRESENTATIVE_ITEM_PHOTOS[$item->slug] ?? null;

        if ($photo === null || ! is_file(public_path($photo['path']))) {
            return null;
        }

        return [
            'src' => asset($photo['path']),
            'alt' => $photo['alt'],
            'caption' => 'Illustrative photo',
        ];
    }
}
