<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 教师模型
 * 主要存放教师的信息，例如：职称、学历。当资源需要频繁使用教师的特定字段时，可以与该表关联。
 */
class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id'];

    protected $dates = ['deleted_at'];

    /**
     * 获取关联的用户信息
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}