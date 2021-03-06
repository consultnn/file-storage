<?php

use app\actions\Download;
use app\actions\Upload;
use app\Router;

require(__DIR__ . '/../config/bootstrap.php');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../App.php');

spl_autoload_register(['App', 'autoload'], true, true);

$localConfigPath = __DIR__ . '/../config/main-local.php';

$config = array_merge(
    require(__DIR__ . '/../config/main.php'),
    file_exists($localConfigPath) ? include $localConfigPath : []
);

\App::instance($config);

Router::register('POST upload/<project:\w+>/<uploadToken:\w+>', Upload::class);
Router::register(
    [
        'GET <file:\w+>_<hash:\w{1,7}>.<extension:\w{3,4}>',
        'GET <file:\w+>_<hash:\w{1,7}>/<translit>.<extension:\w{3,4}>',
        'GET <file:\w+>_<hash:\w{1,7}><params:_[\w\_-]+>.<extension:\w{3,4}>',
        'GET <file:\w+>_<hash:\w{1,7}><params:_[\w\_-]+>/<translit>.<extension:\w{3,4}>',
    ],
    Download::class
);

echo Router::process($_SERVER['REQUEST_URI']);