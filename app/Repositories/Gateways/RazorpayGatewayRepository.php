<?php

namespace App\Repositories\Gateways;

use Razorpay\Api\Api;
use Illuminate\Http\Request;

class RazorpayGatewayRepository
{
    protected $keyId;
    protected $secretKey;

    public function __construct()
    {
        $this->keyId = env('RAZORPAY_KEY_ID');
        $this->secretKey = env('RAZORPAY_SECRET_KEY');
    }

    public function processPayment(array $data)
    {

        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

        // Convert amount to paise (1 INR = 100 paise)
        $order['amount'] = $data['amount'] * 100;

        // Set the currency to INR
        $order['currency'] = 'INR';

        // Use current timestamp as the receipt number (to ensure uniqueness)
        $order['receipt'] = now()->format('YmdHis');

        // Set payment capture to 1 for auto capturing
        $order['payment_capture'] = 1;

        try {
            // Create the Razorpay order
            $razorpayOrder = $api->order->create($order);
            // Return the Razorpay order object or its details
            return response()->json([
                'order_id' => $razorpayOrder->id,
                'amount' => $razorpayOrder->amount,
                'currency' => $razorpayOrder->currency,
                'receipt' => $razorpayOrder->receipt,
                'status' => $razorpayOrder->status,
                'order_details' => $razorpayOrder,
                'software_order_id' => $order['receipt'],
                'gateway' => 'razorpay',
                'blade' => 'gatewayblade.razorpay',
            ]);
        } catch (\Exception $e) {
            // Handle error if order creation fails
            return response()->json([
                'error' => 'Error creating Razorpay order: ' . $e->getMessage()
            ], 500);
        }
    }



    public function checkStatus(string $transactionId)
    {
        // Example logic for checking transaction status
        return "Razorpay: Status for transaction {$transactionId}";
    }

    public function refund(array $data)
    {
        $transactionId = '';
        // Example logic for refund
        return "Razorpay: Refunded transaction {$transactionId}";
    }


    function handleSuccess(array $data)
    {
        $razorpayPaymentId = $data['razorpay_payment_id'];
        $order_id = $data['order_id'];
        $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        try {
            // Fetch the payment from Razorpay using the payment ID
            $payment = $api->payment->fetch($razorpayPaymentId);

            // Check if the payment was successful
            if ($payment->status === 'captured') {
                // Payment is successful, update your order status
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment processed successfully.',
                    'order_id' => $order_id,
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Payment was not successful.',
                    'payment_status' => $payment->status,
                    'order_id' => $order_id,
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    function handleCallback(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        dd($data);
    }
}
