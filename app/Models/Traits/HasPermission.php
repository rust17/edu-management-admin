<?php

namespace App\Models\Traits;

use Encore\Admin\Auth\Database\Administrator;

trait HasPermission
{
    /**
     * Check if current user can see menus for specified roles
     * Since we don't use Laravel-admin's role permission system, we implement a simple permission management here
     *
     * @param Administrator $admin Current user
     * @param string $permissions Menu permission string, e.g.: admin,teacher
     * @return bool
     */
    public static function visible(Administrator $admin, string $permissions): bool
    {
        // Teacher management menu is only visible to admin
        if ($admin->role == 'admin' || in_array($admin->role, explode(',', $permissions))){
            return true;
        }

        return false;
    }
}
