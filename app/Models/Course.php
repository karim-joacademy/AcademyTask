<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\CourseFactory;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    protected $fillable = [ 'title', 'description', 'teacher_id' ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
