<?php
/**
 * IP Deny
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
if(!defined('AWP_PLUGIN_BASEPATH')){ define('AWP_PLUGIN_BASEPATH',plugin_dir_path(__FILE__)); }
if(!defined('AWP_INC_DIR')){ define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc'); }
require_once AWP_INC_DIR . '/config.php';
require_once AWP_LIB_DIR . '/Plugin.php';
/**
 * Class AWP_IPDeny
 */
add_action('wp_ajax_nopriv_delete_ipbannedaccount', 'delete_ipbannedaccount');
add_action('wp_ajax_delete_ipbannedaccount', 'delete_ipbannedaccount');

function delete_ipbannedaccount()
{
	global $wpdb;
	$id = sanitize_text_field($_POST['ip_id']); 
	$table_name=$wpdb->prefix.'absp_ipdeny';
	$result = $wpdb->query($wpdb->prepare("DELETE  FROM " .$table_name
	            . "  WHERE ID =%d ",$id  ));
    if($result)
	{
	 echo "Success::".esc_attr($id);
	 ?>
	 <?php 
	 }
	else {
	echo mysql_error();
	 }
	die();   
}

class AWP_IPDeny extends AWP_Base
{
	
  	/**
     * PHP5 constructor
     */
	
    function __construct()
    {
    	
    }
    
    function &instance()
    {
        static $instances = array();
        if (!isset($instances[0])) {
            $class = __CLASS__;
            $instances[0] =  new $class();
        }        
        return $instances[0];
    }
        
    function settings()
    {
		$IPSettings = new AWP_Request();
    	global $wpdb;
	    $table_name = $wpdb->prefix . "absp_ipdeny";  //table Name
	    if(isset($_POST['absp_ip']) && !empty($_POST['absp_ip'])){
            
            $ip_type= $IPSettings->get_string("ip_type");
            
           
				$error = "";
				if($ip_type == 'Single') :
				$ip_address= $IPSettings->get_string("ip_address");			
			    if(!$ip_address) {
			        $error .= "Please Enter IP Address<br/>";
			    }else {
				    $validate = validateIpAddress($ip_address);
				    if(!$validate) :
				    $error .= "Please Enter Valid IP Address<br/>";
				    endif;
			    }
			    else :
				    $ip_address1= $IPSettings->get_string("ip_address1");
				    $ip_address2= $IPSettings->get_string("ip_address2");
				    $ip_address = $ip_address1.'-'.$ip_address2;
		            if(!$ip_address1) {
				        $error .= "Please Enter Starting IP Address<br/>";
				    }else{
				    	$validate = validateIpAddress($ip_address1);
					    if(!$validate) :
					    $error .= "Please Enter Valid Starting IP Address<br/>";
					    endif;
				    }
		            if(!$ip_address2) {
				        $error .= "Please Enter Ending IP Address<br/>";
				    }else {
				    	$validate = validateIpAddress($ip_address2);
					    if(!$validate) :
					    $error .= "Please Enter Valid Ending IP Address<br/>";
					    endif;
				    }
				    if(empty($error)) :
					   $start_ip = ip2long($ip_address1);
					   $end_ip = ip2long($ip_address2);
					   if($end_ip <= $start_ip)
					    {
					    $error .= "Ending IP Address Should be greater than Starting IP Address.<br/>";
					    }
				    endif;
               			    
			    endif;
        
            
	    if(empty($error)) {
		   $selected_results = $wpdb->get_results("SELECT * FROM " . $table_name . " where ip_address = '". $ip_address . "' ");  
		    if(!empty($selected_results))
		   {
		   	$error = 'This IP Address already banned, Please change Your IP';
		   }
	    }
            if(empty($error)) :
                 /* Insert into table */
                    $data = '';
                    $result = $wpdb->insert( $table_name, array( 'ip_address' => $ip_address, 'ip_type' => $ip_type ), array( '%s', '%s' ) ); 
                    
                    if($result):
	              echo '<div class="error" style="border:none;background:none;color:green;font-weight:bold;font-size:16px;">Sucessfully Added</div>';
	              $ip_address = '';
	              $ip_type = 'Single';
	            endif; 
	   				   			
	   		else:
	   		    echo '<div class="error">'.esc_attr($error).'</div>'; 
   			endif;  
    	 }
    	 
    	 $single_select = '';
		 $range_select = '';
		 $ip_type = (!empty($ip_type)) ? $ip_type : '';
    	 if(trim($ip_type) == 'Range') :
    	 	$range_select = ' selected="selected"';
    	 else:
    	 	$single_select = ' selected="selected"';
		 endif; 
		 $ip_address = (!empty($ip_address)) ? $ip_address : '';
		 $ip_address1 = (!empty($ip_address1)) ? $ip_address1 : '';
		 $ip_address2 = (!empty($ip_address2)) ? $ip_address2 : '';
    	 
    	echo '<div class="wrap"><h2>Block IP Addresses</h2></div>';
        echo '<form action="" method="post" name="absp_ip_address">
               <table class="form-table"><tbody>                			

                <tr valign="top">
					<th valign="top"><label for="ip_type">IP Type </label>				
					</th>
					<td valign="top">
                     <select id="ip_type" name="ip_type">
                     <option value="Single" '.esc_attr($single_select).' >Single</option>
                     <option value="Range" '.esc_attr($range_select).' >Range</option>                     
                    </select>
                    </td>
				</tr>

			   <tr valign="top" id="single_ip" >
					<th valign="top"><label for="ip_address">IP Address </label>				
					</th>
					<td valign="top">
                     <input type="text" value="'.esc_attr($ip_address).'"  id="ip_address" name="ip_address" style="width: 250px;"><span class="description" id="sample_ip">    (eg:100.100.100.100)</span>
                    </td>
				</tr>
				
				 <tr valign="top" id="range_ip" style="display:none;">
					<th valign="top"><label for="ip_address1">IP Address </label>				
					</th>
					<td valign="top">
                     <input type="text" value="'.esc_attr($ip_address1).'"  id="ip_address1" name="ip_address1" style="width: 250px;"><span class="description" >    Starting IP: (eg:100.100.100.100)</span>
                     <br />
                     <input type="text" value="'.esc_attr($ip_address2).'"  id="ip_address2" name="ip_address2" style="width: 250px;"><span class="description" >    Ending IP: (eg:100.100.100.110)</span>
                    </td>
				</tr>
				
				<tr>
					<td colspan="2">
						<p class="submit">
							<input type="submit" value="Save" class="button-primary" id="absp_ip" name="absp_ip">
						</p>
					</td>
				</tr>
             </tbody> </table>
                </form>';
    	
    	$this->list_table();
    	
    	?>

    	<?php 
    }
    
