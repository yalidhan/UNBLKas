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
        Schema::create('period_open_requests', function (Blueprint $table) {
            $table->id();

            $table->integer('year');
            $table->tinyInteger('month');

            // requester
            $table->foreignId('requested_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('reason');

            // status workflow
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            // SPI approval
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            // reopen duration
            $table->timestamp('open_until')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_open_requests');
    }
};
