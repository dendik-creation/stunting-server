<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are protected.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function puskesmas(){
        return $this->belongsTo(Puskesmas::class, 'puskesmas_id');
    }

    public function kabupaten(){
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    private function setGreetingName(): string{
        if(auth()->user()->role == 'admin'){
            return $this->nama_lengkap;
        }else if(auth()->user()->role == 'operator'){
            return $this->nama_lengkap . " | Operator Puskesmas " . $this->puskesmas->nama_puskesmas;
        }else{
            return $this->nama_lengkap . " | Dinas Kab " . $this->kabupaten->nama_kabupaten;
        }
    }

    public function getFilamentName(): string
    {
        return $this->setGreetingName();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

}
