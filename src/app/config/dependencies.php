<?php

declare(strict_types=1);

use app\components\project\ProjectList;
use app\components\storage\image\ImagineEditor;
use app\components\storage\Storage;
use app\middleware\ProjectMiddleware;
use app\middleware\UploadAuthMiddleware;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Slim\Handlers\Strategies\RequestResponseArgs;

return [
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
    'storage' => function (ContainerInterface $container) {
        /** @var \Slim\Collection $settings */
        $settings = $container->get('settings');

        return new Storage($settings->get('storage'));
    },
    'imageEditor' => function () {
        return new ImagineEditor();
    },
    ProjectList::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');

        return new ProjectList($settings['projects']);
    },
    UploadAuthMiddleware::class => function(){
        return new UploadAuthMiddleware();
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
    }
];