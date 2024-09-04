<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnakSakit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['penyakit_anak'];

    protected $casts = [
        'keluarga_id' => 'integer',
    ];


    public function penyakit_anak()
    {
        return $this->hasMany(PenyakitAnak::class, 'anak_sakit_id', 'id');
    }

    public function penyakit_anak_penyerta()
    {
        return $this->hasMany(PenyakitAnak::class)->whereHas('penyakit', function ($query) {
            $query->where('jenis_penyakit', 'penyerta');
        });
    }

    public function penyakit_anak_komplikasi()
    {
        return $this->hasMany(PenyakitAnak::class)->whereHas('penyakit', function ($query) {
            $query->where('jenis_penyakit', 'komplikasi');
        });
    }

    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id');
    }
}
