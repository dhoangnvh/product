<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\ProductImage;

class LoggedinController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = new Product;
        $product->shop_id = $data['shop_id'];
        $product->user_id = $this->user->id;
        $product->name = $data['name'];
        $product->name_long = $data['name'];
        $product->price = $data['price'];
        $product->gender = $data['gender'];
        $product->season_spring = $data['season_spring'];
        $product->season_summer = $data['season_summer'];
        $product->season_autumn = $data['season_autumn'];
        $product->season_winter = $data['season_winter'];
        $product->brand = $data['brand'];
        $product->text = $data['text'];
        $product->categoryset_id = $data['categoryset_id'];
        $product->save();

        $files = $data['files'];

        foreach ($files as $key => $file) {
            $file->store("public/products");
            $file_name = "storage/products/".$file->hashName();

            $image = new ProductImage;
            $image->product_id = $product->id;
            $image->file_name = $file_name;
            $image->save();
        }

        // return data
        $response = $this->convertData($product);

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = Product::find($id);
        $product->shop_id = $data['shop_id'];
        $product->user_id = $this->user->id;
        $product->name = $data['name'];
        $product->name_long = $data['name'];
        $product->price = $data['price'];
        $product->gender = $data['gender'];
        $product->season_spring = $data['season_spring'];
        $product->season_summer = $data['season_summer'];
        $product->season_autumn = $data['season_autumn'];
        $product->season_winter = $data['season_winter'];
        $product->brand = $data['brand'];
        $product->text = $data['text'];
        $product->categoryset_id = $data['categoryset_id'];
        $product->save();

        $files = $data['files'];

        if (count($files) > 0) {
            ProductImage::where('product_id', $product->id)->delete();
        }

        foreach ($files as $key => $file) {
            $file->store("public/products");
            $file_name = "storage/products/".$file->hashName();

            $image = new ProductImage;
            $image->product_id = $product->id;
            $image->file_name = $file_name;
            $image->save();
        }

        // return data
        $response = $this->convertData($product);

        return response()->json($response, 200);
    }

    public function destroy(Request $request, $id)
    {
        Product::find($id)->delete();
        ProductImage::where('product_id', $id)->delete();

        return response()->json(['status'=>'ok'], 200);
    }

    public function convertData($product)
    {
        $images = $product->images;
        $shop = $product->shop;
        $category_set = $product->category_set;
        $category = $category_set == null ? null : $category_set->category()->first();

        $data = [
            'id'    => $product->id,
            'shop_id'   => $product->shop_id,
            'shop_name' => $shop->name,
            'name'  => $product->name,
            'price' => $product->price,
            'gender'    => $product->gender,
            'season_spring' => $product->season_spring,
            'season_summer' => $product->season_summer,
            'season_autumn' => $product->season_autumn,
            'season_winter' => $product->season_winter,
            'brand' => $product->brand,
            'text'  => $product->text,
            'category_name' => $category != null ? $category->breadclumbs : null,
            'images'    => $images->pluck('file_name')->toArray(),
        ];
    }
}
