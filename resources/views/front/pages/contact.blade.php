@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item">Contact Us</li>
            </ol>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-11">
    <div class="container mt-5">
        {{-- @include('front.message') --}}
        <!-- resources/views/front/message.blade.php -->
        @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Error!</h4> {{ Session::get('error') }}
        </div>
        @endif

        @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-check"></i> Success!</h4> {{ Session::get('success') }}
        </div>
        @endif


        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="service no-shadow link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                            <div class="service-icon color-1 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"></path>
                                </svg>
                            </div>
                            <div class="service-contents">
                                <p> 6/7 I, Shivshakti Tenament,
                                    Shanti-nagar, Chitra. Landmark: Behind Press Quatar; Bhavnager, Gujarat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="service no-shadow link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                            <div class="service-icon color-1 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"></path>
                                </svg>
                            </div>
                            <div class="service-contents">
                                <p>customer@shauryaConstruction.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="service no-shadow link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                            <div class="service-icon color-1 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"></path>
                                </svg>
                            </div>
                            <div class="service-contents">
                                <p>+91 7874728723</p>
                            </div>
                        </div>
                        <div class="service no-shadow link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                            <div class="service-icon color-1 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"></path>
                                </svg>
                            </div>
                            <div class="service-contents">
                                <p> +91 7283931539</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card w-75">
                <div class="card-header">
                    <h2 class="h5 mb-0 pt-2 pb-2">Contact Us</h2>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <form action="" method="" id="contactForm" class="row justify-content-center mt-0 gy-4 php-email-form" data-aos="fade-up" data-aos-delay="100">
                            @csrf
                            <div class="col-lg-11">
                                <div class="row g-4 mb-4">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="fname">First name</label>
                                            <input type="text" name="fname" class="form-control" id="fname" placeholder="Please enter first name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="lname">Last name</label>
                                            <input type="text" name="lname" class="form-control" id="lname" placeholder="Please enter last name">
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="email">Email address</label>
                                            <input type="text" name="email" class="form-control" id="email" placeholder="Please enter email">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Please enter message"></textarea>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mt-0">
                                        <button type="submit" name="submit" class="btn btn-dark">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $('#contactForm').submit(function(event) {
        event.preventDefault();

        // Disable submit button
        $('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: '{{ route("contactUpdate") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Enable submit button
                $('button[type="submit"]').prop('disabled', false);

                if (response.status === false) {
                    var errors = response.errors;

                    if (errors.fname) {
                        $("#fname").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.fname);
                    } else {
                        $("#fname").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if (errors.lname) {
                        $("#lname").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.lname);
                    } else {
                        $("#lname").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if (errors.email) {
                        $("#email").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.email);
                    } else {
                        $("#email").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }

                    if (errors.message) {
                        $("#message").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.message);
                    } else {
                        $("#message").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }
                } else {
                    // Success handling: Display flash message and refresh page
                    window.location.reload();
                }
            }
        });
    });
</script>
@endsection
