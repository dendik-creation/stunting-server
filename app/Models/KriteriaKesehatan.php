<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaKesehatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'komponen_kesehatan_id' => 'integer',
        'nilai' => 'integer',
    ];

    public function komponen_kesehatan(){
        return $this->belongsTo(KomponenKesehatan::class, 'komponen_kesehatan_id', 'id');
    }
}
