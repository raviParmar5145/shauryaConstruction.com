<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\DiscountCoupon;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(Request $request) {
        $discountCoupons = DiscountCoupon::latest();
        if (!empty($request->get('table_search'))) {
            $discountCoupons = $discountCoupons->where('name','like','%'.$request->get('table_search').'%');
        }

        $discountCoupons = $discountCoupons->paginate(10);
        return view('admin.coupon.list', compact('discountCoupons'));
    }
    
    public function create() {
        return view('admin.coupon.create');
    }

    public function store(Request $request) {
        //dd($request);
        // Validation rules
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required|in:percent,fixed', // Ensure type is either percent or fixed
            'discount_amount' => 'required|numeric',
            'status' => 'required|in:0,1', // Ensure status is either 0 or 1
            'starts_at' => 'nullable|date', // Ensure starts_at is a valid date
            'expires_at' => 'nullable|date|after:starts_at', // Ensure expires_at is a valid date and after starts_at
        ]);
    
        if ($validator->passes()) {
            // Check if starts_at is less than current date time
            if (!empty($request->starts_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($startAt->isPast()) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date cannot be in the past']
                    ]);
                }
            }
            
            // Check if expires_at is greater than starts_at
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                if ($expiresAt->lte($startAt)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry date must be greater than start date']
                    ]);
                }
            }
    
            // Create new discount coupon
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->description = $request->description;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();
    
            // Success response
            $message = 'Discount coupon added successfully';
            $request->session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            // Validation failed response
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
    
    // public function store(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'code' => 'required',
    //         'type' => 'required',
    //         'discount_amount' => 'required|numeric',
    //         'status' => 'required'
    //     ]);
    
    //     if ($validator->passes()) {

    //         // starting date must be greator than current date
    //         if (!empty($request->starts_at)) {
    //             $now = Carbon::now();
    //             $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
    //             if ($startAt->lte($now) == true) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'errors' => ['starts_at' => 'start date can not be less than currect date time']
    //                 ]);
    //             }
    //         }

    //         // expiry date must be greator than current date
    //         if (!empty($request->starts_at) && !empty($request->expires_at)) {
    //             $now = Carbon::now();
    //             $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
    //             if ($startAt->gt($now) == false) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'errors' => ['expires_at' => 'Expiry date must be greator than start date time']
    //                 ]);
    //             }
    //         }

    //         $discountCode = new DiscountCoupon();
    //         $discountCode->code = $request->code;
    //         $discountCode->name = $request->name;
    //         $discountCode->max_uses = $request->max_uses;
    //         $discountCode->max_uses_user = $request->max_uses_user;
    //         $discountCode->type = $request->type;
    //         $discountCode->discount_amount = $request->discount_amount;
    //         $discountCode->min_amount = $request->min_amount;
    //         $discountCode->status = $request->status;
    //         $discountCode->starts_at = $request->starts_at;
    //         $discountCode->expires_at = $request->expires_at;
    //         $discountCode->save();

    //         $message = 'Discount coupon added successfully';

    //         $request->session()->flash('success', $message);
    //         return response()->json([
    //             'status' => true,
    //             'message' => $message
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     }  
    // }
    
    public function edit($id, Request $request) {
        $coupon = DiscountCoupon::find($id);
     
        if ($coupon == null) {
            session()->flash('error', 'Record not found');
            return redirect()->route('coupon.index');
        }
        $data['coupon'] = $coupon;
        return view('admin.coupon.edit', $data);
    }

    public function update($id, Request $request) {
       $discountCode = DiscountCoupon::find($id);
        if (empty($discountCode)) {

            $request->session()->flash('error', 'Discount Coupons not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Discount Coupons not found'
            ]);
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required|in:percent,fixed', // Ensure type is either percent or fixed
            'discount_amount' => 'required|numeric',
            'status' => 'required|in:0,1', // Ensure status is either 0 or 1
            'starts_at' => 'nullable|date', // Ensure starts_at is a valid date
            'expires_at' => 'nullable|date|after:starts_at', // Ensure expires_at is a valid date and after starts_at
        ]);

        if ($validator->passes()) {
            // Check if starts_at is less than current date time
            if (!empty($request->starts_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($startAt->isPast()) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date cannot be in the past']
                    ]);
                }
            }
            
            // Check if expires_at is greater than starts_at
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                if ($expiresAt->lte($startAt)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry date must be greater than start date']
                    ]);
                }
            }

            // Create new discount coupon
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->description = $request->description;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            // Success response
            $message = 'Discount coupon updated successfully';
            $request->session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            // Validation failed response
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }   
    } 

    public function destroy($id, Request $request) {
       $coupon = DiscountCoupon::find($id);

        if (empty($coupon)) {
            $request->session()->flash('error', 'Discount Coupons not found');
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupons not found'
            ]);
        }

       $coupon->delete();
        $request->session()->flash('success', 'Discount Coupons deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Discount Coupons deleted successfully'
        ]);
    }
}
