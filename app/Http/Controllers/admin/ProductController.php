<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Image;


class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::latest('id')->with('product_image');
    
        if (!empty($request->get('table_search'))) {
            $searchTerm = $request->get('table_search');
            $products = $products->where('title', 'like', "%$searchTerm%")
                                 ->orWhere('price', 'like', "%$searchTerm%")
                                 ->orWhere('qty', 'like', "%$searchTerm%");
        }
    
        $products = $products->paginate(10); // Paginate with 10 items per page
    
        return view('admin.products.list', compact('products'));
    }
    

    public function create() {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request) {
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);
   
        if ($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id  = $request->sub_category;
            $product->brand_id  = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();
           
            //  Save image galary
            if (!empty($request->image_array)) {

                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); // like jpg, gif, png, jpeg
                    

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();
            
                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();
            
                    //Generate Product Thumbnails
                    // Large image
                    $sourcePath = public_path('/temp/'.$tempImageInfo->name);
                    $destinationPath = public_path('/uploads/product/large/'.$imageName);
                    $image = Image::make($sourcePath); // Pass the source path to the make() function
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destinationPath);

                    // Small image
                    $sourcePath = public_path('/temp/' . $tempImageInfo->name);
                    $destinationPath = public_path('/uploads/product/small/'.$imageName);
                    $image = Image::make($sourcePath); // Pass the source path to the make() function
                    $image->fit(300, 300);
                    $image->save($destinationPath);

                }
            }

            $request->session()->flash('success', 'Product added successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
    

    public function edit($id) {

        $product = Product::find($id);
        // If the product is not found, redirect back to the index page
        if (empty($product)) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }

        // Fetch related product
        $relatedProducts = [];
        if($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts =  Product::whereIn('id',$productArray)->get();
        }


        // Retrieve categories and brands for dropdowns
        $categories = Category::orderBy('name', 'ASC')->get();
        $subCategories = SubCategory ::where('category_id',$product->category_id)->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        // Fetch Product image
        $productImage = ProductImage ::where('product_id',$product->id)->get();

        $data = [];
        $data['product'] = $product;
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['brands'] = $brands;
        $data['productImage'] = $productImage;
        $data['relatedProducts'] = $relatedProducts;
        return view('admin.products.edit', $data);
    }
    

    public function update($id, Request $request) {

        $product = Product::find($id);
        if (empty($product)) {

            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Product not found'
            ]);
        }

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);
  
        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id  = $request->sub_category;
            $product->brand_id  = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();
           
            //  Save image galary
           
            $request->session()->flash('success', 'Product updated successfully');
    
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }  
    }
        
    
    public function show($productId, Request $request) {
        // Retrieve product with relationships
        $product = Product::with('category', 'subCategory', 'brand', 'product_image')->find($productId);
    //dd($product);
        if (empty($product)) {
            return redirect()->route('products.index');
        }
    
        // Use eager loading for related models (if required)
        $categories = Category::orderBy('name', 'ASC')->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
    
        // Displaying the view
        return view('admin.products.show', compact('product', 'categories', 'subCategories', 'brands'));
    }
    
    
    public function destroy($id, Request $request) {
        $product = Product::find($id);

        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => true,
                'notFound' => true
            ]);
        }

        // Fetch Product image
        $productImages = ProductImage ::where('product_id',$id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                // Delete for file image 
                File::delete(public_path('uploads/product/large/'.$productImage->image));
                File::delete(public_path('uploads/product/small/'.$productImage->image));
            }
            ProductImage ::where('product_id',$id)->delete();
        }

        $product->delete();
        $request->session()->flash('success', 'Product deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function getProducts(Request $request) {
        $tempProduct = [];
        if($request->term != "") {
                $products = Product::where('title','like','%'.$request->item.'%')->get();
                
                if ($products != null) {
                    foreach ($products as $product) {
                        $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                    }
                }
        }
      
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);

    }

    
}