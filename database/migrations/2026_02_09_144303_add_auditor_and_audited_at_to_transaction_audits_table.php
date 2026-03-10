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
        Schema::table('transaction_audits', function (Blueprint $table) {
            //
            // Auditor (User who performed the audit)
            $table->foreignId('auditor_id')
                  ->after('transaction_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Audit timestamp
            $table->timestamp('audited_at')
                  ->nullable()
                  ->after('status');
            // 🔒 Ensure 1 transaction = 1 audit
            $table->unique('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_audits', function (Blueprint $table) {
            //
            $table->dropForeign(['auditor_id']);
            $table->dropColumn(['auditor_id', 'audited_at']);
            $table->dropUnique(['transaction_id']);
        });
    }
};
