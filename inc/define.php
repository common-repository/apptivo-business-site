<?php
/**
 * Apptivo business plugin configuration
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
if(!defined('AWP_PLUGIN_BASEPATH')){ define('AWP_PLUGIN_BASEPATH',plugin_dir_path(__FILE__)); }
if(!defined('AWP_INC_DIR')){ define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc'); }
require_once AWP_PLUGIN_BASEPATH . '/apptivo-businesssite-plugin.php';
require_once AWP_INC_DIR .'/config.php';
require_once AWP_PLUGIN_BASEPATH.'apptivo/Config.php';

//API Key and Access Keys Settings.

$eCommerce_api_Key = get_option('apptivo_ecommerce_apikey');
$eCommerce_access_key = get_option('apptivo_ecommerce_accesskey');
$business_site_key = get_option('apptivo_sitekey');
if(!empty($eCommerce_api_Key) ){
	update_option('apptivo_apikey',$eCommerce_api_Key);
	update_option('apptivo_accesskey',$eCommerce_access_key);
}else if(!empty($business_site_key)){
	update_option('apptivo_apikey',$business_site_key);
	delete_option('apptivo_sitekey');
}

$apptivo_api_key = get_option('apptivo_apikey');
$apptivo_accesskey = get_option('apptivo_accesskey');
define('APPTIVO_BUSINESS_API_KEY',trim($apptivo_api_key));
define('APPTIVO_BUSINESS_ACCESS_KEY',trim($apptivo_accesskey));
/**
 * Loads plugins
 *
 * @return void
 */
function awp_load_plugins(){
	ob_start();
    //include Plugin Files.
	$plugin_dir = @opendir(AWP_PLUGINS_DIR);
    if ($plugin_dir) {
        while (($entry = @readdir($plugin_dir)) !== false) {
            if (strrchr($entry, '.') === '.php') {
                require_once AWP_PLUGINS_DIR . '/' . $entry;
            }
        }
        @closedir($plugin_dir);
    }


}

/**
 * Load Widgets
 *
 */
function awp_load_widgets(){

 //include widget files
   $widget_dir = @opendir(AWP_WIDGETS_DIR);

    if ($widget_dir) {
        while (($entry = @readdir($widget_dir)) !== false) {
            if (strrchr($entry, '.') === '.php') {
                require_once AWP_WIDGETS_DIR . '/' . $entry;
            }
        }
        @closedir($widget_dir);
    }
}

/**
 * Recursive strips slahes from the var
 *
 * @param mixed $var
 * @return mixed
 */
function awp_stripslashes($var)
{
    if (is_string($var)) {
        return stripslashes($var);
    } elseif (is_array($var)) {
        $var = array_map('awp_stripslashes', $var);
    }

    return $var;
}

/**
 * Sort customformfields by order
 */
function awp_sort_by_order($a, $b) {
	if(is_array($a) && is_array($b))
	{
	return $a["order"] - $b["order"];
	}
	else
	{
		return;
	}
}

/**
 * Search for value using key in multi-dimensional array
 * returns index if value found
 * returns false if no value is found
 */
function awp_recursive_array_search($haystack, $needle, $index = null)
{
    $aIt = new RecursiveArrayIterator($haystack);
    $it = new RecursiveIteratorIterator($aIt);

    while($it->valid()){
		if( ((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle) ){
			return $aIt->key();
		}
		$it->next();
	}
	return false;
}

/**
 * Add contact form scripts and styles, only when short code is present in page/posts
 */
function awp_check_for_shortcode($posts, $shortcode){
	if(empty($posts)){
		return $posts;
	}
	// false because we have to search through the posts first
	$found = false;

	// search through each post
	foreach($posts as $post){
		// check if post_content exists and is a non-empty string
		if(isset($post->post_content) && is_string($post->post_content)){
			// check the post content for the shortcode
			if((stripos($post->post_content, $shortcode)) !== false){
				// we have found a post with the shortcode
				$found = true;
				// stop the search
				break;
			}
		}
	}
	return $found;
}

/**
 * Converts value to boolean
 *
 * @param mixed $value
 * @return boolean
 */
function awp_to_boolean($value)
{
    if (is_string($value)) {
        switch (strtolower($value)) {
            case '+':
            case '1':
            case 'y':
            case 'on':
            case 'yes':
            case 'true':
            case 'enabled':
                return true;

            case '-':
            case '0':
            case 'n':
            case 'no':
            case 'off':
            case 'false':
            case 'disabled':
                return false;
        }
    }

    return (boolean) $value;
}
function awp_date_compare($a, $b)
{
	$t1 = strtotime($a['datetime']);
        $t2 = strtotime($b['datetime']);
	return $t1 - $t2;
}
function awp_creation_date_compare($a, $b)
{
       $t1 = date_create($a->creationDate);
       $t2 = date_create($b->creationDate);
       $diff  = date_diff($t1,$t2);
       return $diff->format("%R%a days");
}
function awp_sort_by_sequence($a, $b) {
	return $a->sequenceNumber - $b->sequenceNumber;
}
/**
 * Pagination
 *
 * @param unknown_type $reload
 * @param unknown_type $page
 * @param unknown_type $tpages
 * @return unknown
 */
function awp_paginate($reload, $page, $tpages,$totalitems) {
	$firstlabel = "<<";
	$prevlabel  = "<";
	$nextlabel  = ">";
	$lastlabel  = ">>";
	$out = '<div class="tablenav top"><div class="tablenav-pages">';
	$out .= '<span class="displaying-num">'.$totalitems.'&nbsp;items</span>';
	// first
	if($page>1) {
		$out.= "<a class=\"first-page\" href=\"" . $reload . "\">" . $firstlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"first-page disabled\" >" . $firstlabel . "</a>\n";
	}

	// previous
	if($page==1) {
		$out.= "<a class=\"first-page disabled\" >" . $prevlabel . "</a>\n";
	}
	elseif($page==2) {
		$out.= "<a class=\"prev-page\" href=\"" . $reload . "\">" . $prevlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"prev-page\" href=\"" . $reload . "&amp;pageno=" . ($page-1) . "\">" . $prevlabel . "</a>\n";
	}

	// current
	$out .='<span class="paging-input"><input type="text" class="current-page" title="Current page" name="paged" value="'.$page.'" size="1"> of <span class="total-pages">'.$tpages.'&nbsp;&nbsp;</span></span>';

	// next
	if($page<$tpages) {
		$out.= "<a class=\"next-page\" href=\"" . $reload . "&amp;pageno=" .($page+1) . "\">" . $nextlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"last-page disabled\" >" . $nextlabel . "</a>\n";
	}

	// last
	if($page<$tpages) {
		$out.= "<a class=\"last-page\" href=\"" . $reload . "&amp;pageno=" . $tpages . "\">" . $lastlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"last-page disabled\" >" . $lastlabel . "</a>\n";
	}

	$out.= "</div></div>";

	return $out;
}
/**
 * To Make Soap Call
 *
 * @param unknown_type $wsdl
 * @param unknown_type $function
 * @param unknown_type $params
 * @return unknown
 */
function getsoapCall($wsdl,$function,$params)
{
	error_log('getsoapCall');
	error_log($wsdl);
	error_log($function);
	error_log(print_r($params));
	
	
   $client = new SoapClient($wsdl);
   try {
    	 $response = $client->__soapCall($function, array($params));

    }catch(Exception $e){
		error_log(print_r($e));
        return 'E_100';
    }
	error_log(print_r($response));
   return $response;
}
/**
 * LoadMemCache Methods.
 *
 * @param unknown_type $wsdl
 * @param unknown_type $key_publishdate
 * @param unknown_type $plugincall_key
 * @param unknown_type $method_publishdate
 * @param unknown_type $plugincall_function
 * @param unknown_type $publishdate_params
 * @param unknown_type $plugincall_params
 * @return unknown
 */
  /**
     * get ApptiovWordPress(awp) Templates
     *
     * @param unknown_type $dir
     * @param unknown_type $type == 'widget' => "Widget Templates" , $type == 'plugin' => "Plugin templates"
     * @return Template Array Lists.
     */
    function get_awpTemplates($dir,$type)
    {
        $default_headers = array(
		'Template Name' => 'Template Name',
        'Template Type' => 'Template Type'
	    );
	    $templates = array();
		$dir_news = $dir;
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_news)) {
		    if ($dh = opendir($dir_news)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ( substr( $file, -4 ) == '.php' )
		        	{
					$plugin_data = get_file_data( $dir_news."/".$file, $default_headers, '' );
					if(strlen(trim($plugin_data['Template Name'])) != 0 )
					{
					   if(strtolower(trim($plugin_data['Template Type'])) == 'widget' && strtolower($type) == 'widget' )
					   {
						$templates[$plugin_data['Template Name']] = $file;
					   }else if(strtolower(trim($plugin_data['Template Type'])) == 'shortcode' && strtolower($type) == 'plugin')
					   {
					   	$templates[$plugin_data['Template Name']] = $file;
					   }else if(strtolower(trim($plugin_data['Template Type'])) == 'inline' && strtolower($type) == 'inline')
                       {
                         $templates[$plugin_data['Template Name']] = $file;
                       }
                    }

		        	}
		        }
		        closedir($dh);
		    }
		}
		return $templates;
    }

