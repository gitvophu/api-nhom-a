<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        return $users = User::all();
    }

    public function paging(Request $request)
    {
        $perPage = 5;
        $page_id = $request->input('page');
        $limit = ($page_id - 1) * $perPage;

        $users = User::paginate($perPage, ['*'], $limit, $page_id)->setPageName('page');
        return $users;
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
            $user = \auth()->user();
            $user->token = str_random(32);
            $user->token_expire = strtotime('1 days');
            $user->save();

            return response()->json(['success' => $user], 200);
        } 
        else{ 
            return response()->json(['error' => false], 401); 
        }
    }
    public function logoutUser(Request $request)
    {

        $input = $request->only('email', 'password');
        if(Auth::attempt($input)){
            $user = \auth()->user();
            $user->token = str_random(32);
            //$user->token_expire = strtotime();
            $user->save();

            return response()->json(['success' => $user], 200);
        }
        else{
            return response()->json(['error' => false], 401);
        }
    }

    public function search(Request $request)
    {
        $input = $request->get('q');

        $user = User::where('name', 'LIKE', "%{$input}%")->get();

        return response()->json(['success' => $user], 200);

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
