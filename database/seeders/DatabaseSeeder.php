<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PenyakitSeeder::class,
            PuskesmasSeeder::class,
            UserSeeder::class,
            KeluargaSeeder::class,
            KriteriaKemandirianSeeder::class,
        ]);
    }
}
