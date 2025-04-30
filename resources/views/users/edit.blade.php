@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Modifier l'utilisateur</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label>Nom :</label>
            <input type="text" name="name" value="{{ $user->name }}" required>
        </div>
        <div>
            <label>Email :</label>
            <input type="email" name="email" value="{{ $user->email }}" required>
        </div>
        <div>
            <label>Rôle :</label>
            <select name="role" required>
                <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                <option value="professeur" @if($user->role == 'professeur') selected @endif>Professeur</option>
                <option value="apprenant" @if($user->role == 'apprenant') selected @endif>Apprenant</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
@endsection

