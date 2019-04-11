<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
class User extends Model
{
    protected $table = "users";
    protected $primarykey = "id";

    public static function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function create($user)
    {
        User::insert([
            'name' => $user['name'],
            'email' =>$user['email'],
            'password' => $user['password'],
        ]);
    }

    public function deleteuser($id)
    {
        $user = User::where('id',$id);
        $user->delete();
    }

}
