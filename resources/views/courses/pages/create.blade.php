@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Ajouter une page au cours : {{ $course->title }}</h1>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.pages.store', $course->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block font-medium dark:text-white">Titre de la page</label>
            <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required>
        </div>

        <div>
            <label for="content" class="block font-medium dark:text-white">Contenu</label>
            <textarea name="content" id="content" rows="6" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required></textarea>
        </div>

        <div>
            <label for="order" class="block font-medium dark:text-white">Ordre (numéro de la page)</label>
            <input type="number" name="order" id="order" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" min="1" value="1" required>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded">
                Ajouter la page
            </button>
        </div>
    </form>
</div>
@endsection