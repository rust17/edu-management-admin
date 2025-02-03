<?php

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        // Get all course-student associations
        $courseStudents = CourseStudent::all();

        // Create invoice for each course-student association
        $courseStudents->each(function ($courseStudent) {
            $course = Course::find($courseStudent->course_id);

            factory(Invoice::class)->create([
                'course_id' => $courseStudent->course_id,
                'student_id' => $courseStudent->student_id,
                'amount' => $course->fee, // Use course fee as invoice amount
                'no' => Invoice::generateNo(),
                'sent_at' => now(),
            ]);
        });
    }
}