/**
 * Remove html tags from string
 *
 * @param string $str
 * @return string
 */
function html_remove($str){
	return $str;
    return preg_replace("/<[^>]*>/","",$str);
}
/**
 * Convert  Object to array.
 *
 * @param unknown_type $objectValue
 * @return unknown
 */
function awp_convertObjToArray($objectValue)
{    if(!empty($objectValue)){
	if(is_array($objectValue)) {
		$arrayValue = $objectValue;
	}
	else {
		$arrayValue = array();
		array_push($arrayValue,$objectValue);
	}
	return $arrayValue;
        }
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
    {
     $cache_obj = new AWP_Cache_Util(); //Create Object in AWP_DataCache class
     $response = $cache_obj->get_data($wsdl, $publishdate_key, $plugincall_key, $publishdate_function, $plugincall_function, $publishdate_params, $plugincall_params);
     return $response;
    }
/**
 * Recursive creates directory
 *
 * @param string $path
 * @param integer $mask
 * @param string
 * @return boolean
 */
function awp_mkdir($path, $mask = 0755, $curr_path = '')
{
    $path = awp_realpath($path);
    $path = trim($path, '/');
    $dirs = explode('/', $path);

    foreach ($dirs as $dir) {
        if ($dir == '') {
            return false;
        }

        $curr_path .= ($curr_path == '' ? '' : '/') . $dir;

        if (!@is_dir($curr_path)) {
            if (@mkdir($curr_path, $mask)) {
                @chmod($curr_path, $mask);
            } else {
                return false;
            }
        }
    }

    return true;
}
/**
 * Recursive remove dir
 *
 * @param string $path
 * @param array $exclude
 * @return void
 */
function awp_rmdir($path, $exclude = array(), $remove = true)
{
    $dir = @opendir($path);

    if ($dir) {
        while (($entry = @readdir($dir)) !== false) {
            $full_path = $path . '/' . $entry;

            if ($entry != '.' && $entry != '..' && !in_array($full_path, $exclude)) {
                if (@is_dir($full_path)) {
                    awp_rmdir($full_path, $exclude);
                } else {
                    @unlink($full_path);
                }
            }
        }

        @closedir($dir);

        if ($remove) {
            @rmdir($path);
        }
    }
}
/**
 * Recursive empty dir
 *
 * @param string $path
 * @param array $exclude
 * @return void
 */
function awp_emptydir($path, $exclude = array())
{
    awp_rmdir($path, $exclude, false);
}
/**
 * Returns realpath of given path
 *
 * @param string $path
 */
function awp_realpath($path)
{
    $path = awp_path($path);
    $parts = explode('/', $path);
    $absolutes = array();

    foreach ($parts as $part) {
        if ('.' == $part) {
            continue;
        }
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    return implode('/', $absolutes);
}
/**
 * Converts win path to unix
 *
 * @param string $path
 * @return string
 */
function awp_path($path)
{
    $path = preg_replace('~[/\\\]+~', '/', $path);
    $path = rtrim($path, '/');
    return $path;
 }
//Label Field
function awp_labelfield($field='',$class='contactform_field_label',$before='',$after='')
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	return $before.'<label for="'.$fieldid.'" class="'.$class.'">'.$showtext.'</label>'.$after;

}

function awp_jobsearch_textfield ($field='',$class='',$before='',$after='')
{

	$fieldid   = $field['fieldid'];
	$showtext  = $field['showtext'];
	$required  = $field['required'];
	$fieldtype = $field['type'];
	$options   = $field['options'];

	switch( $fieldid )
	{
		case "keywords" :
			$html = '<input type="text" value="" class="absp_jobsearch_input_text '.$class.'"  name="'.$fieldid.'" id="'.$fieldid.'">';
		break;

		case "customfield1" :
			if($fieldtype == 'select') :
			     $html .= '<select class="absp_jobsearch_select '.$class.'" value="" name="'.$fieldid.'" id="'.$fieldid.'"  > ';
                 $html .= '<option selected="selected" value="All" style="">Select  '.$showtext.'</option>';
                 foreach($options as $opt_val)
                   {
                   	$option_arr = explode('::',$opt_val);
                    $html .= '<option value="'.$option_arr[0].'" >'.$option_arr[1].'</option>';
                   }
                 $html .= '</select>';
            endif;
        break;

        case "customfield2" :

			if($fieldtype == 'checkbox') :
			   foreach($options as $opt_val)
                   {   $opt_value = strtoupper(trim($opt_val));
                       $opt_value = str_replace(" ","_",$opt_value);
                       $html .= '<input class="absp_jobsearch_input_checkbox '.$class.'" value="'.$opt_value.'" type="checkbox" name="'.$fieldid.'[]'.'" /> &nbsp;&nbsp;<label>'.$opt_val.'</label><br />';
                    }
            endif;

            if($fieldtype == 'select') :
	            $html .= '<select class="absp_jobsearch_select '.$class.'" value="" name="'.$fieldid.'" id="'.$fieldid.'"  >';
	            $html .= '<option value="" style="">Select  '.$showtext.'</option>';
	            foreach($optionvalues as $opt_val)
	                   {   $opt_value = strtoupper(trim($opt_val));
	                       $opt_value = str_replace(" ","_",$opt_value);
	     				   $html .= '<option value="'.$opt_value.'" style="">'.$opt_val.'</option>';
	                    }
	            $html .='</select>';
            endif;

        break;



	}
	 return $html;
}

/**
 * Enter description here...
 *
 * @param unknown_type $forms
 * @param unknown_type $field
 * @param unknown_type $countries
 * @param Bollean  $value_present
 * @param unknown_type $before
 * @param unknown_type $after
 * @return unknown
 */
function awp_textfield($forms='',$field='',$countries='',$value_present='',$before='',$after='',$placeholder=false, $tabindex='')
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['type'];
	$options=$field['options'];
	$optionvalues=array();
    $place_text = '';
	if($placeholder){
		$place_text = 'placeholder="'.$showtext.'"';
	}

	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" || $fieldtype=="multiselect" ){

		if(trim($fieldid) == 'industry'){
			$optionvalues=$options;
			$fieldtype = 'select';
			} else if(trim($options)!=""){
			$optionvalues=preg_split("[\n]",trim($options));//Split the String line by line.
		}

	}


		  if ($value_present) :
		    $postValue = sanitize_text_field($_REQUEST[$fieldid]);
          else :
           	$postValue="";
           endif;


		//Required Class
		if($required){
			$mandate_property='"mandatory="true"';
			$validateclass=" required";
		}
		else{
			$mandate_property="";
			$validateclass="";
		}

		//Field Validation Class
		switch($validation)
		{
			case "email":
				$validateclass .=" email";
			break;
			case "url":
				$validateclass .=" url";
			break;
			case "number":
				$validateclass .=" number";
			break;
		}

		//Captcha Class
		if($fieldid=='captcha')
         {
         $captcha_class = 'captcha';
         }
         else{
         $captcha_class = '';
         }
    switch($fieldtype)
		{
			case "text":
                            $html = '<input  '.$place_text.' type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'" tabindex="'.$tabindex.'">';
			break;
			case "textarea":
				$html  =  '<textarea  '.$place_text.' name="'.$fieldid.'" id="'.$fieldid.'_id"   class="absp_contact_textarea'.$validateclass.'" size="50"  tabindex="'.$tabindex.'">'.$postValue.'</textarea>';
			break;
			case "select":
                if($fieldid == 'country'){
                 $html =  '<select  name="'.$fieldid.'" id="'.$fieldid.'"  class="absp_contact_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
				foreach($countries as $country)
				{
					$country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
                    if($country_Code == trim($country->countryCode)){
                      $selected='selected="selected"';
                     }
                     else{
                        $selected = "";
                     }
                  $html .=  '<option value="'.$country->countryCode.'" '.$selected.'>'.$country->countryName.'</option>';
				}
				$html .=  '</select>';
                 } else  if($fieldid == 'industry')
	                {
		                if(!empty($optionvalues))
		                {
		                $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
		                	foreach( $optionvalues as $optionvalue )
							{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
								{
									$options = explode("::",$optionvalue);
									$html .=  '<option value="'.$options[0].'">'.$options[1].'</option>';
								}
							}
						$html .=  '</select>';
		                }else {

		                	 $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
		                	 $html .=  '<option value="0">Default</option>';
							 $html .=  '</select>';
		                }
	                }
               else{
					$html =  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
					foreach( $optionvalues as $optionvalue )
					{
	                                    if(trim($postValue) == trim($optionvalue)){

	                                       $selected='selected="selected"';
	                                     }
	                                     else{
	                                         $selected='';
	                                     }
						if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
							{
						$html .=  '<option value="'.$optionvalue.'" '.$selected.'>'.$optionvalue.'</option>';
							}
					}
					$html .=  '</select>';
                }
			break;
			case "radio":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
                                     if(trim($postValue) == trim($optionvalue)){
                                      $selected='checked="checked"';
                                     }
                                     else{
                                         $selected = "";
                                     }
                                    if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
						{
					if($i>0)
						$html .='&nbsp;';
					$html .='<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'  tabindex="'.$tabindex.'">';
						}
						$opt++;
				}
			break;
			case "checkbox":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
                                     $selected ="";
                                     foreach($postValue as $value){
                                       if(trim($value) == trim($optionvalue)){
                                           $selected='checked="checked"';
                                       }
                                       }
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
					if($i>0)
					$html .='&nbsp';
					$html.='<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'"  '.$selected.'  tabindex="'.$tabindex.'">';
					$i++;$opt++;
						}
				}
                        break;
            case "captcha":
                  $html ='<div class="captcha_image"><img src="'.$forms['captchaimagepath'].'" id="captchaimg" style="border:1px solid #000;"/></div>
                          <div class="captcha_input"><input type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value=""  class="absp_contact_input_text'.$validateclass.'"  tabindex="'.$tabindex.'" /></div>';
            break;

            case "file":
			   $html ='<input type="file" id="file_upload" name="file_upload"  tabindex="'.$tabindex.'" />';
			   $html.= '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_jobapplicant_input_text'.$validateclass.'"  />';
			break;

		}
		return $before.$html.$after;
}

