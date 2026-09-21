<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas_periode', function (Blueprint $table) {
            $table->id();

            $table->foreignId('petugas_id')
                ->constrained('petugas')
                ->cascadeOnDelete();

            $table->foreignId('periode_id')
                ->constrained('periode')
                ->cascadeOnDelete();

            $table->foreignId('wilayah_id')
                ->constrained('wilayah')
                ->cascadeOnDelete();

            $table->timestamp('assigned_at')->useCurrent();

            $table->timestamps();

            $table->unique([
                'petugas_id',
                'periode_id',
                'wilayah_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_periode');
    }
};