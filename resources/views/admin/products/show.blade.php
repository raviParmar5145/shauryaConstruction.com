@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Show Product</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title">Title</label> :  {{ $product->title }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label> :  {{ $product->slug }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description">Description</label>: {{ strip_tags($product->description) }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price">Price</label>: {{ $product->price }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price">Price</label>: {{ $product->price }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare price</label>: {{ $product->compare_price }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU</label>: {{ $product->sku }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>: {{ $product->barcode }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="qty">QTY</label>: {{ $product->qty }}
                                    </div>
                                </div>	


                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category">Category</label>: {{ $product->category->name }}
                                    </div>
                                </div>	

                                <!-- Sub Category -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sub_category">Sub Category</label>
                                        @if ($product->subCategory)
                                            : {{ $product->subCategory->name }}
                                        @else
                                            : Not specified
                                        @endif
                                    </div>
                                </div>

                               <!-- Brand -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="brand">Brand</label>
                                        @if ($product->brand)
                                            : {{ $product->brand->name }}
                                        @else
                                            : Not specified
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_featured">Featured product</label>: {{ $product->is_featured }}
                                    </div>
                                </div>	
                               
                                <!-- Images -->
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image">Image</label>
                                        @if ($product->images)
                                            @foreach ($product->images as $image)
                                                <img src="{{ asset('uploads/product/small/' . $image->image) }}" alt="" width="150">
                                            @endforeach
                                        @else
                                            <p>No images available.</p>
                                        @endif
                                    </div>
                                </div> --}}

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="text" id="image_id" name="image_id" value="" hidden>
                                        <label for="image">Image</label> :  
                                        @php
                                                $productImage = $product->product_image->first(); // Assuming product_image is a relationship
                                            @endphp
                                            @if(!empty($productImage))
                                                <img src="{{ asset('uploads/product/small/' . $productImage->image) }}" alt="" width="100">
                                            @endif                                          
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label> : {{ $product->status == 1 ? 'Active' : 'Block' }}
                                    </div>
                                </div>	
                            </div>
                        </div>							
                    </div>
             </div>
            <!-- /.card -->
        </section>
	<!-- /.content -->
		
@endsection

@section('customJs')

@endsection
