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

        if (! Schema::hasColumn('keluargas', 'rt')) {
            Schema::table('keluargas', function (Blueprint $table) {
                $table->string('rt', 10)->nullable();
            });
        }

        if (! Schema::hasColumn('keluargas', 'rw')) {
            Schema::table('keluargas', function (Blueprint $table) {
                $table->string('rw', 10)->nullable();
            });
        }

        if (! Schema::hasColumn('keluargas', 'geotagging')) {
            Schema::table('keluargas', function (Blueprint $table) {
                $table->string('geotagging', 255)->nullable();
            });
        }
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
            $columns = ['rt', 'rw', 'geotagging'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('keluargas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
