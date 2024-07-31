@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Sub Category</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">

                <form action="" method="post" id="categoryForm" name="categoryForm">
                    <div class="card">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="name">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Please choise Category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $catego)
                                                    <option value="{{ $catego->id }}" {{ $catego->id ==  $category->category_id ? 'selected' : ''}}>{{ $catego->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $category->name }}">	
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly value="{{ $category->slug }}">	
                                        <p></p>
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option {{ $category->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                            <option {{ $category->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                        </select>
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="showHome">Show on Home</label>
                                        <select name="showHome" id="showHome" class="form-control">
                                            <option {{ $category->showHome == 'Yes' ? 'selected' : '' }} value="Yes">Yes</option>
                                            <option {{ $category->showHome == 'No' ? 'selected' : '' }} value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>							
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>

            </div>
            <!-- /.card -->
        </section>
	<!-- /.content -->
		
@endsection

@section('customJs')
<script>
    $('#categoryForm').submit(function(event){
        event.preventDefault();
        var element = $(this);
        //  alert(element);

        $.ajax({
            url: '{{ route("sub-categories.update",$category->id) }}',
            type: 'put',
            data: element.serializeArray(),
            datatype: 'json',
            success: function(response){

                if (response["status"] == true) {
                        window.location.href="{{ route('sub-categories.index') }}";
                    $('#name').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $('#slug').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $('#category').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                } else {

                    if (response['errors'] == true) {
                        window.location.href="{{ route('sub-categories.index') }}";
                    }

                    var errors = response['errors'];
                    if (errors['name']) {
                        $('#name').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['slug']);
                    } else {
                        $('#slug').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['category']) {
                        $('#category').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['category']);
                    } else {
                        $('#category').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                }
                
               
                //console.log(response);
            },
            error:function(jqXHR, exception){
                console.log("Something want wrong");
            }

        });
    });

    $('#name').change(function(){
        var element = $(this);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title:element.val()},
            datatype: 'json',
            success: function(response){
                if (response["status"] == true) {
                    $('#slug').val(response["slug"]);
                } 
            }
        });
    });

</script>
@endsection
