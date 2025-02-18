<?php

namespace App\Services\AuthService;

use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Http\Requests\StudentRequests\StoreStudentRequest;
use App\Http\Requests\TeacherRequests\StoreTeacherRequest;
use App\Models\User;
use App\Services\AcademyService\IAcademyService;
use App\Services\StudentService\IStudentService;
use App\Services\TeacherService\ITeacherService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService implements IAuthService
{
    private ITeacherService $teacherService;
    private IAcademyService $academyService;
    private IStudentService $studentService;

    public function __construct(ITeacherService $teacherService, IAcademyService $academyService, IStudentService $studentService )
    {
        $this->teacherService = $teacherService;
        $this->academyService = $academyService;
        $this->studentService = $studentService;
    }


    /**
     * Handle user registration.
     *
     * @param RegisterRequest $request
     * @return array
     */
    public function register(RegisterRequest $request): array
    {
        try {
            $validated = $request->validated();

            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'type' => $validated['type'],
                'password' => bcrypt($validated['password']),
            ]);
          
            $user->assignRole($validated['type']);
            $token = $user->createToken('academytask')->plainTextToken;
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
            ]);
            $user->assignRole($validated['type']);

            if ($validated['type'] === 'academy') {
                return $this->registerAcademy($validated, $user);
            }

            if ($validated['type'] === 'teacher') {
                return $this->registerTeacher($user);
            }

            if ($validated['type'] === 'student') {
                return $this->registerStudent($user);
            }

            return [
                'success' => false,
                'message' => 'Invalid user type',
                'status' => 400,
            ];

        } catch (Exception $e) {
            Log::error("Error registering user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while registering the user.',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Handle teacher registration.
     *
     * @param User $user
     * @return array
     */
    protected function registerTeacher(User $user): array
    {
        $teacherData = new StoreTeacherRequest([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user['phone'],
            'academy_id' => 1,
            'course_title' => "default",
            'course_description' => "default",
        ]);
        $teacherResponse = $this->teacherService->createTeacherWithCourse($teacherData);

        if (!$teacherResponse['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create teacher',
                'status' => 500,
                'error' => $teacherResponse['message']
            ];
        }

        return [
            'success' => true,
            'user' => $user,
            'teacher' => $teacherResponse['teacher'],
            'course' => $teacherResponse['course'],
            'token' => $user->createToken('academytask')->plainTextToken,
            'status' => 201
        ];
    }


    /**
     * Handle student registration.
     *
     * @param User $user
     * @return array
     */
    protected function registerStudent(User $user): array
    {
        $studentData = new StoreStudentRequest([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user['phone'],
            'academy_id' => 1,
        ]);
        $studentResponse = $this->studentService->createStudent($studentData);

        if (!$studentResponse['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create student',
                'status' => 500,
                'error' => $studentResponse['message']
            ];
        }

        return [
            'success' => true,
            'user' => $user,
            'student' => $studentResponse['student'],
            'token' => $user->createToken('academytask')->plainTextToken,
            'status' => 201
        ];
    }

    /**
     * Handle academy registration.
     *
     * @param array $validated
     * @param User $user
     * @return array
     */
    protected function registerAcademy(array $validated, User $user): array
    {
        $academyResponse = $this->academyService->createAcademy($validated);

        if (!$academyResponse['success']) {
            return [
                'success' => false,
                'message' => 'Failed to create academy',
                'status' => 500,
                'error' => $academyResponse['message']
            ];
        }

        return [
            'success' => true,
            'user' => $user,
            'academy' => $academyResponse['academy'],
            'token' => $user->createToken('academytask')->plainTextToken,
            'status' => 201
        ];
    }


    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return array
     */
    public function login(LoginRequest $request): array
    {
        try {
            $validated = $request->validated();

            $user = User::query()->where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw new Exception('Invalid credentials');
            }

            $token = $user->createToken('academytask')->plainTextToken;

            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error logging in user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }


    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return array
     */
    public function logout(Request $request): array
    {
        try {
            $user = $request->user();

            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return [
                'success' => true,
                'status' => 200,
            ];
        }
        catch (Exception $e) {
            Log::error("Error logging out user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Logout failed. Please try again later.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}

