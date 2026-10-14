
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('part1_keluarga', 'alasan_penolakan')) {
            Schema::table('part1_keluarga', function (Blueprint $table) {
                $table->text('alasan_penolakan')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('part1_keluarga', 'alasan_penolakan')) {
            Schema::table('part1_keluarga', function (Blueprint $table) {
                $table->dropColumn('alasan_penolakan');
            });
        }
    }
};
