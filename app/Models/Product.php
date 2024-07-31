<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'compare_price',
        'category_id',
        'sub_category_id',
        'brand_id',
        'is_featured',
        'sku',
        'barcode',
        'track_qty',
        'qty',
        'status',
    ];

     // Define relationship with Category model
     public function category()
     {
         return $this->belongsTo(Category::class);
     }
 
     // Define relationship with SubCategory model
     public function subCategory()
     {
         return $this->belongsTo(SubCategory::class, 'sub_category_id');
     }
 
     // Define relationship with Brand model
     public function brand()
     {
         return $this->belongsTo(Brand::class);
     }
 
     // Define relationship with ProductImage model
     public function product_image()
     {
         return $this->hasMany(ProductImage::class);
     }

}