/**
 * SubMit Button Type
 *
 * @param array $contactform
 * @return html field(form submit type)
 */

function awp_submit_type($forms='',$form_submitname='',$class='',$before='',$after='', $tabindex=''){
	if(strlen(trim($form_submitname)) != 0 ) :
	  $html ='<input type="hidden" name="'.$form_submitname.'"/>';
	endif;

      if($forms[submit_button_type] == "submit" ){
      	if(strlen(trim($forms[submit_button_val])) != 0)
      	{
      		$value = $forms[submit_button_val];
      	}else {
      		$value = 'Submit';
      	}
        $button_value = 'value="'.$value.'"';
      }
      else{
      	if(strlen(trim($forms[submit_button_val])) == 0)
      	{
      		$imgSrc = awp_image('submit_button');
      	}else {
      		$imgSrc = $forms[submit_button_val];
      	}
         $button_value = 'src="'.$imgSrc.'"';
      }

      $html .= '<input type="'.$forms[submit_button_type].'" class="'.$class.'" '.$button_value.' name="awp_contactform_submit_'.$forms[name].'"  id="awp_contactform_submit_'.$forms[name].'"  tabindex="'.$tabindex.'"/>';
      return $before.$html.$after;
}


/**
 * Enter description here...
 *
 * @param unknown_type $forms
 * @param unknown_type $field
 * @param unknown_type $countries
 * @param Bollean  $value_present
 * @param unknown_type $before
 * @param unknown_type $after
 * @return unknown
 */
