<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvenementController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Page d'accueil publique
Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Dashboard après login
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes profil (générées par Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes étudiant (auth requis)
Route::middleware(['auth'])->group(function () {
    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/{evenement}', [EvenementController::class, 'show'])->name('evenements.show');
    Route::post('/evenements/{evenement}/inscrire', [EvenementController::class, 'inscrire'])->name('evenements.inscrire');
    Route::delete('/inscriptions/{inscription}/annuler', [EvenementController::class, 'annuler'])->name('inscriptions.annuler');
});

// Routes admin (auth + middleware admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/evenements', [EvenementController::class, 'adminIndex'])->name('admin.evenements.index');
    Route::get('/evenements/create', [EvenementController::class, 'create'])->name('admin.evenements.create');
    Route::post('/evenements', [EvenementController::class, 'store'])->name('admin.evenements.store');
    Route::get('/evenements/{evenement}/edit', [EvenementController::class, 'edit'])->name('admin.evenements.edit');
    Route::put('/evenements/{evenement}', [EvenementController::class, 'update'])->name('admin.evenements.update');
    Route::delete('/evenements/{evenement}', [EvenementController::class, 'destroy'])->name('admin.evenements.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/evenements/export-csv', [DashboardController::class, 'exportCsv'])->name('admin.export.csv');
});

require __DIR__.'/auth.php';