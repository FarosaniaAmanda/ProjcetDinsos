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
        if (! Schema::hasTable('keluargas')) {
            return;
        }

        Schema::table('keluargas', function (Blueprint $table) {
            $table->string('rt', 5)->nullable()->after('kelurahan_id');
            $table->string('rw', 5)->nullable()->after('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('keluargas')) {
            return;
        }

        Schema::table('keluargas', function (Blueprint $table) {
            $table->dropColumn(['rt', 'rw']);
        });
    }
};
