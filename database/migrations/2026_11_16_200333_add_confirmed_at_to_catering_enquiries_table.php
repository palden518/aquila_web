<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * AQL-35: a confirmed enquiry is the bookable event record.
     * confirmed_at records when the enquiry became a booking
     * (conversion tracking). The index speeds up the
     * "is this date already booked?" check used by AQL-35 and AQL-34.
     */
    public function up(): void
    {
        Schema::table('catering_enquiries', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->index(['status', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::table('catering_enquiries', function (Blueprint $table) {
            $table->dropIndex(['status', 'event_date']);
            $table->dropColumn('confirmed_at');
        });
    }
};