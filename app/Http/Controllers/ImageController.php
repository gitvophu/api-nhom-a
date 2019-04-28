<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    protected $obj_image ;
    function __construct(){
        $this->obj_image = new Image();
    }
    function delete(Request $request, $id){
        $user = User::where('token',$request->token)->get();
        if ( count($user) <= 0) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],201);
        }
        $image = Image::where('id',$id)->first();
        $image->delete();
        return response()->json(['success'=>'xóa thanh cong'],200);

    }
    function store(Request $request){
        $validator = Validator::make($request->all(),[
            'product_id'=>'required',
            // 'image'=>'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ],[]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),201);
        }
        if ($request->token == null) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],201);
        }
        $user = User::where('token',$request->token)->get();
       
        if ( count($user) <= 0) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],201);
        }
       
        $rs = $this->obj_image->store($request);
        if ($rs) {
            return response()->json(['success'=>'Thêm thanh cong'],200);
        }
        else{
            return response()->json(['error'=>'Sản phẩm không tồn tại'],201);
        }

    }
    function update_image(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            // 'image'=>'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ],[]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),201);
        }
        if ($request->token == null) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],201);
        }
        $user = User::where('token',$request->token)->get();
       
        if ( count($user) <= 0) {
            return response()->json(['error'=>'Loi xac thuc nguoi dung'],201);
        }
    //    dd($request);
        $rs = $this->obj_image->update_image($request);
        if ($rs) {
            return response()->json(['success'=>'Cap nhat thanh cong'],200);
        }
        else{
            return response()->json(['error'=>'Cập nhật thất bại, ảnh ko tồn tại '],201);
        }

    }
}
