<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\QuizAnswer;
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
                    'content' => "Génère un quiz varié (QCM, vrai/faux, ouvert) de difficulté raisonnable sur ce contenu, en utilisant la syntaxe Markdown. 
                - Utilise des titres (#) pour chaque question.
                - Mets les questions en gras (**).
                - Utilise des listes à puces pour les propositions de réponses.
                - Indique la bonne réponse en la mettant en gras ou avec [x] pour les QCM.
                - Ajoute une ligne de séparation (---) entre chaque question.

                Contenu du cours : " . $course->content,    
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

public function answer(Quiz $quiz)
{
    dd($questions);
    $questions = parseQuizMarkdown($quiz->content);

    return view('quizzes.answer', compact('quiz', 'questions'));
}

public function submitAnswers(Request $request, Quiz $quiz)
{
    $request->validate([
        'answers' => 'required|array',
        'time_spent' => 'nullable|integer'
    ]);

    QuizAnswer::create([
        'quiz_id' => $quiz->id,
        'user_id' => Auth::id(),
        'answers' => $request->answers,
        'time_spent' => $request->time_spent,
    ]);

    return redirect()->route('quizzes.index')->with('success', 'Quiz soumis avec succès !');
}
public function parseQuizMarkdown($markdown)
{
    $questionsRaw = preg_split('/^\s*---\s*$/m', $markdown); // Sépare les questions
    $questions = [];

    foreach ($questionsRaw as $block) {
        $block = trim($block);
        if (!$block) continue;

        // Titre (optionnel)
        if (preg_match('/^#\s*(.+)$/m', $block, $m)) {
            $title = trim($m[1]);
        } else {
            $title = null;
        }

        // Question (en gras)
        if (preg_match('/\*\*(.+?)\*\*/s', $block, $m)) {
            $question = trim($m[1]);
        } else {
            $question = null;
        }

        // Choix (QCM)
        preg_match_all('/^- \[( |x)\] (.+)$/m', $block, $matches, PREG_SET_ORDER);
        $choices = [];
        $correct = null;
        foreach ($matches as $i => $match) {
            $choices[] = $match[2];
            if (strtolower($match[1]) === 'x') {
                $correct = $i;
            }
        }

        // Type de question
        if (count($choices) === 2 && in_array(strtolower($choices[0]), ['vrai', 'faux'])) {
            $type = 'vrai-faux';
        } elseif (count($choices) > 0) {
            $type = 'qcm';
        } else {
            $type = 'ouvert';
        }

        $questions[] = [
            'title' => $title,
            'question' => $question,
            'choices' => $choices,
            'correct' => $correct,
            'type' => $type,
        ];
    }

    return $questions;
}
}

