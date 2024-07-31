@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit User</h1>
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
                <form action="" method="post" id="usersForm" name="usersForm">
                    <div class="card">
                        <div class="card-body">								
                            <div class="row">
                               
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $users->name }}">	
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" class="form-control" placeholder="email" value="{{ $users->email }}">	
                                        <p></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="phone" value="{{ $users->phone }}">
                                        <p></p>
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="form-control">
                                            <option value=" ">select role</option>
                                            <option value="1" {{ $users->role == 1 ? 'selected' : '' }}>Customer</option>
                                            <option value="2" {{ $users->role == 2 ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <p></p>
                                    </div>
                                </div>	
                            </div>
                        </div>							
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>

            </div>
            <!-- /.card -->
        </section>
	<!-- /.content -->
		
@endsection

@section('customJs')
<script>
$('#usersForm').submit(function(event){
        event.preventDefault();
        var formArray = $(this);
        $("button[type='submit']").prop('disabled',true);

        $.ajax({
            url: '{{ route("users.update",$users->id) }}',
            type: 'put',
            data: formArray.serializeArray(),
            datatype: 'json',
            success: function(response){
                $("button[type='submit']").prop('disabled',false);

                if (response["status"] == true) {
                    window.location.href="{{ route('users.index') }}";
                    

                } else {
                    var errors = response['errors'];
                   
                    $(".error").removeClass('invalid-feedback').html('');
                    $("input[type='text'], select").removeClass('is-invalid');

                    $.each(errors, function(key,value) {
                        $(`#${key}`).addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(value); 
                    });
    
                }
            },
            error:function(jqXHR, exception){
                console.log("Something want wrong");
            }

        });
    });



 </script>
@endsection
