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
            $table->string('kepada')->nullable()->after('keterangan'); // Replace 'existing_column' with the column after which you want to add this
            $table->string('ctt_pajak')->nullable();
            $table->string('ctt_bendahara')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['kepada', 'ctt_pajak','ctt_bendahara']);
            //
        });
    }
};
