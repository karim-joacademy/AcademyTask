<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), // Default name (can be overridden)
            'email' => fake()->unique()->safeEmail(), // Default email (can be overridden)
            'password' => static::$password ??= Hash::make('karim'),
            'type' => "",
            'remember_token' => Str::random(10)
        ];
    }

    /**
     * Define a state for an academy user.
     */
    public function academy(string $name = null, string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? $attributes['name'], // Use provided name or fallback
            'email' => $email ?? $attributes['email'], // Use provided email or fallback
            'type' => 'academy',
        ]);
    }

    /**
     * Define a state for a teacher user.
     */
    public function teacher(string $name = null, string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? $attributes['name'],
            'email' => $email ?? $attributes['email'],
            'type' => 'teacher',
        ]);
    }

    /**
     * Define a state for a student user.
     */
    public function student(string $name = null, string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? $attributes['name'],
            'email' => $email ?? $attributes['email'],
            'type' => 'student',
        ]);
    }
}
