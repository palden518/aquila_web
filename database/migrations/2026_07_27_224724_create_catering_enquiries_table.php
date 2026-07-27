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
        Schema::create('catering_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->date('event_date');
            $table->unsignedInteger('guest_count');
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'contacted', 'confirmed', 'declined', 'completed'])
                ->default('new');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_enquiries');
    }
};
