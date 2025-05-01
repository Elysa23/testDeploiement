<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Liste des utilisateurs
   public function index(Request $request)
{
    $query = \App\Models\User::query();

    // Recherche par nom ou email
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    // Filtre par rôle
    if ($request->filled('role')) {
        $query->where('role', $request->input('role'));
    }

    $users = $query->get();

    // Statistiques
    $totalAdmins = \App\Models\User::where('role', 'admin')->count();
    $totalFormateurs = \App\Models\User::where('role', 'professeur')->count();
    $totalApprenants = \App\Models\User::where('role', 'apprenant')->count();

    return view('users.index', compact('users', 'totalAdmins', 'totalFormateurs', 'totalApprenants'));
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

    // Retourne la vue d'édition avec un message de succès
    return redirect()->route('users.edit', $user->id)
        ->with('success', 'Utilisateur modifié avec succès !')
        ->with('redirect', true);
}


    // SUPPRESSION D'UN UTILISATEUR

    public function destroy($id)
{
    $user = \App\Models\User::findOrFail($id);

    // (Optionnel) Empêcher la suppression de soi-même
    if (auth()->id() == $user->id) {
        return redirect('/utilisateurs')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    }

    $user->delete();

    return redirect('/utilisateurs')->with('success', 'Utilisateur supprimé avec succès.');
}
}




