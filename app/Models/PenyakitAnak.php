<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyakitAnak extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['penyakit'];
    public function penyakit(){
        return $this->belongsTo(Penyakit::class, 'penyakit_id', 'id');
    }
}
