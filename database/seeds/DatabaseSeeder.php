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
           'name'=>'Nguyen b',
           'email'=>'nguyenb@gmail.com',
           'password'=>bcrypt('nguyena@gmail.com'),
       ]);
       DB::table('users')->insert([
           'name'=>'Nguyen c',
           'email'=>'nguyenc@gmail.com',
           'password'=>bcrypt('nguyena@gmail.com'),
       ]);
    }
}
