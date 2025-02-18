<?php

namespace Database\Factories;

use App\Models\Academy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Academy>
 */
class AcademyFactory extends Factory
{
    protected $model = Academy::class;

    public function definition(): array
    {
        $name = 'karim';
        $email = 'karim@gmail.com';

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $this->faker->phoneNumber(),
            'user_id' => User::factory()->create([
                'name' => $name,
                'email' => $email,
            ])->id,
        ];
    }

    /**
     * Assigning the role
     * @return AcademyFactory|Factory
     */
    public function configure(): AcademyFactory|Factory
    {
        return $this->afterCreating(function (Academy $academy) {
            $user = User::query()->find($academy->user_id);
            if ($user) {
                $user->assignRole('academy');
            }
        });
    }
}
