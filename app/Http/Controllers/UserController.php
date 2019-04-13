<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Arr;

use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;
class UserController extends Controller
{
    protected $obj_user ;
    function __construct(){
        $this->obj_user = new User();
    }
    public function index()
    {
        $users = User::all();
        return response()->json(['List User' => $users], 200);
    }

    public function paging(Request $request)
    {
        $users = User::paginate(3);
        return $users;
    }
    public function showUser($id)
    {
        $user = new User();
        $obj = $user->show($id);
        if($obj){
            return response()->json(['Success' => $obj], 200);
        }
        return response()->json(['Fail' => 'Không tìm thấy User'], 201);

    }

    public function loginUser(Request $request)
    {
        $input = $request->only('email', 'password');
        if(Auth::attempt($input)){
            $user = \auth()->user();//lấy chính nó
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
        $input = $request->input('token');
        $user = User::where('token', '=', $input)
            ->update([
               'token' => null,
               'token_expire' => null,
            ]);
        if ($user){
            return response()->json([
                'message' => "logout success"
            ], 200);
        }
        return response()->json([
            'message' => "Unauthorized user"
        ], 401);
    }

    public function search(Request $request)
    {
        $input = $request->get('key');
        $user = new User();
        $obj = $user->search($input)->paginate(2);
        return response()->json(['success' => $obj], 200);

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

    public function updateWithImage(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'image'=>'required',
        ],[]);
        if ($validator->fails()) {
            return response()->json(['error'=>'Tham so truyen vao con thieu'],201);
        }
        $rs = $this->obj_user->updateWithImage($request);
        if ($rs) {
            return response()->json(['success'=>'Cap nhat thanh cong'],200);
        }
        else{
            return response()->json(['error'=>'Email ko ton tai, ko tim thay user'],201);
        }
    }
    public function send_upload(Request $request){
        // dd($_FILES['image']['tmp_name']);
        $request = curl_init();
        $_token = csrf_token();
        curl_setopt($request,CURLOPT_URL,'http://127.0.0.1:9000/api/users/update-with-image');
        curl_setopt($request,CURLOPT_POST,true);
        
        $cfile = new CURLFile($_FILES['image']['tmp_name'],$_FILES['image']['type'],$_FILES['image']['name']);
        curl_setopt($request,CURLOPT_POSTFIELDS,[
            // '_token'=>$_token,
            // 'image'=>'@'.$_FILES['image']['tmp_name'],
            // 'image_name'=>$_FILES['image']['name']
            'image'=>$cfile
        ]);
        $rs = curl_exec($request);
        curl_close($request);
        dd($rs);
    }
}
