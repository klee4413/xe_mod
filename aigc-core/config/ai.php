<?php

return [
    'default' => env('AI_DEFAULT_PROVIDER', 'google'),

    'providers' => [
        'google' => [
            'api_key' => env('GOOGLE_AI_API_KEY'),
            'model' => env('AI_MODEL', 'gemini-3.1-flash-lite-preview'),
            'temperature' => env('AI_TEMPERATURE', 0.7),
        ],
    ],
];
