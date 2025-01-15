<?php

namespace App\Models\Traits;

use Encore\Admin\Auth\Database\Administrator;

trait HasPermission
{
    /**
     * 判断当前用户是否可以看到指定角色的菜单
     * 由于我们不使用 Laravel-admin 的角色权限系统，这里自己实现一个简单的权限管理
     *
     * @param Administrator $admin 当前用户
     * @param string $permissions 菜单权限字符串，例如：admin,teacher
     * @return bool
     */
    public static function visible(Administrator $admin, string $permissions): bool
    {
        // 教师管理菜单只对管理员可见
        if ($admin->role == 'admin' || in_array($admin->role, explode(',', $permissions))){
            return true;
        }

        return false;
    }
}
