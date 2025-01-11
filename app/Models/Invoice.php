<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'student_id', 'status', 'amount'
    ];

    protected $dates = ['deleted_at'];

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