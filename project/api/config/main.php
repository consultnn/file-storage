<?php

return [
    'services' => [
        'logger' => [
            'class' => \Monolog\Logger::class,
            'constructor' => [
                'name' => 'file-storage',
                'path' => __DIR__ . 'php://stdout',
                'level' => \Monolog\Logger::DEBUG,
            ],
            'properties' => [

            ]
        ]
    ]
];
