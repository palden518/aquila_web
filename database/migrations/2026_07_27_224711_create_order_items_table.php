<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->restrictOnDelete();

            // Snapshot fields — name/price at time of order, so later menu
            // edits or deletions never change what a past order shows.
            $table->string('item_name');
            $table->decimal('unit_price', 8, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
