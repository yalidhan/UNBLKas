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
        Schema::table('audit_notes', function (Blueprint $table) {
            //
                        //
            // Notetaker (User who performed the note)
            $table->foreignId('notetaker_id')
                  ->after('note')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // note timestamp
            $table->timestamp('note_at')
                  ->nullable()
                  ->after('notetaker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_notes', function (Blueprint $table) {
            //
            $table->dropForeign(['notetaker_id']);
            $table->dropColumn(['notetaker_id', 'note_at']);
        });
    }
};
