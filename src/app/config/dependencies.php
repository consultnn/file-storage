<?php

declare(strict_types=1);

use app\actions\DownloadAction;
use app\actions\UploadAction;
use app\components\project\ProjectList;
use app\components\storage\Storage;
use app\middleware\DownloadAuthMiddleware;
use app\middleware\ProjectMiddleware;
use app\middleware\UploadAuthMiddleware;
use Interop\Container\ContainerInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\Server as GlideServer;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;
use Slim\Handlers\Strategies\RequestResponseArgs;

return [
    // Other
    'foundHandler' => function () {
        return new RequestResponseArgs();
    },
    Filesystem::class => function (ContainerInterface $container){
        /**
         * @var \Slim\Collection $settings
         * @var ProjectList $projectList
         */
        $settings = $container->get('settings');
        $projectList = $container->get(ProjectList::class);

        $storage = $settings->get('storage');

        $storageSettings = array_merge_recursive(
            $storage,
            $projectList->getActiveProject()->getStorage()
        );

        $path = rtrim($storageSettings['directory'], '/') . DIRECTORY_SEPARATOR . ltrim($storageSettings['prefix'], '/');

        $adapter = new Local($path);

        return new Filesystem($adapter);
    },
    Storage::class => function (ContainerInterface $container) {
        return new Storage(
            $container->get(Filesystem::class)
        );
    },
    ProjectList::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        return new ProjectList($settings['projects']);
    },

    // Logging
    'logger' => function(ContainerInterface $container) {
        return $container->get(LoggerInterface::class);
    },
    LoggerInterface::class => function (ContainerInterface $container) {
        /**
         * @var \Slim\Collection $settings
         */
        $settings = $container->get('settings');

        $loggerSettings = $settings->get('logger');

        $logger = new Monolog\Logger($loggerSettings['name']);

        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler(
            new StreamHandler($loggerSettings['path'], $loggerSettings['level'])
        );

        return $logger;
    },

    // Middleware
    DownloadAuthMiddleware::class => function(ContainerInterface $container){
        /**
         * @var ProjectList $projectList
         */
        $projectList = $container->get(ProjectList::class);

        return new DownloadAuthMiddleware(
            $projectList->getActiveProject(),
            $container->get(LoggerInterface::class)
        );
    },
    UploadAuthMiddleware::class => function(ContainerInterface $container){
        /**
         * @var ProjectList $projectList
         */
        $projectList = $container->get(ProjectList::class);

        return new UploadAuthMiddleware(
            $projectList->getActiveProject(),
            $container->get(LoggerInterface::class)
        );
    },
    ProjectMiddleware::class => function(ContainerInterface $container){
        return new ProjectMiddleware(
            $container->get(ProjectList::class),
            $container->get(LoggerInterface::class)
        );
    },

    // Actions
    UploadAction::class => function(ContainerInterface $container){
        return new UploadAction(
            $container->get(Storage::class)
        );
    },
    DownloadAction::class => function(ContainerInterface $container){
        return new DownloadAction(
            $container->get(GlideServer::class),
            $container->get(Storage::class)
        );
    },
    GlideServer::class => function (ContainerInterface $container) {
        /**
         * @var Storage $storage
         */
        $storage = $container->get(Storage::class);

        $source = $storage->getFilesystem();

        // Set cache filesystem
        $cache = new League\Flysystem\Filesystem(
            new League\Flysystem\Adapter\Local('/tmp/')
        );

        // Set image manager
        $imageManager = new Intervention\Image\ImageManager([
            'driver' => 'gd',
        ]);

        // Set manipulators
        $manipulators = [
            new League\Glide\Manipulators\Crop(),
            new League\Glide\Manipulators\Size(2000*2000),
            new League\Glide\Manipulators\Background(),
            new League\Glide\Manipulators\Border(),
            new League\Glide\Manipulators\Encode(),
        ];

        // Set API
        $api = new League\Glide\Api\Api($imageManager, $manipulators);

        // Setup Glide server
        $server = new GlideServer(
            $source,
            $cache,
            $api
        );

        // Set response factory
        $server->setResponseFactory(new \League\Glide\Responses\SlimResponseFactory());

        return $server;
    },
];