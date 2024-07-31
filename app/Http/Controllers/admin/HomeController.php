<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Order;

class HomeController extends Controller
{
    public function index() {
        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();
    
        // Count the users, excluding the currently authenticated admin
        $userCount = User::where('id', '!=', $admin->id)->count();
    
        // Count other resources
       // $CustomerCount = CustomerAddress::count();
        $categoryCount = Category::count();
        $subCategoryCount = SubCategory::count();
        $brandCount = Brand::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $totalPayment = Order::sum('grand_total');
        // Sum of grand_total for orders created in the current month
        $currentMonthSum = Order::whereMonth('created_at', now()->month)->sum('grand_total');
    
        // Return the view with the counts
        return view('admin.dashboard', compact(
            'admin', 
            'userCount', 
            'categoryCount', 
            'subCategoryCount', 
            'brandCount', 
            'productCount', 
            'orderCount',
            //'CustomerCount',
            'totalPayment',
            'currentMonthSum'

        ));
    }
    

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
        
    }
}
