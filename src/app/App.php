<?php

declare(strict_types=1);

namespace app;

use app\exceptions\BadRequestHttpException;

/**
 * Class App
 */
class App extends \Slim\App
{

    /**
     * @var string
     */
    private $name;


    /**
     * @var string
     */
    private $uploadToken;

    /**
     * @var string
     */
    private $downloadToken;


    /**
     * @var array
     */
    private $routes;

    /**
     * @inheritdoc
     */
    public function __construct($container = [])
    {
        parent::__construct($container);
        $this->init();
    }

    /**
     * initialize application
     */
    public function init()
    {
        /** @var \Slim\Http\Request $request */
        $request = $this->getContainer()->get('request');
        $this->name = $request->getQueryParam('domain');

        if (empty($this->name)) {
            throw new BadRequestHttpException();
        }



    }
}
