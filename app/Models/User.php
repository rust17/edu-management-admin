<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

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

    /**
     * 判断当前用户是否可以看到指定角色的菜单
     * 由于我们不使用 Laravel-admin 的角色权限系统，直接返回 true
     *
     * @param array $roles
     * @return bool
     */
    public function visible($roles = []): bool
    {
        return true;
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
}
