<?php

declare(strict_types=1);

use app\actions\Download;
use app\actions\Upload;
use app\middleware\DownloadAuth;
use app\middleware\UploadAuthMiddleware;

$container = $app->getContainer();

$app->post('/upload', Upload::class )->add(UploadAuthMiddleware::class);

//$app->group('/{file:\w+}_{hash:\w{1,7}}', function () {
//    /**
//     * @var \Slim\App $this
//     */
//    $this->get('.{extension:\w{3,4}}', Download::class);
//    $this->get('/{translit}.{extension:\w{3,4}}', Download::class);
//    $this->get('{params:_[\w\_-]+}.{extension:\w{3,4}}', Download::class);
//    $this->get('{params:_[\w\_-]+}/{translit}.{extension:\w{3,4}}', Download::class);
//})->add(new DownloadAuth($container));