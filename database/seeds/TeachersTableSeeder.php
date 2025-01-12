<?php

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 为所有教师角色的用户创建教师扩展信息
        User::where('role', User::ROLE_TEACHER)
            ->whereNotIn('id', Teacher::pluck('user_id')->toArray())
            ->chunk(100, function ($teachers) {
                $teachers->each(function ($teacher) {
                    Teacher::create([
                        'user_id' => $teacher->id,
                    ]);
                });
            });
    }
}
