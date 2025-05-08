<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
        dd($request->all());
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'content' => 'required',   //'required|json',
        ]);

        try{

        Quiz::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);
    } catch (\Exception $e) {
        dd($e->getMessage(), $e->getTraceAsString());
    }

        return redirect()->route('quizzes.index')->with('success', 'Quiz enregistré avec succès !');
    }

    // Affiche un quiz
    public function show(Quiz $quiz)
    {
        return view('quizzes.show', compact('quiz'));
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
}