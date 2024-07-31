<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Image;

class TempImagesController extends Controller
{
    public function create(Request $request) {
        
        if($request->image) {

            $image = $request->image;
            $extension = $image->getClientOriginalExtension();
            $newName = time().'.'.$extension;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/temp',$newName);

            // Generate image Thumbnail
            $sourcePath = public_path(). '/temp/' . $newName;
            $destinationPath = public_path(). '/temp/thumb/' .$newName;
            $image = Image::make($sourcePath);
            $image->fit(300, 275);
            $image->save($destinationPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                 // 'imagePath' => asset('/temp/thumb/'.$newName),
                'imagePath' => url('/temp/thumb/' .$newName),
                'message' => 'Image uploaded successfully'
            ]);
            
        }
       
    }
}
