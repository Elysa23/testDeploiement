<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoursePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'content', 'order'
    ];

    // Relation : une page appartient à un cours
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}