<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'breakfast', 'name' => 'Breakfast', 'sort_order' => 1, 'items' => [
                ['Eggs Your Way', 18, 'Two eggs served with toasted sourdough and butter.'],
                ['Avocado Toast', 19, 'Smashed avocado, tomato, herbs and toasted sourdough.'],
                ['Breakfast Toastie', 16, 'Egg, cheese and your choice of bacon or vegetables.'],
                ['Big Breakfast', 25, 'Eggs, bacon, sausage, mushrooms, tomato and toast.'],
                ['Pancakes', 17, 'Pancakes with seasonal fruit, cream and maple syrup.'],
                ['Granola Bowl', 15, 'Granola, yoghurt, fresh fruit and honey.'],
            ]],
            ['slug' => 'lunch', 'name' => 'Lunch Specials', 'sort_order' => 2, 'items' => [
                ['Aquila Beef Burger', 24, 'Beef patty, lettuce, tomato, cheese, onion and chips.'],
                ['Salt and Pepper Squid', 23, 'Crispy squid with salad, lemon and dipping sauce.'],
                ['Chicken Parmigiana', 26, 'Crumbed chicken, tomato sauce, cheese, chips and salad.'],
                ['Fish and Chips', 25, 'Battered fish, chips, garden salad and tartare sauce.'],
                ['Steak Sandwich', 27, 'Grilled steak, onion, lettuce, tomato and chips.'],
                ['Chicken Wrap', 21, 'Grilled chicken, salad, cheese and house dressing.'],
            ]],
            ['slug' => 'mains', 'name' => 'Mains', 'sort_order' => 3, 'items' => [
                ['Grilled Chicken', 28, 'Grilled chicken, vegetables, potato and house sauce.'],
                ['Grilled Salmon', 32, 'Salmon fillet, seasonal vegetables and lemon butter.'],
                ['Pasta of the Day', 26, "Fresh pasta prepared with the chef's daily sauce."],
                ['Lamb Shoulder', 34, 'Slow cooked lamb with vegetables and red wine sauce.'],
            ]],
            ['slug' => 'salads', 'name' => 'Salads', 'sort_order' => 4, 'items' => [
                ['Chicken Caesar Salad', 22, 'Chicken, lettuce, parmesan, croutons and Caesar dressing.'],
                ['Mediterranean Salad', 20, 'Tomato, cucumber, olives, feta and fresh herbs.'],
                ['Pumpkin and Quinoa Salad', 21, 'Roasted pumpkin, quinoa, leaves, feta and seeds.'],
                ['Garden Salad', 16, 'Mixed leaves, cucumber, tomato, onion and dressing.'],
            ]],
            ['slug' => 'pizza', 'name' => 'Pizza', 'sort_order' => 5, 'items' => [
                ['Margherita', 21, 'Tomato, mozzarella and fresh basil.'],
                ['Hawaiian', 23, 'Tomato, mozzarella, ham and pineapple.'],
                ['Chicken and Bacon', 25, 'Chicken, bacon, onion, mozzarella and barbecue sauce.'],
                ['Vegetarian', 23, 'Mushroom, capsicum, onion, olives and mozzarella.'],
            ]],
            ['slug' => 'sharing', 'name' => 'To Share', 'sort_order' => 6, 'items' => [
                ['Garlic Bread', 10, 'Toasted bread with garlic butter and herbs.'],
                ['Seasoned Wedges', 12, 'Crispy potato wedges served with sour cream.'],
                ['Antipasto Board', 28, 'Selection of meats, cheeses, olives and bread.'],
                ['Calamari Plate', 24, 'Crispy calamari with lemon and house dipping sauce.'],
            ]],
        ];

        foreach ($categories as $categoryData) {
            $category = MenuCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                ['name' => $categoryData['name'], 'sort_order' => $categoryData['sort_order']]
            );

            foreach ($categoryData['items'] as [$name, $price, $description]) {
                MenuItem::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'menu_category_id' => $category->id,
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'is_available' => true,
                        'is_weekly_special' => false,
                    ]
                );
            }
        }

        // Mirrors the "Weekly special" section on the homepage.
        MenuItem::updateOrCreate(
            ['slug' => 'chefs-seasonal-risotto'],
            [
                'menu_category_id' => MenuCategory::where('slug', 'mains')->value('id'),
                'name' => "Chef's Seasonal Risotto",
                'description' => 'A rotating dish crafted weekly by our head chef, showcasing seasonal produce paired with Italian technique. Available for both dine-in and takeaway.',
                'price' => 28,
                'is_available' => true,
                'is_weekly_special' => true,
            ]
        );
    }
}
