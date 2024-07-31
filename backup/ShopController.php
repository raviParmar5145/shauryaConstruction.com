<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\subCategory;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null){
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];


        $categories =  Category::orderBy('name', 'ASC')->with('sub_category')->where('status',1)->get();
        $brands =  Brand::orderBy('name', 'ASC')->where('status',1)->get();
        //dd($request);
        $products =  Product::where('status',1);

        // Apply Filter here
        if (!empty($categorySlug)) {
            $category = Category::where('slug',$categorySlug)->first();
            $products = Product::where('category_id',$category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = subCategory::where('slug',$subCategorySlug)->first();
            $products = Product::where('sub_category_id',$subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',',$request->get('brand'));   
            $products = $products->whereIn('brand_id',$brandsArray);
        }

        if ($request->get('price_max') != '' && $request->get('price_min') != '') { 
            $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
        }
        



        $products =  $products->orderBy('id', 'DESC');
        $products =  $products->get();


        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;
        $data['priceMax'] = intval($request->get('price_max'));
        $data['priceMin'] = intval($request->get('price_min'));
        return view('front.shop', $data);
    }

    public function product($slug) {
        $product = Product::where('slug',$slug)->with('product_image')->first();
        if ($product == null) {
            abort(404);
        }

        // Fetch related product
        $relatedProducts = [];
        if($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts =  Product::whereIn('id',$productArray)->with('product_image')->get();
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;
        return view('front.product', $data);
    }
}
