<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Office lunch package',
                'subtitle' => 'Ideal for team lunches and meetings',
                'is_popular' => false,
                'sort_order' => 1,
                'price' => null,
                'inclusions' => "Set menu with two main course options\nShared antipasto starter\nTea and coffee included\nMinimum group size applies",
            ],
            [
                'name' => 'Corporate event package',
                'subtitle' => 'For functions and celebrations',
                'is_popular' => true,
                'sort_order' => 2,
                'price' => null,
                'inclusions' => "Private dining area\nChoice of set menu or canapés\nBar service available\nDedicated event coordination",
            ],
            [
                'name' => 'Catering package',
                'subtitle' => 'Delivered or collected for your event',
                'is_popular' => false,
                'sort_order' => 3,
                'price' => null,
                'inclusions' => "Selection of platters and shared dishes\nSuitable for offices and private events\nSMS and email confirmation on booking\nNotification once your request is accepted",
            ],
        ];

        foreach ($packages as $data) {
            Package::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
