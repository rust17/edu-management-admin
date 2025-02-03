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
        // Create teacher profiles for all users with teacher role
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