function cases_textfield($forms='',$field='',$countries='',$value_present='',$before='',$after='',$placeholder=false, $tabindex='',$dafaultselect=false,$plugin='',$postValue='',$caseForm=''){

	echo html_entity_decode(esc_attr($before));
	$getConfig=get_option('awp_casesforms');
	$default_select='';
	$values='';
	for($i=0;$i<count($getConfig);$i++){
		if($getConfig[$i]['name']==$caseForm){
			$selectedCValues=$getConfig[$i]['cases_config'];
			$selectedCaseFormProperties = $getConfig[$i]['properties'];
		}
	}

	$fieldid=$field['fieldid'];
	if(isset($field['showtext'])){
		$showtext=$field['showtext'];
	}

	if(isset($field['validation'])){
		$validation=$field['validation'];
	}

	if( isset($field['required']) && $field['required'] !== '' ){
		$required=$field['required'];
	}else{
		$required='';
	}

	if(isset($field['type'])){
		$fieldtype=$field['type'];
	}

	$options=$field['options'];
	if(isset($field['value'])){
        	$values=$field['value'];
	}

	$optionvalues=array();
	$selectvalues=array();
    $place_text = '';
    if($postValue==""){
		$postValue = array();
		$postValue[$fieldid]="";
    }

	if($placeholder){
		$place_text = 'placeholder="'.$showtext.'"';
	}

	if(isset($fieldtype)){
		if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" || $fieldtype=="multiselect" ){
			if(!empty($options)){
				$optionvalues=explode("\n",trim($options));/*Split the String line by line.*/
			}
			$selectvalues=explode("\n",trim($values)); /*Split the String line by line. */
		}
	}

	//Required Class
	if( isset($required) && $field['required'] !== '' ){
		$mandate_property='"mandatory="true"';
		$validateclass=" required";
	}else{
		$mandate_property="";
		$validateclass="";
	}

	//Field Validation Class
	if(isset($validation)){
		switch($validation){
			case "email":
				$validateclass .=" email";
			break;
			case "url":
				$validateclass .=" url";
			break;
			case "number":
				$validateclass .=" number";
			break;
			case "phonenumber":
				$validateclass .=" phonenumber";
			break;
		}
	}

	//Captcha Class
	if($fieldid=='captcha'){
		$captcha_class = 'captcha';
	}else{
		$captcha_class = '';
	}

	if(isset($fieldtype)){
	switch($fieldtype){

			case "text":
				echo '<input  '.esc_attr($place_text).' type="text" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'_id" value="'.esc_attr($postValue[$fieldid]).'"  class="absp_'.esc_attr($plugin).'_input_text'.esc_attr($validateclass).'" tabindex="'.esc_attr($tabindex).'">';
			break;

			case "textarea":
				echo '<textarea  '.esc_attr($place_text).' name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'_id"   class="absp_'.esc_attr($plugin).'_textarea'.esc_attr($validateclass).'" size="50"  tabindex="'.esc_attr($tabindex).'">'.esc_attr($postValue[$fieldid]).'</textarea>';
			break;

			case "select":
				if(_isCurl()){
					if($fieldid=="priority" || $fieldid=="type" || $fieldid=="status"){
						if($fieldid=="priority"){
							$configValues= $selectedCValues["awp_casePriority_selected"];
							$input_text='<input type="hidden" id="priority_name" name="priority_name" value="'.htmlspecialchars($selectedCaseFormProperties['case_priority']).'" />';
						}elseif($fieldid=="type"){
							$input_text="";
							$configValues= $selectedCValues["awp_caseType_selected"];
							$input_text='<input type="hidden" id="type_name" name="type_name" value="'.htmlspecialchars($selectedCaseFormProperties['case_type']).'" />';
						}elseif($fieldid=="status"){    
							$input_text="";
							$configValues= $selectedCValues["awp_caseStatus_selected"];
							$input_text='<input type="hidden" id="status_name" name="status_name" value="'.htmlspecialchars($selectedCaseFormProperties['case_status']).'" />';
						}
						echo '<select name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_'.esc_attr($plugin).'_select'.esc_attr($validateclass).'"  tabindex="'.esc_attr($tabindex).'">';
						if($dafaultselect):
							echo apply_filters('apptivo_business_'.$plugin.'_'.$fieldid.'_default_option',$default_select);
						endif;
						$j=0;
						foreach( $optionvalues as $optionvalue ){
							if(trim($postValue[$fieldid]) == trim($optionvalue)){
								$selected='selected="selected"';
							}else{
								$selected='';
							}

							if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0){
								$attr_name='rel="'.htmlspecialchars($optionvalue).'"';
								if($postValue[$fieldid]==""){
									echo  '<option '.esc_attr(selected($configValues,$selectvalues[$j],false)).' value="'.esc_attr($selectvalues[$j]).'" '.esc_attr($selected).' '.html_entity_decode(esc_attr($attr_name)).'>'.trim(html_entity_decode(esc_attr($optionvalue))).'</option>';
								}else{
									echo  '<option '.esc_attr(selected($postValue[$fieldid],$selectvalues[$j],false)).' value="'.esc_attr($selectvalues[$j]).'" '.esc_attr($selected).' '.html_entity_decode(esc_attr($attr_name)).'>'.trim(html_entity_decode(esc_attr($optionvalue))).'</option>';
								}
								$j++;
							}
						}
						echo  '</select>';
						echo html_entity_decode(esc_attr($input_text));
					}else{
						echo '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_'.esc_attr($plugin).'_select'.esc_attr($validateclass).'"  tabindex="'.esc_attr($tabindex).'">';

						if($dafaultselect){
							$default_select = '<option value="" > -- Select One -- </option>';
							echo apply_filters('apptivo_business_'.$plugin.'_'.$fieldid.'_default_option',$default_select);
						}

						foreach( $optionvalues as $optionvalue ){
							if($postValue == $optionvalue){
								$selected='selected="selected"';
							}else{
								$selected='';
							}
                                        
							if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0){
								echo  '<option value="'.html_entity_decode(esc_attr($optionvalue)).'" '.esc_attr($selected).'>'.html_entity_decode(esc_attr($optionvalue)).'</option>';
							}
               			}
						echo '</select>';
					}
				}else{
					echo '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_'.esc_attr($plugin).'_select'.esc_attr($validateclass).'"  tabindex="'.esc_attr($tabindex).'">';

					if($dafaultselect):
					    $default_select = '<option value="" > -- Select -- </option>';
					    echo apply_filters('apptivo_business_'.$plugin.'_'.$fieldid.'_default_option',$default_select);
					endif;

					foreach( $optionvalues as $optionvalue ){
						if(trim($postValue) == trim($optionvalue)){
							$selected='selected="selected"';
						}else{
							$selected='';
						}

						if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0){
							echo  '<option value="'.html_entity_decode(esc_attr($optionvalue)).'" '.esc_attr($selected).'>'.html_entity_decode(esc_attr($optionvalue)).'</option>';
						}
					}
					echo  '</select>';
				}
			break;

			case "radio":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue ){
					if(trim($postValue[$fieldid]) == trim($optionvalue)){
						$selected='checked="checked"';
					}else{
						$selected = "";
					}
                    if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0){					
						echo '<div class="formsect"><input type="radio" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid.$opt).'" value="'.html_entity_decode(esc_attr($optionvalue)).'"  class="absp_'.esc_attr($plugin).'_input_radio '.esc_attr($validateclass).'" '.esc_attr($selected).'  tabindex="'.esc_attr($tabindex).'"><label for="'.esc_attr($fieldid.$opt).'">'.html_entity_decode(esc_attr($optionvalue)).'</label></div>';
					}
					$opt++;
				}
			break;

			case "checkbox":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue ){
					$selected ="";
					if(isset($postValue)!="" && count($postValue) >1){
						if(isset($postValue[$fieldid])){
							foreach($postValue[$fieldid] as $value){
								if(trim($value) == trim($optionvalue)){
									$selected='checked="checked"';
								}
							}
						}
					}
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0){
						echo '<div class="formsect"><input type="checkbox" name="'.esc_attr($fieldid).'[]" id="'.esc_attr($fieldid.$opt).'" value="'.html_entity_decode(esc_attr($optionvalue)).'"  class="absp_'.esc_attr($plugin).'_input_checkbox '.esc_attr($validateclass).'"  '.esc_attr($selected).'  tabindex="'.esc_attr($tabindex).'"> <label for="'.esc_attr($fieldid.$opt).'">'.html_entity_decode(esc_attr($optionvalue)).'</label></div>';
						$i++;
					}
					$opt++;
				}
			break;

			case "captcha":
				awp_captcha($fieldid,$postValue,$validateclass);
			break;

			case "file":
				echo '<input type="file" id="file_upload" name="file_upload"  tabindex="'.esc_attr($tabindex).'" />';
				echo  '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_'.esc_attr($plugin).'_input_text'.esc_attr($validateclass).'"  />';
			break;

		}
	}
	echo html_entity_decode(esc_attr($after));

}

