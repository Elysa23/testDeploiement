<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Détermine si l'utilisateur peut voir le cours.
     */
    public function view(User $user, Course $course)
    {
        // Le formateur propriétaire ou un admin peut voir
        return $user->id === $course->teacher_id || $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut modifier le cours.
     */
    public function update(User $user, Course $course)
    {
        // Seul le formateur propriétaire ou un admin peut modifier
        return $user->id === $course->teacher_id || $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut supprimer le cours.
     */
    public function delete(User $user, Course $course)
    {
        // Seul le formateur propriétaire ou un admin peut supprimer
        return $user->id === $course->teacher_id || $user->role === 'admin';
    }

    @can('update', $course)
    <a href="{{ route('courses.edit', $course->id) }}"
       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-semibold">Modifier</a>
@endcan
}