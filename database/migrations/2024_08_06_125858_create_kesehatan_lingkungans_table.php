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
        Schema::create('kesehatan_lingkungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarga_id')->constrained();
            $table->integer('nilai_total');
            $table->date('tanggal');
            $table->integer('step');
            $table->boolean('is_healthy')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kesehatan_lingkungans');
    }
};