function cases_submit_type($forms='',$form_submitname='',$class='',$before='',$after='', $tabindex='')
{		$html="";

      if($forms['submit_button_type'] == "submit" ){
      	if(strlen(trim($forms['submit_button_val'])) != 0)
      	{
      		$value = $forms['submit_button_val'];
      	}else {
      		$value = 'Submit';
      	}
        $button_value = 'value="'.$value.'"';
      }
      else{
      	if(strlen(trim($forms['submit_button_val'])) == 0)
      	{
      		$imgSrc = awp_image('submit_button');
      	}else {
      		$imgSrc = $forms['submit_button_val'];
      	}
         $button_value = 'src="'.$imgSrc.'"';
      }
      $html .= '<input type="'.$forms['submit_button_type'].'" class="'.$class.'" '.$button_value.' name="'.$form_submitname.'"  id="'.$form_submitname.'"   tabindex="'.$tabindex.'"/>';
      return $before.$html.$after;
}


/**
 * Create Label Fields
 *
 * @param unknown_type $showtext
 * @param unknown_type $customtext
 * @param unknown_type $class
 * @param unknown_type $before
 * @param unknown_type $after
 * @return unknown
 */
function awp_create_labelfield($showtext='',$customtext='',$class='',$before='',$after='',$fieldid='')
{
    if($showtext == '' || strlen(trim($showtext)) == 0) :
    	$showtext = $customtext;
    endif;
	return $before.'<span for="'.$fieldid.'" class="'.$class.'">'.$showtext.'</span>'.$after;

}

function awp_create_textfiled($type='',$fieldid='',$class='',$before='',$after='')
{
	switch(strtolower($type))
	{
		case "checkbox" :
		    	$html =  $before.'<input type="checkbox" class="'.$class.'" value="" id="'.$fieldid.'" name="'.$fieldid.'" >'.$after;
	    break;
	}
	return $html;

}

//Mandatory Field.
function awp_mandatoryfield($field='',$before='',$after='',$mandatory_symbol = '*')
{
	if(isset($field['required'])){
	$required=$field['required'];
	if($required):
	 return $before.$mandatory_symbol.$after;
	endif;
	}
}

//Powered By Apptivo.
function poweredby_apptivo()
{
	$apptivo_logo = '<a target="_blank" href="http://www.apptivo.com/">
	<img style="border: medium none;" alt="Apptivo.com is the best free way to run your business. Apptivo.com powers ecommerce websites, provides free CMS, free CRM, free ERP, free Project Management and free Invoicing to small businesses." title="Apptivo.com is the best free way to run your business. Apptivo.com powers ecommerce websites, provides free CMS, free CRM, free ERP, free Project Management and free Invoicing to small businesses." src="../images/apptivo.png">
	</a>';
	return $apptivo_logo;
}

/*
 * To Get Case Status, Case Type and Case Priority
 *
 */
function getAllCasesConfigData()
{
	$params = array (
		"a"         => "getCasesConfigData",
		"apiKey"    => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
		);
	$response=getRestAPICall("POST",APPTIVO_CASES_API,$params);
	return $response;
}

/*
 * To Get Case Status, Case Type and Case Priority V6 API
 *
 */
function getCasesConfigData()
{
	$params = array (
		"a"         => "getConfigData",
		"apiKey"    => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
		);
	$response=getRestAPICall("POST",APPTIVO_CASE_V6_API,$params);
	return $response;
}

/*
 * To Get Case Status, Case Type and Case Priority V6 API
 *
 */
function getAllEmployeesAndTeams()
{
	$params = array (
		"a"         => "getAllEmployeesAndTeams",
		"apiKey"    => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
		);
	$response=getRestAPICall("POST",APPTIVO_EMPLOYEES_V6_API,$params);

	return $response;
}

/*
 * To Get Case Auto Generation Number, Case Assignee and Employee
 *
 */
