<?php

declare(strict_types=1);

use app\actions\DownloadAction;
use app\actions\UploadAction;
use app\middleware\DownloadAuthMiddleware;
use app\middleware\UploadAuthMiddleware;

$container = $app->getContainer();

$app->post('/upload', UploadAction::class )->add(UploadAuthMiddleware::class);

$app->group('/{file:\w+}', function () {
    /**
     * @var \Slim\App $this
     */
    $this->get('.{extension:\w{3,4}}', DownloadAction::class);
    $this->get('/{translit}.{extension:\w{3,4}}', DownloadAction::class);
    $this->get('{params:_[\w\_-]+}.{extension:\w{3,4}}', DownloadAction::class);
    $this->get('{params:_[\w\_-]+}/{translit}.{extension:\w{3,4}}', DownloadAction::class);
})->add(DownloadAuthMiddleware::class);