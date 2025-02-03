<?php

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseStudent;

class CourseStudentSeeder extends Seeder
{
    public function run()
    {
        // Get all courses and students
        $courses = Course::all();
        $students = User::where('role', 'student')->get();

        // Randomly assign 5-10 students to each course
        $courses->each(function ($course) use ($students) {
            // Get random 5-10 student IDs
            $studentIds = $students->random(rand(5, 10))->pluck('id');

            // Create course-student associations
            $studentIds->each(function ($studentId) use ($course) {
                CourseStudent::create([
                    'course_id' => $course->id,
                    'student_id' => $studentId
                ]);
            });
        });
    }
}
