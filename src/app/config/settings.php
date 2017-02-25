<?php

declare(strict_types=1);

return [
    'settings' => [
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Monolog settings
        'logger' => [
            'name' => 'file-storage',
            'path' => __DIR__ . 'php://stdout',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'storage' => [
            'directory' => '/var/www/storage',
        ],
        'projects' => [
            'example' => [
                'storage' => [
                    'path' => 'example'
                ],
                'uploadToken' => 'N3edBMSnQrakH9nBK98Gmmrz367JxWCT',
                'downloadToken' => 'pzScy2w6Kuhz2djvMUg6TeNpBmt9rFvW',
            ]
        ],
    ],
];
