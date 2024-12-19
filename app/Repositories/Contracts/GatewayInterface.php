<?php

namespace App\Repositories\Contracts;

interface GatewayInterface
{
    public function processPayment(array $data);
    public function checkStatus(string $transactionId);
    public function refund(array $data);
    public function handleSuccess(array $request);
    public function handleCallback(array $request);
}
