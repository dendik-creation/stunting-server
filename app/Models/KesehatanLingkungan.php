<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KesehatanLingkungan extends Model
{
    use HasFactory;

    const BOBOT = 25;
    const HEALTH_METER = 334;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'step' => 'integer',
        'nilai_total' => 'integer',
        'is_healthy' => 'integer',
    ];


    public function jawaban_kriteria_kesehatan(){
        return $this->hasMany(JawabanKriteriaKesehatan::class, 'kesehatan_lingkungan_id', 'id');
    }
}
