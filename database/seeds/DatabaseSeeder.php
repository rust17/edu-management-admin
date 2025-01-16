<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CourseSeeder::class,
            CourseStudentSeeder::class,
            InvoiceSeeder::class,
            TeachersTableSeeder::class,
            StudentsTableSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
