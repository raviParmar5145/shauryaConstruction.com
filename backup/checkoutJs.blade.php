User
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
            // alert($(this).val());
            $.ajax({
                url: '{{ route("front.getOrderSummery") }}', // Corrected the semicolon to a colon here
                type: 'post',
                data: {country_id: $(this).val()},
                dataType: 'json',
                success:function(response) {
                    if (response.status == true){
                        $('#shippingAmount').html('$'+response.shipingChare);
                        $('#grandTotal').html('$'+response.grandTotal);
                    }
                }
            });
        });

        $('#apply-discount').click(function(){
             alert($(this).val());
            $.ajax({
                url: '{{ route("front.applyDiscount") }}', // Corrected the semicolon to a colon here
                type: 'post',
                data: {code: $('#discount_code').val(), $('#country'),val()},
                dataType: 'json',
                success:function(response) {
                    
                }
            });
        });

    </script>
@endsection