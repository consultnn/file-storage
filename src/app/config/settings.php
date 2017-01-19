<?php

declare(strict_types=1);

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Monolog settings
        'logger' => [
            'name' => 'file-storage',
            'path' => __DIR__ . 'php://stdout',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'storage' => [
            'directory' => '/storage',
        ]
    ],
];
