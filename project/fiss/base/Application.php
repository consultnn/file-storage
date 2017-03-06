<?php

namespace fiss\base;

class Application
{

    private $_services;

    public function __construct($config)
    {
        $this->registerServices($config['services']);
    }

    public function run()
    {
        
    }

    protected function registerServices($config)
    {
        foreach ($config as $serviceName => $serviceConfig) {
            $constructorParameters = !empty($serviceConfig['constructor']) ? $serviceConfig['constructor'] : [];
            $service = new $serviceConfig['class']( ...$constructorParameters);
            foreach ($serviceConfig['options'] as $name => $option) {
                $service->$name = $option;
            }


        }
    }
}
