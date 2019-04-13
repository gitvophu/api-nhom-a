<?php

use Faker\Factory;
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
        DB::table('users')->insert([
            'name' => 'Nguyen A',
            'email' => 'nguyena@gmail.com',
            'password' => bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen B',
            'email' => 'nguyenb@gmail.com',
            'password' => bcrypt('nguyenb@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen C',
            'email' => 'nguyenc@gmail.com',
            'password' => bcrypt('nguyenc@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen D',
            'email' => 'nguyend@gmail.com',
            'password' => bcrypt('nguyend@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen E',
            'email' => 'nguyene@gmail.com',
            'password' => bcrypt('nguyene@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen F',
            'email' => 'nguyenf@gmail.com',
            'password' => bcrypt('nguyenf@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen G',
            'email' => 'nguyeng@gmail.com',
            'password' => bcrypt('nguyeng@gmail.com'),
        ]);

    }
}
