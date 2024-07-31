<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::latest();
        if (!empty($request->get('table_search'))) {
            $categories = $categories->where('name','like','%'.$request->get('table_search').'%')
                    ->orWhere('showHome', 'like', '%' . $request->get('table_search') . '%'); 
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create() {
        return view('admin.category.create');
    }

    public function store(Request $request) {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);
    
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->showHome = $request->showHome;
            $category->status = $request->status;
            $category->save();
    
            // Save image 
            if (!empty($request->image_id)) {
                    $tempImage = TempImage::find($request->image_id);
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);
    
                    $newImageName = $category->id . '.' . $ext;
                    $sourcePath = public_path() . '/temp/' . $tempImage->name;
                    $destinationPath = public_path() . '/uploads/category/' . $newImageName;
                    File::copy($sourcePath, $destinationPath);

                    // Generate image Thumbnail

                    // create instance
                    $destinationPath = public_path(). '/uploads/category/thumb/' . $newImageName;
                    $img = Image::make($sourcePath);
                    // $img->resize(450, 600);
                    $img->fit(450, 600, function ($constraint) {
                        $constraint->upsize();     // add callback functionality to retain maximal original image size
                    });
                    $img->save($destinationPath);

                    $category->image = $newImageName;
                    $category->save();
    
            }
    
            $request->session()->flash('success', 'Category added successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
    

    public function edit($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request) {
        $category = Category::find($categoryId);
        if (empty($category)) {

            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);
    
        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->showHome = $request->showHome;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;
    
            // Save image 
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-'.time().'.' . $ext;
                $sourcePath = public_path() . '/temp/' . $tempImage->name;
                $destinationPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sourcePath, $destinationPath);

                // Generate image Thumbnail

                // create instance
                $destinationPath = public_path(). '/uploads/category/thumb/' . $newImageName;
                $img = Image::make($sourcePath);
                //$img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();     // add callback functionality to retain maximal original image size
                });
                $img->save($destinationPath);

                $category->image = $newImageName;
                $category->save();

                // Delete old images
                File::delete(public_path().'/uploads/category/thumb/' . $oldImage);
                File::delete(public_path().'/uploads/category/' . $oldImage);
            }
    
            $request->session()->flash('success', 'Category updated successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }

    
    public function show($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.show', compact('category'));
    }

    public function destroy($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
        }

        // Delete for file image 
        File::delete(public_path().'/uploads/category/thumb/' . $category->image);
        File::delete(public_path().'/uploads/category/' . $category->image);

        $category->delete();
        $request->session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
