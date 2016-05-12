<?php

namespace Base\Service;

/**
 * abstract Base Service for inharitance and PHP Magic
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
abstract class BaseService
{

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
                throw new \Exception($name . ' does not exist in ' . get_class($this));
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
                throw new \Exception($name . ' does not exist in ' . get_class($this));
        }
    }

}
