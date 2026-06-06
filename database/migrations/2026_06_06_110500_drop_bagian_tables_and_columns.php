<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove bagian_id from anggotas
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropForeign(['bagian_id']);
            $table->dropColumn('bagian_id');
        });

        // Remove bagian_id from kegiatans
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropForeign(['bagian_id']);
            $table->dropColumn('bagian_id');
        });

        // Drop bagians table
        Schema::dropIfExists('bagians');
    }

    public function down(): void
    {
        // Recreate bagians table
        Schema::create('bagians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bagian');
            $table->timestamps();
        });

        // Re-add bagian_id to anggotas
        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('bagian_id')->nullable()->constrained('bagians')->onDelete('cascade');
        });

        // Re-add bagian_id to kegiatans
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->foreignId('bagian_id')->nullable()->constrained('bagians')->onDelete('set null');
        });
    }
};
