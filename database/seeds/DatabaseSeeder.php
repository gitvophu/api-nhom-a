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
        $faker = Faker\Factory::create();
        //tao user
        for($i=0;$i<100;$i++){
            DB::table('users')->insert([
                'name'=>$faker->name,
                'email'=>$faker->email,
                'password'=>bcrypt('111'),
     
            ]);
        }

        //cate
        

      
    }
}
