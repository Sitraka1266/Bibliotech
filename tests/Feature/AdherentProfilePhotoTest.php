<?php

use App\Models\Adherent;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

test('an adherent can be created with a profile photo', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('adherents.store'), [
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa@example.test',
        'telephone' => '0340000000',
        'date_inscription' => '2026-10-07',
        'photo' => UploadedFile::fake()->image('portrait.jpg'),
    ]);

    $response->assertRedirect(route('adherents.index'));
    $adherent = Adherent::where('email', 'soa@example.test')->firstOrFail();
    expect($adherent->photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($adherent->photo_path);

    $this->actingAs($user)
        ->get(route('adherents.show', $adherent))
        ->assertSee(asset('storage/'.$adherent->photo_path), false);
});

test('an adherent profile photo can be replaced', function () {
    Storage::fake('public');
    $oldPhotoPath = 'adherents/old-photo.jpg';
    Storage::disk('public')->put($oldPhotoPath, 'old photo');
    $user = User::factory()->create();
    $adherent = Adherent::create([
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa@example.test',
        'date_inscription' => '2026-10-07',
        'photo_path' => $oldPhotoPath,
    ]);

    $response = $this->actingAs($user)->put(route('adherents.update', $adherent), [
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa@example.test',
        'telephone' => '',
        'date_inscription' => '2026-10-07',
        'photo' => UploadedFile::fake()->image('new-portrait.png'),
    ]);

    $response->assertRedirect(route('adherents.index'));
    $adherent->refresh();
    expect($adherent->photo_path)->not->toBe($oldPhotoPath);
    Storage::disk('public')->assertMissing($oldPhotoPath);
    Storage::disk('public')->assertExists($adherent->photo_path);
});

test('a non-image profile photo is rejected', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->from(route('adherents.create'))->post(route('adherents.store'), [
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa@example.test',
        'date_inscription' => '2026-10-07',
        'photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
    ]);

    $response->assertRedirect(route('adherents.create'));
    $response->assertSessionHasErrors('photo');
    expect(Adherent::where('email', 'soa@example.test')->exists())->toBeFalse();
});

test('an adherent profile photo is removed when the adherent is deleted', function () {
    Storage::fake('public');
    $photoPath = 'adherents/profile-photo.jpg';
    Storage::disk('public')->put($photoPath, 'profile photo');
    $user = User::factory()->create();
    $adherent = Adherent::create([
        'nom' => 'Rakoto',
        'prenom' => 'Soa',
        'email' => 'soa@example.test',
        'date_inscription' => '2026-10-07',
        'photo_path' => $photoPath,
    ]);

    $response = $this->actingAs($user)->delete(route('adherents.destroy', $adherent));

    $response->assertRedirect(route('adherents.index'));
    $this->assertDatabaseMissing('adherents', ['id' => $adherent->id]);
    Storage::disk('public')->assertMissing($photoPath);
});
