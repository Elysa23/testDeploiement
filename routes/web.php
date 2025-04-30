<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Création de la route Get pour afficher le formulaire d'ajout d'utilisateur 28/04/25

use App\Models\User;
use Illuminate\Http\Request;

// Afficher le formulaire d'ajout d'utilisateur


Route::get('/ajouter-utilisateur', function () {
    return view('ajouter-utilisateur');
})->middleware(['auth', IsAdmin::class]); // protégé par connexion


// Création de la route POST pour enregistrer l'utilisateur 28/04

use Illuminate\Support\Facades\Hash;

Route::post('/ajouter-utilisateur', function (Request $request) {
    // Valider les données
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:apprenant,formateur,admin',
    ]);

    // Créer l'utilisateur
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    return redirect('/ajouter-utilisateur')->with('success', 'Utilisateur ajouté avec succès.');
})->middleware(['auth', IsAdmin::class]);


// 29/04 : Route pour l'accès refusé de la page ajouter-utilisateur 

Route::get('/access-denied', function () {
    return view('access-denied');
})->name('access.denied');

// 29/04 : Route pour la liste des utilisateurs

Route::get('/utilisateurs', [App\Http\Controllers\UserController::class, 'index'])
    ->middleware('is_admin');


// 29/04 : Route pour formulaire d'édition de la liste des utilisateurs

// Afficher le formulaire d’édition
Route::get('/utilisateurs/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])
    ->middleware('is_admin')
    ->name('users.edit');

// Enregistrer la modification
Route::put('/utilisateurs/{user}', [App\Http\Controllers\UserController::class, 'update'])
    ->middleware('is_admin')
    ->name('users.update');