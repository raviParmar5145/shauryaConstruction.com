<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Shipping;
use App\Models\Country;
use Illuminate\Http\Request;



class ShippingController extends Controller
{
    public function index(Request $request) {
        $query = Shipping::latest();
    
        // Apply search filter if necessary
        if ($request->has('table_search')) {
            $search = $request->get('table_search');
            $query->where('country_id', 'like', '%' . $search . '%');
        }
        // Paginate the query result
        $shippings = $query->paginate(10);

       $shippings = Shipping::select('shipping_charge.*', 'countries.name as countryName')
            ->latest('shipping_charge.id')
            ->leftJoin('countries', 'countries.id', '=', 'shipping_charge.country_id'); 
    
        if (!empty($request->get('table_search'))) {
           $shippings->where('shipping_charge.amount', 'like', '%' . $request->get('table_search') . '%')
                         ->orWhere('countries.name', 'like', '%' . $request->get('table_search') . '%'); 
        }
       $shippings =$shippings->paginate(10);

        return view('admin.shipping.list', compact('shippings'));
    }
    
    public function create() {
        $countries = Country::get();
        $data['countries'] = $countries;
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request) {
       
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'amount' => 'required',
        ]);
    
        if ($validator->passes()) {
            $shippings = new Shipping();
            $shippings->country_id = $request->country_id;
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
    
    public function edit($shippingsId, Request $request) {
        // Find the shipping entry by ID
        $shippings = Shipping::find($shippingsId);
        // If the entry is not found, redirect to the index route
        if (!$shippings) {
            return redirect()->route('shippings.index')->withErrors('Shipping entry not found.');
        }
        // Get all countries
        $countries = Country::all();
        // Return the edit view with the shipping entry and countries data
        return view('admin.shipping.edit', [
            'shippings' => $shippings,
            'countries' => $countries
        ]);
    }
    

    public function update($shippingsId, Request $request) {
       $shippings = Shipping::find($shippingsId);
        if (empty($shippings)) {

            $request->session()->flash('error', 'Shipping not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Shipping not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'amount' => 'required',
        ]);
    
        if ($validator->passes()) {

           $shippings->country_id = $request->country_id;
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

    public function show($shippingsId) {
        // Find the shipping entry by ID
        $shipping = Shipping::find($shippingsId);
    
        // If the entry is not found, redirect to the index route with an error message
        if (!$shipping) {
            return redirect()->route('shippings.index')->withErrors('Shipping entry not found.');
        }
    
        // Return the show view with the shipping data
        return view('admin.shipping.show', [
            'shipping' => $shipping
        ]);
    }
    
    

    public function destroy($shippingsId, Request $request) {
        $shippings = Shipping::find($shippingsId);

        if (empty($shippings)) {
            $request->session()->flash('error', 'Shipping not found');
            return response()->json([
                'status' => true,
                'message' => 'hippings not found'
            ]);
        }

        $shippings->delete();
        $request->session()->flash('success', 'Shipping deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Shipping deleted successfully'
        ]);
    }
}
