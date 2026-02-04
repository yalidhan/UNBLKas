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
        Schema::table('transactions', function (Blueprint $table) {
            //
             $table->enum('status_transaksi', ['on_progress', 'selesai'])
                  ->default('on_progress')
                  ->after('user_id');
            // 
            $table->string('bukti_file_name')->nullable()->after('status_transaksi');
            $table->string('bukti_file_path')->nullable()->after('bukti_file_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
            $table->dropColumn([
                'status_transaksi',
                'bukti_file_name',
                'bukti_file_path'
            ]);
        });
    }
};
