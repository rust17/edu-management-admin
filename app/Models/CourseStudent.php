<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $fillable = [
        'course_id', 'student_id'
    ];

    public $timestamps = false;

    // 获取关联的课程
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // 获取关联的学生
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}