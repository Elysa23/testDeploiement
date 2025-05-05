<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Les champs qu'on peut remplir massivement
    protected $fillable = [
        'title', 'description', 'duration', 'status', 'thumbnail', 'content', 'attachment', 'video', 'teacher_id'

    ];

    // Relation avec le formateur
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Accesseur pour vérifier si le cours est publié
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }

    public function pages()
    {
        return $this->hasMany(CoursePage::class)->orderBy('order');
    }
}
