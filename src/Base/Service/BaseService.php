<?php

namespace Base\Service;

use Monolog\Logger;
use Doctrine\Common\Cache\FilesystemCache;
/**
 * abstract Base Service for inharitance and PHP Magic
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
abstract class BaseService
{

    /**
     * ServiceFactory from base
     * @var Base\Service\ServiceFactory
     */
    protected $serviceFactory;

    /**
     *
     * @var MemcachedCache
     */
    protected $cache;

    /**
     *
     * @var Logger
     */
    protected $log;

    /**
     * PHP Magic
     * makes all getter functions no longer needed
     * if a special getter function with logic is needed => create methode with same name as property
     *
     * for absolute privates give property name a "_" prefix
     *
     * @param type $name
     * @return Service Object
     * @throws Exception
     */
    public function __get($name)
    {
        switch (true) {
            case substr($name, 0, 1) === '_':
                throw new \Exception($name . ' is set as private in ' . get_class($this));
            case method_exists($this, $name):
                return $this->$name();
            case property_exists($this, $name):
                return $this->$name;
            default:
                throw new \Exception('Fail to get "'. $name . '", it does not exist in ' . get_class($this));
        }
    }

    /**
     *
     * PHP Magic
     * makes all setter functions no longer needed
     *
     * for absolute privates give property name a "_" prefix
     *
     * @param type $name
     * @param type $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        switch (true) {
            case substr($name, 0, 1) === '_':
                throw new \Exception($name . ' is set as private in ' . get_class($this));
            case property_exists($this, $name):
                $this->$name = $value;
            default:
                throw new \Exception('Fail to set "'. $name . '", it does not exist in ' . get_class($this));
        }
    }

    /**
     * EXEPTION to confirm the rule:
     * ServiceFactory is a special one :-s
     *
     * @param \Base\Service\Base\Service\ServiceFactory $serviceFactory
     */
    public function setServiceFactory(ServiceFactory $serviceFactory){
        $this->serviceFactory = $serviceFactory;
        $this->cache = $serviceFactory->getCache();
        $this->log = $serviceFactory->getLog();
    }

    /**
     * get other Service from factory
     *
     * @param type $serviceClassName
     * @return Service
     */
    protected function callService($serviceClassName){
        return $this->serviceFactory->getService($serviceClassName);
    }

}
