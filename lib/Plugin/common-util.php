<?php
/**
 * AWP_Common_Util Class.
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
class AWP_Common_Util
{
	/**
	 * Get site Key information.
	 *
	 * @return unknown
	 */
	function getsiteinfo()
	{
		$apptivo_api_key= get_option('apptivo_apikey');
		return $apptivo_api_key;
	}
	/**
	 * Get Memcache Information.
	 *
	 * @return unknown
	 */
	function mcacheinfo()
	{
		$machchesettings = get_option('awp_memcache_settings');
		return $machchesettings['hostname_portno'];
		
	}
}