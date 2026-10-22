<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->date('tanggal_entri')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->dropColumn('tanggal_entri');
        });
    }
};