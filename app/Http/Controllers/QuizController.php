<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use League\CommonMark\CommonMarkConverter;

class QuizController extends Controller
{
    // Affiche la liste des quiz
    public function index()
    {
        $quizzes = Quiz::with('course', 'user')->latest()->get();
        $courses = \App\Models\Course::all(); 
        return view('quizzes.index', compact('quizzes','courses'));
    }

    // Affiche le formulaire/modal de création de quiz
    public function create()
    {
        $courses = Course::all(); // Ou seulement les cours du formateur connecté
        return view('quizzes.create', compact('courses'));
    }

    // Enregistre le quiz validé
    public function store(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'content' => 'required'
    ]);

    $quiz = Quiz::create([
        'course_id' => $request->course_id,
        'user_id' => Auth::id(),
        'content' => $request->content,
    ]);

    // Si la requête attend du JSON (AJAX)
    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['success' => true, 'quiz_id' => $quiz->id]);
    }

    // Sinon, comportement classique
    return redirect()->route('quizzes.index')->with('success', 'Quiz enregistré avec succès !');
}

    // Affiche un quiz

    public function show(Quiz $quiz)
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $quizContentHtml = $converter->convertToHtml($quiz->content);
    
        return view('quizzes.show', compact('quiz', 'quizContentHtml'));
    }

    // Génère un quiz via l’API IA (exemple avec Mistral)
    public function generate(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        // Appel API Mistral 
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY'),
            'HTTP-Referer' => 'http://localhost', // ou ton nom de domaine
            'X-Title' => 'LaravelQuizGenerator',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-7b-instruct',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Génère un quiz varié (QCM, vrai/faux, ouvert) de difficulté raisonnable sur ce contenu : " . $course->content,
                ],
            ],
        ]);
        


        $quizText = $response->json()['choices'][0]['message']['content'] ?? 'Erreur de génération';

        // Parser le texte en JSON si besoin, ou le retourner tel quel
        return response()->json(['quiz' => $quizText]);
    }

    public function edit(Quiz $quiz)
{
    // Optionnel : vérifier que l'utilisateur a le droit de modifier
    return view('quizzes.edit', compact('quiz'));
}

public function update(Request $request, Quiz $quiz)
{
    $request->validate([
        'content' => 'required'
    ]);

    $quiz->update([
        'content' => $request->content
    ]);

    return redirect()->route('quizzes.index')->with('success', 'Quiz modifié avec succès !');
}

public function destroy(Quiz $quiz)
{
    // Optionnel : vérifier que l'utilisateur a le droit de supprimer
    $quiz->delete();
    return redirect()->route('quizzes.index')->with('success', 'Quiz supprimé avec succès !');
}

}