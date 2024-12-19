<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>

<body>
    <h3>Redirecting to SabPaisa...</h3>
    <form action="{{ $responseData['sabPaisaUrl'] }}" method="POST" id="paymentForm">
        @csrf

        <input type="hidden" name="encData" value="{{ $responseData['encryptedData'] }}">
        <input type="hidden" name="clientCode" value="{{ $responseData['clientcode'] }}">
        <button type="submit">Click here if not redirected</button>
    </form>
    <script>
        document.getElementById('paymentForm').submit();
    </script>
</body>

</html>
