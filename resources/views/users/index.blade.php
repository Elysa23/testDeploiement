@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Liste des utilisateurs</h1>
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
                    <td class="border px-4 py-2">
    <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 dark:text-blue-400 hover:underline">Modifier</a>
</td>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection