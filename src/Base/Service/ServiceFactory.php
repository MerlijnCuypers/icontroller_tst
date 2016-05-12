<?php

namespace Base\Service;

use Monolog\Logger;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Factory class to create service
 * singleton variant is used to check or Service class is only created once
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
class ServiceFactory
{

    private $serviceManager;
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
     * constructor inits array for managing services
     */
    public function __construct()
    {
        $this->serviceManager = [];
    }

    public function setLog(Logger $log)
    {
        $this->log = $log;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function setCache(FilesystemCache $cache)
    {
        $this->cache = $cache;
    }

    public function getCache()
    {
        return $this->cache;
    }

    /**
     * singleton variant to get the requested service
     *
     * @param type $serviceClassName
     * @return Serice
     * @throws \Exception
     */
    public function getService($serviceClassName)
    {
        if (!array_key_exists($serviceClassName, $this->serviceManager)) {
            if (!class_exists($serviceClassName) || !strpos($serviceClassName, 'Service')) {
                throw new \Exception('Service ' . $serviceClassName . ' does not exist');
            }
            $newService = new $serviceClassName();
            $newService->setServiceFactory($this);
            $this->serviceManager[$serviceClassName] = $newService;
        }

        return $this->serviceManager[$serviceClassName];
    }

}
