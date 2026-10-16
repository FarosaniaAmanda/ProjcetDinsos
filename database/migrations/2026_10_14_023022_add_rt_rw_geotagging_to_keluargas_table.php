<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('keluargas')) {
            return;
        }

        Schema::table('keluargas', function (Blueprint $table) {
            if (! Schema::hasColumn('keluargas', 'rt')) {
                $table->string('rt', 10)->nullable();
            }

            if (! Schema::hasColumn('keluargas', 'rw')) {
                $table->string('rw', 10)->nullable();
            }

            if (! Schema::hasColumn('keluargas', 'geotagging')) {
                $table->string('geotagging', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        // These columns are owned by earlier migrations, so dropping them here is unsafe.
    }
};
