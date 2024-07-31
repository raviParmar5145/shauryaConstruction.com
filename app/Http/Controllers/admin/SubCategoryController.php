<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index(Request $request) {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id'); 
    
        if (!empty($request->get('table_search'))) {
            $subCategories->where('sub_categories.name', 'like', '%' . $request->get('table_search') . '%')
                         ->orWhere('categories.name', 'like', '%' . $request->get('table_search') . '%'); 
        }
    
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list', compact('subCategories'));
    }
    
    public function create() {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.create',compact('categories'));
    }

    public function store(Request $request) {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'slug' => 'required|unique:sub_categories'
        ]);
    
        if ($validator->passes()) {
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id  = $request->category;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub Category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
    
    public function edit($categoryId, Request $request) {
        $category = SubCategory::find($categoryId);
     
        if (empty($category)) {
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.edit', compact('category', 'categories'));
    }

    public function update($categoryId, Request $request) {
        $category = SubCategory::find($categoryId);
        if (empty($category)) {

            $request->session()->flash('error', 'Sub Category not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Sub Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$category->id.',id',
        ]);
    
        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->category_id  = $request->category;
            $category->save();
    
            $request->session()->flash('success', 'Sub Category updated successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Sub Category updated successfully'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }

    public function show($categoryId, Request $request) {
        $category = SubCategory::find($categoryId);
    
        if (empty($category)) {
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.show', compact('category', 'categories'));
    }
    

    public function destroy($categoryId, Request $request) {
        $category = SubCategory::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Sub Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Sub Category not found'
            ]);
        }


        $category->delete();
        $request->session()->flash('success', 'Sub Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfully'
        ]);
    }
}
