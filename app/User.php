<?php

namespace App;

use App\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "users";
    protected $primarykey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
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
