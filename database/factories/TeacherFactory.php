<?php

use App\Models\Teacher;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->state('teacher')->create()->id;
        },
    ];
});