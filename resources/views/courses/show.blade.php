@extends('layouts.app')

@section('content')

@php
    $role = Auth::user()->role ?? null;
@endphp

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
        <div class="prose dark:prose-invert max-w-none dark:text-whites">
            {!! nl2br(e($course->content)) !!}
        </div>
    @endif


    <div class="flex justify-between items-center mt-8">
    @if($course->attachment)
        <a href="{{ asset('storage/' . $course->attachment) }}" target="_blank" class="text-blue-600 underline">
            Télécharger le fichier joint
        </a>
    
@endif



</div>

<div class="flex justify-between items-center mt-8">
   

    @can('update', $course)
        <a href="{{ route('courses.edit', $course->id) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-semibold">Modifier</a>
    @endcan

    @if($role === 'admin' || $role === 'formateur')
        <a href="{{ route('courses.pages.manage', $course->id) }}"
           class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded font-semibold ml-2">
            Gérer les pages du cours
        </a>
    @endif
</div>

@php
    $hasCoursePages = $course->pages()->count() > 0;
@endphp

@if($hasCoursePages)
    <div class="mt-8 text-center">
        <a href="{{ route('courses.page', $course->id) }}" 
           class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center">
            <span>Explorer les chapitres du cours</span>
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>
@endif

@endsection