@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Modifier la page du cours : {{ $course->title }}</h1>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.pages.update', [$course->id, $page->id]) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block font-medium dark:text-white">Titre de la page</label>
            <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label for="content" class="block font-medium dark:text-white">Contenu</label>
            <textarea name="content" id="content" rows="6" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>{{ old('content', $page->content) }}</textarea>
        </div>

        <div>
            <label for="order" class="block font-medium dark:text-white">Ordre (numéro de la page)</label>
            <input type="number" name="order" id="order" value="{{ old('order', $page->order) }}" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" min="1" required>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection