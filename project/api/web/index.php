<?php

declare(strict_types=1);

require(__DIR__ . '/../../vendor/autoload.php');

//$config = require(__DIR__ . '/../config/main.php');

// Instantiate the app
//$application = new \fiss\base\Application($config);

$application = new \fiss\base\Application();
// Run app
$application->run();
