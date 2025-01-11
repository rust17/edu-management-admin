<?php

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseStudent;

class CourseStudentSeeder extends Seeder
{
    public function run()
    {
        // 获取所有课程和学生
        $courses = Course::all();
        $students = User::where('role', 'student')->get();

        // 为每个课程随机分配 5-10 个学生
        $courses->each(function ($course) use ($students) {
            // 随机获取 5-10 个学生 ID
            $studentIds = $students->random(rand(5, 10))->pluck('id');

            // 创建课程-学生关联
            $studentIds->each(function ($studentId) use ($course) {
                CourseStudent::create([
                    'course_id' => $course->id,
                    'student_id' => $studentId
                ]);
            });
        });
    }
}
