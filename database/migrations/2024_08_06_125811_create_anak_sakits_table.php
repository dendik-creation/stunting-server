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
        Schema::create('anak_sakits', function (Blueprint $table) {
            $table->id();
            $table->string('nama_anak');
            $table->string('usia');
            $table->double('berat_badan', 8, 2);
            $table->double('tinggi_badan', 8, 2);
            $table->enum('berat_lahir', ['normal', 'rendah']);
            $table->boolean('ibu_bekerja');
            $table->string('pendidikan_ibu');
            $table->boolean('orang_tua_merokok');
            $table->foreignId('keluarga_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak_sakits');
    }
};
