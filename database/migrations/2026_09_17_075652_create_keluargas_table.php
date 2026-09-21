<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluarga', function (Blueprint $table) {
            $table->id();

            $table->foreignId('periode_id')
                ->constrained('periode')
                ->cascadeOnDelete();

            $table->foreignId('petugas_id')
                ->constrained('petugas')
                ->cascadeOnDelete();

            $table->foreignId('wilayah_id')
                ->constrained('wilayah')
                ->cascadeOnDelete();

            $table->string('nomor_kk', 16);

            $table->string('nama_kepala_keluarga');

            $table->text('alamat');

            $table->enum('status_pendataan', [
                'belum_didata',
                'sedang_didata',
                'selesai'
            ])->default('belum_didata');

            $table->dateTime('tanggal_pendaftaran')->nullable();

            $table->timestamps();

            $table->unique([
                'periode_id',
                'nomor_kk'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};