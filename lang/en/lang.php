<?php

return [
    'plugin' => [
        'name' => 'Pensoft Maintenance Plugin',
        'description' => 'Plugin for the maintenance page',
    ],
    'responses' => [
        'ajax' => [
            'message' => 'Sorry, we’re currently down for maintenance. We expect to be back soon. Thanks for your patience.',
        ],
        'view' => [
            'title' => 'Sorry, we’re currently down for maintenance.',
            'message' => 'We expect to be back soon. Thank you for your patience.',
        ],
    ],
];
