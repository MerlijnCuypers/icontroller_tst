<?php

namespace Base;

use Base\Service\ServiceFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * kernel type of class
 * to keep flow consistant
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
class Base
{

    /**
     *
     * @var ServiceFactory
     */
    private $serviceFactory;

    /**
     *
     * @var Logger
     */
    public $log;

    public function __construct()
    {
        $this->initLogger();
        $this->serviceFactory = new ServiceFactory;
    }

    /**
     * init monolog
     * and register run of command
     */
    private function initLogger()
    {
        $this->log = new Logger('base');
        // todo: would be nicer to set log path based on yml config file...
        $this->log->pushHandler(new StreamHandler(__DIR__ . '/../../../app/logs/base.log', Logger::INFO));
    }

    /**
     * create controller
     * @param type $controllerName
     * @return \Mikko\Base\controllerName
     */
    public function getController($controllerName)
    {
        $controller = new $controllerName($this);

        return $controller;
    }

    /**
     * getter ServiceFactory
     * @return ServiceFactory
     */
    public function getServiceFactory()
    {
        return $this->serviceFactory;
    }

    /**
     * getter Logger
     * @return Logger
     */
    public function getLogger()
    {
        return $this->log;
    }

}