    /*IP Address Lists*/
function list_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "absp_ipdeny";  //table Name
	$Results = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY cur_timestamp DESC");
	$datas = '';
	if(empty($Results)) {
		$datas = ' <tr ><td colspan="3" style="text-align:center;color:#f00;" >No data available.</td>';
	}else {
		foreach($Results as $selected_results):
		$datas .= '<tr id="tr_'.$selected_results->ID.'" >';
		$datas .= '<td>'.$selected_results->ip_address .'</td>';
		$datas .= '<td>'.$selected_results->ip_type .'</td>';
		$datas .= '<td><a href="javascript:delete_ipbanned('.$selected_results->ID.')" >Delete</a></td>';
		$datas .= '</tr>';
		endforeach;
	}
	
	echo '<div style="width:600px;" class="wrap"><h2>Blocked IP Addresses</h2><table class="widefat plugins" width="700" cellspacing="0" cellpadding="0">
        <thead><tr><th>IP Address</th><th>IP Type</th><th>Action</th></tr></thead>
        <tfoot><tr><th>IP Address</th><th>IP Type</th><th>Action</th></tr></tfoot>
        <tbody>'.html_entity_decode(esc_attr($datas)).'
        </tbody>
        </table></div>';
	
	?>

	<?php 
	
}
    

}

function validateIpAddress($ip_addr)
{
	
  //first of all the format of the ip address is matched
  if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr))
  {
    //now all the intger values are separated
    $parts=explode(".",$ip_addr);
    //now we need to check each part can range from 0-255
    foreach($parts as $ip_parts)
    {
      if(intval($ip_parts)>255 || intval($ip_parts)<0)
      return false; //if number is not within range of 0-255
    }
    return true;
  }
  else
    return false; //if format of ip address doesn't matches
    

}

function check_blockip()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "absp_ipdeny";  //table Name
	$ipdeny_table = absp_table_exists($table_name);
        
	if(!$ipdeny_table)
	{
		return false;
	}	
	$visitorIp = get_RealIpAddr();

	$single_results = $wpdb->get_results("SELECT * FROM " . $table_name . " where ip_type='Single'");
	$singles = array();	
	foreach($single_results as $singleresults)
	{
		array_push($singles, $singleresults->ip_address);
	}
	
	$range_results = $wpdb->get_results("SELECT * FROM " . $table_name . " where ip_type='Range'");
	$ranges = array();	
	foreach($range_results as $rangeresults)
	{
		array_push($ranges, $rangeresults->ip_address);
	}
    //Chk with Single IP
	$status = array_search($visitorIp, $singles);

	// Let's check if $status has a true OR false value.
	if($status !== false){
	    return "E_IP";
	    exit;
	}
	   
	$visit_ip = ip2long($visitorIp);
	foreach($ranges as $range_ip){
		$rangeparts = explode('-',$range_ip);		
		$start_ip = ip2long($rangeparts[0]);
	    $end_ip = ip2long($rangeparts[1]);
	    $start_ip. '-' .$end_ip;	   
	    if($visit_ip >= $start_ip && $visit_ip <= $end_ip){
	    	return "E_IP";
		    exit;
	    }
	}
	  
	return false;
}

function get_RealIpAddr(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){   //check ip from shared internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //check if ip is passed from a proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	// In some cases, multiple IP addresses might be present in the X-Forwarded-For header
	// The client's IP address is usually the first one in the list
	if (strpos($ip, ',') !== false){
		$ips = explode(',', $ip);
		$ip = trim($ips[0]);
	}

	return $ip;
}

function absp_table_exists($tableName)
{ 
	 global $wpdb;
	 $db= $wpdb->get_var("SHOW TABLES LIKE '$tableName'");
	 if($db == $tableName)
	 {
	   return TRUE;
	 }
	 else
	 {
	  return FALSE;
	 } 
}
