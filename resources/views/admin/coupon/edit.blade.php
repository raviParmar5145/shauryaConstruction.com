@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Coupon Code</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('coupons.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">

                <form action="" method="post" id="discountForm" name="discountForm">
                    <div class="card">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Code</label>
                                        <input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code" value="{{  $coupon->code }}">	
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name" value="{{  $coupon->name }}">	
                                        <p></p>
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Max Uses</label>
                                        <input type="text" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses" value="{{  $coupon->max_uses }}">	
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Max Uses User</label>
                                        <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User" value="{{  $coupon->max_uses_user }}">	
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option {{ $coupon->type == 'percent' ? 'selected' : '' }} value="percent">Percent</option>
                                            <option {{ $coupon->type == 'fixed' ? 'selected' : '' }} value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Discount Amount</label>
                                        <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount" value="{{  $coupon->discount_amount }}">	
                                        <p></p>
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Min Amount</label>
                                        <input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount" value="{{  $coupon->min_amount }}">	
                                        <p></p>
                                    </div>
                                </div>	
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option {{ $coupon->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                            <option {{ $coupon->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                        </select>
                                    </div>
                                </div>		

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Starts At</label>
                                        <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At" value="{{  $coupon->starts_at }}">	
                                        <p></p>
                                    </div>
                                </div>	

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Expires At</label>
                                        <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires At" value="{{  $coupon->expires_at }}">	
                                        <p></p>
                                    </div>
                                </div>	  
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description">Description</label> <!-- Corrected the 'for' attribute -->
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="5">{{  $coupon->description }}</textarea>
                                        <p></p>
                                    </div>
                                </div>
                                

                            </div>
                        </div>							
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>

            </div>
            <!-- /.card -->
        </section>
	<!-- /.content -->
		
@endsection

@section('customJs')
<script>

    $(document).ready(function(){
        $('#starts_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });

        $('#expires_at').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });

    $('#discountForm').submit(function(event){
        event.preventDefault();
        var element = $(this);
        //  alert(element);

        $.ajax({
            url: '{{ route("coupons.update", $coupon->id) }}',
            type: 'put',
            data: element.serializeArray(),
            datatype: 'json',
            success: function(response){

                if (response["status"] == true) {
                    window.location.href="{{ route('coupons.index') }}";
                    $('#name').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $('#slug').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                } else {
                    var errors = response['errors'];
                    if (errors['code']) {
                        $('#code').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['code']);
                    } else {
                        $('#code').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['discount_amount']) {
                        $('#discount_amount').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['discount_amount']);
                    } else {
                        $('#discount_amount').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['starts_at']) {
                        $('#starts_at').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['starts_at']);
                    } else {
                        $('#starts_at').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['expires_at']) {
                        $('#expires_at').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['expires_at']);
                    } else {
                        $('#expires_at').removeClass('is-invalid')
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

</script>
@endsection
