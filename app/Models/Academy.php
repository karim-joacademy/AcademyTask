<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\AcademyFactory;

class Academy extends Model
{
    /** @use HasFactory<AcademyFactory> */
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'user_id'];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
