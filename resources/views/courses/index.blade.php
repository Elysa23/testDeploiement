@extends('layouts.app')

@section('content')

<div class="flex space-x-10 mb-6 justify-center mt-10 ">
    <a href="{{ route('courses.index', ['status' => 'published']) }}"
       class="px-4 py-2 rounded-t {{ $status == 'published' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white' }}">
        Publiés
    </a>
    <a href="{{ route('courses.index', ['status' => 'draft']) }}"
       class="px-4 py-2 rounded-t {{ $status == 'draft' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white' }}">
        Brouillons
    </a>
    <a href="{{ route('courses.index', ['status' => 'archived']) }}"
       class="px-4 py-2 rounded-t {{ $status == 'archived' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-white' }}">
        Archivés
    </a>
</div>

{{-- Message de succès flottant --}}
@if(session('success'))
    <div 
        id="success-message"
        class="fixed inset-0 flex items-center justify-center z-50"
        style="background: rgba(0,0,0,0.2);"
    >
        <div class="bg-green-500 text-white px-8 py-4 rounded shadow-lg text-lg font-semibold animate-fade-in">
            {{ session('success') }}
        </div>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 3000); // 3 secondes
    </script>
@endif

<a href="{{ route('courses.create') }}"
   class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded mb-4 inline-block">
    + Nouveau cours
</a>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($courses as $course)
        <div class="bg-[#ddbcf3] dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col justify-between h-full">
        @if($course->thumbnail)
    <img src="{{ asset('storage/' . $course->thumbnail) }}"
         alt="Miniature du cours"
         class="w-full h-40 object-cover rounded-t mb-4">
@endif
            <div>
                <h2 class="text-xl font-bold mb-2 dark:text-white">{{ $course->title }}</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($course->description, 100) }}</p>
                <span class="inline-block px-3 py-1 rounded bg-blue-100 text-blue-800 text-xs mb-2">
                    {{ ucfirst($course->status) }}
                </span>
            </div>
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('courses.show', $course->id) }}"
                   class="text-blue-600 hover:underline font-semibold">Voir</a>
                <a href="{{ route('courses.edit', $course->id) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-semibold">Modifier</a>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center text-gray-500">Aucun cours trouvé.</div>
    @endforelse
</div>

@endsection