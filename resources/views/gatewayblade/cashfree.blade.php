<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    <div class="contact-page">
        <div class="container">
            <div class="row">
                <style>
                    #renderBtn {
                        cursor: pointer;
                    }
                </style>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <div class="single-adderss-block text-center pe-auto" id="renderBtn">
                        <h3 class="mb-4">PayMent</h3>
                        <img class="img-fluid img-thumbnail pe-auto" src="{{ asset('front/img/images.webp') }}"
                            alt="">
                    </div>
                </div>
                <div class="col-md-4">
                </div>


            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha384-oLlnbB+/nnrzRmEIlUC7Ydp+09ohOlcua+2srf6oAMcOo0g5F6X6p+4AkElnmbw5" crossorigin="anonymous">
    </script>
    <script>
        const cashfree = Cashfree({
            mode: "{{ $responseData['mode'] }}" //sandbox or production
        });
        window.onload = function() {
            document.getElementById('renderBtn').click();
        };

        document.getElementById('renderBtn').addEventListener('click', function() {
            cashfree.checkout({
                paymentSessionId: "{{ $responseData['data']['payment_session_id'] }}",
            });
        });
    </script>
</body>

</html>
