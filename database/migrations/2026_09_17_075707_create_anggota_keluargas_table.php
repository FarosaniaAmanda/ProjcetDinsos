<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id();

            $table->foreignId('keluarga_id')
                ->constrained('keluarga')
                ->cascadeOnDelete();

            $table->string('nik', 16)->unique();

            $table->string('nama_lengkap');

            $table->enum('jenis_kelamin', [
                'L',
                'P'
            ]);

            $table->date('tanggal_lahir');

            $table->unsignedInteger('umur')->nullable();

            $table->enum('status_keluarga', [
                'kepala_keluarga',
                'suami',
                'istri',
                'anak',
                'orang_tua',
                'saudara',
                'lainnya'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_keluarga');
    }
};