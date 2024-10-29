<?php

/**
 * AWP Plugin base class
 */

/**
 * Class AWP_Base
 */
class AWP_Base
{
    /**
     * Config
     *
     * @var AIP_Config
     */
    var $_config = null;
    
    /**
     * PHP5 Constructor
     */
    function __construct()
    {
       /*
        * Do something...
        */
    }
    
    /**
     * Runs plugin
     */
    function run()
    {
    }
    
    /**
     * Returns plugin instance
     *
     * @return AWP_Base
     */
    function &instance()
    {
        static $instances = array();
        
        if (!isset($instances[0])) {
            $class = __CLASS__;
            $instances[0] =  new $class();
        }
        
        return $instances[0];
    }
    
}
