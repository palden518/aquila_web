<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * EP2: guest can pick a specific package on the enquiry form.
     * Nullable: "Not sure yet" is a valid answer.
     * nullOnDelete: deleting a package in admin never deletes enquiries.
     */
    public function up(): void
    {
        Schema::table('catering_enquiries', function (Blueprint $table) {
            $table->foreignId('package_id')
                ->nullable()
                ->after('event_type')
                ->constrained('packages')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('catering_enquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('package_id');
        });
    }
};