<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'teacher_id' => User::factory()->create(['role' => 'teacher'])->user_id,
            'category_id' => Category::factory(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'thumbnail_course' => $this->faker->imageUrl(640, 480, 'education'),
            'url_video' => $this->faker->url
        ];
    }
}
