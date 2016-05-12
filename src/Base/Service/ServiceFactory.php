<?php

namespace Base\Service;

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
     * constructor inits array for managing services
     */
    public function __construct()
    {
        $this->serviceManager = [];
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
            $this->serviceManager[$serviceClassName] = new $serviceClassName();
        }

        return $this->serviceManager[$serviceClassName];
    }

}
