<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

$settings = array_merge(
    require __DIR__ . '/../config/settings.php', // Slim configuration
    require __DIR__ . '/../config/dependencies.php' // DIC configuration
);

// Instantiate the app
$app = new \app\App($settings);

// Register middleware
require __DIR__ . '/../config/middleware.php';

// Register routes
//require __DIR__ . '/../config/routes.php';

// Run app
$app->run();
