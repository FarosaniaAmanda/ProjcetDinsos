<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rt_rws', function (Blueprint $table) {
            $table->id();

            $table->string('kelurahan_id', 13)->index();

            $table->string('rt', 3);
            $table->string('rw', 3);

            $table->timestamps();

            $table->unique(
                ['kelurahan_id', 'rt', 'rw'],
                'rt_rws_kelurahan_rt_rw_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rt_rws');
    }
};