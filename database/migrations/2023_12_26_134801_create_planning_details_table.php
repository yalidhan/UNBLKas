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
        Schema::create('planning_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_id');
            $table->foreign('planning_id')
                ->references('id')->on('plannings')
                ->onDelete('cascade');
            $table->unsignedBigInteger('account_id');    
            $table->foreign('account_id')
                ->references('id')->on('accounts');         
            $table->integer('nominal')->default(0);
            $table->integer('nominal_disetujui')->default(0);
            $table->string('group_rektorat',25);
            $table->integer('approved_by_wr2')->default(0);
            $table->string('note_wr2',25);
            $table->integer('approved_by_rektor')->default(0);
            $table->string('note_rektor',25);
            $table->string('pj',25);
            $table->string('satuan_ukur_kinerja',100)->nullable();
            $table->string('target_kinerja',100);
            $table->string('capaian_kinerja',100);
            $table->string('waktu_pelaksanaan',25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planning_details');
    }
};
