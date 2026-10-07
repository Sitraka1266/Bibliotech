<?php

use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\travelTo;

uses(LazilyRefreshDatabase::class);

test('the CSV export filters loan status and borrowing date period', function () {
    travelTo(Carbon::parse('2026-10-07 12:00:00'));
    $user = User::factory()->create();
    $adherent = Adherent::create([
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa@example.test',
        'date_inscription' => '2026-01-01',
    ]);
    $createLoan = function (string $title, string $borrowedAt, ?string $returnedAt) use ($adherent): void {
        $book = Livre::create([
            'titre' => $title,
            'auteur' => 'Auteur',
            'isbn' => fake()->unique()->isbn13(),
            'categorie' => 'Roman',
            'annee' => 2020,
            'quantite_totale' => 1,
            'quantite_disponible' => 0,
        ]);

        Emprunt::create([
            'livre_id' => $book->id,
            'adherent_id' => $adherent->id,
            'date_emprunt' => $borrowedAt,
            'date_retour_prevue' => Carbon::parse($borrowedAt)->addDays(14)->toDateString(),
            'date_retour_effective' => $returnedAt,
        ]);
    };

    $createLoan('En cours récent', '2026-10-07', null);
    $createLoan('En cours intermédiaire', '2026-09-16', null);
    $createLoan('En cours ancien', '2026-07-07', null);
    $createLoan('Historique récent', '2026-10-07', '2026-10-07');
    $createLoan('Historique intermédiaire', '2026-09-16', '2026-09-20');
    $createLoan('Historique ancien', '2026-07-07', '2026-07-10');

    $response = actingAs($user)->get(route('emprunts.export'));
    $response->assertDownload('emprunts_en_cours_2026-10-07.csv');
    $content = $response->streamedContent();
    expect($content)
        ->toContain('En cours récent')
        ->toContain('En cours ancien')
        ->not->toContain('Historique récent');

    $response = actingAs($user)->get(route('emprunts.export', [
        'statut' => 'historique',
        'periode' => 4,
        'unite' => 'semaines',
    ]));
    $response->assertDownload('emprunts_historique_4_semaines_2026-10-07.csv');
    $content = $response->streamedContent();
    expect($content)
        ->toContain('Historique récent')
        ->toContain('Historique intermédiaire')
        ->not->toContain('Historique ancien')
        ->not->toContain('En cours récent');

    $response = actingAs($user)->get(route('emprunts.export', [
        'statut' => 'tous',
        'periode' => 2,
        'unite' => 'mois',
    ]));
    $response->assertDownload('emprunts_tous_2_mois_2026-10-07.csv');
    $content = $response->streamedContent();
    expect($content)
        ->toContain('En cours récent')
        ->toContain('Historique récent')
        ->not->toContain('En cours ancien')
        ->not->toContain('Historique ancien');
});

test('the export form offers status and optional period filters', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('emprunts.index'))
        ->assertSee('name="statut"', false)
        ->assertSee('name="periode"', false)
        ->assertSee('name="unite"', false)
        ->assertSee('Historique (rendus)');
});

test('the CSV export rejects unsupported filters', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('emprunts.export', [
        'statut' => 'invalid',
        'periode' => 0,
        'unite' => 'annees',
    ]));

    $response->assertSessionHasErrors(['statut', 'periode', 'unite']);
});
