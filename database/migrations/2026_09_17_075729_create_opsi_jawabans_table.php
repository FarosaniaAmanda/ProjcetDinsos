<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opsi_jawaban', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kuesioner_id')
                ->constrained('kuesioner')
                ->cascadeOnDelete();

            $table->string('kode_opsi');

            $table->string('opsi_jawaban');

            $table->unsignedInteger('urutan')->default(1);

            $table->timestamps();

            $table->unique([
                'kuesioner_id',
                'kode_opsi'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi_jawaban');
    }
};