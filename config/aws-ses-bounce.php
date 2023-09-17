<?php

return [
    'api_middleware' => env('ASB_API_MIDDLEWARE', []),
    'email_test_api_enabled' => env('ASB_EMAIL_TEST_API_ENABLED', true),
    'dump' => [
        'enabled' => env('ASB_DUMP_ENABLED', false),
        'url' => env('ASB_DUMP_URL', config('app.url')),
    ],
    'validate_signature' => env('ASB_VALIDATE_SIGNATURE', false),
    'max_bounce_count' => env('ASB_MAX_BOUNCE_COUNT', 3),
    'max_complaint_count' => env('ASB_MAX_COMPLAINT_COUNT', 3),
    'soft_delete_notifications' => env('ASB_SOFT_DELETE_NOTIFICATIONS', false),
    'deliveries_max_age_in_days' => env('ASB_DELIVERIES_MAX_AGE_IN_DAYS', 7),
    'bounce_reasons' => [
        '5.1.1' => [
            'message' => 'Mailbox Does Not Exist',
            'reply_code' => '550',
        ],
        '5.3.4' => [
            'message' => 'Message Too Large',
            'reply_code' => '552',
        ],
        '5.2.2' => [
            'message' => 'Mailbox Full',
            'reply_code' => '552',
        ],
        '5.6.1' => [
            'message' => 'Message Content Rejected',
            'reply_code' => '500',
        ],
        '5.0.0' => [
            'message' => 'Unknown Failure',
            'reply_code' => '554',
        ],
        '4.0.0' => [
            'message' => 'Temporary Failure',
            'reply_code' => '450',
        ],
    ],
];
