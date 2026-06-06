<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kehadirans', function (Blueprint $table) {
            $table->unique(['kegiatan_id', 'anggota_id'], 'kehadirans_kegiatan_anggota_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kehadirans', function (Blueprint $table) {
            $table->dropUnique('kehadirans_kegiatan_anggota_unique');
        });
    }
};
