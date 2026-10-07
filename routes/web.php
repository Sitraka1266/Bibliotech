<?php

use App\Http\Controllers\AdherentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\LivreController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->name('login.post');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/tableau-de-bord', DashboardController::class)->name('dashboard');
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    Route::resource('livres', LivreController::class)
        ->except('show')->parameters(['livres' => 'livre']);
    Route::resource('adherents', AdherentController::class)
        ->parameters(['adherents' => 'adherent']);

    Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
    Route::get('/emprunts/export', [EmpruntController::class, 'export'])->name('emprunts.export');
    Route::get('/emprunts/nouveau', [EmpruntController::class, 'create'])->name('emprunts.create');
    Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');
    Route::post('/emprunts/{emprunt}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');
});
