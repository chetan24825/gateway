<?php

namespace App\Repositories\Gateways;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class PhonepeGateWay
{
    protected $merchantId;
    protected $saltKey;
    protected $saltIndex;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = env('PHONEPE_MERCHANT_ID');
        $this->saltKey = env('PHONEPE_SALT_KEY');
        $this->saltIndex = env('PHONEPE_SALT_INDEX');
        $this->baseUrl = env('PHONEPE_API_URL');
    }

    public function processPayment(array $data1)
    {
        // dd($data);
        $data = [
            "merchantId" => $this->merchantId,
            "merchantTransactionId" =>  now()->format('YmdHis'),
            "merchantUserId" =>  now()->format('YmdHis'),
            "amount" => $data1['amount'], // Amount in paise
            "redirectUrl" => url('/payment/success?gateway=phonepe'),
            "redirectMode" => "POST",
            "callbackUrl" => url('/payment/callback?gateway=phonepe'),
            "mobileNumber" => '7009518487',
            "paymentInstrument" => [
                "type" => "PAY_PAGE",
            ]
        ];

        // Log the request data for debugging
        Log::info('PhonePe Payment Initiation Request Data:', $data);

        // Encode data
        $payload = json_encode($data);
        $encodedPayload = base64_encode($payload);

        // Log the encoded payload for verification
        Log::info('PhonePe Encoded Payload:', ['payload' => $encodedPayload]);

        // Generate checksum (X-VERIFY)
        $saltKey = $this->saltKey;
        $saltIndex =  $this->saltIndex;
        $apiEndpoint = '/pg/v1/pay';
        $xVerify = hash('sha256', $encodedPayload . $apiEndpoint . $saltKey) . '###' . $saltIndex;

        // Log the X-VERIFY value for debugging
        Log::info('PhonePe X-VERIFY:', ['xVerify' => $xVerify]);

        try {
            // Initialize HTTP client and send the request
            $client = new Client();
            $response = $client->post("{$this->baseUrl}/pg/v1/pay", [
                'json' => [
                    'request' => $encodedPayload,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $xVerify,
                ]
            ]);

            // Log the raw response for debugging
            $responseData = json_decode($response->getBody()->getContents(), true);
            Log::info('PhonePe API Response:', $responseData);

            dd($responseData);
            // Check the response from PhonePe
            if ($responseData['success']) {
                return response()->json([
                    'data' => $responseData,
                    'gateway' => 'phonepe',
                    'blade' => 'gatewayblade.phonepe',
                ]);
                // return redirect()->to($responseData['data']['instrumentResponse']['redirectInfo']['url']);
            } else {
                // Log the error details if the request failed
                Log::error('PhonePe Payment Initiation Failed:', $responseData);
                return response()->json(['error' => 'Payment initiation failed.']);
            }
        } catch (\Exception $e) {
            // Log the exception if something goes wrong during the request
            Log::error("PhonePe API Request Error: " . $e->getMessage());
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }



    public function refund(array $data) {}


    public function checkStatus($data)
    {
        dd($data);
    }

    public function handleSuccess(array $data) {}


    public function handleCallback(Request $request)
    {
        // Validate the callback data (verify X-VERIFY header and process response)
        $payload = $request->getContent();
        $xVerify = $request->header('X-VERIFY');

        // Check the X-VERIFY header against the payload to avoid fraudulent responses
        $saltKey = $this->saltKey;
        $saltIndex = $this->saltIndex;
        $apiEndpoint = '/pg/v1/pay';
        $calculatedXVerify = hash('sha256', $payload . $apiEndpoint . $saltKey) . '###' . $saltIndex;

        if ($xVerify !== $calculatedXVerify) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }

        // Process the response data (Payment Status)
        $responseData = json_decode($payload, true);
        if ($responseData['success'] && $responseData['data']['code'] === 'PAYMENT_INITIATED') {
            dd('Payment initiated successfully', $responseData);
            // Handle payment initiation success
        } else {
            dd('Payment initiated failed', $responseData);
            // Handle payment failure
        }

        return response()->json(['success' => true]);
    }
}
