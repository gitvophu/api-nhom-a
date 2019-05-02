<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\User_Type;
use App\User;

class UserTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $obj_user ;
    protected $user;
    function __construct(){
        $this->obj_user = new User_Type();
        $this->user = new User();
    }
    //list user-type
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
        $check = $this->user->checkToken($request->all());
        if ($check['role'] == -1) {
            $users = $this->obj_user->showAllUser_Type();
            return response()->json(['status' => 200, 'List UserType' => $users], 200);
        }else{
            return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'role' => 'required',
            'token' => 'required',
        ],
        [
            'role.required' => 'Chưa nhập quyền',
            'token.required' => 'Tài khoản chưa xác thực',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);            
        }
        $check = $this->user->checkToken($request->all());
        if ($check['role'] == -1) {
            $obj = $this->obj_user->createUserType($request->all());
            return response()->json(['status' => 200, 'Create success' => $obj], 200);
        }else{
            return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $usertype = $this->obj_user->find($id);
        $validator = Validator::make($request->all(),[
            'role' => '',
            'token' => 'required',
        ],
        [
            'token.required' => 'Tài khoản chưa xác thực',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);            
        }
        $usertype->role = $request->role;
        $check = $this->user->checkToken($request->all());
        if ($check['role'] == -1) {
            $obj = $this->obj_user->updateUserType($id,$usertype->role);
            return response()->json(['status' => 200, 'Update success' => $obj], 200);
        }else{
            return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        
        $usertype = $this->obj_user->find($id);
        $validator = Validator::make($request->all(),[
            'token' => 'required',
        ],
        [
            'token.required' => 'Tài khoản chưa xác thực',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);            
        }
        if($usertype)
        {
            if($request->token != null)
            {       
                $check = $this->user->checkToken($request->all());
                if ($check['role'] == -1) {
                    $obj = $this->obj_user->deleteUserType($id);
                    return response()->json(['status' => 200, 'Delete success' => $obj], 200);
                }else{
                    return response()->json(['status' => 401, 'error' => 'Account has not been verified'], 401);
                }
            }
        }
    }
}
