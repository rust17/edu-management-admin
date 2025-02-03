<?php

namespace App\Console\Commands;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminInit extends Command
{
    /**
     * Command name and arguments
     *
     * @var string
     */
    protected $signature = 'admin:init {--password=} {--menu=}';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Initialize admin password and menu';

    /**
     * Execute command
     *
     * @return mixed
     */
    public function handle()
    {
        // Initialize admin password
        if ($password = $this->option('password')) {
            $this->initAdminPassword($password);
        }

        // Initialize admin menu
        if ($menu = $this->option('menu')) {
            $this->initAdminMenu($menu);
        }
    }

    /**
     * Initialize admin password
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

            $this->info('Admin account initialized');
        } catch (\Exception $e) {
            $this->error('Failed to initialize admin password: ' . $e->getMessage());
        }
    }

    /**
     * Initialize admin menu
     *
     * @param string $menu
     */
    protected function initAdminMenu($menu)
    {
        try {
            // Clear existing menu
            DB::table('admin_menu')->truncate();

            // Create menu
            DB::table('admin_menu')->insert([
                [
                    'id' => 1,
                    'title' => 'Teacher Management',
                    'icon' => 'fa-book',
                    'uri' => null,
                    'parent_id' => 0,
                    'order' => 0,
                    'permission' => 'admin',
                ],
                [
                    'id' => 2,
                    'title' => 'Teacher List',
                    'icon' => 'fa-bars',
                    'uri' => 'teachers',
                    'parent_id' => 1,
                    'order' => 0,
                    'permission' => 'admin',
                ],
                [
                    'id' => 3,
                    'title' => 'Student Management',
                    'icon' => 'fa-users',
                    'uri' => null,
                    'parent_id' => 0,
                    'order' => 0,
                    'permission' => 'admin,teacher',
                ],
                [
                    'id' => 4,
                    'title' => 'Student List',
                    'icon' => 'fa-bars',
                    'uri' => 'students',
                    'parent_id' => 3,
                    'order' => 0,
                    'permission' => 'admin,teacher',
                ]
            ]);

            $this->info('Admin menu initialized');
        } catch (\Exception $e) {
            $this->error('Failed to initialize admin menu: ' . $e->getMessage());
        }
    }
}
