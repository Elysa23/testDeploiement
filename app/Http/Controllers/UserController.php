<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Liste des utilisateurs
    public function index()
{
    $users = \App\Models\User::all(); // Récupère tous les utilisateurs
    return view('users.index', compact('users')); // Envoie à la vue
}


//Edition du formulaire de modification des utilisateurs

public function edit($id)
{
    // On récupère l'utilisateur à modifier
    $user = \App\Models\User::findOrFail($id);
    // On envoie l'utilisateur à la vue d'édition
    return view('users.edit', compact('user'));
}

public function update(Request $request, $id)
{
    // On récupère l'utilisateur à modifier
    $user = \App\Models\User::findOrFail($id);

    // On valide les données du formulaire
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$user->id,
        'role' => 'required|in:admin,professeur,apprenant',
    ]);

    // On met à jour l'utilisateur
    $user->update($request->only(['name', 'email', 'role']));

    // On redirige vers la liste avec un message de succès
    return redirect('/utilisateurs')->with('success', 'Utilisateur modifié avec succès.');
}

}




