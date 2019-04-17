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
        $validator = Validator::make($request->all(),[
            'key' => '',
            'token'=>'required',
        ],
        [
            'token' => 'Unauthorized user',
        ]);
        $user_id = User::where('token','=',$request->token)->get();
        if($request->token == null)
        {
            return response()->json(['error' => $validator->errors(), 'Unauthorized user' => 401], 401); 
        }else
        {
            $user = new User();
            $obj = $user->search($request->key)->paginate(2);
            return response()->json(['success' => $obj], 200);
        }
    }
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'token' => 'required'
        ],
        [
            'name.required' => 'Chưa nhập tên',
            'email.required' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu quá ngắn',
            'token.required' => 'Yeu cau xac thuc nguoi dung'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);            
        }
        $user = User::createByToken($request->all());
        if(!$user){
            return response()->json(['error' => 'Unauthorized user', 'status' => 401], 401);
        }
        if (intval($user['role']) != 0){
            return response()->json(['error' => 'Can not create an object because you do not have permission.', 'status' => 401], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user_ = $user->create($input);
        return response()->json(['success'=> 'create success', 'status' => 201], 201);
    }
    public function updateUser(Request $request, $id)
    {
        // dd($request->all());
        $user_id = User::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'token'=>'required'

        ]);
        // if($validator->fails()){
        //     return response()->json(['error' => 'fail'],400);
        // }
        if ($request->token == null) {
            return response()->json(['error' => 'Loi xac thuc nguoi dung'],401);
           
        }
        else{
            if ($user_id->token == $request->token) {
                $user_id->name = $request->name;
            }
            else{
                return response()->json(['error' => 'Loi xac thuc nguoi dung'],401);
            }
        }
       
        
        
        $user_id->save();
        return response()->json(['success' => 'Cap nhat user thanh cong'],200);
    }
    public function changeUserPassword(Request $request, $id)
    {        
        // if($request->password != null)
        // {            
        //     if($request->name != null)
        //     {
        //         $validator = Validator::make($request->all(),
        //             [
        //                 'name' => 'required|min:3',
        //                 'password' => 'required|min:6'
        //             ]
        //         );
        //         if ($validator->fails()) {
        //             return response()->json(['error' => $validator, 'status' => 401], 401);
        //         }
        //         else {
        //             User::updateUserChangeName_Password($request->all(), $id);
        //             return response()->json(['success' => 'Update name, password success', 'status' => 200], 200);
        //         }
        //     }
        //     else {
        //         $validator = Validator::make($request->all(),
        //             [
        //                 'password' => 'required|min:6'
        //             ]
        //         );
        //         if ($validator->fails()) {
        //             return response()->json(['error' => $validator, 'status' => 401], 401);
        //         }
        //         else {
        //             User::updateUserChangePassword($request->all(), $id);
        //             return response()->json(['success' => 'Update password success', 'status' => 200], 200);
        //         }
        //     }
        // }
        // else {
        //     if($request->name == null)
        //     {
        //         return response()->json(['error' => 'Name not null', 'status' => 401], 401);
        //     }
        // }
        // User::updateUserNoChangePassword($request->all(), $id);
        $user = User::show($id);
        
        $validator = Validator::make($request->all(),
            [
                'password' => 'required|min:6',
                'token' => 'required',
            ],
            [
                'password.required' => 'Mật khẩu quá ngắn',
                'token.required' => 'Yêu cầu xác thực người dùng'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);
        }
        if($user->token != $request->token)
        {
            return response()->json(['error' => 'Unauthorized user', 'status' => 401], 401);
        }
        User::updateUserChangePassword($request->all(), $id);
        return response()->json(['success' => 'Update password success', 'status' => 200], 200);
    }

    public function deleteUser(Request $request,$id)
    {
        $input = $request->get('token');
        $user_admin = User::where('token','=',$request->token)->first();
        if($input != null)
        {
            if($user_admin['role'] == 0)
            {
                $user = new User();
                $obj = $user->deleteuser($id);
                return response()->json(['success' => 'delete success'], 200);
            }else {
                return response()->json(['error' => 'Admin moi xoa duoc'], 404);
            }
        }else{
                return response()->json(['error' => 'Unauthorized user'], 206);       
        }
        
    }

    public function upload()
    {
        
    }

    public function updateWithImage(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            // 'name'=>'required',
            'email'=>'required',
            // 'phone'=>'required',
            'image'=>'required',
        ],[]);
        if ($validator->fails()) {
            return response()->json(['error'=>'Tham so truyen vao con thieu'],201);
        }
        if ($request->token == null) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],401);
        }
        else{
            
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
