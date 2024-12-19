<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\Gateways\PhonepeGateWay;
use App\Repositories\Gateways\CashFreeGateWay;
use App\Repositories\Gateways\SabpaisaGateWay;
use App\Repositories\Gateways\RazorpayGatewayRepository;

class GatewayService
{
    protected $gateway;

    public function __construct(string $gateway)
    {
        $this->gateway = $this->resolveGateway($gateway);
    }

    protected function resolveGateway(string $gateway)
    {
        return match ($gateway) {
            'razorpay' => new RazorpayGatewayRepository(),
            'cashfree' => new CashFreeGateWay(),
            'sabpaisa' => new SabpaisaGateWay(),
            'phonepe' => new PhonepeGateWay(),
            default => throw new \Exception('Invalid Gateway Selected'),
        };
    }

    public function processPayment(array $data)
    {

        return $this->gateway->processPayment($data);
    }

    public function checkStatus(string $transactionId)
    {
        return $this->gateway->checkStatus($transactionId);
    }

    public function refund(array $data)
    {
        return $this->gateway->refund($data);
    }

    public function handleSuccess(array $request)
    {
        return $this->gateway->handleSuccess($request);
    }

    public function handleCallback(Request $request)
    {
        return $this->gateway->handleCallback($request);
    }
}
