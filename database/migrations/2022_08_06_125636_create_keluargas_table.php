<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->string('nama_lengkap');
            $table->string('alamat');
            $table->string('desa');
            $table->string('rt');
            $table->string('rw');
            $table->string('no_telp');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_free_stunting')->default(false);
            $table->foreignId('puskesmas_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