function getCaseConfigureData()
{
	$params = array (
		"a"         => "getCaseConfigureData",
		"apiKey"    => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
		);
	$response=getRestAPICall("POST",APPTIVO_CASES_API,$params);

	return $response;
}


/*
 * To Get Contact Status, Contact Type and Contact Priority
 *
 */
function getAllContactConfigData()
{
	$params = array (
		"a"         => "getLeadConfigData",
		"apiKey"    => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
		);
	$response=getRestAPICall("POST",APPTIVO_LEAD_API,$params);
	return $response;
}


/*
 * To Get Contact Status, Contact Type and Contact Priority - V6 API
 *
 */
function getContactConfigData()
{
	$params = array (
		"a"         => "getConfigData",
		"apiKey"    => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
		);
	$response=getRestAPICall("POST",APPTIVO_LEAD_V6_API,$params);
	//error_log(" getContactConfigData response :- ".json_encode($response));
	return $response;
}

/* Generate Captcha */
function awp_reCaptcha()
{
	if(get_option ('apptivo_business_recaptcha_mode')=="yes"){
		$option=get_option('apptivo_business_recaptcha_settings');
		$option=json_decode($option);
		if($option->recaptcha_publickey!= "" && $option->recaptcha_privatekey!=""){
			echo " <script type='text/javascript'>
					var RecaptchaOptions = {
						theme : '".esc_attr($option->recaptcha_theme)."',
						lang : '".esc_attr($option->recaptcha_language)."',
						};
				</script>
			<style type='text/css'>
			#recaptcha_image{width: 226px;}
			#recaptcha_area{width: 245px !important;}
			#recaptcha_widget_div{xzoom:0.75;x-moz-transform: scale(0.72);text-align: center;}
			#recaptcha_response_field{width:160px !important;height:32px;font-size:18px !important;}
			#recaptcha_image img{width:300px !important;}
			#recaptcha_privacy{display: none;}
			#recaptcha_widget_div label.error{display: none !important;}
			.recaptcha_source{width:40%;}
			.captcha_key_error{clear:both;text-align:center;width:58%;margin:0 auto;padding:0px;display:block;}
			span#error-message{color:#f00;width:100% !important;}
			</style>";
			require_once AWP_ASSETS_DIR.'/captcha/recaptchalib.php';
			echo "<div style='width:340px;' class='recaptcha_source'> <span for='captcha' generated='true' id='error-message' class='error'></span> <input type='hidden' name='recaptcha_token' id='recaptcha_token'>";
			?>
			<script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr($option->recaptcha_publickey); ?>" async defer></script>

			<script>
				(function(){
					// Check if grecaptcha object exists after the script is loaded
					setTimeout(function(){
						if (typeof grecaptcha === 'undefined' || typeof grecaptcha.execute !== 'function') {
							//document.getElementById('error-message').innerText = "reCAPTCHA script failed to load. Please enter a valid Google reCAPTCHA key or try again later.";
							document.getElementById('error-message').innerText = "Please enter a valid Google V3 reCAPTCHA key.";
						}else{
							document.getElementById('error-message').innerText = "";
							grecaptcha.ready(function(){
								grecaptcha.execute('<?php echo esc_attr($option->recaptcha_publickey); ?>', { action: 'submit' })
									.then(function(token){
										document.getElementById('recaptcha_token').value = token;
									}).catch(function(error){
										// Handle the error
										document.getElementById('error-message').innerText = "Error: " + error.message;
									});
							});
						}
					}, 1000); // Delay for checking if grecaptcha is available

				})();
			</script>
			<?php			
			//echo recaptcha_get_html($option->recaptcha_publickey);
			echo "</div>";
			//echo "<div class='awp_recaptcha_error'><label for='recaptcha_response_field' generated='true' class='error'></label></div>";
			return;
		}else{
			echo '<div style="width:340px;" class="captcha_image">Please Enable reCaptcha in Plugin Settings.</div>';
			return;
		}
	}elseif(get_option ('apptivo_business_recaptcha_mode')=="no"){
		return;
	}else{
		echo '<div style="width:340px;" class="captcha_image">Please Enable reCaptcha in Plugin Settings.</div>';
		return;
	}
}

/* Get Captcha Response */
function captchaValidation($privatekey,$response_field)
{
	require_once(AWP_ASSETS_DIR.'/captcha/recaptchalib.php');
	$resp = recaptcha_check_answers($privatekey, $_SERVER["REMOTE_ADDR"], $response_field);

	if(!$resp->is_valid){
		$response= $resp->error;
	}else{
		$response =$resp->is_valid;
	}
	return $response;
}

/* Check's Captcha if enabled or disabled  */
function checkCaptchaOption()
{
	//echo "<pre> apptivo_business_recaptcha_mode :- ".get_option ('apptivo_business_recaptcha_mode');echo "</pre>";
	if(get_option ('apptivo_business_recaptcha_mode')!="yes"){
		echo '<input type="hidden" id="awp_captcha_enable" value=""/>';
	}elseif(get_option ('apptivo_business_recaptcha_mode')=="yes"){
		echo '<input type="hidden" id="awp_captcha_enable" value="yes"/>';
	}
}

// Create Lead Source
function CreateContactFormLeads($sourceName,$leadSources)
{

	/* checking the leadsource from configData */
	$checkLead = "notpresent_in_app";
	$response= new stdClass();
	foreach($leadSources as $leadSource){
		if( trim(strtoupper($sourceName)) == trim(strtoupper($leadSource->name))){
			$checkLead = "present_in_app";
		}
	}
	
	if($checkLead=="present_in_app"){
		$leadSource = "present_in_app";
		return $leadSource;
	}

	if($checkLead=="notpresent_in_app" && $checkLead!=''){
		$details='{"name":"'.$sourceName.'","emailId":"","description":"'.$sourceName.'"}';
		$params = array(
			"a" =>"createLeadSource",
			"leadSource"=>$details,
			"apiKey"=> APPTIVO_BUSINESS_API_KEY,
			"accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
		);
		$respone=getRestAPICall("POST",APPTIVO_APPSETTINGS_V5_API,$params);
		
		//return $respone;
		return "present_in_app";
	}
}

function checkSoapextension($currentOption) {
if (!extension_loaded('soap')) {
echo '<div class="awp_updated" id="errormessage">
<p style="color:#f00;font-weight:bold;text-align:center;"> SOAP extension required to run Apptivo Business Site CRM  plugin- ' . esc_attr($currentOption) . '. </p>
</div>
<style type="text/css">
.awp_updated{background-color: #FFFFE0;border-color: #E6DB55;border-radius: 5px 5px 5px 5px;border-style: solid;border-width: 1px;line-height: 0.9em;}
</style>'
    ;
    exit;
    }
}


function getDomain($url){
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
        return $regs['domain'];
    }
    return FALSE;
}



