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
            'directory' => '/var/data/storage',
        ],
        'projects' => [
            'example' => [
                'storage' => [
                    'prefix' => 'example'
                ],
                'uploadToken' => 'N3edBMSnQrakH9nBK98Gmmrz367JxWCT',
                'downloadSignKey' => 'v-LK4WCdhcfcc%jt*VC2cj%nVpu+xQKvLUA%H86kRVk_4bgG8&CWM#k*b_7MUJpmTc=4GFmKFp7=K%67je-skxC5vz+r#xT?62tT?Aw%FtQ4Y3gvnwHTwqhxUh89wCa_',
            ]
        ],
    ],
];
