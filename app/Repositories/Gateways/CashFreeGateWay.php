<?php

namespace App\Repositories\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use LoveyCom\CashFree\PaymentGateway\Refund;

class CashFreeGateWay
{
    protected $appId;
    protected $secretKey;
    protected $mode;
    protected $baseUrl;
    protected $baseUrlTest;

    public function __construct()
    {
        $this->appId = env('CASHFREE_APP_ID');
        $this->secretKey = env('CASHFREE_SECRET_KEY');
        $this->mode = env('CASHFREE_MODE');
        $this->baseUrlTest = env('CASHFREE_TEST_URL');
        $this->baseUrl = env('CASHFREE_LIVE_URL');
    }

    public function processPayment(array $data)
    {
        $url = $this->mode == 'test' ? $this->baseUrlTest : $this->baseUrl;
        $orderData = [
            'order_id' => now()->format('YmdHis'), // Replace with your unique order ID
            'order_amount' =>  $data['amount'], // Replace with the order amount
            'order_currency' => 'INR',
            'customer_details' => [
                "customer_id" => now()->format('YmdHis'),
                "customer_name" => "Chetan Kumar",
                "customer_email" => "chetankumar24825@gmail.com",
                "customer_phone" => '7009518487'
            ],
            "order_meta" => [
                "return_url" => url('/payment/success?order_id={order_id}&gateway=cashfree')
            ]
        ];



        $response = Http::withHeaders([
            'X-Client-Id' =>  $this->appId,
            'X-Client-Secret' => $this->secretKey,
            'x-api-version' => '2023-08-01',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url . '/orders', $orderData);

        if ($response->successful()) {
            $responseData = $response->json();
            return response()->json([
                'success' => 'Payment successful',
                'data' => $responseData,
                'blade' => 'gatewayblade.cashfree',
                'gateway' => 'cashfree',
                'mode' => $this->mode == 'test' ? 'sandbox' : 'production',
            ]);
        }
        return response()->json(['error' => 'Payment failed']);
    }



    public function refund(array $data)
    {
        $orderId = '';
        $referenceId = '';
        $amount = '';
        $remark = "";
        $refund = new Refund();

        try {
            // Using the create method for a regular refund
            $response = $refund->create($orderId, $referenceId, $amount, $remark);
            return response()->json(['success' => 'Refund successful', 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function checkStatus($data)
    {
        dd($data);
        // Example logic for checking transaction status
    }

    public function handleSuccess(array $data)
    {
        $url = $this->mode == 'test' ? $this->baseUrlTest : $this->baseUrl;
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'x-api-version' => '2023-08-01',
            'X-Client-Id' =>  $this->appId,
            'X-Client-Secret' => $this->secretKey
        ])->get($url . '/orders/' . $data['order_id'] . '/payments');

        $payments = $response->json();
        dd($payments, $data['order_id']);
        if ($response->successful()) {
            $payments = $response->json();
            return response()->json(['success' => 'Payment successful', 'data' => $payments]);
        } else {
            return response()->json(['error' => 'Payment failed']);
        }
    }

    function handleCallback(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        dd($data);
    }
}
