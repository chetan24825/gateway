<?php

namespace App\Repositories\Gateways;

use Illuminate\Http\Request;


class SabpaisaGateWay
{
    protected $baseUrl;
    protected $clientCode;
    protected $userName;
    protected $password;
    protected $authKey;
    protected $authIV;
    private const OPENSSL_CIPHER_NAME = "aes-128-cbc";
    private const CIPHER_KEY_LEN = 16;

    public function __construct()
    {
        $this->baseUrl = env('SABPAISA_BASE_URL');
        $this->clientCode = env('SABPAISA_CLIENT_CODE');
        $this->userName = env('SABPAISA_USER_NAME');
        $this->password = env('SABPAISA_PASSWORD');
        $this->authKey = env('SABPAISA_AUTH_KEY');
        $this->authIV = env('SABPAISA_AUTH_IV');
    }

    public function processPayment(array $data)
    {
        // Prepare the payment data
        $paymentData = [
            'payerName' => 'Chetan',
            'payerEmail' => 'chetankumar24825@gmail.com',
            'payerMobile' => '7009518487',
            'clientTxnId' => now()->format('YmdHis'),
            'amount' => $data['amount'],
            'clientCode' => $this->clientCode,
            'transUserName' => $this->userName,
            'transUserPassword' => $this->password,
            'callbackUrl' => url('/payment/success'),
            'channelId' => 'W', // Web
            // 'payerAddress' => 'loghar Gate, Amritsar',
            'mcc' => '5137', // MCC code (replace this with your actual code)
            'transDate' => now()->format('YmdHis'), // Current timestamp in the required format
        ];

        // Encrypt payment data
        try {
            $key = $this->authKey;
            $iv = $this->authIV;
            $encryptedData = $this->encrypt($key, $iv, http_build_query($paymentData));
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
        $sabPaisaUrl = $this->baseUrl;
        $clientcode = $this->clientCode;

        return response()->json([
            'sabPaisaUrl' => $sabPaisaUrl,
            'encryptedData' => $encryptedData,
            'paymentData' => $paymentData,
            'clientcode' => $clientcode,
            'blade' => 'gatewayblade.sabpaisa',
            'gateway' => 'sabpaisa',
        ]);
    }



    public function checkStatus(string $transactionId)
    {
        // Example logic for checking transaction status
        return "CashFree: Status for transaction {$transactionId}";
    }

    public function handleSuccess(array $data) {}



    private static function fixKey($key)
    {
        if (strlen($key) < self::CIPHER_KEY_LEN) {
            return str_pad("$key", self::CIPHER_KEY_LEN, "0");
        }

        if (strlen($key) > self::CIPHER_KEY_LEN) {
            return substr($key, 0, self::CIPHER_KEY_LEN);
        }

        return $key;
    }


    public static function encrypt($key, $iv, $data)
    {
        $encodedEncryptedData = base64_encode(openssl_encrypt($data, self::OPENSSL_CIPHER_NAME, self::fixKey($key), OPENSSL_RAW_DATA, $iv));
        $encodedIV = base64_encode($iv);
        $encryptedPayload = $encodedEncryptedData . ":" . $encodedIV;

        return $encryptedPayload;
    }

    public static function decrypt($key, $iv, $data)
    {
        $parts = explode(':', $data);
        $encrypted = $parts[0];
        $iv = $parts[1];
        $decryptedData = openssl_decrypt(base64_decode($encrypted), self::OPENSSL_CIPHER_NAME, self::fixKey($key), OPENSSL_RAW_DATA, base64_decode($iv));

        return $decryptedData;
    }

    public function refund(array $data) {}

    function handleCallback(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        dd($data);
    }
}
