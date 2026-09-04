<?php

return [
    'name' => env('COMPANY_NAME', env('APP_NAME', 'Engineering Management')),
    'legal_name' => env('COMPANY_LEGAL_NAME', env('COMPANY_NAME', 'PT Engineering Management Indonesia')),
    'logo' => env('COMPANY_LOGO', null),
    'address' => env('COMPANY_ADDRESS', 'Jl. Engineering No. 123, Jakarta Selatan 12430, DKI Jakarta - Indonesia'),
    'phone' => env('COMPANY_PHONE', '(021) 1234-5678'),
    'email' => env('COMPANY_EMAIL', 'info@engineering.local'),
    'website' => env('COMPANY_WEBSITE', 'www.engineering.local'),
    'tagline' => env('COMPANY_TAGLINE', 'Department of Engineering'),
];
