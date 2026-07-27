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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // customers must be logged in to place an order 
            $table->string('order_number')->unique();
            $table->enum('type', ['dine_in', 'takeaway'])->default('takeaway');
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])
                ->default('pending');
            $table->decimal('subtotal', 8, 2);
            $table->decimal('total', 8, 2);
            $table->text('notes')->nullable();
            $table->dateTime('pickup_time')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
