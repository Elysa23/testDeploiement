@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 dark:text-white">{{ $course->title }}</h1>

    <!-- 05/05 Barre suivi de progression du cours -->
    {{-- Barre de progression --}}
    @if(Auth::user() && Auth::user()->role === 'apprenant')
    <div class="mb-6">
        <div class="flex justify-between items-center mb-1">
            <span class="text-sm text-gray-700 dark:text-gray-200">
                Page {{ $currentPageNumber }} sur {{ $totalPages }}
            </span>
            <span class="text-sm text-gray-700 dark:text-gray-200">
                {{ $progressPercent }}%
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>
    @endif

<!-- 05/05 Affichage mention "cours terminé-->

@if(Auth::user() && Auth::user()->role === 'apprenant' && isset($isLastPage) && $isLastPage)
    <div class="flex items-center justify-center mt-8">
        <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 text-lg font-semibold shadow">
            🎉 Cours terminé ! Bravo !
        </span>
    </div>
@endif

    <h2 class="text-xl font-semibold mb-2 dark:text-white">{{ $page->title }}</h2>
    <div class="prose dark:prose-invert max-w-none mb-6 dark:text-white">
        {!! nl2br(e($page->content)) !!}
    </div>

    <div class="flex justify-between mt-8">
        @if($prevPage)
            <a href="{{ route('courses.page', [$course->id, $prevPage->id]) }}" class="bg-gray-300 px-4 py-2 rounded">Précédent</a>
        @else
            <span></span>
        @endif

        @if($nextPage)
            <a href="{{ route('courses.page', [$course->id, $nextPage->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Suivant</a>
        @else
            <span>Fin du cours !</span>
        @endif
    </div>
</div>
@endsection