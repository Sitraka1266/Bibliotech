<?php

use App\Models\Livre;
use Database\Seeders\LivreSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('the Malagasy book seeder adds twenty books once and preserves existing books', function () {
    $existingBook = Livre::create([
        'titre' => 'Titre saisi manuellement',
        'auteur' => 'Auteur existant',
        'isbn' => '9782865370764',
        'categorie' => 'Roman',
        'annee' => 2000,
        'quantite_totale' => 5,
        'quantite_disponible' => 3,
    ]);

    $this->seed(LivreSeeder::class);
    $this->seed(LivreSeeder::class);

    expect(Livre::query()->count())->toBe(20);
    $existingBook->refresh();
    expect($existingBook->titre)->toBe('Titre saisi manuellement')
        ->and($existingBook->quantite_totale)->toBe(5)
        ->and($existingBook->quantite_disponible)->toBe(3);
});
