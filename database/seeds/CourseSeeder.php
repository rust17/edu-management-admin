<?php

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // 获取所有教师
        $teachers = User::where('role', 'teacher')->get();

        // 为每个教师创建 2-4 个课程
        $teachers->each(function ($teacher) {
            $coursesCount = rand(2, 4);
            factory(Course::class, $coursesCount)->create([
                'teacher_id' => $teacher->id
            ]);
        });
    }
}
