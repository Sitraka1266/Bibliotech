<?php

use App\Models\Adherent;
use Database\Seeders\AdherentSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('the adherent seeder adds twenty records once and preserves existing records', function () {
    $existingAdherent = Adherent::create([
        'nom' => 'Nom personnalisé',
        'prenom' => 'Prénom personnalisé',
        'email' => 'andry.rakoto@example.com',
        'telephone' => '0349999999',
        'date_inscription' => '2025-01-01',
    ]);

    $this->seed(AdherentSeeder::class);
    $this->seed(AdherentSeeder::class);

    expect(Adherent::query()->count())->toBe(20);
    $existingAdherent->refresh();
    expect($existingAdherent->nom)->toBe('Nom personnalisé')
        ->and($existingAdherent->telephone)->toBe('0349999999')
        ->and($existingAdherent->date_inscription->toDateString())->toBe('2025-01-01');
});
