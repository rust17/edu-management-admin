<?php

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Get all teachers
        $teachers = User::where('role', 'teacher')->get();

        // Create 2-4 courses for each teacher
        $teachers->each(function ($teacher) {
            $coursesCount = rand(2, 4);
            factory(Course::class, $coursesCount)->create([
                'teacher_id' => $teacher->id
            ]);
        });
    }
}
