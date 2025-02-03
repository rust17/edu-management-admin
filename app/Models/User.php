<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Invoice;
use App\Models\Traits\HasPermission;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public $avatar = '/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg';

    const ROLE_TEACHER = 'teacher';
    const ROLE_STUDENT = 'student';

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($user) {
            // When deleting a student, delete: student profile, invoices, and course enrollments
            if ($user->role == self::ROLE_STUDENT) {
                $user->studentProfile()->delete();
                $user->invoices->map(function (Invoice $invoice) {
                    $invoice->payment()->delete();
                    $invoice->delete();
                });
                CourseStudent::query()->where('student_id', $user->id)->delete();
            }

            // When deleting a teacher, delete: admin account, teacher profile, courses, and related invoices
            if ($user->role == self::ROLE_TEACHER) {
                $user->teacherProfile()->delete();
                $user->teacherCourses->map(function (Course $course) {
                    $course->invoices->map(function (Invoice $invoice) {
                        $invoice->payment()->delete();
                        $invoice->delete();
                    });
                    CourseStudent::query()->where('course_id', $course->id)->delete();
                    $course->delete();
                });
            }
        });
    }

    // Get all courses taught by this teacher
    public function teacherCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Get all courses enrolled by this student
    public function studentCourses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id');
    }

    // Get all invoices for this student
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    /**
     * Get teacher profile information
     */
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get student profile information
     */
    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get admin profile information
     */
    public function adminProfile()
    {
        return $this->hasOne(Administrator::class);
    }
}
