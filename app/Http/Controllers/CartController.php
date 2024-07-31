<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\ShippingCharge;
use App\Models\DiscountCoupon;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        $product = Product::with('product_image')->find($request->id);
        
        if($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }
        if(Cart::count() > 0) {
            $message = 'alredy added cart';
            $status = false;
            // Product Foutn in cart
            // Cart is empty
            $cartContent = Cart::content();
            $productAlredyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlredyExist = true;
                }
            }

            if ($productAlredyExist == false) {
                Cart::add( $product->id,  $product->title, 1,  $product->price, ['productImage' =>  (!empty($product->product_image)) ?  $product->product_image->first() : '']);
                
                $status = true;
                $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title.' alredy added in cart';
            }
        } else {
            Cart::add( $product->id,  $product->title, 1,  $product->price, ['productImage' =>  (!empty($product->product_image)) ?  $product->product_image->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> added in your cart successfully.';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
        
    }

    public function cart() {

        // if (Cart::count() == 0) {
        //     return view('front.cartEmpty');
        // }
        $cartContent = Cart::content();
        // dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request) {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);

        // check qty available in stock
        if ($product->track_qty == 'Yes') {
            //dd($product->qty);
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $status = true;
                $message = 'cart updated successfully';
                session()->flash('success', $message);
            } else {
                $message = 'Requested qty ('.$qty.') not available in stock';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $status = true;
            $message = 'cart updated successfully';
            session()->flash('success', $message);
        }

        // session()->flash('success', $message);
        return response()->json([
            'status' => $status,
            'message' => $message
        ]); 
    }

    public function deleteItem(Request $request) {

        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Item not found in cart';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]); 
        }

        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully.';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]); 
    }




    public function checkout() {

        $discount = 0;

        // if cart is empty
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        // Check if the user is logged in
        if (Auth::check() == false) {
            // If the user is not logged in, and the session does not already contain 'url.intended', set it
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            // Redirect the user to the login page
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        
        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        $subTotal = Cart::subtotal(2,'.',''); 


        // Apply Discount
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Calculate shipping here
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();
            //dd($shippingInfo, $userCountry);
    
            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach(Cart::content() as $item) {
                $totalQty +=$item->qty; 
            }
    
            if ($shippingInfo == null) {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                $totalShippingCharge = $totalQty*$shippingInfo->amount;
            }
            $totalShippingCharge = $totalQty*$shippingInfo->amount;
            $grandTotal =  ($subTotal-$discount)+$totalShippingCharge;

        } else {
            $grandTotal = ($subTotal-$discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout',[
            'countries' =>$countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => number_format($discount,2),
            'grandTotal' => $grandTotal
        ]);
    }

    public function processCheckout(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);
    
        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    
        // Get the authenticated user
        $user = Auth::user();
        // step - 2 save user address

        // Update or create the customer address
        CustomerAddress::updateOrCreate(
            // Match the user_id condition
            ['user_id' => $user->id],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email, // Corrected field name
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city, // Corrected field name
                'state' => $request->state, // Corrected field name
                'zip' => $request->zip // Corrected field name
            ]
        );
        //
        if ($request->payment_method == 'cod') {

            $discountCodeId = '';
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2, '.', '');
            $grandTotal = $shipping+$subTotal;

            // Apply Discount
            if (session()->has('code')) {
                $code = session()->get('code');
               
              
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount/100)*$subTotal;
                 
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
                //dd($code->type.' = ' .($code->discount_amount/100)*$subTotal.','.$code->type.' ='.$code->discount_amount);
            }
            
            // Calculate Shipping
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
            // dd($shippingInfo, $request);
            $totalQty = 0;
            foreach(Cart::content() as $item) {
                $totalQty +=$item->qty; 
            }

            if($shippingInfo != null) {
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;
            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;
            }

            // step - 3 store data in oder table
            $order =  new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->discount = $discount;
            $order->grand_total = $grandTotal;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->save();

            // step - 4 store oder item in oder item table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItems;
                $orderItem->product_id = $item->id; 
                $orderItem->order_id = $order->id; 
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }
            // Send Order Email
            orderEmail($order->id,'customer');
            
            session()->flash('success', 'You have successfully place your order.');

            Cart::destroy();

            session()->forget('code');

            return response()->json([
                'status' => true,
                'orderId' => $order->id,
                'message' => 'Order saved successfully.',
            ]);

        } elseif ($request->payment_method == 'stripe') {
            // Stripe payment logic
    
            // Set your secret key
            Stripe::setApiKey(env('STRIPE_SECRET'));
    
            // Create a charge
            $charge = Charge::create([
                'amount' => $grandTotal * 100, // Amount in cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Payment for order ' . $order->id,
            ]);

            
    
            // Check if charge was successful
            if ($charge->status == 'succeeded') {
                // Update order payment status
                $order->payment_status = 'paid';
                $order->save();
    
                // Send Order Email
                orderEmail($order->id, 'customer');
    
                session()->flash('success', 'Your payment was successful. Order placed successfully.');
    
                Cart::destroy();
                session()->forget('code');
    
                return response()->json([
                    'status' => true,
                    'orderId' => $order->id,
                    'message' => 'Order placed and payment processed successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment processing failed. Please try again.',
                ]);
            }
        }
    }

    public function thankyou($id) {
        return view('front.thanks',[
            'id' =>$id,
        ]);
    }

     // County change event
    public function getOrderSummery(Request $request) {
        $subTotal =  Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = 0;

        // Apply Discount
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt4" id="discount-response">
                    <strong>'.session()->get('code')->code.'</strong>
                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                </div>';
        }

        if($request->country_id > 0) {
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
            $totalQty = 0;
            foreach(Cart::content() as $item) {
                $totalQty +=$item->qty; 
            }

            if($shippingInfo != null) {

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal =  ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]); 
            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal =  ($subTotal-$discount)+$shippingCharge;
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]); 
            }
        } else {
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format(($subTotal-$discount),2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format(0,2),
                ]); 

        }
    }

    public function applyDiscount(Request $request) {
         $code = DiscountCoupon::where('code',$request->code)->first();
         
         if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid discount coupon'
            ]);
        }
    
        // check if coupon date is valid or not 
        $now = Carbon::now();
        // echo $now->format('Y-m-d H:i:s');
        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount coupon is not yet active'
                ]);
            }
        } 
        // code for expires_at
        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Discount coupon has expired'
                ]);
            }
        } 

        // Max Uses User check
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();
            
            if($couponUsed >= $code->max_uses){
               return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon'
               ]);  
            }
        }

        // Max Uses User check
        if ($code->max_uses_user > 0){
            $couponUsedByUser = Order::where(['coupon_code_id'=> $code->id, 'user_id' => Auth::user()->id])->count();
            
            if($couponUsedByUser >= $code->max_uses_user){
               return response()->json([
                    'status' => false,
                    'message' => 'You already used this coupon'
               ]);  
            }
        }

        // Minimum amount condition check
        $subTotal = Cart::subtotal(2,'.','');
        if ($code->min_amount > $subTotal) {
            return response()->json([
                'status' => false,
                'message' => 'You min amountmust be $'.$code->min_amount. '.'
           ]);  
        }

        //dd($subTotal, $code->min_amount);
        session()->put('code',$code);
        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request) {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
    

}
