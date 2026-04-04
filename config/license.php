<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Received
    |--------------------------------------------------------------------------
    | Agar payment aa jaye to .env mein PAYMENT_RECEIVED=true kar do.
    | Yeh system ko unlock kar dega.
    */
    'payment_received' => env('PAYMENT_RECEIVED', false),

    /*
    |--------------------------------------------------------------------------
    | Unlock Secret Key
    |--------------------------------------------------------------------------
    | Emergency unlock ke liye secret key. .env mein set karo.
    | SYSTEM_UNLOCK_KEY=your_secret_key_here
    */
    'unlock_key' => env('SYSTEM_UNLOCK_KEY', null),
];
