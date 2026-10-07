<?php

use App\Models\Adherent;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

uses(LazilyRefreshDatabase::class);

test('the book search returns at most fifteen matching books that are in stock', function () {
    $user = User::factory()->create();

    foreach (range(1, 16) as $number) {
        Livre::create([
            'titre' => "Roman Recherche {$number}",
            'auteur' => 'Auteur Malagasy',
            'isbn' => fake()->unique()->isbn13(),
            'categorie' => 'Roman',
            'annee' => 2020,
            'quantite_totale' => 1,
            'quantite_disponible' => 1,
        ]);
    }

    Livre::create([
        'titre' => 'Roman Recherche indisponible',
        'auteur' => 'Auteur Malagasy',
        'isbn' => fake()->unique()->isbn13(),
        'categorie' => 'Roman',
        'annee' => 2020,
        'quantite_totale' => 1,
        'quantite_disponible' => 0,
    ]);

    $response = actingAs($user)->getJson(route('emprunts.recherche.livres', ['q' => 'Recherche']));

    $response->assertOk()
        ->assertJsonCount(15, 'results')
        ->assertJsonMissing(['text' => 'Roman Recherche indisponible — Auteur Malagasy (0 dispo.)']);
});

test('the adherent search finds matches by email without loading the full list', function () {
    $user = User::factory()->create();
    $adherent = Adherent::create([
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa.rakoto@example.test',
        'date_inscription' => today(),
    ]);
    Adherent::create([
        'nom' => 'Rabe',
        'prenom' => 'Mamy',
        'email' => 'mamy.rabe@example.test',
        'date_inscription' => today(),
    ]);

    $response = actingAs($user)->getJson(route('emprunts.recherche.adherents', ['q' => 'soa.rakoto']));

    $response->assertOk()
        ->assertJsonCount(1, 'results')
        ->assertJsonPath('results.0.id', $adherent->id)
        ->assertJsonPath('results.0.text', 'Soa RAKOTO — soa.rakoto@example.test');
});

test('the search endpoints reject queries shorter than two characters', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->getJson(route('emprunts.recherche.livres', ['q' => 'a']));

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('q');
});

test('the search endpoints require authentication', function () {
    $response = getJson(route('emprunts.recherche.adherents', ['q' => 'Rakoto']));

    $response->assertUnauthorized();
});
