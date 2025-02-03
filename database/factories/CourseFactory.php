<?php

use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Course;
use Carbon\Carbon;

$factory->define(Course::class, function (Faker $faker) {
    $subjects = [
        'Advanced Mathematics', 'College English', 'Computer Fundamentals',
        'Data Structures', 'Operating Systems', 'Database Principles',
        'Software Engineering', 'Computer Networks', 'Artificial Intelligence',
        'Machine Learning', 'Web Development', 'Mobile App Development',
        'Python Programming', 'Java Programming', 'C++ Programming',
        'Network Security', 'Cloud Computing', 'Big Data Analytics',
        'IoT Technology', 'Blockchain Basics'
    ];

    $levels = ['Basic', 'Intermediate', 'Advanced'];
    $types = ['Theory', 'Practice', 'Seminar'];

    // Generate random year-month within last 2 years (1st day of month)
    $date = Carbon::now()->subMonths(rand(0, 24))->startOfMonth();

    return [
        'name' => $faker->randomElement($subjects) .
                 $faker->randomElement($levels) .
                 $faker->randomElement($types) . '课程',
        $faker->randomElement($levels) .
        $faker->randomElement($types) . ' Course',
        'year_month' => $date,
        'fee' => $faker->randomFloat(2, 100, 10000),
        'teacher_id' => function () {
            return factory(User::class)->create(['role' => 'teacher'])->id;
        },
    ];
});
