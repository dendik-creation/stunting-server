<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KesehatanLingkungan extends Model
{
    use HasFactory;

    const int BOBOT = 25;
    const int HEALTH_METER = 334;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function jawaban_kriteria_kesehatan(){
        return $this->hasMany(JawabanKriteriaKesehatan::class, 'kesehatan_lingkungan_id', 'id');
    }
}
