<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $successStatus = 200;
    public function index()
    {
        return $users = User::all();
    }

    public function showUser($id)
    {
        $user = new User();
        $obj = $user->show($id);
        return $obj;
    }

    public function loginUser(Request $request)
    {
        $input = $request->only('email', 'password');
        if(Auth::attempt($input)){ 
            return response()->json(['success' => true], 200); 
        } 
        else{ 
            return response()->json(['error' => false], 401); 
        }
    }

    public function createUser(Request $request)
    {
        $user = new User();
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user_ = $user->create($input);
        return response()->json(['success'=> 'create success'], 200);
    }

    public function updateUser(Request $request,$id)
    {
        $user_id = User::find($id);
        $validator = Validator::make($request->all(),[
            'name' => '',
            'password' => '',
        ]);
        if($validator->fails()){
            return response()->json(['error' => 'fail'],400);
        }
        $user_id->name = $request['name'];
        if($request->name == null)
        {
            $user_id->name = $user_id->name;
        }else{
            $user_id->name = $request['name'];
        }
        if($request->password == null)
        {
            $user_id->password = $user_id->password;
        }else{
            $user_id->password = bcrypt($request->password);
        }
        $user_id->save();
        return response()->json(['success' => 'uppdate success'],200);
    }

    public function deleteUser($id)
    {
        $user = new User();
        $obj = $user->deleteuser($id);
        return response()->json(['success' => 'delete success'],204);
    }
}
