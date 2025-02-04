<?php

namespace App\Services\CourseService;

use App\Http\Requests\CourseRequests\StoreCourseRequest;
use App\Http\Requests\CourseRequests\UpdateCourseRequest;
use App\Models\Course;

interface ICourseService
{
    /**
     * Retrieve all courses.
     *
     * @return array
     */
    public function getAllCourses(): array;

    /**
     * Create a new course.
     *
     * @param StoreCourseRequest $request
     * @return array
     */
    public function createCourse(StoreCourseRequest $request): array;

    /**
     * Get a course with its teacher.
     *
     * @param Course $course
     * @return array
     */

    public function getCourseWithTeacher(Course $course): array;

    /**
     * Update a course.
     *
     * @param UpdateCourseRequest $request
     * @param int $id
     * @return array
     */
    public function updateCourse(UpdateCourseRequest $request, int $id): array;

    /**
     * Delete a course.
     *
     * @param int $id
     * @return array
     */
    public function deleteCourse(int $id): array;
}
