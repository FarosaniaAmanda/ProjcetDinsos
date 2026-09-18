<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawabans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pendataan_id')
                ->constrained('pendataans')
                ->cascadeOnDelete();

            $table->foreignId('kuesioner_id')
                ->constrained('kuesioners')
                ->cascadeOnDelete();

            $table->foreignId('anggota_keluarga_id')
                ->nullable()
                ->constrained('anggota_keluargas')
                ->nullOnDelete();

            $table->foreignId('opsi_jawaban_id')
                ->nullable()
                ->constrained('opsi_jawabans')
                ->nullOnDelete();

            $table->text('jawaban_text')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'pendataan_id',
                    'kuesioner_id',
                    'anggota_keluarga_id'
                ],
                'jawaban_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawabans');
    }
};