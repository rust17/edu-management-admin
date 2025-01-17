<?php

namespace App\Console\Commands;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminInit extends Command
{
    /**
     * 命令名称和参数
     *
     * @var string
     */
    protected $signature = 'admin:init {--password=} {--menu=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '初始化管理员密码和菜单';

    /**
     * 执行命令
     *
     * @return mixed
     */
    public function handle()
    {
        // 初始化管理员密码
        if ($password = $this->option('password')) {
            $this->initAdminPassword($password);
        }

        // 初始化管理员菜单
        if ($menu = $this->option('menu')) {
            $this->initAdminMenu($menu);
        }
    }

    /**
     * 初始化管理员密码
     *
     * @param string $password
     */
    protected function initAdminPassword($password)
    {
        try {
            $admin = Administrator::firstOrNew([
                'username' => 'admin',
                'role' => 'admin',
            ]);

            $admin->role = 'admin';
            $admin->password = Hash::make($password);
            $admin->name = 'Administrator';

            $admin->save();

            $this->info('管理员账号已初始化');
        } catch (\Exception $e) {
            $this->error('初始化管理员密码失败: ' . $e->getMessage());
        }
    }

    /**
     * 初始化管理后台菜单
     *
     * @param string $menu
     */
    protected function initAdminMenu($menu)
    {
        try {
            // 清空现有菜单
            DB::table('admin_menu')->truncate();

            // 创建菜单
            DB::table('admin_menu')->insert([
                [
                'id' => 1,
                'title' => '教师管理',
                'icon' => 'fa-book',
                'uri' => null,
                'parent_id' => 0,
                'order' => 0,
                'permission' => 'admin',
                ],
                [
                    'id' => 2,
                    'title' => '教师列表',
                    'icon' => 'fa-bars',
                    'uri' => 'teachers',
                    'parent_id' => 1,
                    'order' => 0,
                    'permission' => 'admin',
                ],
                [
                    'id' => 3,
                    'title' => '学生管理',
                    'icon' => 'fa-users',
                    'uri' => null,
                    'parent_id' => 0,
                    'order' => 0,
                    'permission' => 'admin,teacher',
                ],
                [
                    'id' => 4,
                    'title' => '学生列表',
                    'icon' => 'fa-bars',
                    'uri' => 'students',
                    'parent_id' => 3,
                    'order' => 0,
                    'permission' => 'admin,teacher',
                ]
            ]);

            $this->info('管理后台菜单已初始化');
        } catch (\Exception $e) {
            $this->error('初始化管理后台菜单失败: ' . $e->getMessage());
        }
    }
}
