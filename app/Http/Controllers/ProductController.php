<?php
/**
 * Created by PhpStorm.
 * User: phong
 * Date: 4/22/2019
 * Time: 12:37 PM
 */

namespace App\Http\Controllers;

use App\Http\Models\Product;
use App\Models\Image;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $obj_product;
    protected $obj_validator;
    private $productTransform;
    public function __construct(ProductTransformer $productTransform)
    {
        $this->obj_product = new Product();
        // validators
        // $this->obj_validator = new ProductValidator();
        $this->productTransform = $productTransform;
    }
    public function transformProduct($product)
    {
                $new_product = [];
                $images = Image::where('product_id', $product['id'])->get();
                foreach ($images as $image) {
                    $product['image'][] = asset("uploads/" . $image->name);
                }
                $new_product = [
                    'id'=>$product['id'],
                    'name'=>$product['name'],
                    'description'=>$product['description'],
                    'cate_name'=>$product['cate_name'],
                    'image'=>$product['image'],
                ];
        return $new_product;
    }
    public function transformProductArray($products)
    {
        $new_products = [];
        if (is_array($products)) {
            # code...
            foreach ($products as $product) {
                $images = Image::where('product_id', $product['id'])->get();
                foreach ($images as $image) {
                    $product['image'][] = asset("uploads/" . $image->name);
                }
                $new_product = [
                    'id'=>$product['id'],
                    'name'=>$product['name'],
                    'description'=>$product['description'],
                    'cate_name'=>$product['cate_name'],
                    'image'=>$product['image'],
                ];
                $new_products[] = $new_product;
            }
        }
       
        return $new_products;
    }
    public function index()
    {
        $products = $this->obj_product->showAllProduct();
        $products = $products->toArray();
        $products = $this->transformProductArray($products);
        // dd($products);
        return response()->json(['status' => 200, 'List Product' => $products], 200);
    }

    public function paging()
    {
        $products = $this->obj_product->pageProduct();
        return response()->json(['status' => 206, 'List Product' => $products], 206);
    }

    public function showProduct($id)
    {
        $obj_product = $this->obj_product->show($id);
        if (!$obj_product) {
            return response()->json(['status' => 201, 'Fail' => 'Find not Product'], 201);
        }
        $obj_product = $this->transformProduct($obj_product);
        return response()->json(['status' => 200, 'Product' => $obj_product], 200);
    }

    public function createProduct(Request $request)
    {

//        $validator = Validator::make($request->all(), [
        //            'name' => 'required',
        //            'price' => 'required|numeric',
        //            'description' => 'required',
        //        ],
        //            [
        //                'name.required' => 'Chưa nhập tên',
        //                'price.required' => 'Chưa nhập giá',
        //                'price.numeric' => 'Giá phải nhập số',
        //                'description.required' => 'Chưa nhập nội dung',
        //            ]);
        //        if ($validator->fails()) {
        //            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);
        //        }
        if ($this->obj_validator->validate($request->all())) {
            $this->obj_product->insertProduct($request->all());
            return response()->json(['success' => 'create success', 'status' => 200], 200);
        } else {
            return response()->json(['error' => $this->obj_validator->errors(), 'status' => 400], 400);
        }

    }

    public function updateProduct(Request $request, $id)
    {
        $product = $this->obj_product->findProductByID($id);

        $validator = Validator::make($request->all(), [
            'name' => '',
            'price' => 'numeric',
            'description' => '',
        ],
            [
                'price.numeric' => 'Giá phải nhập số',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => 400], 400);
        }

        $name = $request->name;
        $price = $request->price;
        $disciption = $request->description;

        if ($product) {
            if ($name == null) {
                $name = $product->name;
            }
            if ($price == null) {
                $price = $product->price;
            }
            if ($disciption == null) {
                $disciption = $product->description;
            }
            $input = [$name, $price, $disciption];

            $this->obj_product->updateProduct($input, $id);

            return response()->json(['success' => 'Update success', 'status' => 201], 201);
        } else {
            return response()->json(['message' => 'Find not product', 'status' => 404], 404);
        }

    }

    public function deleteProduct($id)
    {
        $product = $this->obj_product->find($id);
        if ($product) {
            $this->obj_product->deleteProduct($id);
            return response()->json(['success' => 'delete success', 'status' => 204], 204);
        } else {
            return response()->json(['message' => 'Find not product', 'status' => 404], 404);
        }

    }
}
