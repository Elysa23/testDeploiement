@extends('layouts.app')

@section('content')


<div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Créer un nouveau cours</h1>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block font-medium dark:text-white">Titre du cours</label>
            <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label for="description" class="block font-medium dark:text-white">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required></textarea>
        </div>

        <div>
            <label for="duration" class="block font-medium dark:text-white">Durée (en minutes)</label>
            <input type="number" name="duration" id="duration" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" min="1">
        </div>

        <div>
            <label for="status" class="block font-medium dark:text-white">Statut</label>
            <select name="status" id="status" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                <option value="draft">Brouillon</option>
                <option value="published">Publié</option>
            </select>
        </div>

        <div>
            <label for="thumbnail" class="block font-medium dark:text-white">Image (optionnelle)</label>
            <input type="file" name="thumbnail" id="thumbnail" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
        </div>

   

        <!--Champ contenu-->


        <div>
    <label for="content" class="block font-medium dark:text-white">Contenu détaillé</label>
    <textarea name="content" id="content" rows="8" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white"></textarea>
</div>

<div>
    <label for="attachment" class="block font-medium dark:text-white">Fichier (PDF, Word, etc.)</label>
    <input type="file" name="attachment" id="attachment" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
</div>

<div>
    <label for="video" class="block font-medium dark:text-white">Vidéo (URL ou fichier)</label>
    <input type="file" name="video" id="video" accept="video/*" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
    {{-- Ou pour une URL : <input type="text" name="video" id="video" ... > --}}
</div>

<div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded">
                Créer le cours
            </button>
        </div>

    </form>
</div>



@endsection