function sendRegisterationEmailToUser($fname,$phoneNumber,$lname,$email){
	$adminEmail =  get_option('admin_email');
		
		//apptivo name change into there domain name subject 
		$protocol=strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$url =  $protocol .'://' . $_SERVER['SERVER_NAME'];
		$domainname = getDomain($url) ;
		$mailsubject = $domainname." "."Newsletter form signup";
        $to = $email;
        $protocol=strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$url =  $protocol .'://' . $_SERVER['SERVER_NAME'];            
$domain = getDomain($url);

        $msg = '<table bgcolor="#e9eaea" style="font-family:"Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif;font-weight:300;font-size:14px;border-spacing:0;width:100%;background:#e9eaea;margin:0;padding:0">
  <tbody>
    <tr style="font-weight:300;font-size:14px;margin:0;padding:0">
      <td width="600" valign="top" style="font-weight:300;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;margin:0 auto;padding:0">
        <div style="font-weight:300;font-size:14px;margin:50px 0;padding:0">
          <div style="font-weight:300;font-size:14px;display:block!important;max-width:600px!important;clear:both!important;margin:0 auto;padding:0">
            <table style="font-weight:300;font-size:14px;border-spacing:0;line-height:0;margin:0;padding:0">
              <tbody style="font-weight:300;font-size:14px;margin:0;padding:0">
                <tr style="font-weight:300;font-size:14px;margin:0;padding:0">
                  <td width="600" valign="top" align="center" style="font-weight:300;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;text-align:center;margin:0 auto;padding:0;background-color:#1fade4;"> <a target="_blank" style="font-weight:300;font-size:14px;text-decoration:none;margin:0;padding:0" href="https://www.apptivo.com"> </td>
                </tr>
              </tbody>
            </table>
            <table bgcolor="#ffffff" style="font-weight:300;font-size:14px;border-spacing:0;width:600px;border-radius:0 0 2px 2px;background:#ffffff;margin:0;padding:20px">
              <tbody style="font-weight:300;font-size:14px;margin:0;padding:0">
                <tr style="font-weight:300;font-size:14px;margin:0;padding:0">
                  <td valign="top" align="left" style="font-weight:300;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;text-align:left;margin:0 auto;padding:0">
                      <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0 0 20px;padding:0;font-weight: bold">Hi There,</p>
                    <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0 0 20px;padding:0">Newsletter subscribed sucessfully</p>
                    <p>&nbsp;</p>
                    <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0;padding:0;font-weight: bold">Thanks,</p>
                    <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0 0 20px;padding:0"><strong style="font-size:14px;margin:0;padding:0">'.$domain.'</strong></p>
                  </td>
                </tr>                
              </tbody>
            </table>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>';
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        		$headers .= 'From:  <'.$adminEmail . ">\r\n";
        $headers .='Reply-To:  no-reply@'.$domain. "\r\n" ;

         mail($to, $mailsubject, $msg, $headers);
    

}

function sendRegisterationEmailToAdmin($fname,$phoneNumber,$lname,$email){
$adminEmail =  get_option('admin_email');
$protocol=strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
$url =  $protocol .'://' . $_SERVER['SERVER_NAME'];            
$domain = getDomain($url);
$From = 'noreply@'.$domain;

$mailsubject = "Newsletter signup notification  ".$fname.' '.$lname;

        //$to = $adminEmail;
        $to = trim($adminEmail);


        
	$msg = '<table bgcolor="#e9eaea" style="font-family:"Helvetica Neue","Helvetica",Helvetica,Arial,sans-serif;font-weight:300;font-size:14px;border-spacing:0;width:100%;background:#e9eaea;margin:0;padding:0">
  <tbody>
    <tr style="font-weight:300;font-size:14px;margin:0;padding:0">
      <td width="600" valign="top" style="font-weight:300;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;margin:0 auto;padding:0">
        <div style="font-weight:300;font-size:14px;margin:50px 0;padding:0">
          <div style="font-weight:300;font-size:14px;display:block!important;max-width:600px!important;clear:both!important;margin:0 auto;padding:0">
            <table style="font-weight:300;font-size:14px;border-spacing:0;line-height:0;margin:0;padding:0">
              <tbody style="font-weight:300;font-size:14px;margin:0;padding:0">
                <tr style="font-weight:300;font-size:14px;margin:0;padding:0">
                  <td width="600" valign="top" align="center" style="font-weight:300;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;text-align:center;margin:0 auto;padding:0;background-color:#1fade4;"> <a target="_blank" style="font-weight:300;font-size:14px;text-decoration:none;margin:0;padding:0" href="https://www.apptivo.com"></a> </td>
                </tr>
              </tbody>
            </table>
            <table bgcolor="#ffffff" style="font-weight:300;font-size:14px;border-spacing:0;width:600px;border-radius:0 0 2px 2px;background:#ffffff;margin:0;padding:20px">
              <tbody style="font-weight:300;font-size:14px;margin:0;padding:0">
                <tr style="font-weight:300;font-size:14px;margin:0;padding:0">
                  <td valign="top" align="left" style="font-weight:300;font-size:14px;vertical-align:top;display:block!important;max-width:600px!important;clear:both!important;text-align:left;margin:0 auto;padding:0">
                      <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0 0 20px;padding:0;font-weight: bold">Hi There,</p>
                    <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0 0 20px;padding:0">'.$email.' is subscribed for Newsletter</p>
                    <p>&nbsp;</p>
                    <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0;padding:0;font-weight: bold">Thanks,</p>
                    <p style="font-weight:300;font-size:14px;line-height:1.6!important;margin:0 0 20px;padding:0"><strong style="font-size:14px;margin:0;padding:0">'.$domain.'</strong></p>
                  </td>
                </tr>                
              </tbody>
            </table>
          </div>
        </div>
      </td>
    </tr>
  </tbody>
</table>
';
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From:  <'.$adminEmail . ">\r\n";
        $headers .='Reply-To:  no-reply@'.$domain. "\r\n" ;
        mail($adminEmail, $mailsubject, $msg, $headers);
    

}


/**
 * Create TargetLists.
 *
 * @param unknown_type $category
 * @param unknown_type $fname
 * @param unknown_type $lname
 * @param unknown_type $email
 * @param unknown_type $userId
 * @return unknown
 */
