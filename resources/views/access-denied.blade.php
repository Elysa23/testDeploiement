@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded mb-4">
            <strong>Accès refusé :</strong> {{ session('error') ?? "Vous n'avez pas les droits pour accéder à cette page." }}
        </div>
        <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Retour au dashboard
        </a>
    </div>
@endsection