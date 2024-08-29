<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKriteriaKemandirian extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        "tingkat_kemandirian_id" => "integer",
        "kriteria_kemandirian_id" => "integer",
        "keluarga_id" => "integer"
    ];

    public function kriteria_kemandirian(){
        return $this->belongsTo(KriteriaKemandirian::class, 'kriteria_kemandirian_id', 'id');
    }
}