function createTargetList($category,$fname,$lname,$email,$phoneNumber,$comments,$notesLabel,$userId=null)
{
   $target_category = target_lists_category(trim($category));
   if(trim($category) != $target_category )
   {
   	return $target_category;
   }

   $verification = check_blockip();
      
   if($verification){
   	 return $verification;
   }
   
   $targetParams='{"firstName":"'.$fname.'","phoneNumbers": [{"id": "contact_phone_input","phoneType": "Business","phoneTypeCode": "PHONE_BUSINESS","phoneNumber": "'.$phoneNumber.'"}],"lastName":"'.$lname.'","jobTitle":"","emailAddresses": [{"id": "cont_email_input","emailType": "Business","emailTypeCode": "BUSINESS","emailAddress": "'.$email.'"}]}';
   $params = array (
                "a" => "createTarget",
                "idxTarget" => $targetParams,
				"targetListId" => $category,
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
		);
		$response=getRestAPICall("POST", APPTIVO_TARGETS_V6_API,$params);
		if(isset($response) && $response->responseObject->id !=''){
        sendRegisterationEmailToAdmin($fname,$phoneNumber,$lname,$email);
        sendRegisterationEmailToUser($fname,$phoneNumber,$lname,$email);
			$targetId=$response->responseObject->id;
			if($comments !=''){
				$awpServiceObj=new AWPAPIServices();
				$objectId = APPTIVO_TARGET_OBJECT_ID;
				$objRefId = $targetId;
				$objectRefName = $response->responseObject->firstName .' '.$response->responseObject->lastName;
				$awpServiceObj->createTargetListNotes($comments,$notesLabel,$targetId,$objectId,$objRefId,$objectRefName);
				
			}
		}
        
    return $response;
    
}


function getAllTargetList(){
	$params = array (
                "a" => "getAllTargetLists",
                "startIndex" => 0,
				"numRecords" => 50,
				"sortColumn"=>'name',
				"sortDir"=>'asc',
				"iDisplayStart"=>0,
				"iDisplayLength"=>50,
				"sSortDir_0"=>'asc',
				"selectedLetter"=>'All',
				"type"=> 'by-directory',
				"typeId"=>'All',
				"objectId"=>APPTIVO_TARGET_OBJECT_ID,
				"apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
		);
		$response=getRestAPICall("POST", APPTIVO_TARGETS_V6_API,$params);
   		return $response;
		
}



/*
 *
 * To get Current Browser
 */

function get_current_browser()
{
if(isset($_SERVER['HTTP_USER_AGENT'])){
    $agent = $_SERVER['HTTP_USER_AGENT'];
}
if(strlen(strstr($agent,"Firefox")) > 0 ){
	$browser = 'firefox';
}
else if(strlen(strstr($agent,"Chrome")) > 0 ){

    $browser = 'chrome';

}
else
{
   $browser = 'other';
}
return $browser;
}

/* Simple Captcha Integration */

function awp_simple_captcha($fieldid,$postValue,$validateclass,$fg_color,$bg_color)
{
         $captcha_instance = new AWPSimpleCaptcha();
         list($r, $g, $b) = sscanf($fg_color, "#%02x%02x%02x");
         $captcha_instance->fg = array($r,$g,$b);
         list($bg_r, $bg_g, $bg_b) = sscanf($bg_color, "#%02x%02x%02x");
		 $captcha_instance->bg = array( $bg_r,$bg_g,$bg_b );
         $word = $captcha_instance->generate_random_word();
         $prefix = mt_rand();
         $image=$captcha_instance->generate_image($prefix, $word );
         $awp_upload_url=wp_upload_dir();
         $awp_url=path_join( $awp_upload_url['baseurl'], 'awp_captcha' );
	 echo '<input type="hidden" value="'.esc_attr($prefix).'" name="awp_simple_captcha_challenge"><img id="captcha_image" src="'.esc_attr($awp_url) ."/". esc_attr($image). '" alt=""/>&nbsp;&nbsp;&nbsp;<input type="text" name="simple_'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'_id" value="" class="absp_contact_input_text'.esc_attr($validateclass).'" size="5">';
        
}
/* CleanUp All unwanted Captcha Images */

add_action( 'template_redirect', 'awp_captcha_cleanup' );

function awp_captcha_cleanup(){
         $captcha_instance = new AWPSimpleCaptcha();
         $captcha_instance->cleanup(60);

}
function awp_captcha($fieldid, $postValue, $validateclass) {

    $option = get_option('apptivo_business_recaptcha_settings');
    $option = json_decode($option);
    $fg_color = $option->awp_fg_color;
    $bg_color = $option->awp_bg_color;
    if ($option->awp_captcha_type == 'recaptcha') {
		awp_reCaptcha();
    } elseif ($option->awp_captcha_type == 'simplecaptcha') {
        awp_simple_captcha($fieldid, $postValue, $validateclass, $fg_color, $bg_color);
    }
}

/*
 * Call occur when fatal error occurs in site.
 *
 */
register_shutdown_function( "absp_fatal_error_handler" );

function absp_fatal_error_handler() {
  $errfile = "unknown file";
  $errstr  = "shutdown";
  $errno   = E_CORE_ERROR;
  $errline = 0;

  $error = error_get_last();

  if( $error !== NULL) {
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];
  }
absp_show_error_log($errno, $errfile, $errline, $errstr);
}

function absp_show_error_log($errorno,$file,$line,$message)
{
	absp_currentError($message);
}

function absp_currentError($message)
{
	if(strpos($message, "SOAP-ERROR: Parsing WSDL: Couldn't load")!=false || strpos($message, "Maximum execution time of 30 seconds exceeded")!=false ||  strpos($message, "SoapClient::SoapClient()")!=false){
		echo awp_messagelist("fatal_error").'<style type="text/css"> .awp_updated{background-color: #FFFFE0;border-color: #E6DB55;border-radius: 5px 5px 5px 5px;border-style: solid;border-width: 1px;line-height: 0.9em;} </style>';
	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
		var p;
		p = jQuery(".awp_updated").detach();
		jQuery(".wrap").append(p);
	//	jQuery(".awp_updated").fadeOut(10000);
			});
		</script>';
	}
}

/*
 * Check the option and add or update option.
 */

function check_option($option_name,$option_value)
{

	$get= get_option($option_name);
	if($get=="")
	{
		add_option($option_name,$option_value);
	}
	else
	{
		update_option($option_name, $option_value);
	}
}

/*
 * Apptivo REST API CALL
 */
if (!function_exists('_isCurl')) {

    function _isCurl() {
        return function_exists('curl_version');
    }

}
// changed common call api for all rest call
if (!function_exists('getRestAPICall')) {

    function getRestAPICall($method, $url, $data = false) {

      /*  if ($method == "POST") {
            $args = array(
                'body' => $data,
                'timeout' => '50',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array()
            );
         
            
            $response = wp_remote_post( $url, $args );
    
          } else{
    
            $args = array(
                'headers' => array(
                    //'Authorization' => 'Basic ' . base64_encode( YOUR_USERNAME . ':' . YOUR_PASSWORD )
                )
            );
            
            $response = wp_remote_get( $url, $args );
          }
    
    
    
            $result = json_decode($response[body]);
		   */
        $result =   getRestAPICall1($method, $url, $data );
            return $result;
    }
}
?>
