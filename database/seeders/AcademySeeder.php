<?php

namespace Database\Seeders;

use App\Models\Academy;
use Illuminate\Database\Seeder;

class AcademySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Academy::factory(1)->create();
    }
}
