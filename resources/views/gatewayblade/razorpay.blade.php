<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    {{-- @include('fronts.layouts.header') --}}

    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
                    <div class="single-adderss-block text-center">
                        <h3 class="mb-4">Razorpay Payment</h3>
                        <!-- Image as the Payment Button -->
                        <img id="payImage" class="img-fluid img-thumbnail" src="{{ asset('front/img/razerpay.webp') }}"
                            alt="Pay Now with Razorpay">
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>

    {{-- @include('fronts.layouts.script') --}}
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha384-oLlnbB+/nnrzRmEIlUC7Ydp+09ohOlcua+2srf6oAMcOo0g5F6X6p+4AkElnmbw5" crossorigin="anonymous">
    </script>

    <script>
        // Razorpay options
        const options = {
            "key": "{{ env('RAZORPAY_KEY_ID') }}", // Razorpay Key ID
            "amount": "{{ $responseData['amount'] }}", // Amount in paise (ensure it's multiplied by 100)
            "currency": "{{ $responseData['currency'] }}",
            "name": "Win5Club",
            "description": "Order Payment",
            "image": "{{ asset('your-logo.png') }}",
            "order_id": "{{ $responseData['order_id'] }}", // Razorpay Order ID
            "handler": function(response) {
                // Successful payment handler
                window.location.href =
                    `/payment/success?razorpay_payment_id=${response.razorpay_payment_id}&razorpay_order_id=${response.razorpay_order_id}&order_id={{ $responseData['software_order_id'] }}&gateway=razorpay`;

            },
            "prefill": {
                "name": "{{ $username ?? 'chetan' }}",
                "contact": "{{ $mobile ?? '7009518487' }}"
            },
            "theme": {
                "color": "#3399cc"
            },
            "modal": {
                "ondismiss": function() {
                    // Handle payment cancel or close event
                    if (confirm("Are you sure you want to cancel the payment?")) {
                        window.location.href =
                            `/razorpay/payments/cancel?order_id={{ $responseData['software_order_id'] }}`;
                    } else {
                        console.log("Payment popup closed, but no cancellation action.");
                    }
                }
            }
        };

        // Initialize Razorpay instance
        const rzp = new Razorpay(options);

        // Function to open Razorpay
        function openRazorpay() {
            rzp.open();
        }

        setTimeout(() => {
            console.log("Opening Razorpay modal on page load");
            openRazorpay();
        }, 1000);

        // Open Razorpay popup on image click
        document.getElementById('payImage').addEventListener('click', function(e) {
            e.preventDefault();
            openRazorpay();
        });
    </script>
</body>

</html>
