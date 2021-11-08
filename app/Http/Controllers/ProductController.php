<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {        
        return response()->json(['user' => $this->user]);
    }

    public function getDetail(Request $request, $id)
    {
        $product = Product::find($id);
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

        return response()->json($data, 200);
    }

    public function searchProduct(Request $request)
    {
        $text = $request->keyword;
        $products = Product::with(['images', 'category_set.category', 'shop'])->where('name', 'like', "%$text%")->get();
        $data = [];
        foreach ($products as $product) {
            $images = $product->images;
            $shop = $product->shop;
            $category_set = $product->category_set;
            $category = $category_set == null ? null : $category_set->category()->first();

            $data[] = [
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

        return response()->json($data, 200);
    }

    public function getProductBySeason($season, $limit=9, $page=1)
    {
        $skip = ($page - 1) * $limit;
        if ($limit == 9) {
            $products = Product::with(['images', 'category_set.category', 'shop'])
                ->where($season, 1)->inRandomOrder()->limit(9)->get();
        } else {
            $products = Product::with(['images', 'category_set.category', 'shop'])
                ->where($season, 1)->skip(intval($skip))->take(intval($limit))->get();
        }

        $data = [];
        foreach ($products as $product) {
            $images = $product->images;
            $shop = $product->shop;
            $category_set = $product->category_set;
            $category = $category_set == null ? null : $category_set->category()->first();

            $data[] = [
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

        return response()->json($data, 200);
    }
}
