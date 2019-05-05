<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class User_Type extends Model
{
    protected $table = 'user_type';
    protected $primaryKey = 'id';

    public function showAllUser_Type()
    {
        return User_Type::select('id','name')->get();
    }

    public function createUserType($data)
    {
        User_Type::insert([
            'name' => $data['name'],
        ]);
    }


    public function updateUserType($id,$data)
    {
        User_Type::where('id',$id)
        ->update([
            'name' => $data,
        ]);
    }

    public function deleteUserType($id)
    {
        User_Type::where('id',$id)->delete();
    }

}
