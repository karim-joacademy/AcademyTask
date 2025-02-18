<?php

namespace App\Models;

use Database\Factories\TeacherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<TeacherFactory> */
    use HasFactory;

    protected $fillable = [ 'name', 'email', 'phone', 'academy_id', 'user_id' ];

    public function academy()
    {
        return $this->belongsTo(Academy::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
