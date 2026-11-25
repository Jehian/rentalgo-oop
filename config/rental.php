<?php

return [

    'currency' => 'IDR',

    'timezone' => 'Asia/Jakarta',

    'car_insurance_rate' => 0.10,

    'motorcycle_weekly_discount_rate' => 0.10,

    'motorcycle_weekly_min_days' => 7,

    'late_fee_rate_per_day' => 0.50,

    'image_disk' => 'public',

    'image_folder' => 'vehicles',

    'code_prefixes' => [
        'rental' => 'RNT',
        'payment' => 'PAY',
    ],

    'demo_password' => env('RENTAL_DEMO_PASSWORD', 'password'),

];
