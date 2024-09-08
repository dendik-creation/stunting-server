<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatKemandirian extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'step' => 'integer',
        'keluarga_id' => 'integer',
    ];

    public function jawaban_kriteria_kemandirian(){
        return $this->hasMany(JawabanKriteriaKemandirian::class, 'tingkat_kemandirian_id', 'id');
    }
}
