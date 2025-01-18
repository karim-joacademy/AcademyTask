<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student');
    }

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }
}
