<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $primarykey = "id";

    public function showAllProduct(){
        return Product::get();
    }

    public function show($id)
    {
        return Product::where('id', '=', $id)->first();
    }

    public function pageProduct(){
        return Product::paginate(5);
    }

    public function insertProduct($data){
        Product::insert([
            'name'=>$data['name'],
            'price'=>$data['price'],
            'desciption'=>$data['desciption'],
            'created_at' => date('Y-m-d H-i-s'),
        ]);
    }

    public function updateProduct($data, $id){
        Product::where('id', $id)
            ->update([
                'name' => $data[0],
                'price'=> $data[1],
                'desciption'=> $data[2],
                'updated_at' => date('Y-m-d H-i-s'),
            ]);
    }

    public function findProductByID($id)
    {
        return Product::find($id)->first();
    }

    public function deleteProduct($id)
    {
        Product::where('id',$id)->delete();
    }
}
