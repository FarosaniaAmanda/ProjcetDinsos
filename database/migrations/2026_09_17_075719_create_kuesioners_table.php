<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuesioner', function (Blueprint $table) {
            $table->id();

            $table->foreignId('periode_id')
                ->constrained('periode')
                ->cascadeOnDelete();

            $table->string('kode_pertanyaan');

            $table->text('pertanyaan');

            $table->enum('jenis_jawaban', [
                'pilihan_ganda',
                'multiple_choice',
                'jawaban_singkat'
            ]);

            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('urutan')->default(1);

            $table->timestamps();

            $table->unique([
                'periode_id',
                'kode_pertanyaan'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuesioner');
    }
};