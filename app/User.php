<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'name', 'email', 'password', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function insertUser($data){
        User::insert([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
        ]);
    }
    
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


    public function updateWithImage($request){
        // dd($request->all());
        
        $user = User::where('email',$request->email)->first();
        if ($user) {
            // $user->phone = $request->phone;
            $user->name = $request->name;
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $file->move('uploads',$fileName);
                $user->image = $fileName;
                // $file->
               
            }
            $user->save();
            return true;
        }
        return false;
        

       
    }

    public function search($query)
    {
        $user = User::where('name','like',"%{$query}%");
        return $user;
    }

}
