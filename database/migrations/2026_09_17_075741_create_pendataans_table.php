<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendataan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('keluarga_id')
                ->constrained('keluarga')
                ->cascadeOnDelete();

            $table->foreignId('periode_id')
                ->constrained('periode')
                ->cascadeOnDelete();

            $table->foreignId('petugas_id')
                ->constrained('petugas')
                ->cascadeOnDelete();

            $table->enum('status', [
                'draft',
                'approved',
                'reject'
            ])->default('draft');

            $table->text('catatan')->nullable();

            $table->dateTime('mulai_diisi')->nullable();

            $table->dateTime('disubmit_at')->nullable();

            $table->dateTime('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique('keluarga_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendataan');
    }
};