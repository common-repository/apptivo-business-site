<?php
/**
 * AWP_Datachae(Apptivo Wordpress Datacache).
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once 'common-util.php';
require_once 'File.php';
class AWP_Cache_Util extends AWP_Common_Util
{
	
   /**
     * Memcache object
     *
     * @var Memcache
     */
    var $_memcache = null;
    
    /**
     * PHP5 constructor
     */
    function __construct()
    {   
    	if(class_exists('Memcache')) 
    	{
    		$this->_memcache =  new Memcache();
    	}
    	
    	if(class_exists('Plugin_Cache_File'))
    	{ 
    		$this->_disccache =  new Plugin_Cache_File();
    	}
         
    }
    
	/**
	 * To check if the memcahe isconnect or not.
	 *
	 * @param unknown_type $host
	 * @param unknown_type $port
	 */	
	function connectmcache($hostname_portno = '')
	{  	
		if(isset($hostname_portno) && $hostname_portno != '')
	{
		$host_port = explode(":",$hostname_portno);
		$hostname = trim($host_port[0]);
		$portno = trim($host_port[1]);
		$awp_memcache_connect = $this->_memcache->connect($hostname,$portno);
	}else {
		
		if(!defined('AWP_MEMCACHED_HOST') && !defined('AWP_MEMCACHED_PORT'))
		{
		$mcache = $this->mcacheinfo();
		$host_port = explode(":",$mcache);
		$hostname = trim($host_port[0]);
		$port = trim($host_port[1]);
		if(!empty($hostname) && !empty($port)) :		
			$awp_memcache_connect = $this->_memcache->connect($hostname,$port);
		else:
			return false;
		endif;
		}
		else { 
			$awp_memcache_connect = $this->_memcache->connect(AWP_MEMCACHED_HOST,AWP_MEMCACHED_PORT);
		}
	}
        return  $awp_memcache_connect;
	}
	/**
	 * Test MemCache connect.
	 *
	 * @param unknown_type $hostname
	 * @param unknown_type $port
	 */
	function testmcacheconnect($hostname,$port)
	{
		$awp_memcache_connect = $this->_memcache->connect($hostname,$port);		
		return  $awp_memcache_connect;
	}
	/**
	 * To store Datas to Memcache
	 *
	 * @param unknown_type $key
	 * @param unknown_type $data
	 */
	function storedata($key,$data)
	{   
		if(!defined('APPTIVO_BUSINESS_API_KEY'))
		{
		$api_key = $this->getsiteinfo();
		}else {
		$api_key = APPTIVO_BUSINESS_API_KEY;			
		}
		$key = $api_key.$key;
		return @$this->_memcache->set($key, $data);   
	}
	/**
	 * To get the Datas from Memcahe
	 *
	 * @param unknown_type $key
	 */
	function getdata($key)
	{   
	    if(!defined('APPTIVO_BUSINESS_API_KEY'))
		{
		$api_key = $this->getsiteinfo();
		}else {
		$api_key = APPTIVO_BUSINESS_API_KEY;			
		}	
		$key = $api_key.$key;		
		return @$this->_memcache->get($key);
	}
    
    /**
     * Deletes data
     *
     * @param string $key
     * @return boolean
     */
    function delete($key)
    {   
        if(!defined('APPTIVO_BUSINESS_API_KEY'))
		{
		$api_key = $this->getsiteinfo();
		}else {
		$api_key = APPTIVO_BUSINESS_API_KEY;			
		}
		$key = $api_key.$key;
        return @$this->_memcache->delete($key);
    }
    
    /**
     * Flushes all data
     *
     * @return boolean
     */
    function flush()
    {
        return @$this->_memcache->flush();
    }
    /**
     * check memcahce enable
     * @return <type> 
     */
    function check_memcache_enable(){
         if(class_exists('Memcache'))
           if($this->connectmcache())
             return 1;
         return 0;
    }
    /**
     *
     * @param <type> $plugincall_function
     * @param <type> $plugincall_params
     * @param <type> $publishdate_function
     * @param <type> $publishdate_params
     * @param <type> $plugincall_key
     * @param <type> $publishdate_key
     * @return <type>
     */
    function get_memache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key){
                        $awp_cache_publishdate = $this->getdata($publishdate_key);
						
                      	if(empty($awp_cache_publishdate)) //Check the published date key value is set in memcahe or not.
		    	{ 
                            $response = $this->set_memcache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key);
		    	}
                        else {
                               $publish_date = getsoapCall(APPTIVO_BUSINESS_SERVICES,$publishdate_function,$publishdate_params);
                                $publish_prevDate =   $publish_date->return;
                                if($publish_date!="E_100")
                                { 
		    		if($publish_prevDate == $awp_cache_publishdate)
		    		{   
                                    $response = $this->getdata($plugincall_key);
		   		}else {
                                    $response = $this->set_memcache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key);
		    		}
                                }
                                else{
                                    $response = $this->getdata($plugincall_key);
                                }
		    	}
                       return $response;
    }
    /**
     *
     * @param <type> $plugincall_function
     * @param <type> $plugincall_params
     * @param <type> $publishdate_function
     * @param <type> $publishdate_params
     * @param <type> $plugincall_key
     * @param <type> $publishdate_key
     * @return <type> 
     */
    function set_memcache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key){
        $response = getsoapCall(APPTIVO_BUSINESS_SERVICES, $plugincall_function, $plugincall_params);
        $publish_date = getsoapCall(APPTIVO_BUSINESS_SERVICES, $publishdate_function, $publishdate_params);
        $this->storedata($plugincall_key, $response);
        $this->storedata($publishdate_key, $publish_date->return);
        return $response;
    }
    /**
     *
     * @param <type> $wsdl
     * @param string $publishdate_key
     * @param string $plugincall_key
     * @param <type> $publishdate_function
     * @param <type> $plugincall_function
     * @param <type> $publishdate_params
     * @param <type> $plugincall_params
     * @return <type> 
     */
    function get_data($wsdl,$publishdate_key,$plugincall_key,$publishdate_function,$plugincall_function,$publishdate_params,$plugincall_params)
    {         $publishdate_key = APPTIVO_BUSINESS_API_KEY.$publishdate_key;
              $plugincall_key  = APPTIVO_BUSINESS_API_KEY.$plugincall_key;
            if($this->check_memcache_enable())
          	{
                $response = $this->get_memache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key);
          	}
          	else {
              	 $response = $this->get_diskcache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key);
          	}
               return $response;
    }
    /**
     *
     * @param <type> $plugincall_function
     * @param <type> $plugincall_params
     * @param <type> $publishdate_function
     * @param <type> $publishdate_params
     * @param <type> $plugincall_key
     * @param <type> $publishdate_key
     * @return <type> 
     */
    function get_diskcache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key){
      $awp_cache_publishdate = $this->_disccache->get($publishdate_key);
       if (empty($awp_cache_publishdate)) { //Check the published date key value is set in memcahe or not.
            $response = $this->set_diskcache_data($plugincall_function, $plugincall_params, $publishdate_function, $publishdate_params, $plugincall_key, $publishdate_key);
        }
        else {
        	$publish_date = getsoapCall(APPTIVO_BUSINESS_SERVICES, $publishdate_function, $publishdate_params);
            $publish_prevDate = $publish_date->return;
            if ($publish_date != "E_100") {
                if ($publish_prevDate == $awp_cache_publishdate) {
                    $response = $this->_disccache->get($plugincall_key);
            } else {
                    $response = $this->set_diskcache_data($plugincall_function, $plugincall_params, $publishdate_function, $publishdate_params, $plugincall_key, $publishdate_key);
                }
            } else {
                $response = $this->_disccache->get($plugincall_key);
            }
        }
        return $response;
    }
    /**
     *
     * @param <type> $plugincall_function
     * @param <type> $plugincall_params
     * @param <type> $publishdate_function
     * @param <type> $publishdate_params
     * @param <type> $plugincall_key
     * @param <type> $publishdate_key
     * @return <type> 
     */
     function set_diskcache_data($plugincall_function,$plugincall_params,$publishdate_function,$publishdate_params,$plugincall_key,$publishdate_key){
       $response = getsoapCall(APPTIVO_BUSINESS_SERVICES, $plugincall_function, $plugincall_params);
       $publish_date = getsoapCall(APPTIVO_BUSINESS_SERVICES, $publishdate_function, $publishdate_params);
       $this->_disccache->set($plugincall_key, $response);
       $this->_disccache->set($publishdate_key, $publish_date);
       return $response;
    }
}
?>