@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4 dark:text-white">
            Quiz : {{ $quiz->course->title ?? 'Cours inconnu' }}
        </h1>
        <form id="quiz-form" method="POST" action="{{ route('quizzes.submit', $quiz) }}">
            @csrf
            @foreach($questions as $index => $q)
                <div class="mb-6">
                    <div class="font-semibold mb-2 dark:text-white">
                        {{ $index + 1 }}. {{ $q['question'] }}
                    </div>
                    @if($q['type'] === 'qcm')
                        @foreach($q['choices'] as $choiceIndex => $choice)
                            <label class="block mb-1">
                                <input type="radio" name="answers[{{ $index }}]" value="{{ $choiceIndex }}" class="mr-2" required>
                                {{ $choice }}
                            </label>
                        @endforeach
                    @elseif($q['type'] === 'vrai-faux')
                        <label class="block mb-1">
                            <input type="radio" name="answers[{{ $index }}]" value="vrai" class="mr-2" required> Vrai
                        </label>
                        <label class="block mb-1">
                            <input type="radio" name="answers[{{ $index }}]" value="faux" class="mr-2" required> Faux
                        </label>
                    @elseif($q['type'] === 'ouvert')
                        <textarea name="answers[{{ $index }}]" rows="2" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white" required></textarea>
                    @endif
                </div>
            @endforeach

            <div class="flex justify-end mt-8">
                <button type="submit" class="bg-green-600 hover:bg-green-800 text-white px-6 py-2 rounded text-lg">
                    Soumettre mes réponses
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Timer (exemple 5 minutes)
let timeLeft = 300;
let timer = setInterval(() => {
    timeLeft--;
    document.getElementById('timer').textContent = "Temps restant : " + Math.floor(timeLeft/60) + "m " + (timeLeft%60) + "s";
    if (timeLeft <= 0) {
        clearInterval(timer);
        document.getElementById('quiz-form').submit();
    }
}, 1000);

// Optionnel : gestion de la pagination JS si tu veux paginer les questions
// (on peut l'ajouter ensuite si tu veux)
</script>
@endsection