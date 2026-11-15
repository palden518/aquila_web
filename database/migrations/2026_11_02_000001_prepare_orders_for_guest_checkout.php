<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guests order without an account, so the order itself must hold the
     * customer's contact details, and user_id must become optional. Nothing
     * else about the original orders table changes (type, status and
     * pickup_time are already suitable).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (! Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable();
            }
            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone', 50)->nullable();
            }
        });

        // orders.user_id is NOT NULL in the original schema (customers had to
        // be logged in). Guest checkout needs it to be optional. Raw SQL keeps
        // the existing foreign key and avoids needing doctrine/dbal.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true) && Schema::hasColumn('orders', 'user_id')) {
            DB::statement('ALTER TABLE orders MODIFY user_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['customer_name', 'customer_email', 'customer_phone'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
