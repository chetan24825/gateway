<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhonePe Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
</head>

<body>
    <div class="contact-page">
        <div class="container">
            <div class="row">
                <style>
                    #payImage {
                        cursor: pointer;
                    }
                </style>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="single-address-block text-center">
                        <h3 class="mb-4">PhonePe Payment</h3>
                        <!-- Image as the Payment Button -->
                        <img id="payImage" class="img-fluid img-thumbnail" src="{{ asset('front/img/phonepe.webp') }}"
                            alt="Pay Now with PhonePe">
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>

    @include('fronts.layouts.script')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Prepare the data to send via AJAX
            var data = {
                mobile: "{{ $mobile }}",
                amount: "{{ $amount }}",
                order_id: "{{ $order_id }}",
                user_id: "{{ $user_id }}"
            };

            // Get CSRF token from the meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send data via AJAX to initiate the PhonePe payment
            $.ajax({
                url: "{{ route('payment.initiate') }}", // Replace with your actual route
                type: "POST",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Add CSRF token to request headers
                },
                success: function(response) {
                    // Handle success response
                    if (response.success) {
                        // Redirect to the PhonePe payment page
                        window.location.href = response.redirectUrl;
                    } else {
                        // Show error if payment initiation failed
                        alert('Payment initiation failed: ' + response.message);
                    }
                },
                error: function() {
                    // Handle error during the AJAX request
                    alert('An error occurred while initiating the payment.');
                }
            });
        });
    </script>
</body>

</html>
