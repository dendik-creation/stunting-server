<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\AnakSakit;
use App\Models\Keluarga;
use App\Models\Penyakit;
use App\Models\Puskesmas;
use App\Models\User;
use App\Policies\AnakSakitPolicy;
use App\Policies\KabupatenPolicy;
use App\Policies\KeluargaPolicy;
use App\Policies\PenyakitPolicy;
use App\Policies\PuskesmasPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Keluarga::class => KeluargaPolicy::class,
        Penyakit::class => PenyakitPolicy::class,
        AnakSakit::class => AnakSakitPolicy::class,
        Puskesmas::class => PuskesmasPolicy::class,
        KabupatenPolicy::class => KabupatenPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
