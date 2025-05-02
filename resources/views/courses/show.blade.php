@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow dark:text-white">
    <h1 class="text-3xl font-bold mb-4 dark:text-white">{{ $course->title }}</h1>
    <span class="inline-block px-3 py-1 rounded bg-blue-100 text-blue-800 text-xs mb-4">
        {{ ucfirst($course->status) }}
    </span>
    <p class="text-gray-600 dark:text-gray-300 mb-6">
        {{ $course->description }}
    </p>

   

    @if($course->video)
    <div class="mb-4">
        <video controls class="w-full max-h-96">
            <source src="{{ asset('storage/' . $course->video) }}" type="video/mp4">
            Votre navigateur ne supporte pas la lecture vidéo.
        </video>
    </div>
@endif

    {{-- Affichage du contenu --}}
    @if(!empty($course->content))
        <div class="prose dark:prose-invert max-w-none">
            {!! nl2br(e($course->content)) !!}
        </div>
    @endif


    <div class="flex justify-between items-center mt-8">
    @if($course->attachment)
        <a href="{{ asset('storage/' . $course->attachment) }}" target="_blank" class="text-blue-600 underline">
            Télécharger le fichier joint
        </a>
    
@endif

@if(Auth::user()->role !== 'apprenant')
        <a href="{{ route('courses.edit', $course->id) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-semibold">Modifier</a>
@endif
</div>




@endsection