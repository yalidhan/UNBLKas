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
        Schema::create('period_closings', function (Blueprint $table) {
            $table->id();

            $table->integer('year');
            $table->tinyInteger('month');

            $table->boolean('is_closed')->default(false);

            // closing info
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();

            // temporary reopen window
            $table->timestamp('reopened_at')->nullable();
            $table->timestamp('reopen_expires_at')->nullable();

            $table->timestamps();

            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_closings');
    }
};
