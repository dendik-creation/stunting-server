<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function puskesmas(){
        return $this->hasMany(Puskesmas::class, 'kabupaten_id', 'id');
    }

    public function users(){
        return $this->hasMany(User::class, 'kabupaten_id', 'id');
    }
}
