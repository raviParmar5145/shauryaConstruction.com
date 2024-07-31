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
          

        // if cart is empty redirect to cart
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

    
        //  If the user is not logged in, and the session does not already contain 'url.intended', set it
        if (Auth::check() == false) {
           
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            // Redirect the user to the login page
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        // dd($customerAddress, Auth::user()->id);
        
        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        // CALCULATE SHIPING 
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();
    
            $totalQty = 0;
            $totalShipingChare = 0;
            $grandTotal = 0;
            foreach(Cart::content() as $item) {
                $totalQty +=$item->qty; 
            }
    
            $totalShipingChare = $totalQty*$shippingInfo->amount;
            $grandTotal =  Cart::subtotal(2,'.','')+$totalShipingChare;

        } else {
            $totalShipingChare = 0;
            $grandTotal =  Cart::subtotal(2,'.','');
            
        }

        //echo $shippingInfo->amount;

        return view('front.checkout',[
            'countries' =>$countries,
            'customerAddress' => $customerAddress,
            'totalShipingChare' => $totalShipingChare,
            'grandTotal' => $grandTotal
        ]);
    }

    public function processCheckout(Request $request) {

       // dd(Cart::subtotal(2, '.', ''));
        // Validate the request data
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
        // dd( $user);
        // step - 2 save user address

        // Update or create the customer address
        CustomerAddress::updateOrCreate(
            // Match the user_id condition
            ['user_id' => $user->id],
            // Update or create data
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

            $shiping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2, '.', '');
            $grandTotal = $shiping+$subTotal;
            
            // step - 3 store data in oder table
            $order =  new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shiping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order-> email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order-> apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->save();

            // step - 4 store oder item in oder item table
         
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItems;
                // Correct assignment: 'order_id' should be the 'id' of the order you've created
                $orderItem->order_id = $order->id; 
                // 'product_id' should be the product ID from the cart item
                $orderItem->product_id = $item->id; 
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }
            
            session()->flash('success', 'You have successfully place your order.');
            Cart::destroy();
            return response()->json([
                'status' => true,
                'orderId' => $order->id,
                'message' => 'Order saved successfully.',
            ]);

        } else {
            // 
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

        if($request->country_id > 0) {
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
            
            $totalQty = 0;
            foreach(Cart::content() as $item) {
                $totalQty +=$item->qty; 
            }

            if($shippingInfo != null) {

                $shipingChare = $totalQty*$shippingInfo->amount;
                $grandTotal =  $subTotal+$shipingChare;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'shipingChare' => number_format($shipingChare,2),
                ]); 
            // }
            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shipingChare = $totalQty*$shippingInfo->amount;
                $grandTotal =  $subTotal+$shipingChare;
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'shipingChare' => number_format($shipingChare,2),
                ]); 
            }
        } else {
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($subTotal,2),
                    'shipingChare' => number_format(0,2),
                ]); 

        }
    }

}
