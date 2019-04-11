<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(){
        return response()->json(['list' => User::all()], 200);
    }

    public function login(Request $request){
        $req = $request->only('email', 'password');
        if(Auth::attempt($req))
        {
            return response()->json(['login' => true], 200);
        }else {
            return response()->json(['login' => false], 401);
        }
    }

    public function userInfo($id){
        $user = User::find($id);
        if(!$user)
        {
            return response()->json(['error' => 'Not found!'], 404);
        }
        return response()->json(['success' => $user], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::insert($input);
        var_dump($user);die();
        return response()->json(['success' => 'Create success'], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => '',
            'email' => 'email',
            'password' => ''
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::where('id', $id)->update($input);
        return response()->json(['success' => $user], 200);
    }

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'Delete seccuess'], 204);
    }
}
