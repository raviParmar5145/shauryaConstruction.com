@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Show Sub Category</h1>
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
                    <div class="card">
                        <div class="card-body">								
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Category</label> : {{ $category->category->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label> :  {{ $category->name }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label> :  {{ $category->slug }}
                                    </div>
                                </div>	
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label> : {{ $category->status == 1 ? 'Active' : 'Block' }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="showHome">Show on Home</label> : {{ $category->showHome == 'Yes' ? 'Yes' : 'No' }}
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
