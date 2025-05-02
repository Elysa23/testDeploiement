@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 dark:text-white">Cours disponibles</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col justify-between h-full">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Miniature du cours" class="w-full h-40 object-cover rounded-t mb-4">
                @endif
                <div>
                    <h2 class="text-xl font-bold mb-2 dark:text-white">{{ $course->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($course->description, 100) }}</p>
                </div>
                <div class="flex justify-end items-center mt-4">
                    <a href="{{ route('courses.show', $course->id) }}" class="text-blue-600 hover:underline font-semibold">Voir</a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500">Aucun cours disponible pour le moment.</div>
        @endforelse
    </div>
</div>
@endsection