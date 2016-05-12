<?php

namespace Base\Controller;

use \Base\Base;

/**
 * BaseController
 * sets base properties to use
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
abstract class BaseController
{

    /**
     * ServiceFactory from base
     * @var Base\Service\ServiceFactory
     */
    public $serviceFactory;

    /**
     * Logger from base
     * @var Monolog\Logger
     */
    public $log;

    /**
     * set base in controller
     * @param Base $base
     */
    public function __construct(Base $base)
    {
        $this->serviceFactory = $base->getServiceFactory();
        $this->log = $base->getLogger();
        $this->initMainService();
    }

    /**
     * main linked service of controller
     * if exists
     * @var type
     */
    public $service;

    /**
     * try to get service matching the controller
     */
    public function initMainService()
    {
        $serviceName = str_replace('Controller', 'Service', get_class($this));
        if (class_exists($serviceName)) {
            $this->service = $this->serviceFactory->getService($serviceName);
        }
    }

}
