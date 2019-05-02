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
            'email' => 'truongdieumy97@gmail.com',
            'password' => bcrypt('123456'),
            'role' => '-1',
        ]);
        DB::table('users')->insert([
            'name' => 'chan',
            'email' => 'chan@gmail.com',
            'password' => bcrypt('chan@gmail.com'),
            'role' => '1',
        ]);
        DB::table('users')->insert([
            'name' => 'Nguyen C',
            'email' => 'nguyenc@gmail.com',
            'password' => bcrypt('nguyenc@gmail.com'),
            'role' => '0',
        ]);
        DB::table('users')->insert([
            'name' => 'Vo Ngoc Phu',
            'email' => 'vongocphu04@gmail.com',
            'password' => bcrypt('vongocphu04@gmail.com'),
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

        //product
        DB::table('products')->insert([
            'name' => 'product 1',
            'price' => '100000',
            'description' => 'test san pham 1',
            'cate_id' => 1,
        ]);

        DB::table('products')->insert([
            'name' => 'product 2',
            'price' => '100000',
            'description' => 'test san pham 2',
            'cate_id' => 2,
        ]);
        DB::table('products')->insert([
            'name' => 'product 3',
            'price' => '100000',
            'description' => 'test san pham 3',
            'cate_id' => 3,
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

        //table user_type
        DB::table('user_type')->insert([
            'role' => 'admin',
        ]);

        DB::table('user_type')->insert([
            'role' => 'teacher',
        ]);


        DB::table('user_type')->insert([
            'role' => 'student',
        ]);

        DB::table('product_categories')->insert([
            'name' => 'Cate 1',
        ]);

        DB::table('product_categories')->insert([
            'name' => 'Cate 2',
        ]);

        DB::table('product_categories')->insert([
            'name' => 'Cate 3',
        ]);

    }
}
