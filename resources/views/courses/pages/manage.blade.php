@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Pages du cours : {{ $course->title }}</h1>

    <a href="{{ route('courses.pages.create', $course->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Ajouter une page</a>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <ul>
        @forelse($pages as $page)
            <li class="mb-2 flex justify-between items-center">
                <span>{{ $page->order }}. {{ $page->title }}</span>
                <span>
                    <a href="{{ route('courses.pages.edit', [$course->id, $page->id]) }}" class="text-yellow-600 mr-2">Modifier</a>
                    <form action="{{ route('courses.pages.destroy', [$course->id, $page->id]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600" onclick="return confirm('Supprimer cette page ?')">Supprimer</button>
                    </form>
                </span>
            </li>
        @empty
            <li>Aucune page pour ce cours.</li>
        @endforelse
    </ul>
</div>
@endsection