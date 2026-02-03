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
        Schema::create('audit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')
                  ->constrained('transaction_audits')
                  ->cascadeOnDelete()
                  ->index(); // ✅ index audit_id only

            $table->foreignId('auditor_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->text('note');

            $table->timestamp('read_at')->nullable(); // ✅ timestamp read_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_notes');
    }
};
