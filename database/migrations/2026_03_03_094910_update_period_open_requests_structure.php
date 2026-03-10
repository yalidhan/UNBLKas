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
        Schema::table('period_open_requests', function (Blueprint $table) {

            // Remove unique constraint
            $table->dropUnique('unique_period_request_status');

            // Add is_read column
            $table->boolean('is_read')
                ->default(false)
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('period_open_requests', function (Blueprint $table) {

            // Restore unique constraint
            $table->unique(
                ['year', 'month', 'status'],
                'unique_period_request_status'
            );

            // Remove is_read column
            $table->dropColumn('is_read');
        });
    }
};
