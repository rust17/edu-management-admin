<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create student profiles for all users with student role
        User::where('role', User::ROLE_STUDENT)
            ->whereNotIn('id', Student::pluck('user_id')->toArray())
            ->chunk(100, function ($students) {
                $students->each(function ($student) {
                    Student::create([
                        'user_id' => $student->id,
                    ]);
                });
            });
    }
}
