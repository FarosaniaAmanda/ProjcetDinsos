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
        Schema::table('part2_kondisi_rumahs', function (Blueprint $table) {
            $table->string('status_kepemilikan_lainnya')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('part2_kondisi_rumahs', function (Blueprint $table) {
            $table->dropColumn('status_kepemilikan_lainnya');
        });
    }
};
