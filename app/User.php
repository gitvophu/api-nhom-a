<?php

namespace App;

use App\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

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

    public static function register($data)
    {
        $user = new User();
        $user->email = $data['email'];
        $user->phone = $data['tel'];
        $user->password = $data['password'];
        $user->name = $data['name'];
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();
        // DB::table('users')->insert([
        //     'email'=>$data['email'],
        //     'phone'=>$data['tel'],
        //     'password'=>bcrypt($data['password']),
        //     'name'=>$data['name'],
        //     'created_at'=>date('Y-m-d H:i:s'),
        //     'updated_at'=>date('Y-m-d H:i:s'),
        // ]);
    }

    public static function check_user_is_active($credentials)
    {

        $data = [];
        $user_attempt = User::where('email', $credentials['email'])->first();

        if ($user_attempt) {
            // dd($user_attempt);
            if ($user_attempt->active == 0) {
                // dd('active 0');
                $minutes = round((time() - strtotime($user_attempt->last_access)) / 60);
                // $minutes = $user_attempt->last_access;
                if ($minutes >= 30) {
                    User::where('email', $credentials['email'])
                        ->update([
                            'active' => 1,
                            'last_access' => date('Y-m-d H:i:m'),
                        ]);
                } else {
                    $data['flag'] = false;
                    $data['minutes'] = $minutes;
                }
            } else {
                $data['flag'] = true;
            }
        }

        return $data;
    }

    public static function try_login($credentials)
    {
        $flag = false;
        $user_attempt = User::where('email', $credentials['email'])->first();
        if (Auth::attempt($credentials)) {

            User::where('email', $credentials['email'])
                ->update([
                    'attempt' => 0,
                ]);
            // return redirect()->route('index');
            $flag = true;
        } else {

            if ($user_attempt) {
                User::where('email', $credentials['email'])
                    ->update([
                        'last_access' => date('Y-m-d H:i:s'),
                        'attempt' => $user_attempt->attempt + 1,
                    ]);
                if ($user_attempt->attempt + 1 >= 3) {
                    User::where('email', $credentials['email'])
                        ->update([
                            'active' => 0,
                        ]);
                }
            }
            $flag = false;
            // return redirect()->route('login-get')->with('fail_msg','Sai tài khoản hoặc mật khẩu');

        }
        return $flag;
    }

    public static function updateUser($data)
    {

        if ($data['password'] != null) {

            User::where('email', $data['email'])
                ->update([
                    'password' => bcrypt($data['password']),
                    // 'email'=>$request->email,
                    'phone' => $data['tel'],
                    'name' => $data['name'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        } else {

            User::where('email', $data['email'])
                ->update([
                    // 'email'=>$request->email,
                    'phone' => $data['tel'],
                    'name' => $data['name'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }
    }
}
