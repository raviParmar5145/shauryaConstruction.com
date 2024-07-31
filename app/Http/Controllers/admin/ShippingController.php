<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ShippingCharge;
use App\Models\Country;
use Illuminate\Http\Request;



class ShippingController extends Controller
{
    public function create() {
        $countries = Country::get();
        $shippingCharges = ShippingCharge::select('shipping_charge.*', 'countries.name')
            ->leftJoin('countries', 'countries.id', 'shipping_charge.country_id')->get();
       // dd($shippingCharges);
        $data['countries'] = $countries;
        $data['shippingCharges'] = $shippingCharges;
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required',
        ]);
    
        if ($validator->passes()) {
            $count = ShippingCharge::where('country_id', $request->country)->count();
            if ($count > 0) {
                session()->flash('error', 'Shipping already added');
                return response()->json([
                    'status' => true,
                ]);
            }
            $shippings = new ShippingCharge();
            $shippings->country_id = $request->country;
            $shippings->amount = $request->amount;
            $shippings->save();

            $request->session()->flash('success', 'Shipping added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Shipping added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
    
    public function edit($id, Request $request) {
        // Find the shipping entry by ID
        $shippingCharge = ShippingCharge::find($id);

        $countries = Country::get();
        $data['countries'] = $countries;
        $data['shippingCharge'] = $shippingCharge;

        return view('admin.shipping.edit',$data);
    }
    

    public function update($id, Request $request) {
       $shippings = ShippingCharge::find($id);
        if (empty($shippings)) {

            $request->session()->flash('error', 'Shipping not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Shipping not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required',
        ]);
    
        if ($validator->passes()) {

           $shippings->country_id = $request->country;
           $shippings->amount = $request->amount;
           $shippings->save();
    
            $request->session()->flash('success', 'Shipping updated successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Shipping updated successfully'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }

   
    public function destroy($id, Request $request) {
        $shippingCharge = ShippingCharge::find($id);

        if ($shippingCharge == null) {
            $request->session()->flash('error', 'Shipping not found');
            return response()->json([
                'status' => true,
                'message' => 'hippings not found'
            ]);
        }

        $shippingCharge->delete();
        $request->session()->flash('success', 'Shipping deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Shipping deleted successfully'
        ]);
    }
}
