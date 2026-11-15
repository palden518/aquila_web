<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

abstract class MenuTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));

        foreach ([
            '2026_07_27_224624_create_menu_categories_table.php',
            '2026_07_27_224647_create_menu_items_table.php',
            '2026_07_27_224742_create_settings_table.php',
        ] as $migration) {
            $exitCode = Artisan::call('migrate', [
                '--path' => database_path('migrations/' . $migration),
                '--realpath' => true,
                '--force' => true,
            ]);

            $this->assertSame(0, $exitCode, Artisan::output());
        }
    }
}
