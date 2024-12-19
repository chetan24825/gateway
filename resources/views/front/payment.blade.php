<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <!-- Include Bootstrap (Optional for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Make a Payment</h2>
        <form action="{{ route('payment') }}" method="POST">
            @csrf

            <!-- Amount Field -->
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount"
                    required>
            </div>

            <!-- Gateway Selection -->
            <div class="mb-3">
                <label for="gateway" class="form-label">Select Payment Gateway</label>
                <select name="gateway" id="gateway" class="form-select" required>
                    <option value="">Choose a gateway</option>
                    <option value="cashfree">Cashfree</option>
                    <option value="phonepe">Phonepe</option>
                    <option value="sabpaisa">Sabpaisa</option>
                    <option value="razorpay">Razorpay</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    </div>
</body>

</html>
