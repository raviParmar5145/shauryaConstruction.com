<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request) {
        $brands = Brand::latest();
        if (!empty($request->get('table_search'))) {
            $brands = $brands->where('name','like','%'.$request->get('table_search').'%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }
    
    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands'
        ]);
    
        if ($validator->passes()) {
            $brands = new Brand();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success', 'Brand added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
    
    public function edit($brandsId, Request $request) {
        $brands = Brand::find($brandsId);
     
        if (empty($brands)) {
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit', compact('brands'));
    }

    public function update($brandsId, Request $request) {
       $brands = Brand::find($brandsId);
        if (empty($brands)) {

            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brands->id.',id',
        ]);
    
        if ($validator->passes()) {

           $brands->name = $request->name;
           $brands->slug = $request->slug;
           $brands->status = $request->status;
           $brands->save();
    
            $request->session()->flash('success', 'Brand updated successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }

    public function show($brandsId, Request $request) {
        $brands = Brand::find($brandsId);
    
        if (empty($brands)) {
            return redirect()->route('brands.index');
        }
        
        return view('admin.brands.show', compact('brands'));
    }
    

    public function destroy($brandsId, Request $request) {
        $brands = Brand::find($brandsId);

        if (empty($brands)) {
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => true,
                'message' => 'Brand not found'
            ]);
        }

        $brands->delete();
        $request->session()->flash('success', 'Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }
}
