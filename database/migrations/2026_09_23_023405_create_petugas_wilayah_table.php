<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas_wilayah', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rt_rw_id');

            $table->timestamps();

            $table->index('user_id');
            $table->index('rt_rw_id');

            $table->unique(
                ['user_id', 'rt_rw_id'],
                'petugas_wilayah_user_rt_rw_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_wilayah');
    }
};