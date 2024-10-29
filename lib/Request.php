<?php

/**
 * AWP Request object
 */

/**
 * Class AWP_Request
 */
class AWP_Request
{
    /**
     * Returns request value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get($key, $default = null)
    {
        $AWP_Request = new AWP_Request();
        $request = $AWP_Request->get_request();
        
        if (isset($request[$key])) {
            $value = $request[$key];
            
            if (defined('TEMPLATEPATH') || get_magic_quotes_gpc()) {
                $value = AWP_stripslashes($value);
            }
            
            return $value;
        }
        
        return $default;
    }
    
    /**
     * Returns string value
     *
     * @param string $key
     * @param string $default
     * @param boolean $trim
     * @return string
     */
    function get_string($key, $default = '', $trim = true)
    {
        $AWP_Request = new AWP_Request();
        $value = (string) $AWP_Request->get($key, $default);
        
        return ($trim) ? trim($value) : $value;
    }
    
    /**
     * Returns integer value
     *
     * @param string $key
     * @param integer $default
     * @return integer
     */
    function get_integer($key, $default = 0)
    {
        $AWP_Request = new AWP_Request();
        return (integer) $AWP_Request->get($key, $default);
    }
    
    /**
     * Returns double value
     *
     * @param string $key
     * @param double $default
     * @return double
     */
    function get_double($key, $default = 0.)
    {
        $AWP_Request = new AWP_Request();
        return (double) $AWP_Request->get($key, $default);
    }
    
    /**
     * Returns boolean value
     *
     * @param string $key
     * @param boolean $default
     * @return boolean
     */
    function get_boolean($key, $default = false)
    {
        $AWP_Request = new AWP_Request();
        return awp_to_boolean($AWP_Request->get($key, $default));
    }
    
    /**
     * Returns array value
     *
     * @param string $key
     * @param array $default
     * @return array
     */
    function get_array($key, $default = array())
    {
        $AWP_Request = new AWP_Request();
        $value = $AWP_Request->get($key);
        
        if (is_array($value)) {
            return $value;
        } elseif ($value != '') {
            return preg_split("/[\r\n,;]+/", trim($value));
        }
        
        return $default;
    }
    
    /**
     * Returns request array
     * 
     * @return array
     */
    function get_request()
    {
        return array_merge($_GET, $_POST);
    }
}