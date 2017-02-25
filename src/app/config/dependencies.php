<?php

declare(strict_types=1);

use app\actions\UploadAction;
use app\components\project\ProjectList;
use app\components\storage\image\ImagineEditor;
use app\components\storage\Storage;
use app\middleware\DownloadAuthMiddleware;
use app\middleware\ProjectMiddleware;
use app\middleware\UploadAuthMiddleware;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Slim\Handlers\Strategies\RequestResponseArgs;

return [
    // Other
    'foundHandler' => function () {
        return new RequestResponseArgs();
    },
    'logger' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];
        $logger = new Monolog\Logger($settings['name']);
        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

        return $logger;
    },
    'imageEditor' => function () {
        return new ImagineEditor();
    },
    Storage::class => function (ContainerInterface $container) {
        /**
         * @var \Slim\Collection $settings
         * @var ProjectList $projectList
         */
        $settings = $container->get('settings');
        $projectList = $container->get(ProjectList::class);

        $storage = $settings->get('storage');

        $storage = array_merge_recursive($storage, $projectList->getActiveProject()->getStorage());

        return new Storage($storage);
    },
    ProjectList::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        return new ProjectList($settings['projects']);
    },

    // Middleware
    DownloadAuthMiddleware::class => function(ContainerInterface $container){
        /**
         * @var ProjectList $projectList
         */
        $projectList = $container->get(ProjectList::class);

        return new DownloadAuthMiddleware($projectList->getActiveProject());
    },
    UploadAuthMiddleware::class => function(ContainerInterface $container){
        /**
         * @var ProjectList $projectList
         */
        $projectList = $container->get(ProjectList::class);

        return new UploadAuthMiddleware($projectList->getActiveProject());
    },
    ProjectMiddleware::class => function(ContainerInterface $container){
        return new ProjectMiddleware(
            $container->get(ProjectList::class)

        );
    },

    // Actions
    UploadAction::class => function(ContainerInterface $container){
        return new UploadAction(
            $container->get(Storage::class)
        );
    }
];