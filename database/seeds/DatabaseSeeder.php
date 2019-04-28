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
            'name' => 'Dieu My',
            'email' => 'dieumy@gmail.com',
            'password' => bcrypt('dieumy@gmail.com'),
            'role' => (0),
        ]);
        DB::table('users')->insert([
            'name' => 'chan',
            'email' => 'chan@gmail.com',
            'password' => bcrypt('chan@gmail.com'),
            'role' => (1),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen C',
            'email' => 'nguyenc@gmail.com',
            'password' => bcrypt('nguyenc@gmail.com'),
            'role' => (1),
        ]);
        DB::table('users')->insert([
            'name' => 'Vo Ngoc Phu',
            'email' => 'vongocphu04@gmail.com',
            'password' => bcrypt('vongocphu04@gmail.com'),
            'role' => (1),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen E',
            'email' => 'nguyene@gmail.com',
            'password' => bcrypt('nguyene@gmail.com'),
            'role' => (1),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen F',
            'email' => 'nguyenf@gmail.com',
            'password' => bcrypt('nguyenf@gmail.com'),
            'role' => (1),
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen G',
            'email' => 'nguyeng@gmail.com',
            'password' => bcrypt('nguyeng@gmail.com'),
            'role' => (1),
        ]);

        //product
        DB::table('products')->insert([
            'name' => 'product 1',
            'price' => '100000',
            'desciption' => 'test san pham 1',
        ]);

        DB::table('products')->insert([
            'name' => 'product 2',
            'price' => '100000',
            'desciption' => 'test san pham 2',
        ]);
        DB::table('products')->insert([
            'name' => 'product 3',
            'price' => '100000',
            'desciption' => 'test san pham 3',
        ]);

        //images
        DB::table('images')->insert([
            'name' => 'image1.jpg',
            'product_id' => '1',
        ]);
        DB::table('images')->insert([
            'name' => 'image2.jpg',
            'product_id' => '1',
        ]);
        DB::table('images')->insert([
            'name' => 'image3.jpg',
            'product_id' => '2',
        ]);

    }
}
