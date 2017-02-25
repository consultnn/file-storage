<?php

declare(strict_types=1);

use app\middleware\ProjectMiddleware;

$app->add(ProjectMiddleware::class);