<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Affiche la liste des cours du formateur connecté
    public function index(Request $request)
    {
        $status = $request->get('status', 'published');
        $search = $request->get('search');
    
        $query = \App\Models\Course::query();
    
        // Filtrer par statut
        $query->where('status', $status);
    
        // Filtrer par titre si recherche
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }
    
        // Filtrer selon le rôle
        if (Auth::user()->role === 'formateur') {
            $query->where('teacher_id', Auth::id());
        }
    
        // Pour l'admin, pas de filtre supplémentaire
        $courses = $query->paginate(6);

        return view('courses.index', compact('courses', 'status', 'search'));
    }
    // Affiche le formulaire de création d’un cours
    public function create()
    {
        return view('courses.create');
       
    }

    // Enregistre un nouveau cours
   
    public function store(Request $request)
    {
        // 1. Validation des données du formulaire
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'content' => 'nullable',
            'duration' => 'nullable|integer',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'video' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:51200',
        ]);
    
        // 2. Récupérer toutes les données du formulaire
        $data = $request->all();
    
        // 3. Gérer l'upload de la miniature (image)
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
    
        // 4. Gérer l'upload du fichier joint (PDF, Word, etc.)
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }
    
        // 5. Gérer l'upload de la vidéo
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('videos', 'public');
        }
    
        // 6. Associer le cours au formateur connecté
        $data['teacher_id'] = Auth::id();
    
        // 7. Créer le cours en base de données
        Course::create($data);
    
        // 8. Redirection avec message de succès
        return redirect()->route('courses.index', '$course->id')->with('success', 'Cours créé avec succès !');
    }


    // Affiche le détail d’un cours
    public function show($id)
    {
        $course = \App\Models\Course::findOrFail($id);
        // Optionnel : vérifier l'accès via Policy
        return view('courses.show', compact('course'));
    }
    // Affiche le formulaire d’édition
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    // Met à jour un cours
    public function update(Request $request, $id)
{
    // 1. Validation des données
    $request->validate([
        'title' => 'required',
        'description' => 'required',
        // ... autres règles selon tes besoins
    ]);

    // 2. Récupérer le cours à modifier
    $course = Course::findOrFail($id);

    // 3. Récupérer toutes les données du formulaire
    $data = $request->all();

    // Upload de la miniature 
    
    if ($request->hasFile('thumbnail')) {
        $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
    }
    
    // 4. Gérer l'upload des fichiers
    if ($request->hasFile('attachment')) {
        $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
    }
    if ($request->hasFile('video')) {
        $data['video'] = $request->file('video')->store('videos', 'public');
    }

    // 5. Mettre à jour le cours
    $course->update($data);

    

    // 6. Redirection avec message de succès
    return redirect()->route('courses.index')->with('success', 'Cours modifié avec succès !');
}

    // Supprime un cours
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Cours supprimé avec succès !');
    }

    // Méthode pour supprimer le thmbnail

    public function removeThumbnail($id)
{
    $course = Course::findOrFail($id);

    // Supprimer le fichier du disque si présent
    if ($course->thumbnail && \Storage::disk('public')->exists($course->thumbnail)) {
        \Storage::disk('public')->delete($course->thumbnail);
    }

    // Mettre à jour la BDD
    $course->thumbnail = null;
    $course->save();

    return back()->with('success', 'Image supprimée avec succès.');
}

    // Affichage des cours publiés côté apprenant

    public function publishedForStudents()
    {
            // On récupère uniquement les cours publiés
$courses = \App\Models\Course::where('status', 'published')->get();

return view('courses.published', compact('courses'));
    }


    public function published(Request $request)
{
    $search = $request->get('search');

    $query = \App\Models\Course::where('status', 'published');

    if ($search) {
        $query->where('title', 'like', '%' . $search . '%');
    }
    $courses = $query->paginate(6);
    return view('courses.published', compact('courses', 'search'));
}
}