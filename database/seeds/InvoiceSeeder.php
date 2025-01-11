<?php

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        // 获取所有课程-学生关联
        $courseStudents = CourseStudent::all();

        // 为每个课程-学生关联创建发票
        $courseStudents->each(function ($courseStudent) {
            $course = Course::find($courseStudent->course_id);

            factory(Invoice::class)->create([
                'course_id' => $courseStudent->course_id,
                'student_id' => $courseStudent->student_id,
                'amount' => $course->fee // 使用课程的费用作为发票金额
            ]);
        });
    }
}
