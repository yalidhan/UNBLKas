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
        //
        Schema::table('period_open_requests', function (Blueprint $table) {

            // durasi yang diminta (jam)
            $table->integer('requested_duration_hours')
                ->nullable()
                ->after('reason');

            // alasan penolakan SPI
            $table->text('rejection_reason')
                ->nullable()
                ->after('approved_at');

            // optional: constraint to reduce duplicate pending
            $table->unique(['year', 'month', 'status'],
                'unique_period_request_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('period_open_requests', function (Blueprint $table) {

            $table->dropUnique('unique_period_request_status');

            $table->dropColumn([
                'requested_duration_hours',
                'rejection_reason'
            ]);
        });
    }
};
