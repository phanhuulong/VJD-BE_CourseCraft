<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Tạo 5 giáo viên
        User::factory(5)->create(['role' => 'teacher']);

        // Tạo 10 sinh viên
        User::factory(10)->create(['role' => 'student']);

        // Tạo 5 danh mục
        Category::factory(5)->create();

        // Tạo 10 khóa học
        Course::factory(10)->create();

        // Tạo 20 lượt đăng ký khóa học ngẫu nhiên
        Enrollment::factory(20)->create();
    }
}
