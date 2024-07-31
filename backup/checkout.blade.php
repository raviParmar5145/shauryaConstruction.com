@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form id="orderForm" name="orderForm" action="" method="post">
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control">
                                            <option value="">Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country) 
                                                        {{-- <option value={{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option> --}}
                                                        <option value="{{ $country->id }}" 
                                                            {{ !empty($customerAddress) && $customerAddress->country_id == $country->id ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                        
                                                        @endforeach                                     
                                                @endif
                                            
                                        </select>
                                        <p></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->address : '' }}</textarea>
                                        <p></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="apartment" id="apartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : '' }}">
                                    </div>            
                                </div>
    
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ (!empty($customerAddress)) ? $customerAddress->state : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="notes" id="notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->notes : '' }}</textarea>
                                    </div>            
                                </div>
    
                            </div>
                        </div>
                    </div>    
                </div>
               
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summary</h2>
                    </div>                    
                    <div class="card cart-summary">
                        <div class="card-body">
                            <!-- Iterate through the cart content using Cart::content() -->
                            @foreach (Cart::content() as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                    <div class="h6">${{ $item->price * $item->qty }}</div>
                                </div>
                            @endforeach
                            <hr>
                            <!-- Display the subtotal using Cart::subtotal() -->
                            <div class="d-flex justify-content-between summary-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                            </div>
                            <!-- Static shipping and total values -->
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong  id="shipingAmount">${{ number_format($totalShipingChare, 2) }}</strong></div>
                            </div><hr>
                            <div class="d-flex justify-content-between mt-2 summary-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong id="grandTotal">${{ number_format($grandTotal, 2) }}</strong></div>
                            </div>                            
                        </div>
                    </div>   
                    <!-- Payment form remains unchanged -->
                    <div class="card payment-form">
                        <h3 class="card-title h5 mb-3">Payment Method</h3>
                        <div>
                            <input checked type="radio" name="payment_method" value="cod" id="payment_method_one">
                            <label for="payment_method_one" class="form-check-label"> Case On Deliver</label>
                        </div>
                        <div>
                            <input type="radio" name="payment_method" value="case" id="payment_method_two">
                            <label for="payment_method_two" class="form-check-label"> Stripe</label>
                        </div>
    
                        <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                            <div class="mb-3">
                                <label for="card_number" class="mb-2">Card Number</label>
                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control" pattern="(0[1-9]|1[0-2])\/[0-9]{4}">
                                    <small class="text-muted">MM/YYYY format.</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">CVV Code</label>
                                    <input type="text" name="cvv" id="cvv" placeholder="123" class="form-control">
                                </div>
                            </div>
                            
                        </div> 
                        <div class="pt-4">
                            <button type="submit" class="btn-dark btn btn-block w-100">Pay Now $({{ Cart::subtotal() }})</button>
                        </div>                      
                    </div>
                    <!-- CREDIT CARD FORM ENDS HERE -->
                </div>
                
            </div>
        </form>

    </div>
</section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_one").click(function(){
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").addClass('d-none');
            }         
        })

        $("#payment_method_two").click(function(){
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").removeClass('d-none');
            }         
        })

        // $('#orderForm').submit(function(event){
        //     event.preventDefault();

        //     $('button[type="submit"]').pop('disabled',true);
        //     $.ajax({
        //         url: '{{ route("front.processCheckout") }}',
        //         type: 'post',
        //         data: $(this).serializeArray(),
        //         dataType: 'json',
        //         success: function(response){
        //             var errors = response.errors;
        //             $('button[type="submit"]').pop('disabled',false);
        //             dd(response);
        //             if (response.status == false) {
        //                 if (errors.first_name) {
        //                     $("#first_name").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.first_name);
        //                 } else {
        //                     $("#first_name").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.last_name) {
        //                     $("#last_name").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.last_name);
        //                 } else {
        //                     $("#last_name").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.email) {
        //                     $("#email").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.email);
        //                 } else {
        //                     $("#email").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.country) {
        //                     $("#country").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.country);
        //                 } else {
        //                     $("#country").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.address) {
        //                     $("#address").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.address);
        //                 } else {
        //                     $("#address").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.city) {
        //                     $("#city").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.city);
        //                 } else {
        //                     $("#city").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.state) {
        //                     $("#state").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.state);
        //                 } else {
        //                     $("#state").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.first_name) {
        //                     $("#zip").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.zip);
        //                 } else {
        //                     $("#zip").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 }
    
        //                 if (errors.mobile) {
        //                     $("#mobile").addClass('is-invalid')
        //                         .siblings("p")
        //                         .addClass('invalid-feedback')
        //                         .html(errors.mobile);
        //                 } else {
        //                     $("#mobile").removeClass('is-invalid')
        //                         .siblings("p")
        //                         .removeClass('invalid-feedback')
        //                         .html('');
        //                 } 
        //             } else {
        //                 window.location.href="{{ url('/thanks/') }}/"+response.orderId;
        //             }
        //         }
        //     });
        // });


        $('#orderForm').submit(function(event){
            event.preventDefault();

            // Disable submit button
            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    var errors = response.errors;

                    // Enable submit button
                    $('button[type="submit"]').prop('disabled', false);

                    if (response.status == false) {
                        // Error handling
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.first_name);
                        } else {
                            $("#first_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.last_name);
                        } else {
                            $("#last_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.country) {
                            $("#country").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.country);
                        } else {
                            $("#country").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.address) {
                            $("#address").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.address);
                        } else {
                            $("#address").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.city) {
                            $("#city").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.city);
                        } else {
                            $("#city").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.state) {
                            $("#state").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.state);
                        } else {
                            $("#state").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.first_name) {
                            $("#zip").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.zip);
                        } else {
                            $("#zip").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
    
                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.mobile);
                        } else {
                            $("#mobile").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        } 
                    } else {
                        // Redirect to thank you page
                            window.location.href = "{{ url('/thanks') }}/" + response.orderId;
                    }
                }
            });
        });

        $('#country').change(function(){
            alert($(this).val());
            $.ajax({
                url: '{{ route("front.getOrderSummery") }}', // Corrected the semicolon to a colon here
                type: 'post',
                data: {country_id: $(this).val()},
                dataType: 'json',
                success:function(response) {
                    if (response.status == true){
                        $('#shipingAmount').html('$'.response.shipingChare);
                        $('#grandTotal').html('$'.response.grandTotal);
                    }
                }
            });
        });

    </script>
@endsection