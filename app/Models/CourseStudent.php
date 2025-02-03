<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    protected $fillable = [
        'course_id', 'student_id'
    ];

    public $timestamps = false;

    // Get associated course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Get associated student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}