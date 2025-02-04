<?php

namespace App\Providers;

use App\Services\AcademyService\AcademyService;
use App\Services\AcademyService\IAcademyService;
use App\Services\AuthService\AuthService;
use App\Services\AuthService\IAuthService;
use App\Services\CourseService\CourseService;
use App\Services\CourseService\ICourseService;
use App\Services\StudentService\IStudentService;
use App\Services\StudentService\StudentService;
use App\Services\TeacherService\ITeacherService;
use App\Services\TeacherService\TeacherService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IAcademyService::class, AcademyService::class);
        $this->app->bind(ICourseService::class, CourseService::class);
        $this->app->bind(IStudentService::class, StudentService::class);
        $this->app->bind(ITeacherService::class, TeacherService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
