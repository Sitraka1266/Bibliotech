<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Bibliothécaire', 'password' => 'password']
        );

        $this->call([
            LivreSeeder::class,
            AdherentSeeder::class,
        ]);
    }
}
