<?php

declare(strict_types=1);

use app\actions\DownloadAction;
use app\actions\UploadAction;
use app\middleware\DownloadAuthMiddleware;
use app\middleware\UploadAuthMiddleware;

$container = $app->getContainer();

$app->post('/upload', UploadAction::class )
    ->add(UploadAuthMiddleware::class)
;

$app->get('/{file:\w+}.{extension:\w{3,4}}', DownloadAction::class)
    ->add(DownloadAuthMiddleware::class)
;

$app->get('/{file:\w+}', DownloadAction::class)
    ->add(DownloadAuthMiddleware::class)
;