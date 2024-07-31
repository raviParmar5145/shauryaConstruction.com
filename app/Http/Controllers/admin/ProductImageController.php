<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request) {
       
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();
       
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'. $ext;
        $productImage->image = $imageName;
        $productImage->save();

        //Generate Product Thumbnails
        // Large image
        $destinationPath = public_path('/uploads/product/large/'.$imageName);
        $image = Image::make($sourcePath); // Pass the source path to the make() function
        $image->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save($destinationPath);

        // Small image
        $destinationPath = public_path('/uploads/product/small/'.$imageName);
        $image = Image::make($sourcePath); // Pass the source path to the make() function
        $image->fit(300, 300);
        $image->save($destinationPath);

        return response()->json([
            'status' =>true,
            'image_id' => $productImage->id,
            'imagePath' => url('/uploads/product/small/'.$productImage->image),
            'message' => 'Image savedd successfully',
        ]);
        
    }

    public function destroy(Request $request) {
        $productImage = ProductImage::find($request->id);

        if (empty($productImage)) {
            $request->session()->flash('error', 'Product image not found');
            return response()->json([
                'status' => true,
                'message' => 'Product image not found'
            ]);
        }

        
        // Delete for file image 
        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));

        $productImage->delete();
        $request->session()->flash('success', 'Product image deleted successfully');

        return response()->json([
            'status' => true,
            'productImage' => $productImage->id,
            'message' => 'Product image deleted successfully'
        ]);
    }
}
