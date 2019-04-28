<?php

namespace App\Models;

use App\Http\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = "images";

    public function store($request)
    {
        // dd($request->all());

        $product = Product::where('id', $request->product_id)->first();

        if ($product != null) {
            // $user->phone = $request->phone;
            // $user->name = $request->name;

            if ($request->hasFile('image')) {
                $files = $request->file('image');
                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName();
                    $file->move('uploads', $fileName);
                    $image = new Image();
                    $image->product_id = $request->product_id;
                    $image->name = $fileName;
                    $image->save();
                }

                // $file->

            }
            // $user->save();
            return true;
        } else {
            return false;
        }
        return false;

    }
    public function update_image($request)
    {
    
        $image = Image::where('id', $request->id)->first();

        if ($image != null) {
            // $user->phone = $request->phone;
            // $user->name = $request->name;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $file->move('uploads', $fileName);
                $image->name = $fileName;
                $image->save();

                // $file->

            }
            // $user->save();
            return true;
        } else {
            return false;
        }
        return false;

    }
}
