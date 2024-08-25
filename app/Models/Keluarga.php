<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'is_approved' => 'integer',
        'is_free_stunting' => 'integer',
    ];

    public function puskesmas(){
        return $this->belongsTo(Puskesmas::class, 'puskesmas_id');
    }

    public function tingkat_kemandirian(){
        return $this->hasMany(TingkatKemandirian::class, 'keluarga_id', 'id');
    }

    public function anak_sakit(){
        return $this->belongsTo(AnakSakit::class, 'keluarga_id', 'id');
    }

    public function kesehatan_lingkungan(){
        return $this->hasMany(KesehatanLingkungan::class, 'keluarga_id', 'id');
    }
}
