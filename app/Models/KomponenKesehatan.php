<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenKesehatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['kriteria_kesehatan'];

    public function kriteria_kesehatan()
    {
        return $this->hasMany(KriteriaKesehatan::class, 'komponen_kesehatan_id', 'id');
    }
}
