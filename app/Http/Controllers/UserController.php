<?php

namespace App\Http\Controllers;

use App\User;

use CURLFile;

use App\Mail\TestEmail;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ValidatorAPI;
class UserController extends Controller
{
    protected $obj_user ;
    function __construct(){
        $this->obj_user = new User();
    }
    
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ], [
            'token.required' => 'The token field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'error' => $validator->errors()], 401);
        }
        $check = $this->obj_user->checkToken_P($request->all());
        if ($check == true) {
            $users = $this->obj_user->showAllUser();
            return response()->json(['status' => 200, 'List User' => $users], 200);
        }else{
            return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
        }
    }

    public function paging(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
        ],[
            'token.required' => 'The token field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'error' => $validator->errors()], 401);
        }
        $check =$this->obj_user->checkToken_P($request->all());
        if($check == true){
            $users = $this->obj_user->pageUser();
            return response()->json(['status' => 206, 'List User' => $users], 206);
        }else{
            return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
        }
    }
    public function showUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
        ],[
            'token.required' => 'The token field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'error' => $validator->errors()], 401);
        }
        $check = $this->obj_user->checkToken_P($request->all());
        if($check == true){
            $obj = $this->obj_user->show($id);
            if($obj){
                return response()->json(['status' => 200, 'User' => $obj], 200);
            }else{
                return response()->json(['status' => 201, 'Fail' => 'Find not User'], 201);
            }
        }else{
            return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
        }

    }

    public function loginUser(Request $request)
    {
        $input = $request->only('email','password');
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors(), 'fails' => 401], 401);            
        }
        if(Auth::attempt($input)){
            $user = \auth()->user();
            $user->token = str_random(32);
            $user->token_expire = strtotime('1 days');
            $user->save();

            return response()->json(['success' => $user], 200);
        } 
        else{ 
            return response()->json(['error' => 'Login fail'], 401); 
        }
    }
    public function logoutUser(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required',
        ],[
            'token.required' => 'The token field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 401, 'error' => $validator->errors()], 401);
        }
        $check = $this->obj_user->checkToken_P($request->all());
        if($check == true){
            $this->obj_user->logoutUser($request->all());
            return response()->json(['status' => 200, 'message' => "Logout success"], 200);
        }else{
            return response()->json(['status' => 401, 'message' => "Unauthorized user"], 401);
        }
    }

    public function search(Request $request)
    {
        if($request->token != null)
        {
            if($request->key != null)
            {
                $user_id = $this->obj_user->checkToken($request->all());
                $obj = $this->obj_user->search($request->key)->paginate(2);
                return response()->json(['success' => $obj], 200);
            }
            else {
                return response()->json(['error' => 'Unauthorized user','status' => 401], 401); 
            }
            
        }else
        {
            return response()->json(['error' => 'Unauthorized user','status' => 401], 401); 
        }
    }
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'token' => 'required'
        ],
        [
            'name.required' => 'Chưa nhập tên',
            'name.min' => 'Tên quá ngắn',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Chưa nhập password',
            'password.min' => 'Mật khẩu quá ngắn',
            'role.required' => 'Chọn quyền cho tài khoản',
            'token.required' => 'Yêu cầu xác thực người dùng'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);            
        }
        $user = $this->obj_user->createByToken($request->all());
        if(!$user){
            return response()->json(['error' => 'Unauthorized user', 'status' => 401], 401);
        }
        if (intval($user['role']) != 1){
            return response()->json(['error' => 'Can not create an object because you do not have permission.', 'status' => 401], 401);
        }
        $input = $request->all();
        //dd($request->all());
        $input['password'] = bcrypt($input['password']);
        $user_ = $user->create($input);
        return response()->json(['success'=> 'create success', 'status' => 201], 201);
    }
    public function updateUser(Request $request, $id)
    {
        $user_id = $this->obj_user->find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:2',
            'token'=> 'required',
            'role' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $check = $this->obj_user->checkToken($request->all());
        if($check['role'] == 1)
        {
                $user_id->name = $request->name;
                $user_id->role = $request->role;
        }else
        {
            return response()->json(['error' => 'Loi xac thuc nguoi dung'],500);
        }
        $user_id->save();
        return response()->json(['success' => 'Cap nhat user thanh cong'],200);
    }
    public function changeUserPassword(Request $request, $id)
    {
        
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
        $this->obj_user->updateUserChangePassword($request->all(), $id);
        return response()->json(['success' => 'Update password success', 'status' => 200], 200);
    }

    public function deleteUser(Request $request,$id)
    {
        if($request->token != null)
        {       
            $user_admin = $this->obj_user->checkToken($request->all());
            if($user_admin['role'] == -1)
            {
                $obj = $this->obj_user->deleteuser($id);
                return response()->json(['success' => 'delete success'], 200);
            }else {
                return response()->json(['error' => 'You must be Admin to delete user'], 404);
            }
        }else{
                return response()->json(['error' => 'Unauthorized user'], 406);       
        }
        
    }

    public function updateWithImage(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            // 'name'=>'required',
            'email'=>'required',
            // 'phone'=>'required',
            'image'=>'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ],[]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),201);
        }
        if ($request->token == null) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],201);
        }
       
        $rs = $this->obj_user->updateWithImage($request);
        
        if (!$rs) {
            return response()->json(['error'=>'xay ra loi'],201);
        }
        else if ($rs === 3) {
            return response()->json(['error'=>'Loi xac thuc'],201);
        }
        else if($rs === 2){
            return response()->json(['error'=>'Email ko ton tai, ko tim thay user'],201);
        }
        else{
            return response()->json(['success'=>'Cap nhat thanh cong'],200);
        }
    }
    // public function send_upload(Request $request){
    //     // dd($_FILES['image']['tmp_name']);
    //     $request = curl_init();
    //     $_token = csrf_token();
    //     curl_setopt($request,CURLOPT_URL,'http://127.0.0.1:9000/api/users/update-with-image');
    //     curl_setopt($request,CURLOPT_POST,true);
        
    //     $cfile = new CURLFile($_FILES['image']['tmp_name'],$_FILES['image']['type'],$_FILES['image']['name']);
    //     curl_setopt($request,CURLOPT_POSTFIELDS,[
    //         // '_token'=>$_token,
    //         // 'image'=>'@'.$_FILES['image']['tmp_name'],
    //         // 'image_name'=>$_FILES['image']['name']
    //         'image'=>$cfile
    //     ]);
    //     $rs = curl_exec($request);
    //     curl_close($request);
    //     dd($rs);
    // }

    function phuSendMail(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
        ],[]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),201);
        }
        $email = $request->email;
        if ($email == null) {
            return response()->json(['error'=>'Chua truyen email']);
        }
        $reset_pass_token = str_random(30);
        $user = $this->obj_user->where('email',$email)->first();
        if (!$user) {
            return response()->json(['error'=>'Email ko ton tai']);
        }
        $user->reset_pass_token = $reset_pass_token;
        $user->save();
        $link = route('reset-link',['token'=>$reset_pass_token,'email'=>$email]);
        // dd($link);
        Mail::send('emails.reset_email', array(
            'link'=> $link
        ), function($message) use ($email){
	        $message->to($email, 'User')->subject('Xin chào');
	    });
        
        return response()->json(['success'=>'Gửi email thành công']);
    }

    function phuResetLink($token, $email){
        $user = $this->obj_user->where('email',$email)->first();

        if ($user->reset_pass_token == $token) {
            return view("reset_form",compact('email'));
        }
        else{
            echo "sai ";
        }
    }
    function do_reset(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|min:6'
        ],[]);
        if ($validator->fails()) {
            echo "Reset mật khẩu thất bại, lỗi dữ liệu truyền vào ko đúng ràng buộc";
        }
        $email = $request->email;
        $password = $request->password;
        $user = User::where('email',$email)->first();
        $user->password = bcrypt($password);
        $user->reset_pass_token = "";
        $user->save();
        echo "Reset mật khẩu thành công";
    }
}
