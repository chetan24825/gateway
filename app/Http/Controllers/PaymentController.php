<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GatewayService;

class PaymentController extends Controller
{

    public function index()
    {
        return view('front.payment');
    }



    public function processPayment(Request $request)
    {
        $gateway = $request->input('gateway'); // Example: 'paypal', 'stripe', 'razorpay'
        $service = new GatewayService($gateway);
        $response = $service->processPayment($request->all());
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $response->getData(true); // Convert to array
        } else {
            $responseData = $response; // If it's not JsonResponse, just use it as-is
        }
        dd($responseData);
        return view($responseData['blade'], compact('responseData'));
    }

    public function checkStatus(Request $request, $transactionId)
    {
        $gateway = $request->input('gateway');
        $service = new GatewayService($gateway);
        $response = $service->checkStatus($transactionId);
        return response()->json(['status' => $response]);
    }

    public function refund(Request $request, $transactionId)
    {
        $gateway = $request->input('gateway');
        $service = new GatewayService($gateway);

        $response = $service->refund($transactionId);

        return response()->json(['message' => $response]);
    }

    public function handleSuccess(Request $request)
    {
        $gateway = $request->input('gateway'); // Example: 'paypal', 'stripe', 'razorpay'
        $service = new GatewayService($gateway);
        $response = $service->handleSuccess($request->all());

        return response()->json(['message' => $response]);
    }



    public function handleCancel(Request $request)
    {
        // Handle Razorpay cancellation logic here
        return response()->json(['message' => 'Razorpay payment canceled']);
    }

    public function handleCallback(Request $request)
    {
        $gateway = $request->input('gateway'); // Example: 'paypal', 'stripe', 'razorpay'
        $service = new GatewayService($gateway);
        $response = $service->handleCallback($request);
    }
}
