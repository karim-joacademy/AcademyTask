<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;
use Illuminate\Support\Str;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->firstName();
        $email = Str::slug($name) . '@gmail.com';

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $this->faker->phoneNumber(),
            'academy_id' => 1,
            'user_id' => User::factory()->create([
                'name' => $name,
                'email' => $email,
            ])->id,
        ];
    }

    /**
     * Assigning the role
     * @return TeacherFactory|Factory
     */
    public function configure(): TeacherFactory|Factory
    {
        return $this->afterCreating(function (Teacher $teacher) {
            $user = User::query()->find($teacher->user_id);
            if ($user) {
                $user->assignRole('teacher');
            }
        });
    }
}
