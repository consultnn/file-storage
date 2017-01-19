<?php

namespace fiss\base;

use fiss\Fiss;

class Application
{

    private static $_instance;


    public function __construct($config = [])
    {
        Fiss::$app = $this;
        static::$_instance = $this;

        Fiss::configure($this, $config);
        $this->init();
    }

    public function init()
    {

    }

    public function run()
    {
        
    }

}
