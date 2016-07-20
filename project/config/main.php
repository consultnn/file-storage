<?php
return [
    /** Secret tokens for upload files **/
    'uploadToken' => [
        'd41d8cd98f00b204e9800998ecf8427e',
    ],
    'mime-types' => include __DIR__ . '/mime-types.php',
    'components' => [
        'image' => [
            'class' => \app\components\Image::class,
            'uploadToken' => 'd41d8cd98f00b204e9800998ecf8427e',
            'downloadToken' => '9038463',
        ],
    ],
];
