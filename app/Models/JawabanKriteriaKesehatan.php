<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKriteriaKesehatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['kriteria_kesehatan'];

    protected $casts = [
        "keluarga_id" => "integer",
        "komponen_kesehatan_id" => "integer",
        "kriteria_kesehatan_id" => "integer",
        "kesehatan_lingkungan_id" => "integer",
    ];

    public function kriteria_kesehatan()
    {
        return $this->belongsTo(KriteriaKesehatan::class, 'kriteria_kesehatan_id');
    }

    public function komponen_kesehatan()
    {
        return $this->belongsTo(KomponenKesehatan::class, 'komponen_kesehatan_id', 'id');
    }
}
