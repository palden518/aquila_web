<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Specials & Packages page shows a subtitle line and a "Most
     * popular" ribbon per package, and lets price be omitted in favour of
     * "Price on enquiry" — none of which the original schema supported.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
            $table->boolean('is_popular')->default(false)->after('is_active');
            $table->unsignedInteger('sort_order')->default(0)->after('is_popular');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'is_popular', 'sort_order']);
            $table->decimal('price', 8, 2)->nullable(false)->change();
        });
    }
};
