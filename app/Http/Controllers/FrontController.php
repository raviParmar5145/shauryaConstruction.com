<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FrontController extends Controller
{
    public function index() {
        $products = Product::where('is_featured','Yes')
            ->orderBy('id','DESC')
            ->where('status',1)
            ->take(8)
            ->get();
        $latestProducts = Product::orderBy('id','DESC')
            ->where('status',1)
            ->take(8)
            ->get();
       //dd($latestProducts);
        $data['featuredProducts'] = $products;
        $data['latestProducts'] = $latestProducts;
        return view('front.home',$data);
    }

}
