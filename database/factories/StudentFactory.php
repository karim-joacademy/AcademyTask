<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->firstName();
        $email = Str::slug($name, '.') . '@gmail.com';

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
     * @return StudentFactory|Factory
     */
    public function configure(): StudentFactory|Factory
    {
        return $this->afterCreating(function (Student $student) {
            $user = User::query()->find($student->user_id);
            if ($user) {
                $user->assignRole('student');
            }
        });
    }
}
