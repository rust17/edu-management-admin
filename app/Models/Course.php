<?php

namespace App\Models;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'year_month', 'fee', 'teacher_id'
    ];

    protected $dates = [
        'year_month',
        'deleted_at'
    ];

    // Get course teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Get all students enrolled in this course
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students', 'course_id', 'student_id');
    }

    // Get all invoices for this course
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}