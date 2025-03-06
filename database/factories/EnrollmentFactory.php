<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => User::where('role', 'student')->inRandomOrder()->first()->user_id ?? User::factory()->create(['role' => 'student'])->user_id,
            'course_id' => Course::inRandomOrder()->first()->course_id ?? Course::factory()->create()->course_id,
        ];
    }
}
