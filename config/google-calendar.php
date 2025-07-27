<?php

return [

    'default_auth_profile' => env('GOOGLE_CALENDAR_AUTH_PROFILE', 'service_account'),
    'auth_profiles' => [
        'service_account' => [
            'credentials_json' => storage_path('app/google-calendar/service-account-credentials.json'),
        ],
        'oauth' => [
            'credentials_json' => storage_path('app/google-calendar/oauth-credentials.json'),
            'token_json' => storage_path('app/google-calendar/oauth-token.json'),
        ],
    ],

    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

    // 'scopes' => [
    //     'https://www.googleapis.com/auth/userinfo.email',
    //     'https://www.googleapis.com/auth/userinfo.profile',
    //     'https://www.googleapis.com/auth/calendar',
    // ],

    'scopes' => [
        'openid',
        'email',
        'profile',
        'https://www.googleapis.com/auth/calendar.events',
    ],

    /*
     *  The email address of the user account to impersonate.
     */
    'user_to_impersonate' => env('GOOGLE_CALENDAR_IMPERSONATE'),
];
