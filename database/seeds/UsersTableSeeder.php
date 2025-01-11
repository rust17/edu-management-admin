<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 创建一个管理员
        factory(User::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'name' => '系统管理员'
        ]);

        // 创建5个教师
        factory(User::class, 5)->create([
            'role' => 'teacher'
        ]);

        // 创建20个学生
        factory(User::class, 20)->create([
            'role' => 'student'
        ]);
    }
}
