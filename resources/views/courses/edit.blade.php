@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Modifier le cours</h1>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block font-medium dark:text-white">Titre du cours</label>
            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label for="description" class="block font-medium dark:text-white">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>{{ old('description', $course->description) }}</textarea>
        </div>

        <div>
            <label for="content" class="block font-medium dark:text-white">Contenu détaillé</label>
            <textarea name="content" id="content" rows="8" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">{{ old('content', $course->content) }}</textarea>
        </div>

        <div>
            <label for="duration" class="block font-medium dark:text-white">Durée (en minutes)</label>
            <input type="number" name="duration" id="duration" value="{{ old('duration', $course->duration) }}" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" min="1">
        </div>

        <div>
            <label for="status" class="block font-medium dark:text-white">Statut</label>
            <select name="status" id="status" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
                <option value="draft" @if(old('status', $course->status) == 'draft') selected @endif>Brouillon</option>
                <option value="published" @if(old('status', $course->status) == 'published') selected @endif>Publié</option>
                <option value="archived" @if(old('status', $course->status) == 'archived') selected @endif>Archivé</option>
            </select>
        </div>

        <div>
            <label for="thumbnail" class="block font-medium dark:text-white">Image (optionnelle)</label>
            <input type="file" name="thumbnail" id="thumbnail" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Miniature" class="mt-2 w-32 rounded">
            @endif

            
        </div>

        <div>
            <label for="attachment" class="block font-medium dark:text-white">Fichier (PDF, Word, etc.)</label>
            <input type="file" name="attachment" id="attachment" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
            @if($course->attachment)
                <a href="{{ asset('storage/' . $course->attachment) }}" target="_blank" class="text-blue-600 underline mt-2 block">Fichier actuel</a>
            @endif
        </div>

        <div>
            <label for="video" class="block font-medium dark:text-white">Vidéo (optionnelle)</label>
            <input type="file" name="video" id="video" accept="video/*" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
            @if($course->video)
                <video controls class="w-32 mt-2">
                    <source src="{{ asset('storage/' . $course->video) }}" type="video/mp4">
                </video>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded">
                Enregistrer les modifications
            </button>
        </div>
    </form>
  
</div>
@endsection