<?php

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'invoice_id' => function () {
            return factory(Invoice::class)->create()->id;
        },
        'student_id' => function () {
            return factory(User::class)->create(['role' => 'student'])->id;
        },
        'amount' => $faker->randomFloat(2, 100, 10000),
        'paid_at' => now(),
        'transaction_no' => Str::random(30),
        'transaction_fee' => 10,
        'payment_platform' => 'omise',
        'payment_method' => 'card',
    ];
});
