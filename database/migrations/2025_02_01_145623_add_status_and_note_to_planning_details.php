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
        Schema::table('planning_details', function (Blueprint $table) {
            $table->enum('status',['Pending','Paid','Unpaid'])->default('Pending')->after('note_rektor');
            $table->string('note')->nullable()->after('status');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planning_details', function (Blueprint $table) {
            $table->dropColumn(['status', 'note']);
            //
        });
    }
};
