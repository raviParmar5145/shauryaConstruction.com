@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">    
            <form action="" method="post" name="registrationForm" id="registrationForm">
                @csrf
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name" value="{{ old('name') }}">
                    <p class="error"></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" id="email" name="email" value="{{ old('email') }}">
                    <p class="error"></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Password" id="password" name="password">
                    <p class="error"></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                    <p class="error"></p>
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div> 
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
            </form>			
            <div class="text-center small">Already have an account? <a href="{{ route('account.login') }}">Login Now</a></div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
    <script>
    $('#registrationForm').submit(function(event){
        event.preventDefault();
        var formArray = $(this);
        $("button[type='submit']").prop('disabled',true);

        $.ajax({
            url: '{{ route("account.processRegister") }}',
            type: 'post',
            data: formArray.serializeArray(),
            datatype: 'json',
            success: function(response){
                $("button[type='submit']").prop('disabled',false);

                if (response["status"] == true) {
                    window.location.href="{{ route('account.login') }}";
                  

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