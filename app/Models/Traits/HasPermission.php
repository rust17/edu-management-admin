<?php

namespace App\Models\Traits;

use App\Models\User;

trait HasPermission
{
    /**
     * 判断当前用户是否可以看到指定角色的菜单
     * 由于我们不使用 Laravel-admin 的角色权限系统，这里自己实现一个简单的权限管理
     *
     * @param string $permissions
     * @return bool
     */
    public function visible(string $permissions): bool
    {
        // 教师管理菜单只对管理员可见
        if ($this->isAdmin() && $this->isRoles(explode(',', $permissions))) {
            return true;
        }

        // 学生管理菜单对管理员和教师可见
        if ($this->isAdmin() ||
            $this->isTeacher() &&
            $this->isRoles(explode(',', $permissions))
        ) {
            return true;
        }

        return false;
    }

    /**
     * 判断当前用户是否是管理员
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isRole(User::ROLE_ADMIN);
    }

    /**
     * 判断当前用户是否是教师
     *
     * @return bool
     */
    public function isTeacher(): bool
    {
        return $this->isRole(User::ROLE_TEACHER);
    }

    /**
     * 判断当前用户是否属于某个角色
     *
     * @param string $role
     * @return bool
     */
    public function isRole(string $role): bool
    {
        return in_array($role, explode(',', $this->role));
    }

    /**
     * 判断当前用户是否属于多个角色
     *
     * @param array $roles
     * @return bool
     */
    public function isRoles(array $roles): bool
    {
        return count(array_intersect($roles, explode(',', $this->role))) > 0;
    }
}
