<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\CourseFactory;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student');
    }

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }
}
