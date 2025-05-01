@extends('layouts.app')

@section('content')

<!--Statistiques-->

<div class="flex flex-wrap gap-4 mb-6">
    <div class="bg-blue-100 text-blue-800 px-6 py-4 rounded shadow w-48 text-center">
        <div class="text-2xl font-bold">{{ $totalAdmins }}</div>
        <div>Admins</div>
    </div>
    <div class="bg-green-100 text-green-800 px-6 py-4 rounded shadow w-48 text-center">
        <div class="text-2xl font-bold">{{ $totalFormateurs }}</div>
        <div>Formateurs</div>
    </div>
    <div class="bg-yellow-100 text-yellow-800 px-6 py-4 rounded shadow w-48 text-center">
        <div class="text-2xl font-bold">{{ $totalApprenants }}</div>
        <div>Apprenants</div>
    </div>
</div>

    <h1 class="text-2xl font-bold mb-4 flex justify-center dark:text-white">Liste des utilisateurs</h1>

    <div class="flex justify-end mb-4">
    <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow"
        onclick="document.getElementById('add-user-modal').style.display='flex'">
        Ajouter un utilisateur
    </button>
</div>

<!-- Modal d'ajout d'utilisateur -->
<div id="add-user-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <!-- Bouton de fermeture -->
        <button
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl"
            onclick="document.getElementById('add-user-modal').style.display='none'">
            &times;
        </button>
        <h2 class="text-xl font-bold mb-4 ">Ajouter un utilisateur</h2>
        <form action="{{ url('/utilisateurs') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block font-medium">Nom</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label for="password" class="block font-medium">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label for="role" class="block font-medium">Rôle</label>
                <select name="role" id="role" class="w-full border rounded px-3 py-2" required>
                    <option value="admin">Admin</option>
                    <option value="formateur">Formateur</option>
                    <option value="apprenant">Apprenant</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

            <!-- Formulaire de recherche-->

        <form id="search-form" method="GET" action="{{ url('/utilisateurs') }}" class="mb-4 flex flex-wrap gap-2 items-center">
    <input id="search-input" type="text" name="search" placeholder="Nom ou email..." value="{{ request('search') }}"
        class="border rounded px-3 py-2 dark:bg-gray-600" />
    <select name="role" class="border rounded px-7 py-2 dark:bg-gray-600">
        <option value=""> Rôles </option>
        <option value="admin" @if(request('role')=='admin') selected @endif>Admin</option>
        <option value="formateur" @if(request('role')=='formateur') selected @endif>Formateur</option>
        <option value="apprenant" @if(request('role')=='apprenant') selected @endif>Apprenant</option>
    </select>
    <button type="submit" class="bg-blue-400 text-white px-4 py-2 rounded">Rechercher</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');

        let lastValue = searchInput.value;

        searchInput.addEventListener('input', function() {
            if (lastValue !== '' && searchInput.value === '') {
                // Si on vient de vider le champ, on soumet le formulaire
                searchForm.submit();
            }
            lastValue = searchInput.value;
        });
    });
</script>

    <table class="min-w-full bg-white dark:bg-gray-600">
        <thead>
            <tr>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Rôle</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ $user->role }}</td>
                    <td class="border px-4 py-2">
    <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 dark:text-blue-400 hover:underline">Modifier</a>

                    </td>
                    <td class="border px-4 py-2">
                        
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                                    Supprimer
                                </button>
                            </form>
                    </td>
                   
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection