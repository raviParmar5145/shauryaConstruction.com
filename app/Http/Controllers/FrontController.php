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

    public function about(){
        return view('front.pages.about');
    }

    public function contact(){
        return view('front.pages.contact');
    }

    public function contactUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ], [
            'fname.required' => 'The first name is required.',
            'fname.string' => 'The first name must be a string.',
            'fname.max' => 'The first name may not be greater than 255 characters.',
            'lname.required' => 'The last name is required.',
            'lname.string' => 'The last name must be a string.',
            'lname.max' => 'The last name may not be greater than 255 characters.',
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email.',
            'email.max' => 'The email address may not be greater than 255 characters.',
            'message.required' => 'The message is required.',
            'message.string' => 'The message must be a string.',
        ]);
    

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Save the contact form data to the database
        Contact::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Your message has been sent successfully!'
        ]);
    }

    public function privacy(){
        return view('front.pages.privacy');
    }

    public function termsConditions(){
        return view('front.pages.termsConditions');
    }

    public function refundPolicy(){
        return view('front.pages.refundPolicy');
    }

}
