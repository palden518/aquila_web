<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Corporate Package',
                'price' => 4000,
                'description' => 'Full-service corporate events with venue hire, catering and dedicated event manager.',
                'inclusions' => "Venue hire (7 hours)\nDedicated event manager\nSeasonal plated menu\nBeverage package",
            ],
            [
                'name' => 'Celebration Package',
                'price' => 5500,
                'description' => 'Curated tasting menus and beverage pairing for milestone occasions.',
                'inclusions' => "Venue hire (10 hours)\nCurated tasting menu\nDessert platter\nPremium beverage pairing",
            ],
        ];

        foreach ($packages as $data) {
            Package::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
