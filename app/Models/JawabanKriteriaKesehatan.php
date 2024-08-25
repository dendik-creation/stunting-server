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

    public function kriteria_kesehatan()
    {
        return $this->belongsTo(KriteriaKesehatan::class, 'kriteria_kesehatan_id');
    }
}
