@extends('admin.layouts.app')

@section('contect')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">					
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Shipping</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('shippings.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" method="post" id="shippingsForm" name="shippingsForm">
                    <div class="card">
                        <div class="card-body">								
                            <div class="row">
                               
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="country_id">Country</label>
                                        <select name="country_id" id="country_id" class="form-control">
                                            <option value="">Select a Country</option>
                                            @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                                <option value="rest_of_world">Rest of the world</option>
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">	
                                        <p></p>
                                    </div>
                                </div>	
                            </div>
                        </div>							
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('shippings.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </form>

            </div>
            <!-- /.card -->
        </section>
	<!-- /.content -->
		
@endsection

@section('customJs')
<script>
    $('#shippingsForm').submit(function(event){
        event.preventDefault();
        var element = $(this);
        $.ajax({
            url: '{{ route("shippings.store") }}',
            type: 'post',
            data: element.serializeArray(),
            datatype: 'json',
            success: function(response){

                if (response["status"] == true) {
                    window.location.href="{{ route('shippings.index') }}";
                    $('#country_id').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $('#amount').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                } else {
                    var errors = response['errors'];
                    if (errors['country_id']) {
                        $('#country_id').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['country_id']);
                    } else {
                        $('#country_id').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                    if (errors['amount']) {
                        $('#amount').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['amount']);
                    } else {
                        $('#amount').removeClass('is-invalid')
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
