
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('part5_foto_rumahs')) {
            return;
        }

        Schema::create('part5_foto_rumahs', function (Blueprint $table): void {
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_general_ci');

            $table->id();
            $table->string('keluarga_periode_kode');
            $table->string('jenis_foto');
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(
                ['keluarga_periode_kode', 'jenis_foto'],
                'part5_foto_rumahs_periode_jenis_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part5_foto_rumahs');
    }
};
