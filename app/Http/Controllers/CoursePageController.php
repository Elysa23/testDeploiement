<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePage;
use App\Models\CourseProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class CoursePageController extends Controller
{
// Afficher une page précise d’un cours

public function showPage($course_id, $page_id = null)
{
    $course = Course::findOrFail($course_id);
    $pages = $course->pages;

      // Vérifier si le cours a des pages
      if ($pages->isEmpty()) {
        return redirect()->route('courses.show', $course_id)
            ->with('info', 'Ce cours n\'a pas encore de pages détaillées.');
      }

    // Si aucune page précisée, on prend la première
    if ($page_id) {
        $page = CoursePage::where('course_id', $course_id)->findOrFail($page_id);
    } else {
        $page = $course->pages()->first();
    }

    // Enregistrer la progression de l’apprenant
    if (Auth::check() && Auth::user()->role === 'apprenant') {
        CourseProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'course_id' => $course_id,
            ],
            [
                'course_page_id' => $page->id,
                'status' => 'in_progress',
            ]
        );
    }

    // Pour la navigation (page suivante/précédente)
    $currentIndex = $pages->search(function ($p) use ($page) {
        return $p->id === $page->id;
    });
    $prevPage = $pages->get($currentIndex - 1);
    $nextPage = $pages->get($currentIndex + 1);

    // 05/05 Calcul de la progression sur les cours
    $isLastPage = $nextPage === null;

    $totalPages = $pages->count();
    $currentPageNumber = $currentIndex + 1; // car $currentIndex commence à 0
    $progressPercent = ($totalPages > 0) ? round(($currentPageNumber / $totalPages) * 100) : 0;

    return view('courses.page', compact('course', 'page', 'prevPage', 'nextPage', 'currentPageNumber', 'totalPages', 'progressPercent', 'isLastPage'));

}

// 05/05 Gestion des pages des cours par les admins ou formateurs

public function manage($course_id)

{
    $course = \App\Models\Course::findOrFail($course_id);
    $pages = $course->pages()->orderBy('order')->get();
    return view('courses.pages.manage', compact('course', 'pages'));
}

// 05/05 Affichage formulaire d'ajout d'une page

public function create($course_id)
{
    $course = \App\Models\Course::findOrFail($course_id);
    return view('courses.pages.create', compact('course'));
}

// 05/05 : Ajouter une nouvelle page

public function store(Request $request, $course_id)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'order' => 'required|integer|min:1',
    ]);

    \App\Models\CoursePage::create([
        'course_id' => $course_id,
        'title' => $request->title,
        'content' => $request->content,
        'order' => $request->order,
    ]);

    return redirect()->route('courses.pages.manage', $course_id)
        ->with('success', 'Page ajoutée avec succès !');
}

// 05/05  Formulaire d'édition d'une page des cours

public function edit($course_id, $page_id)
{
    $course = \App\Models\Course::findOrFail($course_id);
    $page = \App\Models\CoursePage::findOrFail($page_id);
    return view('courses.pages.edit', compact('course', 'page'));
}

// 05/05 Mise à jour d'une page

public function update(Request $request, $course_id, $page_id)
{
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'order' => 'required|integer|min:1',
    ]);

    $page = \App\Models\CoursePage::findOrFail($page_id);
    $page->update([
        'title' => $request->title,
        'content' => $request->content,
        'order' => $request->order,
    ]);

    return redirect()->route('courses.pages.manage', $course_id)
        ->with('success', 'Page modifiée avec succès !');
}


// 05/05 Suppression d'une page

public function destroy($course_id, $page_id)
{
    $page = \App\Models\CoursePage::findOrFail($page_id);
    $page->delete();

    return redirect()->route('courses.pages.manage', $course_id)
        ->with('success', 'Page supprimée avec succès !');
}

}