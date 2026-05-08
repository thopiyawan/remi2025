<?php

declare(strict_types=1);

// return [

//     /*
//     |--------------------------------------------------------------------------
//     | Gemini API Key
//     |--------------------------------------------------------------------------
//     |
//     | Here you may specify your Gemini API Key and organization. This will be
//     | used to authenticate with the Gemini API - you can find your API key
//     | on Google AI Studio, at https://aistudio.google.com/app/apikey.
//     */

//     'api_key' => env('GEMINI_API_KEY', 'something'),

//     /*
//     |--------------------------------------------------------------------------
//     | Gemini Base URL
//     |--------------------------------------------------------------------------
//     |
//     | If you need a specific base URL for the Gemini API, you can provide it here.
//     | Otherwise, leave empty to use the default value.
//     */
//     'base_url' => env('GEMINI_BASE_URL'),

//     /*
//     |--------------------------------------------------------------------------
//     | Request Timeout
//     |--------------------------------------------------------------------------
//     |
//     | The timeout may be used to specify the maximum number of seconds to wait
//     | for a response. By default, the client will time out after 30 seconds.
//     */

//     'request_timeout' => env('GEMINI_REQUEST_TIMEOUT', 30),

    
// ];

return [
    'driver' => 'vertex_ai', // สำคัญมาก: ต้องเปลี่ยนจาก 'google' เป็น 'vertex_ai'
    
    'drivers' => [
        'vertex_ai' => [
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'a-medcare-x9ux'),
            'location' => env('GOOGLE_CLOUD_LOCATION', 'us-central1'),
            'credentials' => env('GOOGLE_APPLICATION_CREDENTIALS'),
        ],
    ],
];