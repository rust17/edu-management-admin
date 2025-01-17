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
            // 删除学生时，需要删除：学生扩展信息、学生账单信息、学生参加的课程信息
            if ($user->role == self::ROLE_STUDENT) {
                $user->studentProfile()->delete();
                $user->invoices->map(function (Invoice $invoice) {
                    $invoice->payment()->delete();
                    $invoice->delete();
                });
                CourseStudent::query()->where('student_id', $user->id)->delete();
            }

            // 删除老师时，需要关联删除：老师的管理后台账号、老师的扩展信息、老师的课程信息、老师创建的账单
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

    // 获取该教师的所有课程
    public function teacherCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // 获取该学生参加的所有课程
    public function studentCourses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id');
    }

    // 获取该学生的所有发票
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    /**
     * 获取教师扩展信息
     */
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * 获取学生扩展信息
     */
    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * 获取管理员扩展信息
     */
    public function adminProfile()
    {
        return $this->hasOne(Administrator::class);
    }
}
