<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Optional extras the owner offers at checkout (a drink, a side, extra sauce, cutlery...).
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('price', 8, 2)->default(0); // 0 = free (e.g. cutlery)
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_available');
        });

        // What a customer actually ordered. The name and price are copied across, and
        // addon_id is nullable, so deleting or editing an add-on never changes a past order.
        Schema::create('order_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('addon_name');
            $table->decimal('unit_price', 8, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_addons');
        Schema::dropIfExists('addons');
    }
};
