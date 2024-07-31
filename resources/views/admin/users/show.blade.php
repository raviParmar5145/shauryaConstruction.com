@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Show User</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
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
                                        <label for="name">Name</label> :  {{ $users->name }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Email</label> :  {{ $users->email }}
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Phone</label> :  {{ $users->phone }}
                                    </div>
                                </div>	
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Role</label> : {{ $users->role == 1 ? 'Customer' : 'Admin' }}
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
