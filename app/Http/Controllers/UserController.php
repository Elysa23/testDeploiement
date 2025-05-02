<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Liste des utilisateurs
    public function index(Request $request)
    {
        $query = User::query();

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

        $users = $query->paginate($request->input('per_page', 10));

         // Ajout de logs pour déboguer
        \Log::info('Comptage des utilisateurs :');
    
        // Stats avec logs

        $stats = [
            'admin' => User::where('role', 'admin')->count(),
            'formateur' => User::where('role', 'formateur')->count(),
            'apprenant' => User::where('role', 'apprenant')->count(),
            'nouveaux_mois' => User::where('role', 'apprenant')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Log des résultats
        \Log::info('Stats calculées :', $stats);

        // Vérifions tous les formateurs
        $tousFormateurs = User::where('role', 'formateur')->get();
        \Log::info('Liste des formateurs :', $tousFormateurs->toArray());


        // Calcul de l'évolution
        $stats['evolution'] = [
            'mois_dernier' => User::where('role', 'apprenant')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'ce_mois' => $stats['nouveaux_mois']
        ];

        // Calcul du pourcentage d'évolution
        $stats['pourcentage_evolution'] = $stats['evolution']['mois_dernier'] > 0 
            ? (($stats['evolution']['ce_mois'] - $stats['evolution']['mois_dernier']) / $stats['evolution']['mois_dernier']) * 100 
            : 0;

        // Retourner à la fois les users et les stats
        return view('users.index', compact('users', 'stats'));
    }

    // Edition du formulaire de modification des utilisateurs
    public function edit($id)
    {
        // On récupère l'utilisateur à modifier
        $user = User::findOrFail($id);
        // On envoie l'utilisateur à la vue d'édition
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // On récupère l'utilisateur à modifier
        $user = User::findOrFail($id);

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
        $user = User::findOrFail($id);

        // (Optionnel) Empêcher la suppression de soi-même
        if (auth()->id() == $user->id) {
            return redirect('/utilisateurs')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect('/utilisateurs')->with('success', 'Utilisateur supprimé avec succès.');
    }
}