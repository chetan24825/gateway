<?php

return [
    //These are for the Marketplace
    'appID' => env('CASHFREE_APP_ID'),
    'secretKey' => env('CASHFREE_SECRET_KEY'),
    'testURL' => 'https://ces-gamma.cashfree.com',
    'prodURL' => 'https://ces-api.cashfree.com',
    'maxReturn' => 100, //this is for request pagination
    'isLive' => env('CASHFREE_TEST_MODE', false),

    //For the PaymentGateway.
    'PG' => [
        'appID' => env('CASHFREE_APP_ID'),
        'secretKey' => env('CASHFREE_SECRET_KEY'),
        'testURL' => 'https://test.cashfree.com',
        'prodURL' => 'https://api.cashfree.com',
        'isLive' => env('CASHFREE_TEST_MODE', false)
    ]
];
