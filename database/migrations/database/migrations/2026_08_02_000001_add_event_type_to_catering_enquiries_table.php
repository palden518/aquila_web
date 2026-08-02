<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The events.blade.php "Send a Catering Inquiry" form has an Event type
     * select (Corporate / Wedding / Private party / Off-site catering) but
     * the original catering_enquiries table has no column for it. This adds
     * one without touching anything else in the schema.
     */
    public function up(): void
    {
        Schema::table('catering_enquiries', function (Blueprint $table) {
            $table->enum('event_type', ['corporate', 'wedding', 'private_party', 'off_site'])
                ->nullable()
                ->after('guest_count');
        });
    }

    public function down(): void
    {
        Schema::table('catering_enquiries', function (Blueprint $table) {
            $table->dropColumn('event_type');
        });
    }
};
