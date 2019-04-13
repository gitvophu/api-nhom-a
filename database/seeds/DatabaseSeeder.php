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
           'name'=>'Nguyen a',
           'email'=>'nguyenc@gmail.com',
           'password'=>bcrypt('nguyena@gmail.com'),
       ]);
        DB::table('users')->insert([
            'name'=>'Nguyen aa',
            'email'=>'nguyen1@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen c',
            'email'=>'nguyen2@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen b',
            'email'=>'nguyen3@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen c',
            'email'=>'nguyen4@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen b',
            'email'=>'nguyen5@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen c',
            'email'=>'nguyen6@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen b',
            'email'=>'nguyen7@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen c',
            'email'=>'nguyen8@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen b',
            'email'=>'nguyen9@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen c',
            'email'=>'nguyen10@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen b',
            'email'=>'nguyen11@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
        DB::table('users')->insert([
            'name'=>'Nguyen c',
            'email'=>'nguyen12@gmail.com',
            'password'=>bcrypt('nguyena@gmail.com'),
        ]);
    }
}
