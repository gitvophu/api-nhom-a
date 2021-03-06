<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->text('permissions')->nullable();
            $table->boolean('activated')->default(0);
            $table->boolean('banned')->default(0);
            $table->string('activation_code')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('persist_code')->nullable();
            $table->string('reset_password_code')->nullable();
            $table->boolean('protected')->default(0);
            $table->timestamps();
            // setup index
            $table->unique('email');
            $table->index('activation_code');
            $table->index('reset_password_code');
            //nhom a
            $table->string('name',100)->nullable();
            $table->string('image',100)->nullable();
            $table->string('token',100)->nullable();
            $table->string('reset_pass_token',100)->nullable();
            $table->integer('token_expire')->nullable();
            $table->integer('token_status')->nullable();
            $table->integer('role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }

}
