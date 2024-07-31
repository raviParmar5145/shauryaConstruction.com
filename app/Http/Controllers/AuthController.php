<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItems;

class AuthController extends Controller
{
    public function login() {
        return view('front.account.login');
    }

    public function register() {
        return view('front.account.register');
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);
  
        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();

            $request->session()->flash('success', 'You have been registerd successfully');
            return response()->json([
                'status' => true,
                'message' => 'You have been registerd successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }  
    

    public function profileUpdate(Request $request, $id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();

            $request->session()->flash('success', 'Your profile has been updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Your profile has been updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
 

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);  
        
        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))) {
                if (session()->has('url.intended')) {
                   return redirect(session()->get('url.intended'));
                }

                return redirect()->route('account.profile');
               
            } else {
                 return redirect()->route('account.login') 
                    ->with('error', 'Email / Password is in correct')
                    ->withInput($request->only('email'));
            }

        } else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile() {
        // Get the authenticated user
        $user = Auth::user(); 

        return view('front.account.profile',compact('user')); 
    }

    public function logout() {
        // Log out the currently authenticated user
        Auth::logout();
        // Redirect the user to the login view with a success message
        return redirect()->route('account.login')->with('success', 'You have successfully logged out!');
    }

    public function orders() {
        $data = [];
        $user = Auth::user(); 
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();

        $data['orders'] = $orders;
        return view('front.account.order',$data); 
    }

    public function orderDetail($id) {
        $data = [];
        $user = Auth::user(); 
        $order = Order::where('user_id', $user->id)->where('id',$id)->orderBy('created_at', 'DESC')->first();

        $orderItems = OrderItems::where('order_id',$id)->get();
        $orderItemsCount = OrderItems::where('order_id',$id)->count();

        $data['order'] = $order;
        $data['orderItems'] = $orderItems;
        $data['orderItemsCount'] = $orderItemsCount;
        return view('front.account.order-detail',$data); 
    }

    public function changePassword() {
        return view('front.account.change-password');
    }
    
}
