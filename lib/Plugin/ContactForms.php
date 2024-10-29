<?php
/**
 * Apptivo Contact forms Plugin.
 * @package  apptivo-business-site
 * @author Rajkumar <rmohanasundaram[at]apptivo[dot]com>
 */
if(!defined('AWP_PLUGIN_BASEPATH')){ define('AWP_PLUGIN_BASEPATH',plugin_dir_path(__FILE__)); }
if(!defined('AWP_INC_DIR')){ define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc'); }
require_once AWP_INC_DIR . '/config.php';
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/define.php';
require_once AWP_INC_DIR . '/apptivo_services/labelDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/noteDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/LeadDetails.php';
require_once AWP_ASSETS_DIR.'/captcha/simple-captcha/simple-captcha.php'; 
require_once AWP_LIB_DIR.'/Plugin/AWPServices.php';
require_once AWP_APPTIVO_DIR.'ConfigDataUtil.php';
require_once AWP_APPTIVO_DIR.'DataRetrieval.php';
/**
 * Class AWP_ContactForms
 */

class AWP_ContactForms extends AWP_Base
{
	var $_plugin_activated = false;
	/**
	 * PHP5 constructor
	 */
	function __construct()
	{
		$settings=array();
		$this->_plugin_activated=false;
		$settings=get_option("awp_plugins");
		if(get_option("awp_plugins")!=="false"){
			if($settings["contactforms"])
			$this->_plugin_activated=true;
		}
	}

	/**
	 * Returns plugin instance
	 *
	 * @return AWP_ContactForms
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
	/**
	 * Runs plugin
	 */
	/*function run(){
		if($this->_plugin_activated){
			add_shortcode('apptivocontactform', array(&$this,'showcontactform'));
			add_action( 'contextual_help', array(&$this,'inlinedocument'), 10, 2 );
		}
	}*/

	function run(){
		if($this->_plugin_activated){
			add_shortcode('apptivocontactform', array(&$this,'showcontactform'));
		}
	}

	/*function inlinedocument( $text, $screen){
		$helpcontent = '';
		if( $screen == 'apptivo_page_awp_contactforms')
		{
			$helpcontent ='<p><strong>Activating Contact Form:</strong></p>
    	<ul><li>To activate Contact Form plugin, Check on Enable option for "Contact Forms" under Plugin settings in Apptivo General Settings.</li></ul>
    	<div><strong>Creating New Contact Form</strong>:</div>
    	<div><ul>
    	<li>Enter valid Contact Form name and click on Add New. ( This Form Name will be saved as Lead Source in collected lead in Apptivo Leads App</li></ul><div>
    	<strong>Configuring Contact Form</strong></div>
    	<div><ol>
    	<li>Contact Form can be configured using the section "Contact Form Configuration". ( This section will be shown automatically after first Contact Form is created )</li>
    	<li>Select your Contact Form from the "Contact Form" Drop down.</li><li>Select type of Template (Plugin or Theme) from the "Template Type" dropdown. Current version of plugin supports only Plugin Template.</li>
    	<li>Choose one of the available layouts for the form from "Template Layout" dropdown.</li>
    	<li>Content provided on field "Confirmation Message" will be shown in Site after user submitted this contact form.</li>
    	<li>Custom CSS provides option to override styles of Layout choosen in Step 4. Refer Custom CSS help section for Style details.</li>
    	<li>Option "Submit Button Type" provides option to select Button or Image to be placed for Contact Form submission.</li>
    	<li>If Option "Button" is selected, User can provide text to be displayed in "Submit Button Text" field.</li>
    	<li>If Option "image" is selected, User can provide the url of buttom image to be displayed in "Button Image URL" field.</li>
    	<li>Leads collected through Contact Form can be subscribed to Apptivo Target List. Apptivo Target List for this form can be selected using "Apptivo Target List" drop down. ( Target Lists created in Apptivo Target List app will be listed on this dropdown )</li>
    	<li>On selecting the "Apptivo Target List", Admin can decide whether user should be added to the Target List by default, or end user to choose subscribe using the option "Provide subscribe option to user?"</li><li>Totally 13 inbuilt fields and 5 custom fields can be added into your contact form using Contact Form Fields section.</li>
    	<li>13 inbuilt fields in Contact Form Fields section has options Show flag, Required flag, Display order (in the form) and Display Text to customize.</li><li>Among 13 inbuilt fields Last Name and Email are by default mandatory and it cant excluded from the form.</li><li>Custom fields can be of Field Type Check Box, Radio option, Select, Text box and Text area. Field values can be provided on Option Values.</li></ol>
    	<div><strong>Contact Form Shortcode:</strong></div><div><ol><li>Once Contact Form is created and configured, short code for the particular form will be displayed on "Contact Form Configuration" section.</li>
    	<li>Shortcode for the selected form can be copied from the field "Form Shortcode"</li><li>Paste the short code on the Page or Post.</li></ol><div><strong>Custom CSS:</strong></div><div><ul>
    	<li>Below are the list of CSS Style class names used on Contact Form. Below style class names can be defined in "Custom CSS" field in "Contact Form Configuration" to apply your styles.</li></ul><div>
    	<pre>Label 		:   absp_contact_label<br />mandatory       :   absp_contact_mandatory<br />input text      :   absp_contact_input_text<br />Select          :   absp_contact_select<br />textarea        :   absp_contact_textarea<br />select		:   absp_contact_select<br />input 657  :   absp_contact_input_checkbox<br />input multiselect  :   absp_contact_input_multiselect<br />input radio	:   absp_contact_input_radio<br />submit          :   absp_contact_button_submit</pre></div><strong></strong></div></div><strong></strong></div></div>';
		}
		if($helpcontent == '')
		{
			return $text;
		}else {
			return $helpcontent;
		}
	}*/
	function inlinedocument($text, $screen){
		$helpcontent = '';
		if($screen->id === 'apptivo_page_awp_contactforms') {
			$helpcontent ='<p><strong>Activating Contact Form:</strong></p>
    	<ul><li>To activate Contact Form plugin, Check on Enable option for "Contact Forms" under Plugin settings in Apptivo General Settings.</li></ul>
    	<div><strong>Creating New Contact Form</strong>:</div>
    	<div><ul>
    	<li>Enter valid Contact Form name and click on Add New. ( This Form Name will be saved as Lead Source in collected lead in Apptivo Leads App</li></ul><div>
    	<strong>Configuring Contact Form</strong></div>
    	<div><ol>
    	<li>Contact Form can be configured using the section "Contact Form Configuration". ( This section will be shown automatically after first Contact Form is created )</li>
    	<li>Select your Contact Form from the "Contact Form" Drop down.</li><li>Select type of Template (Plugin or Theme) from the "Template Type" dropdown. Current version of plugin supports only Plugin Template.</li>
    	<li>Choose one of the available layouts for the form from "Template Layout" dropdown.</li>
    	<li>Content provided on field "Confirmation Message" will be shown in Site after user submitted this contact form.</li>
    	<li>Custom CSS provides option to override styles of Layout choosen in Step 4. Refer Custom CSS help section for Style details.</li>
    	<li>Option "Submit Button Type" provides option to select Button or Image to be placed for Contact Form submission.</li>
    	<li>If Option "Button" is selected, User can provide text to be displayed in "Submit Button Text" field.</li>
    	<li>If Option "image" is selected, User can provide the url of buttom image to be displayed in "Button Image URL" field.</li>
    	<li>Leads collected through Contact Form can be subscribed to Apptivo Target List. Apptivo Target List for this form can be selected using "Apptivo Target List" drop down. ( Target Lists created in Apptivo Target List app will be listed on this dropdown )</li>
    	<li>On selecting the "Apptivo Target List", Admin can decide whether user should be added to the Target List by default, or end user to choose subscribe using the option "Provide subscribe option to user?"</li><li>Totally 13 inbuilt fields and 5 custom fields can be added into your contact form using Contact Form Fields section.</li>
    	<li>13 inbuilt fields in Contact Form Fields section has options Show flag, Required flag, Display order (in the form) and Display Text to customize.</li><li>Among 13 inbuilt fields Last Name and Email are by default mandatory and it cant excluded from the form.</li><li>Custom fields can be of Field Type Check Box, Radio option, Select, Text box and Text area. Field values can be provided on Option Values.</li></ol>
    	<div><strong>Contact Form Shortcode:</strong></div><div><ol><li>Once Contact Form is created and configured, short code for the particular form will be displayed on "Contact Form Configuration" section.</li>
    	<li>Shortcode for the selected form can be copied from the field "Form Shortcode"</li><li>Paste the short code on the Page or Post.</li></ol><div><strong>Custom CSS:</strong></div><div><ul>
    	<li>Below are the list of CSS Style class names used on Contact Form. Below style class names can be defined in "Custom CSS" field in "Contact Form Configuration" to apply your styles.</li></ul><div>
    	<pre>Label 		:   absp_contact_label<br />mandatory       :   absp_contact_mandatory<br />input text      :   absp_contact_input_text<br />Select          :   absp_contact_select<br />textarea        :   absp_contact_textarea<br />select		:   absp_contact_select<br />input 657  :   absp_contact_input_checkbox<br />input multiselect  :   absp_contact_input_multiselect<br />input radio	:   absp_contact_input_radio<br />submit          :   absp_contact_button_submit</pre></div><strong></strong></div></div><strong></strong></div></div>';
		}
	
		if($helpcontent == '') {
			return $text;
		} else {
			return $helpcontent;
		}
	}

	/**
	 * Contact Form shortcode handler
	 */
	function showcontactform($atts){
		extract(shortcode_atts(array('name'=>  ''), $atts));
		ob_start();
		$formname=trim($name);
		$content="";
		$successmsg="";
		if(isset($_POST['awp_contactformname'])){
			$submitformname=sanitize_text_field($_POST['awp_contactformname']);
		}
		$value_present = false;
		if(isset($_POST['awp_contactform_value']) && !empty($_POST['awp_contactform_value'])){
			die("No bot is allowed to submit.");
			return false;
		}

		if(isset($_POST['awp_contactform_submit']) && isset($_POST['awp_contactform_value']) && empty($_POST['awp_contactform_value'])){
			$awp_contactform_submit = sanitize_text_field($_POST['awp_contactform_submit']);
			if(isset($submitformname) && $submitformname==$formname ){
				if(isset($_POST["recaptcha_token"]) && empty($_POST["recaptcha_token"])){
					$value_present = true;
					$captch_error = awp_messagelist("v3recaptcha_error");
				}
				if(isset($_POST["simple_captcha"]) && !empty($_POST["simple_captcha"]) ){
					$awp_simple_captcha = sanitize_text_field($_POST['awp_simple_captcha_challenge']);
					if(isset($awp_simple_captcha) && !empty($awp_simple_captcha)){
						$captcha_instance = new AWPSimpleCaptcha();
						$response = $captcha_instance->check($awp_simple_captcha, sanitize_text_field($_POST["simple_captcha"]));
						$captcha_instance->remove( $awp_simple_captcha );

						if($response != 1){
							$value_present = true;
							$captch_error = awp_messagelist("recaptcha_error");
						}else{
							$successmsg=$this->save_contact($submitformname);
						}
					}else{
						$captcha_instance = new ReallySimpleCaptcha();
						$captcha_instance->remove( $awp_simple_captcha );
						$captch_error = awp_messagelist("recaptcha_error");
					}
				}else if(isset($_POST["recaptcha_token"]) && !empty($_POST["recaptcha_token"])){
						//$response_field =   sanitize_text_field($_POST["g-recaptcha-response"]);
						$response_field =   sanitize_text_field($_POST["recaptcha_token"]);
						$option=get_option('apptivo_business_recaptcha_settings');
						$option=json_decode($option);
						$private_key = $option->recaptcha_privatekey;
						$response = captchaValidation($private_key,  $response_field);

						if($response != 1){
							$value_present = true;
							$captch_error = awp_messagelist("v3recaptcha_error");
						}else{
							$successmsg=$this->save_contact($submitformname);
						}
				}else{
					// Incase of no recaptcha is set, we can collect the data
					if(get_option ('apptivo_business_recaptcha_mode')=="no"){
						$successmsg=$this->save_contact($submitformname);
					}
				}
			}
		}

		$contactform=$this->get_contactform_fields($formname);
		if(strlen(trim($successmsg)) != 0 && $contactform['confmsg_pagemode'] == 'other' ) :
			$location = get_permalink($contactform['confmsg_pageid']);
			$verification = check_blockip();
			if($verification){
				echo awp_messagelist('IP_banned');
				return;
			}
			wp_safe_redirect($location);
		endif;
		if(isset($contactform['fields'])){
			foreach($contactform['fields'] as $field){
				if(is_array($field)){
					if($field['fieldid']=="country"){
						$countrylist = $this->getAllCountryList();
						break;
					}
				}
			}
		}
	
		/* Get Contact From Width Size  */
		$contact_width_size=$contactform['contact_width_type'];

		if((isset($contactform) && !empty($contactform)) && (isset($contactform['fields']) && !empty($contactform['fields']))){
			//Registering Validation Scripts.
			//$this->loadscripts();
                        add_action('wp_footer', 'abwpExternalScripts');
			include $contactform['templatefile'];
		}else {
			echo awp_messagelist('contactform-display-page');
		}
		return ob_get_clean();
	}

	/**
	 * Save contact from submitted
	 */
	function save_contact($formname,$ajaxform=false){
		$customfieldArr = array();
		$confmsg = '';
		if($ajaxform)
		{
			if(isset($_POST['captcha']) && !empty($_POST['captcha'])){
				if(trim(sanitize_text_field($_POST['captcha'])) != $_SESSION['apptivo_business_captcha_code'])
				{
					$captch_error = 'Please enter correct Verification code';
					return $captch_error;
				}
			}
		}

		$contactform=$this->get_contactform_fields($formname);
		if(isset($contactform) && !empty($contactform)){
			
			$validatephp = 0;
		    $validatephpreq = 0 ;
		    $validatephpnumber = 0 ;
			foreach ( $_POST as $key => $value )
            {

                 foreach($contactform['fields'] as $contactformfields)
				{
					
					if($contactformfields['fieldid'] == $key )
					{
						if($contactformfields['required'] != '' || $contactformfields['required'] == 1 || $contactformfields['required'] == 'on')
						{
							$nn = $contactformfields['fieldid'];
							if(is_array($_POST[$nn]))
							{
								/* Santize for Array Values */
								$keys = array_keys($_POST[$nn]);
								$keys = array_map('sanitize_key', $keys);
								$values = array_values($_POST[$nn]);
								$values = array_map('sanitize_text_field', $values);
								$contactformArray = array_combine($keys, $values);
								/* Santize for Array Values */
							}
							else{
								$contactformArray = sanitize_text_field($_POST[$nn]);

							}
 							if($contactformArray == ''){
								echo  '<div id="error_'.esc_attr($contactformfields['fieldid']).'" style="color:red" class="absp_error error_'.esc_attr($contactformfields['fieldid']).'">'.esc_attr(ucfirst($contactformfields['fieldid']))." is required </div>";
								$validatephpreq = 1 ;
							}
						}
					}
				}
            }
			if((isset($_POST['telephonenumber1']) && !empty($_POST['telephonenumber1'])) && (isset($_POST['telephonenumber2']) && !empty($_POST['telephonenumber2'])) && (isset($_POST['telephonenumber3']) && !empty($_POST['telephonenumber3']))){
				if($validatephpreq == 0)
				{
					if(strlen((string) sanitize_text_field($_POST['telephonenumber1'])) !=3  || strlen((string) sanitize_text_field($_POST['telephonenumber2'])) !=3 || strlen((string) sanitize_text_field($_POST['telephonenumber3']))!=4 ){
					
						echo  '<div id="error_'.esc_attr($contactformfields['fieldid']).'" style="color:red" class="error absp_error error_'.esc_attr($contactformfields['fieldid']).'"> Phone Number is not valid </div>';
						$validatephp = 1 ;

					}
				}
				if($validatephpreq == 0 && $validatephp == 0){
					if(!is_numeric( sanitize_text_field($_POST['telephonenumber1']))   || !is_numeric( sanitize_text_field($_POST['telephonenumber2'])) || !is_numeric( sanitize_text_field($_POST['telephonenumber3']))){
					
						echo  '<div id="error_'.esc_attr($contactformfields['fieldid']).'" style="color:red" class="error absp_error error_'.esc_attr($contactformfields['fieldid']).'"> Phone Number is not valid </div>';
						$validatephpnumber = 1 ;

					}
				}
			}
			if(($validatephp == 0) && ($validatephpreq == 0) && ($validatephpnumber == 0)){
				$contactformfields=$contactform['fields'];
				$submittedformvalues=array();
				$submittedformvalues['name']=$contactform['name'];
				if(isset($_POST['subscribe']) && !empty($_POST['subscribe'])){
					$submittedformvalues['targetlist']=$contactform['targetlist'];
				}
				else{
					if($contactform['subscribe_option']=='no'){
						$submittedformvalues['targetlist']=$contactform['targetlist'];
					}
				}
				$customfields="";

				foreach($contactformfields as $field)
				{
					$fieldid=  isset($field['fieldid']) ? $field['fieldid'] : '';
					$pos=strpos($fieldid, "customfield");
					if($pos===false){
						if($fieldid=='telephonenumber'){
						$telephone1= isset($_POST['telephonenumber1'])?sanitize_text_field($_POST['telephonenumber1']):'';
						
							if(isset($telephone1) && !empty($telephone1)){
								
								$submittedformvalues[$fieldid]= sanitize_text_field(isValueSet($_POST['telephonenumber1'])).sanitize_text_field(isValueSet($_POST['telephonenumber2'])).sanitize_text_field(isValueSet($_POST['telephonenumber3']));
							}
							else if(isset($_POST['telephonenumber_string']))
							{
								$submittedformvalues[$fieldid]= isset($_POST['telephonenumber_string'])?sanitize_text_field($_POST['telephonenumber_string']):'';
							}
							else{

								$submittedformvalues[$fieldid]= isset($_POST[$fieldid])?sanitize_text_field($_POST[$fieldid]):'';

							}
						}
						else{
							$submittedformvalues[$fieldid]= isset($_POST[$fieldid])?stripslashes(sanitize_text_field($_POST[$fieldid])):'';

						}
					}else{
						if(trim($customfields)!="")
						{
							if(is_array($_POST[$fieldid]))
							{
								$keys = array_keys($_POST[$fieldid]);
								$keys = array_map('sanitize_key', $keys);

								$values = array_values($_POST[$fieldid]);
								$values = array_map('sanitize_text_field', $values);

								$CustomArr = array_combine($keys, $values);

								$customfieldVal= "";
								for($i=0; $i<count($CustomArr); $i++)
								{
									$customfieldVal .= ($i==(count($CustomArr)-1))?$CustomArr[$i]:$CustomArr[$i].", ";
								}

							}else if(!is_array($_POST[$fieldid])) {
								$customfieldVal = sanitize_text_field($_POST[$fieldid]);
							}
							if($customfieldVal != '') {
							$formLabelName = $field['showtext'];
							$customfieldArr[$formLabelName] = stripslashes($customfieldVal);
							$customfields.="<b>".$field['showtext']."</b>:&nbsp;".stripslashes($customfieldVal)."<br/>";
							}
						}
						else
						{
							if(is_array($_POST[$fieldid]))
							{
								$keys = array_keys($_POST[$fieldid]);
								$keys = array_map('sanitize_key', $keys);

								$values = array_values($_POST[$fieldid]);
								$values = array_map('sanitize_text_field', $values);

								$CustomArr = array_combine($keys, $values);

								$customfieldVal= "";
								for($i=0; $i<count($CustomArr); $i++)
								{
									$customfieldVal .= ($i==(count($CustomArr)-1))?$CustomArr[$i]:$CustomArr[$i].", ";
								}

							}else if(!is_array($_POST[$fieldid])) {
								$customfieldVal = sanitize_text_field($_POST[$fieldid]);
							}
							if($customfieldVal != '') {
							$formLabelName = $field['showtext'];
							$customfieldArr[$formLabelName] = stripslashes($customfieldVal);
							$customfields .= "<b>".$field['showtext']."</b>:".stripslashes($customfieldVal)."<br/>";
							}
						}
					}
				}
				
				
				$_SESSION['AddradioMismatchValues'] = '';
				$_SESSION['AddselectMismatchValues'] = '';
				$_SESSION['AddcheckMismatchValues'] = '';
			
				
				/* Custom Field mapping with Apptivo Leads Config data */
				$apptivoArr = array();
				$unmatchFields = array();
				$configObj = new configData();
				$customAttributes = array();
				$sections = $configObj->appConfigData(APPTIVO_LEAD_V6_API,APPTIVO_LEAD_OBJECT_ID);
				foreach($sections as $section){
					$sectionName = $section->getSecLabel();
					$attributes = $section->getAttributeList();
					foreach($attributes as $attribute){
						$labelName = $attribute->getAttributeLabel();
						$fieldType = $attribute->getAttributeType();
							if($fieldType == 'Custom'){	
								if(array_key_exists($labelName, $customfieldArr)){
									$apptivoArr[] = $labelName;
										$dataRetriObj = new DataRetrieval();
										$cust = $dataRetriObj->customAttribute($customAttributes,$sectionName,$labelName,$attribute, $customfieldArr);
										if(!empty($cust)){
										$customAttributes[] = $cust;
										}
								}
							}
					
					
					}
				}
				
							
				/* Creating note text for unmatched fields */
				$customfields = '';
				if(isset($customfieldArr) && $customfieldArr != ""){
					foreach($customfieldArr as $customLabelName => $customValue){
						if(!in_array($customLabelName, $apptivoArr)){
								$customfields .= "<b>".$customLabelName."</b>:".stripslashes($customValue)."<br/>";
						}
					}
				}
				
				$AddcheckMismatchValues = '';
				/*copying selected mismatched values to notes tab*/
				if(isset($_SESSION['AddcheckMismatchValues']) && $_SESSION['AddcheckMismatchValues'] != ""){
					foreach($_SESSION['AddcheckMismatchValues'] as $checkLablename => $checkval){
						foreach($checkval as $values){
						$AddcheckMismatchValues .= "<b>".$checkLablename."</b>:".trim($values)."<br/>";
						}
					}
				}
			
					
				$customfields .= "<br/><b>Requested IP</b>:".stripslashes(get_RealIpAddr());
				
				$customfields = $_SESSION['AddradioMismatchValues'].'<br>'.$_SESSION['AddselectMismatchValues'].'<br>'.$AddcheckMismatchValues.'<br>'.$customfields;
				
				$_SESSION['AddradioMismatchValues'] = '';
				$_SESSION['AddselectMismatchValues'] = '';
				$_SESSION['AddcheckMismatchValues'] = '';
				if(trim($customfields)!="")
				{
					$submittedformvalues["notes"]=$customfields;
				}
				$firstName="";$jobTitle="";$company="";$address1="";$address2="";$city="";$state="";$zipCode="";$bestWayToContact="";$phoneNumber="";$comments="";$targetname="";
				if(isset($submittedformvalues['firstname'])){
								$firstName = sanitize_text_field($submittedformvalues['firstname']);
				}
				if(isset($submittedformvalues['lastname'])){
								$lastName = sanitize_text_field($submittedformvalues['lastname']);
				}
				if(isset($submittedformvalues['email'])){
								$emailId = sanitize_email($submittedformvalues['email']);
				}
				if(isset($submittedformvalues['jobtitle'])){
								$jobTitle = sanitize_text_field($submittedformvalues['jobtitle']);
				}
				if(isset($submittedformvalues['company'])){
				$company =  sanitize_text_field($submittedformvalues['company']);
				}
				if(isset($submittedformvalues['address1'])){
								$address1 = sanitize_text_field($submittedformvalues['address1']);
				}
				if(isset($submittedformvalues['address2'])){
								$address2 = sanitize_text_field($submittedformvalues['address2']);
				}
				if(isset($submittedformvalues['city'])){
								$city = sanitize_text_field($submittedformvalues['city']);
				}
				if(isset($submittedformvalues['state'])){
								$state = sanitize_text_field($submittedformvalues['state']);
				}
				if(isset($submittedformvalues['zipcode'])){
								$zipCode = sanitize_text_field($submittedformvalues['zipcode']);
				}
				if(isset($submittedformvalues['simple_captcha'])){
								$simple_captcha=sanitize_text_field($submittedformvalues['simple_captcha']);
				}
				if(isset($submittedformvalues['bestway'])){
								$bestWayToContact = sanitize_text_field($submittedformvalues['bestway']);
				}
				if(isset($submittedformvalues['country'])){
								$country = sanitize_text_field($submittedformvalues['country']);
				}
				if(isset($submittedformvalues['name'])){
								$leadSource = sanitize_text_field($submittedformvalues['name']);
				}
				if(isset($submittedformvalues['telephonenumber'])){
								$phoneNumber = sanitize_text_field($submittedformvalues['telephonenumber']);
				}
				if(isset($submittedformvalues['comments'])){
								$comments = sanitize_text_field($submittedformvalues['comments']);
				}
				if(isset($submittedformvalues['notes'])){
				$noteDetails = $submittedformvalues['notes'];
				}
				$targetlistid="";
				if(isset($submittedformvalues['targetlist'])){
				$targetlistid = sanitize_text_field($submittedformvalues['targetlist']);
				}
				
		
				
				if((isset($_POST['state']) && !empty($_POST['state'])) && (isset($_POST['statename']) && !empty($_POST['statename']))){
					
					$stateCode=sanitize_text_field($_POST['statecode']);
					$stateName=sanitize_text_field($_POST['statename']);
					
				}else{
					$stateCode ="";
					$stateName ="";
				}
				
				/* Get Country Id & Country Name*/
				if((isset($_POST['country_id']) && !empty($_POST['country_id'])) && (isset($_POST['country_name']) && !empty($_POST['country_name']))){
					$countryId=sanitize_text_field($_POST['country_id']);
					$countryName=sanitize_text_field($_POST['country_name']);				
					$countrylist = $this->getAllCountryList();			
					foreach($countrylist as $country)
					{					
						if($country ->countryName == $countryName)
						{
							$countryCode = $country->countryCode;
						}					
					}
				}else{		
					
					$firmdetailsbussiness = getfirmdetails();
					if(!empty($firmdetailsbussiness)) {
						$countryfirmset = $firmdetailsbussiness->appComCountry;
						$countryId=    $countryfirmset->id;
						$countryName=$countryfirmset->countryName;
						$countryCode = $countryfirmset->countryCode;
					} else {
						$countryId="176";
						$countryName= "United States";
						$countryCode = "US";
					}
				}
				
				if(!empty($noteDetails)){
					$parent1NoteId="";
					$parent1details = nl2br($noteDetails);
					$awp_services_obj=new AWPAPIServices();
					$noteDetails = $awp_services_obj->notes('Custom Fields',$parent1details,$parent1NoteId);

					$contactformdetails=$this->get_settings($leadSource);
					$formproperties=$contactformdetails['properties'];
					
					if(isset($targetlistid) && $targetlistid!=''){
							$getTargetName=$awp_services_obj->getTargetListcategory();
						if($getTargetName!="")
						{
							foreach($getTargetName as $category){
								if($category->id==$formproperties['targetlist'])
								{
									$category->name = (property_exists($category,'name')) ? $category->name : '';
									$targetname=$category->name;
								}
							}
						}
					}
				}

				$contactAccountName="";
				$customerAccountName="";
				
				if(!_isCurl())
				{
					$targetname=$targetlistid;
				}
				if(strlen(trim($firstName)) == 0 ) :
				$firstName = null;
				endif;
				if(strlen(trim($lastName))==0 || strlen(trim($emailId))==0 || !filter_var($emailId, FILTER_VALIDATE_EMAIL)){
					echo awp_messagelist('no_redirection');
				}else{

					/* check manually and update the values */

					$form_name = sanitize_text_field($_POST['awp_contactformname']);
					$contact_formvalues=$this->get_contactform_fields($form_name);
					
					if(!empty($_POST['status_name']) || !empty($_POST['status_id']) || !empty($_POST['rank_id']) || !empty($_POST['rank_id']) || !empty($_POST['type_name']) || !empty($_POST['type_id']) || !empty($_POST['label_name']) || !empty($_POST['label_id'])){

						$contact_status=sanitize_text_field($_POST['status_name']);
						$contact_status_id=sanitize_text_field($_POST['status_id']);
						$contact_address=isset($_POST['contact_address'])?sanitize_text_field($_POST['contact_address']):'';
						$contact_address_id=isset($_POST['contact_address_id'])?sanitize_text_field($_POST['contact_address_id']):'';
						$contact_type=sanitize_text_field($_POST['type_name']);
						$contact_type_id=sanitize_text_field($_POST['type_id']);
						$contact_rank=sanitize_text_field($_POST['rank_name']);
						$contact_rank_id=sanitize_text_field($_POST['rank_id']);
						$contact_label_id=sanitize_text_field($_POST['label_id']);
						$contact_label_name=sanitize_text_field($_POST['label_name']);
					}
					if($contact_status=="" && $contact_status_id==""){
						$contact_status=$contact_formvalues['contact_status'];
						$contact_status_id=$contact_formvalues['contact_status_id'];
			
					}
					if($contact_type=="" && $contact_type_id==""){
						$contact_type=$contact_formvalues['contact_type'];
						$contact_type_id=$contact_formvalues['contact_type_id'];
					}
					if($contact_rank=="" && $contact_rank_id==""){
						$contact_rank=$contact_formvalues['contact_rank'];
						$contact_rank_id=$contact_formvalues['contact_rank_id'];
					}
					/* Get Lead Source,Type,Status */
					
					$contact_source=$contact_formvalues['contact_source'];
					$contact_source_id=$contact_formvalues['contact_source_id'];

					if($contact_label_id=="" && $contact_label_name==""){
						$contact_label_id=$contact_formvalues['contact_label_id'];
						$contact_label_name=$contact_formvalues['contact_label_name'];
			
					}
					
					/* Check Wheather leadSource Selected or not */
					if($contact_source=="Select One" || $contact_source=="" && $contact_source_id==0){
						$leadSource=$leadSource;$leadSourceId=strtoupper($leadSource);
					}else{
						$leadSource=$contact_source;$leadSourceId=$contact_source_id;
					}

					$contact_address=$contact_formvalues['contact_address'];
					$contact_address_id=$contact_formvalues['contact_address_id'];
					
					/* Check Wheather leadSource Selected or not */
					if($contact_address=="Select One" || $contact_address=="" && $contact_address_id==0){
						$address=$address;$addressId=strtoupper($addressId);
					}else{
						$address=$contact_address;$addressId=$contact_address_id;
					}

					$assigneeName		=   trim($contact_formvalues["contact_assignee_name"]);
					$assigneeObjId		=	($contact_formvalues["contact_assignee_type"] == 'team') ? APPTIVO_TEAM_OBJECT_ID : APPTIVO_EMPLOYEE_OBJECT_ID;
					$assigneeObjRefId	=	$contact_formvalues["contact_assignee_type_id"];
					$contactAssociates 	=  	trim($contact_formvalues["contact_associates"]);
					$createAssociates 	= 	trim($contact_formvalues["contact_create_associates"]);
				
					/* Check wheather Team exist or not */
					if($assigneeName=='No Team'){
							$assigneeName= $assigneeObjId = $assigneeObjRefId = "";
					}
					$associates=[];
					if($contactAssociates != "No Need")
					{ 
							$associates=$awp_services_obj->awpContactAssociates($emailId, $contactAssociates);
					}
					$customerAccountId = $customerAccountName = "";
					if(count($associates) !="")
					{
						if(isset($associates["leadCustomerId"])){
						$customerAccountId	=	$associates["leadCustomerId"];
						}
						if(isset($associates["leadCustomer"])){
						$customerAccountName=	$associates["leadCustomer"];
						}
					}
					
					if($customerAccountId == "" && $createAssociates == "customer" && $contactAssociates!="No Need"){
						
						if( ($addressId != '' || isset($addressId)) ){
							$address1 = ($address1 != '' && isset($address1))? $address1 : '';
							$address2 = ($address2 != '' && isset($address2))? $address2 : '';
							$city = ($city != '' && isset($city))? $city : '';
							$state = ($state != '' && isset($state))? $state : '';
							$zipCode = ($zipCode != '' && isset($zipCode))? $zipCode : '';
							$address = ($address != '' && isset($address))? $address : '';
							$countryId = ($countryId != '' && isset($countryId))? $countryId : '';
							$countryName = ($countryName != '' && isset($countryName))? $countryName : '';
							$countryCode = ($countryCode != '' && isset($countryCode))? $countryCode : '';

							$createCustomerResponse=$awp_services_obj->createLeadCustomer( $lastName, $assigneeName, $assigneeObjId, $assigneeObjRefId, $phoneNumber, $emailId, $address1, $address2, $city, $state, $zipCode, $address, $addressId, $countryId, $countryName, $countryCode );
						}else{
							$createCustomerResponse=$awp_services_obj->createCustomer( $lastName, $assigneeName, $assigneeObjId,$assigneeObjRefId, $phoneNumber, $emailId );
						}					
						$customerAccountId=$createCustomerResponse['leadCustomerId'];
						$customerAccountName=$createCustomerResponse['leadCustomer'];
					}
					
					$verification = check_blockip();
					if($verification){
						if($verification == 'E_IP'){
							echo awp_messagelist('IP_banned');
						}
						return;
					}
					$leadSource=strtoupper($leadSource);
					$saveLeadresponse = $awp_services_obj->saveLeadDetails($firstName , $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact,$address,$addressId, $countryId,$countryName,$countryCode, $leadSource, $leadSourceId,$phoneNumber, $comments, $noteDetails,$targetname, $customerAccountId, $customerAccountName, $contact_status, $contact_type, $contact_rank, $contact_status_id, $contact_type_id, $contact_rank_id, $contact_label_id, $assigneeName,$assigneeObjId, $assigneeObjRefId, $customAttributes);
					
					$leadId= (isset($saveLeadresponse->lead->leadId)) ? $saveLeadresponse->lead->leadId : '';
					$leadfullName = (isset($saveLeadresponse->lead->fullName)) ? $saveLeadresponse->lead->fullName : '';
					$alreadyExists = (isset($saveLeadresponse->result)) ? $saveLeadresponse->result : "";
						if($noteDetails!="" && $leadId!=""){
							$noteText=$noteDetails->noteText;
							$saveNotesResponse=$awp_services_obj->saveNotes(APPTIVO_LEAD_OBJECT_ID,$leadId,$leadfullName,$noteText);
						}
						if($leadId!=""){
							$response='Success';
							if (extension_loaded('soap') && $targetlistid!="") {
								$notesLabel = (!empty($notesLabel)) ? $notesLabel : '';
								createTargetList($targetlistid, addslashes($firstName), addslashes($lastName), addslashes($emailId), addslashes($phoneNumber), addslashes($comments),$notesLabel);
							}
						}elseif ($alreadyExists == "Already exists"){
							$response = "Already exists";
						}
						else{
							$response='E_100';
						}
					if(_isCurl()){
						
					}else{
						/*$leads = new AWP_LeadDetails(APPTIVO_BUSINESS_API_KEY,$firstName, $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $country, $leadSource, $phoneNumber, $comments, $noteDetails,$targetlistid);
						$params = array (
												"arg0" => APPTIVO_BUSINESS_API_KEY,
												"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
												"arg2" => $leads
						);
						$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createLeadWithLeadSource',$params);
						*/
						//error_log("line 586 ContactForms.php", 3, "curlenable.log");
						//error_log("curl not enabled please enable curl", 3, "curl_enable.log");
						$response= $response->return->statusMessage;
					}
					
					$response_msg = $response;
					if($response_msg=='Success' && $response != 'E_100'){
						if(!empty($contactform['confmsg'])){
							$confmsg = $contactform['confmsg'];
						}
						else{
							$confmsg="Your request has been submitted. Thanks for contacting us.";
						}
					}elseif ($response == "Already exists") {
						echo  '<div  style="color:red" class="absp_error email_exists">This Email already exists. </div>';
					}
					else if($response == 'E_IP'){ echo awp_messagelist('IP_banned'); }
					else{ echo awp_messagelist('contactlead-display-page'); }
				}
			}
		}
		return $confmsg;
	}

	/**
	 * Get contactform and its fields to render in page which is using shortcode
	 */
	function get_contactform_fields($formname){
            
		$formExists="";
		$contact_forms=array();
		$contactform=array();
		$contactformdetails=array();
		$formname=trim($formname);

		$contact_forms=get_option('awp_contactforms');

		if($formname=="")
		$formExists="";
		else if(!empty($contact_forms))
		$formExists = awp_recursive_array_search($contact_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$contactform=$contact_forms[$formExists];
			//echo "<pre> contactform:- ";print_r($contactform);echo "</pre>";

			//build contactformdetails array
			$contactformdetails['name']=$contactform['name'];
			//echo "<pre> contactformdetails:- ";print_r($contactformdetails);echo "</pre>";

			//add properties
			$contactformproperties=$contactform['properties'];
			//echo "<pre> contactformproperties:- ";print_r($contactformproperties);echo "</pre>";

			//add details
			$contactformdetails['tmpltype']=$contactformproperties['tmpltype'];
			$contactformdetails['layout']=$contactformproperties['layout'];
			$contactformdetails['confmsg']= stripslashes($contactformproperties['confmsg']);
			$contactformdetails['confmsg_pagemode']= $contactformproperties['confirm_msg_page'];
			$contactformdetails['confmsg_pageid']= $contactformproperties['confirm_msg_pageid'];
			$contactformdetails['targetlist']=$contactformproperties['targetlist'];
			$contactformdetails['css']=stripslashes($contactformproperties['css']);
			$contactformdetails['subscribe_option']=$contactformproperties['subscribe_option'];
			$contactformdetails['subscribe_to_newsletter_displaytext']=stripslashes($contactformproperties['subscribe_to_newsletter_displaytext']);
			$contactformdetails['submit_button_type']=$contactformproperties['submit_button_type'];
			$contactformdetails['submit_button_val']=$contactformproperties['submit_button_val'];
			$contactformdetails['contact_create_associates']=$contactformproperties['contact_create_associates'];
			$contactformdetails['contact_assignee_type']=$contactformproperties['contact_assignee_type'];
			$contactformdetails['contact_assignee_type_id']=$contactformproperties['contact_assignee_type_id'];
			$contactformdetails['contact_assignee_name']=$contactformproperties['contact_assignee_name'];
			$contactformdetails['contact_status']=$contactformproperties['contact_status'];
			$contactformdetails['contact_type']=$contactformproperties['contact_type'];
			$contactformdetails['contact_source']=$contactformproperties['contact_source'];
            $contactformdetails['contact_address']=$contactformproperties['contact_address'];
			$contactformdetails['contact_address_id']=$contactformproperties['contact_address_id'];
			$contactformdetails['contact_associates']=$contactformproperties['contact_associates'];
			$contactformdetails['contact_rank']=$contactformproperties['contact_rank'];
			$contactformdetails['contact_status_id']=$contactformproperties['contact_status_id'];
			$contactformdetails['contact_type_id']=$contactformproperties['contact_type_id'];
			$contactformdetails['contact_source_id']=$contactformproperties['contact_source_id'];
			$contactformdetails['contact_rank_id']=$contactformproperties['contact_rank_id'];
			$contactformdetails['contact_width_type']=$contactformproperties['contact_width_type'];

			$check_browser	=	get_current_browser();
			if(isset($chrome)){
				if($check_browser=="chrome") { $chrome= ".recaptcha_source{width:40%;}"; }
				echo '<style type="text/css"> form input.required{color:#000;}label.error,.absp_contact_mandatory{color:#FF0400;}.absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error{color:red;font-weight:bold;padding-bottom:5px;}'.esc_attr($chrome).'</style>';
			}
			//include template files
			if($contactformproperties['tmpltype']=="awp_plugin_template") :
				$templatefile=AWP_CONTACTFORM_TEMPLATEPATH."/".$contactformproperties['layout']; // Plugin templates
			else :
				$templatefile=TEMPLATEPATH."/contactforms/".$contactformproperties['layout']; // theme templates
			endif;

			$contactformdetails['templatefile']=$templatefile;

			$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
			$characters_on_image = 6;
			$code = '';
			$i = 0;
			while ($i < $characters_on_image) {
				$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
				$i++;
			}
			$_SESSION['apptivo_business_captcha_code'] = $code;
			$contactformdetails['captchaimagepath'] = AWP_PLUGIN_BASEURL.'/assets/captcha/captcha_code_file.php?captcha_code='.$code;
			//add fields
			$contactformfields=$contactform['fields'];
			if(!empty($contactformfields)){
				usort($contactformfields, "awp_sort_by_order");
				$newcontactformfields=$contactformfields;
				$contactformdetails['fields']=$newcontactformfields;
			}
		}
		return $contactformdetails;
	}


	/**
	 * Get Contact form settings by form name to render in Admin
	 */
	function get_settings($formname){
		$formExists="";
		$contact_forms=array();
		$contactform=array();
		$formname=trim($formname);
		
		$contact_forms=get_option('awp_contactforms');
		if($formname=="")
		$formExists="";
		else if(!empty($contact_forms))
		$formExists = awp_recursive_array_search($contact_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$contactform=$contact_forms[$formExists];
		}
		return $contactform;
	}

	/**
	 * Return master fields lists supported by Apptivo Contact Form
	 */
	function get_master_fields()
	{
		$fields = array(
		array('fieldid' => 'firstname','fieldname' => 'First Name','defaulttext' => 'First Name','showorder' => '1','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','showorder' => '2','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'leadStatus','fieldname' => 'Lead Status','defaulttext' => 'Lead Status','showorder' => '3','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'leadType','fieldname' => 'Lead Type','defaulttext' => 'Lead Type','showorder' => '4','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'leadSource','fieldname' => 'Lead Source','defaulttext' => 'Lead Source','showorder' => '5','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'leadRank','fieldname' => 'Lead Rank','defaulttext' => 'Lead Rank','showorder' => '6','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '7','validation' => 'email','fieldtype' => 'text'),
		array('fieldid' => 'jobtitle','fieldname' => 'Job Title','defaulttext' => 'Job Title','showorder' => '8','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'company','fieldname' => 'Company','defaulttext' => 'Company','showorder' => '9','validation' => 'text','fieldtype' => 'text'),
        array('fieldid' => 'addressTypes','fieldname' => 'Address Type','defaulttext' => 'Address Type','showorder' => '24','validation' => 'text','fieldtype' => 'select'),
        array('fieldid' => 'address1','fieldname' => 'Address1','defaulttext' => 'Address1','showorder' => '10','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'address2','fieldname' => 'Address2','defaulttext' => 'Address2','showorder' => '11','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'city','fieldname' => 'City','defaulttext' => 'City','showorder' => '12','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'state','fieldname' => 'State','defaulttext' => 'State','showorder' => '13','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'zipcode','fieldname' => 'ZipCode','defaulttext' => 'ZipCode','showorder' => '14','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'country','fieldname' => 'Country','defaulttext' => 'Country','showorder' => '15','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'telephonenumber','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','showorder' => '16','validation' => '','fieldtype' => 'text'),
		array('fieldid' => 'comments','fieldname' => 'Comments','defaulttext' => 'Comments','showorder' => '17','validation' => 'textarea','fieldtype' => 'textarea'),
		array('fieldid' => 'captcha','fieldname' => 'Captcha','defaulttext' => 'Captcha','showorder' => '18','validation' => 'text','fieldtype' => 'captcha'),
		array('fieldid' => 'labels','fieldname' => 'Tags','defaulttext' => 'Tags','showorder' => '19','validation' => '','fieldtype' => 'multiselect'),
		array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '20','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '21','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '22','validation' => '','fieldtype' => 'multiselect'),
		array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '23','validation' => '','fieldtype' => 'radio'),
		array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '25','validation' => '','fieldtype' => 'checkbox')

		);
		//For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom');
		if(!empty($addtional_custom)):
		$fields = array_merge($fields,$addtional_custom);
		endif;

		return $fields;
	}
	/**
	 * Retrieve list of validations supported by Apptivo Contact Form
	 *
	 */
	function get_master_validations(){
		$validations = array(
		array('validationLabel' => 'None','validation' => 'none'),
		array('validationLabel' => 'Email ID','validation' => 'email'),
		array('validationLabel' => 'Number','validation' => 'number')
		);
		return $validations;
	}
	/**
	 *
	 * * Retrieve list of Field Types supported by Apptivo Contact Form
	 */
	function get_master_fieldtypes(){
		$fieldtypes = array(
		array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'), //toggle
		array('fieldtypeLabel' => 'Multiselect','fieldtype' => 'multiselect'), //->checkbox show 
		array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
		array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
		array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
		array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
		);
		return $fieldtypes;
	}

	/**
	 * return array of plugin templates available with Template name and template file name
	 */
	function get_plugin_templates(){
		$default_headers = array(
		'Template Name' => 'Template Name'
		);
		$templates = array();
		$dir_contact = AWP_CONTACTFORM_TEMPLATEPATH;
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_contact)) {
			if ($dh = opendir($dir_contact)) {
				while (($file = readdir($dh)) !== false) {
					if ( substr( $file, -4 ) == '.php' )
					{
						$plugin_data = get_file_data( $dir_contact."/".$file, $default_headers, '' );
						if(strlen(trim($plugin_data['Template Name'])) != 0 )
						{
							$templates[$plugin_data['Template Name']] = $file;
						}
					}
				}

				closedir($dh);
			}
		}
		return $templates;
	}

	/**
	 * Create field array
	 */
	function createformfield_array($fieldid,$showtext,$required,$type,$validation,$options,$displayorder){

		$displayorder = (trim($displayorder)=="")?0:trim($displayorder);

		$options = (is_array($options))?$options:stripslashes(str_replace( array('"'), '', strip_tags($options)));

		if( trim($type) != 'text' && trim($type) != 'textarea' && trim($type) != 'select'
		&& trim($type) != 'radio' && trim($type) != 'checkbox' && trim($type) != 'multiselect')
		{
			$pos = strpos(trim($fieldid), 'customfield');
			if( $pos !== false )
			{
				if( !is_array($options) && trim($options) == '')
				{
					return '';
				}
			}
		}

		$contactformfield= array(
	            'fieldid'=>$fieldid,
                'showtext' => stripslashes(str_replace( array('"'), '', strip_tags($showtext))),
	            'required' => $required,
				'type' => $type,
				'validation' => $validation,
				'options' => $options,
	   			'order' => $displayorder
		);
		return $contactformfield;
	}


	/**
	 * It renders UI in Admin page
	 */
	function options(){
		$updatemessage="";
		if(!empty($_POST['delformname']))   //Delete Form Name:
		{
			if(strlen(trim($_POST['delformname'])) != 0)
			{
				$formname = sanitize_text_field($_POST['delformname']);
				$contact_forms=get_option('awp_contactforms');
				$formExists = awp_recursive_array_search($contact_forms,$formname,'name' );
				if(isset($formExists))
				{
					unset($contact_forms[$formExists]);
				}
				$contact_sort_form = array();
				foreach($contact_forms as $contact_forms_tosort )
				{
					array_push($contact_sort_form,$contact_forms_tosort);
				}

				update_option('awp_contactforms', $contact_sort_form);
				$updatemessage= 'Contact Form "'.$formname.'" Deleted Successfully.';
			}
		}

		$contact_forms=array();
		$contactformdetails=array();
		$contact_forms=get_option('awp_contactforms');
		$required = '';

		/*
		 * Saving New form
		 */
		if(isset($_POST['newcontactformname']))
		{
			$newcontactformname =   sanitize_text_field($_POST['newcontactformname']);
			$newcontactformname = preg_replace('/\s+/', '_', $newcontactformname);
			$leadSources = json_decode($_SESSION['contact_config'])->leadSource;
			//$newcontactformname = preg_replace('/[^\w]/', '', $newcontactformname);
			
			$newcontactformname=trim($newcontactformname);
			if(_isCurl()){
				if($newcontactformname != '_' && $newcontactformname != '' ){
					$response= CreateContactFormLeads($newcontactformname,$leadSources);
				}else{
					$updatemessage= "Form Name should be valid data.";
				}
			}else{
                $response = "soap"; 
            }
							
				//check local db form name exist
				$contact_forms=get_option('awp_contactforms');
				$formExists="";
			if(!empty($contact_forms))
				$formExists = awp_recursive_array_search($contact_forms,$newcontactformname,'name' );
				

			if($newcontactformname!='')
			{
				$contactform=array();
				$contactform=$this->get_settings($newcontactformname);
				if( count($contactform)==0 && $response=="present_in_app" && $formExists=='' )
				{
					$newcontactformname_array =array("name"=>$newcontactformname);
					$newcontactform=array($newcontactformname_array);
					if( empty($contact_forms) ){

						update_option('awp_contactforms',$newcontactform);
					}else{
						array_push($contact_forms, $newcontactformname_array);


						update_option('awp_contactforms',$contact_forms);
					}
					$contact_forms=get_option('awp_contactforms');
					$contactform=$this->get_settings($newcontactformname);
					$selectedcontactform=$newcontactformname;
					$updatemessage= "Contact Form created. Please configure settings using the below Configuration section.";
				}else{
					$updatemessage= "<span style='color:#f00;'>Form already exists. To change configuration, please select the form from below configuration section.</span>";
				}
			}else{
				$updatemessage= "Form Name cannot be empty or should valid data.";
			}
		}

		/*
		 * Loading the settings of selected form
		 */
		if(isset($_POST['awp_contactform_select_form']))
		{
			$selectedcontactform =  trim(sanitize_text_field($_POST['awp_contactform_select_form']));
			if($selectedcontactform!='')
			{
				$contactform=array();
				$contactform=$this->get_settings($selectedcontactform);
				if( empty($contactform))
				{
					//"Selected form configuration doestn exist.";
				}else{
					$contactformdetails=$contactform;
				}
			}
		}

		/*
		 * Saving selected form settings
		 */
		if(isset($_POST['awp_contactform_settings']) && !empty($_POST['awp_contactform_settings'])){
			$templatelayout="";
			
			contactOptions('save');
			$absp_contact_config_tag='';
			
			if(isset($_POST['awp_labels'])){
				//Tag values

				$selected_values = explode(',', sanitize_text_field($_POST['awp_labels']));
				foreach($selected_values as $selected_value) {
					// Do something with each selected value
					$absp_contact_config_tag .= $selected_value . ", ";
				}
				$absp_contact_config_tag = rtrim($absp_contact_config_tag, ', ');
			}else{
				$absp_contact_config_tag = '';
			}

			//echo "<pre> absp_contact_config_tag";print_r($absp_contact_config_tag);echo "</pre>";

			$contactConfigDetails	=array("awp_leadSource_selected"=>sanitize_text_field($_POST['absp_contact_config_leadSource']),"awp_addressTypes_selected"=>sanitize_text_field($_POST['absp_contact_config_addressTypes']),"awp_leadType_selected"=>sanitize_text_field($_POST['absp_contact_config_leadType']),"awp_leadStatus_selected"=>sanitize_text_field($_POST['absp_contact_config_leadStatus']),"awp_leadRank_selected"=>sanitize_text_field($_POST['absp_contact_config_leadRank']),"awp_labels_selected"=>sanitize_text_field($absp_contact_config_tag));
			$newformname=sanitize_text_field($_POST['awp_contactform_name']);
			
			if(sanitize_text_field($_POST['awp_contactform_templatetype'])=="awp_plugin_template")
			$templatelayout=sanitize_text_field($_POST['awp_contactform_plugintemplatelayout']);
			else
			$templatelayout=sanitize_text_field($_POST['awp_contactform_themetemplatelayout']);
				
			if(sanitize_text_field($_POST["awp_contact_select_assignee"]) == 'team'){
				$assignee_type_id = sanitize_text_field($_POST['awp_contact_select_assignee_team']);
			}else{
				$assignee_type_id = sanitize_text_field($_POST['awp_contact_select_assignee_employee']);
			}
			$contactformproperties=array(
							'tmpltype' => isValueSet(sanitize_text_field($_POST['awp_contactform_templatetype'])),
							'layout' => isValueSet($templatelayout),
							'confmsg' => isValueSet(stripslashes(sanitize_text_field($_POST['awp_contactform_confirmationmsg']))),
							'confirm_msg_page' => isValueSet(sanitize_text_field($_POST['awp_contactform_confirm_msg_page'])),
							'confirm_msg_pageid' => isValueSet(sanitize_text_field($_POST['awp_contactform_confirmmsg_pageid'])),
							'targetlist' =>isset($_POST['awp_contactform_targetlist'])?sanitize_text_field($_POST['awp_contactform_targetlist']):'',
							'css' => isValueSet(stripslashes($_POST['awp_contactform_customcss'])),
							'subscribe_option' => isset($_POST['subscribe_option'])? sanitize_text_field($_POST['subscribe_option']):'',
							'subscribe_to_newsletter_displaytext' => isset($_POST['awp_subscribe_to_newsletter'])?stripslashes(sanitize_text_field($_POST['awp_subscribe_to_newsletter'])):'',
							'submit_button_type' => isValueSet(sanitize_text_field($_POST['awp_contactform_submit_type'])),
							'submit_button_val' => isValueSet(sanitize_text_field($_POST['awp_contactform_submit_value'])),
							'contact_associates' => isValueSet(sanitize_text_field($_POST['awp_contact_associates'])),
							'contact_create_associates' => isValueSet(sanitize_text_field($_POST['awp_contact_createassociate'])),
							'contact_assignee_type' => isValueSet(sanitize_text_field($_POST['awp_contact_select_assignee'])),
							'contact_assignee_type_id' => $assignee_type_id,
							'contact_assignee_name' => isValueSet(trim(sanitize_text_field($_POST['select_assignee_name_val']))),
							'contact_address' => isValueSet(sanitize_text_field($_POST['awp_addressTypes'])),
							'contact_address_id'=> isValueSet(sanitize_text_field($_POST['absp_contact_config_addressTypes'])),
							'contact_status' => isValueSet(sanitize_text_field($_POST['awp_leadStatus'])),
							'contact_type' => isValueSet(sanitize_text_field($_POST['awp_leadType'])),
							'contact_source' => isValueSet(sanitize_text_field($_POST['awp_leadSource'])),
							'contact_rank' => isValueSet(sanitize_text_field($_POST['awp_leadRank'])),
							'contact_status_id'=> isValueSet(sanitize_text_field($_POST['absp_contact_config_leadStatus'])),
							'contact_type_id'=> isValueSet(sanitize_text_field($_POST['absp_contact_config_leadType'])),
							'contact_source_id'=> isValueSet(sanitize_text_field($_POST['absp_contact_config_leadSource'])),
							'contact_rank_id'=> isValueSet(sanitize_text_field($_POST['absp_contact_config_leadRank'])),
							'contact_width_type'=> isValueSet(sanitize_text_field($_POST['awp_contact_width_type'])),
							'contact_label' => isValueSet(sanitize_text_field($absp_contact_config_tag)),
			);

			//New Custom fields
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 20;
			for($i=6;$i<200;$i++)
			{

				if(isset($_POST['customfield'.$i.'_newest']) && !empty($_POST['customfield'.$i.'_newest'])){
					$addtional_custom = array('fieldid' => 'customfield'.$i.'','fieldname' => 'Custom Field '.$i.'',
					                     'defaulttext' => 'Custom Field'.$i.'','showorder' => $addtional_order,'validation' => '',
					                     'fieldtype' => 'select');
					$addtional_order++;
					array_push($stack, $addtional_custom);

				}else {
					break;
				}
			}

			if(isset($stack) && !empty($stack)) :
			update_option('awp_addtional_custom',$stack);
			endif;

			//General Contact form fields
			$contactformfields=array();
			foreach( $this->get_master_fields() as $fieldsmasterproperties )
			{
				$enabled=0;
				$contactformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				if(!empty ($_POST[$fieldid.'_order'])){
					$displayorder = sanitize_text_field($_POST[$fieldid.'_order']);
				}
				else{
					$displayorder = $fieldsmasterproperties['showorder'];
				}
				if(!empty ($_POST[$fieldid.'_text'])){
					$displaytext = sanitize_text_field($_POST[$fieldid.'_text']);
				}
				else{
					$displaytext = $fieldsmasterproperties['defaulttext'];
				}
				if($fieldid=='lastname' || $fieldid=='email')
				{
					$enabled = 1;
					$required = 1;
				}
				else if($fieldid=='captcha')
				{
					$enabled = isset($_POST[$fieldid.'_show']) ? sanitize_text_field($_POST[$fieldid.'_show']) : '';
					$required = 1;
				}
				else
				{
					$enabled = isset($_POST[$fieldid.'_show'])?sanitize_text_field($_POST[$fieldid.'_show']):'';
					$required = isset($_POST[$fieldid.'_require'])?sanitize_text_field($_POST[$fieldid.'_require']):'';
				}
				if($enabled){
					$_POST[$fieldid.'_validation'] = (array_key_exists($fieldid.'_validation',$_POST)) ? sanitize_text_field($_POST[$fieldid.'_validation']) : "none";
					$_POST[$fieldid.'_options'] = (isset($_POST[$fieldid.'_options']) && !empty($_POST[$fieldid.'_options'])) ? sanitize_textarea_field($_POST[$fieldid.'_options']) : "";
					$contactformfield=$this->createformfield_array($fieldid,$displaytext,$required,sanitize_text_field($_POST[$fieldid.'_type']),$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
					array_push($contactformfields, $contactformfield);
				}
			}
			//usort($contactformfields, "awp_sort_by_order");
			if(isset($contactformfields) && !empty($contactformfields)){
				$newcontactformdetails=array('name'=>$newformname,'properties'=>$contactformproperties,'fields'=>$contactformfields,'contact_config'=>$contactConfigDetails);

				$formExists="";
				if(isset($contact_forms) && !empty($contact_forms))
				$formExists = awp_recursive_array_search($contact_forms,$newformname,'name' );
				if(trim($formExists)!=="" ){

					unset($contact_forms[$formExists]);
					array_push($contact_forms, $newcontactformdetails);
					sort($contact_forms);
					contactOptions('save');
					update_option('awp_contactforms',$contact_forms);
					$contact_forms=get_option('awp_contactforms');
					$updatemessage= "Contact Form '".$newformname."' settings updated. Use Shortcode '[apptivocontactform name=\"".$newformname."\"]' in your page to use this form.";

				}


			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for Contact Form.</span>";
			}
			$selectedcontactform=$newformname;
		}

		// Now display the settings editing screen
		echo '<div class="wrap">';
		// header
		echo "<h2>" . __( 'Apptivo Contact Forms', 'awp_contactform' ) . "</h2>";
		checkCaptchaOption();
		echo '<div class="contactform_err"></div>';
		//if updatemessage is not empty display the div
		if(trim($updatemessage)!=""){
			?>
<div id="message" class="updated">
	<p>
	<?php echo esc_attr($updatemessage);?>
	</p>
</div>
	<?php }
	if(!$this->_plugin_activated){
		$disabledForm = 'disabled="disabled"';
		$disabledOption = TRUE;
		echo "Contact Forms is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
	}
	else 
	{
		$disabledForm ='';
	}

	//get the count of total contact forms created
	$contactformscount=0;
	if(isset($contact_forms) && !empty($contact_forms)){
		$contactformscount=count($contact_forms);
	}else{
		$contactformscount=0;
	}

	if($contactformscount < 100){
		?>
<form name="awp_contactform_new" id="awp_contactform_new" method="post" action="">

	<p>
		<img id="elementToResize"
			src="<?php echo awp_flow_diagram('contactform');?>" alt="contactform"
			title="contactform" />
	</p>
	<p style="margin: 10px;">
		For Complete instructions,see the <a
			href="<?php echo awp_developerguide('contactform');?>"
			target="_blank">Developer's Guide.</a>
	</p>

	<p>
	<?php _e("Contact Form Name", 'apptivo-businesssite' ); ?>
		<span style="color: #f00;">*</span>&nbsp;&nbsp;<input type="text"
			name="newcontactformname" id="newcontactformname" size="20"
			maxlength="50" value=""> <span class="description"><?php _e('This form name will be used as your Lead Source in Apptivo.','apptivo-businesssite'); ?>
		</span>
	</p>
	
	<p>
		<input <?php echo esc_attr($disabledForm);?> type="submit" name="Submit"
			class="button-primary" value="<?php esc_attr_e('Add New') ?>" />
	</p>

</form>

	<?php
	}

	if(isset($contact_forms) && !empty($contact_forms)){
		$newsletter_categories = $this->getNewsletterCategory();
		$themetemplates = get_awpTemplates(TEMPLATEPATH.'/contactforms','Plugin');
		$plugintemplates=$this->get_plugin_templates();
		arsort($plugintemplates);

		?>
<br>
<hr />
		<?php
		echo "<h2>" . __( 'Contact Form Configuration', 'awp_contactform' ) . "</h2>";
		?>
		<?php
		if(!isset($selectedcontactform))
		{
			$selectedcontactform="";
		}	
		
		
		if(trim($selectedcontactform)==""){
			$selectedcontactform=$contact_forms[0]['name'];
		}
		$contactformdetails=$this->get_settings($selectedcontactform);
		if(count($contactformdetails)>0){
			$selectedcontactform=$contactformdetails['name'];
			$contactformdetails['fields'] = (array_key_exists('fields',$contactformdetails)) ? $contactformdetails['fields'] : '';
			$fields=$contactformdetails['fields'];
			$contactformdetails['properties'] = (array_key_exists('properties',$contactformdetails)) ? $contactformdetails['properties'] : [];
			$formproperties=$contactformdetails['properties'];
		}
		?>

<table class="form-table">
	<tbody>
	<?php if(empty($formproperties['tmpltype'])) :  //To check contact form settings are save or not.
	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for contact form.</span>';
	endif; ?>
		<tr valign="top">
			<th valign="top"><label for="awp_contactform_select_form"><?php _e("Contact Form", 'apptivo-businesssite' ); ?>:</label>
			</th>
			<td valign="top">
				<form name="awp_contact_selection_form" method="post" action=""
					style="float: left;">
					<select name="awp_contactform_select_form"
						id="awp_contactform_select_form" onchange="this.form.submit();">
						<?php
						for($i=0; $i<count($contact_forms); $i++)
						{ ?>
						<option value="<?php echo esc_attr($contact_forms[$i]['name'])?>"
						<?php if(trim($selectedcontactform)==$contact_forms[$i]['name'])
						echo "selected='true'";?>>
						<?php echo esc_attr($contact_forms[$i]['name'])?>
						</option>
						<?php } ?>
					</select>

				</form> &nbsp;&nbsp;&nbsp;&nbsp; <?php if($this->_plugin_activated)
				{ ?>
				<form name="awp_contact_delete_form" method="post" action=""
					style="float: left; padding-left: 30px;">
					<a
						href="javascript:contact_confirmation('<?php echo esc_attr($selectedcontactform); ?>')">Delete</a>
					<input type="hidden" name="delformname" id="delformname" />
				</form> <?php } ?>
			</td>

		</tr>
	</tbody>
</table>

				<?php
				if(_isCurl())
				{
					$configDatas	= contactOptions($save=null);
					//$configDatas	= get_option("awp_contact_configdata");
					$configDatas	= json_decode($configDatas);
					//echo "<pre> configDatas :- ";print_r($configDatas);echo "</pre>";
					if(isset($configDatas->leadAssignee)){
						foreach ($configDatas->leadAssignee as $assigne_key => $assigne_value){
							if($assigne_value->assigneeObjectId == APPTIVO_EMPLOYEE_OBJECT_ID){
								$assignee_employee_list[$assigne_value->assigneeName] = $assigne_value->assigneeObjectRefId;
							}
							else if($assigne_value->assigneeObjectId == APPTIVO_TEAM_OBJECT_ID){
								$assignee_team_list[$assigne_value->assigneeName] = $assigne_value->assigneeObjectRefId;
							}
						}
						$contact_assignee_default_name = $configDatas->leadAssignee[0]->assigneeName;
					}else{
						$contact_assignee_default_name = '';
					}
					if(!isset($assignee_employee_list)){
						$assignee_employee_list["No Employee"] = '';
					}
					if(!isset($assignee_team_list)){
						$assignee_team_list["No Team"] = '';
					}
				}
				if(isset($formproperties['contact_assignee_name'])){
					$contact_assignee_name = $formproperties['contact_assignee_name'];
				}else{
					$case_assignee_default_name = (isset($case_assignee_default_name)) ? $case_assignee_default_name : '';
					$contact_assignee_name = $case_assignee_default_name;
				}

				?>

<form name="awp_contact_settings_form" method="post" action="">
	<table class="form-table">
		<tbody>
		<?php if(isset($formproperties['tmpltype']) && !empty($formproperties['tmpltype'])) :?>
			<tr valign="top">
				<th valign="top"><label for="contactform_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description"><?php _e('Copy and Paste this shortcode in your page to display this contact form.','apptivo-businesssite'); ?>
				</span>
				</th>
				<td valign="top"><span id="awp_customform_shortcode"
					name="awp_customform_shortcode"> <input style="width: 300px;"
						type="text" id="contactform_shortcode"
						name="contactform_shortcode" readonly="true"
						value='[apptivocontactform name="<?php echo esc_attr($selectedcontactform)?>"]' />
				</span> <span style="margin: 10px;">*Developers Guide - <a
						href="<?php echo awp_developerguide('contactform-shortcode');?>"
						target="_blank">Contact Form Shortcodes.</a> </span>
				</td>
			</tr>
			<?php endif; ?>
			<tr valign="top">
				<th valign="top"><label for="awp_contactform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
				</th>
				<td valign="top"><input type="hidden" id="awp_contactform_name"
					name="awp_contactform_name"
					value="<?php echo esc_attr($selectedcontactform);?>"> <select
					name="awp_contactform_templatetype"
					id="awp_contactform_templatetype"
					onchange="change_contact_Template();">
						<option value="awp_plugin_template"
						<?php 
						$formproperties['tmpltype'] = (array_key_exists('tmpltype',$formproperties)) ? $formproperties['tmpltype'] : [];
						selected($formproperties['tmpltype'],'awp_plugin_template'); ?>>Plugin
							Templates</option>
							<?php if(isset($themetemplates) && !empty($themetemplates)) : ?>
						<option value="theme_template"
						<?php selected($formproperties['tmpltype'],'theme_template'); ?>>Templates
							from Current Theme</option>
							<?php endif; ?>
				</select> <span style="margin: 10px;">*Developers Guide - <a
						href="<?php echo awp_developerguide('contactform-template');?>"
						target="_blank">Contact Form Templates.</a> </span>
				</td>
			</tr>
			<tr valign="top">
				<th valign="top"><label for="awp_contactform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description">Selecting Theme template which
						doesnt support Contact form structure will wont show the contact
						form in webpage.</span>
				</th>
				<td valign="top"><select name="awp_contactform_plugintemplatelayout"
					id="awp_contactform_plugintemplatelayout"
					<?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?>>
					<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
						<option value="<?php echo esc_attr($plugintemplates[$template])?>"
						<?php
						$formproperties['layout'] = (isset($formproperties['layout']) && !empty($formproperties['layout'])) ? $formproperties['layout'] : '';
						selected($formproperties['layout'],$plugintemplates[$template]); ?>>
							<?php echo esc_attr($template)?>
						</option>
						<?php }  ?>
				</select> <select name="awp_contactform_themetemplatelayout"
					id="awp_contactform_themetemplatelayout"
					<?php
					$formproperties['tmpltype'] = (array_key_exists('tmpltype',$formproperties)) ? $formproperties['tmpltype'] : '';
					if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?>>
					<?php foreach (array_keys( $themetemplates ) as $template ) : ?>
						<option value="<?php echo esc_attr($themetemplates[$template])?>"
						<?php selected($formproperties['layout'],$themetemplates[$template]);?>>
							<?php echo esc_attr($template)?>
						</option>
						<?php endforeach;?>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_contact_samepage"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label>
				</th>
				<td valign="top"><input type="radio" value="same"
					id="awp_contact_samepage" name="awp_contactform_confirm_msg_page"
					<?php 
					$formproperties['confirm_msg_page'] = (array_key_exists('confirm_msg_page',$formproperties)) ? $formproperties['confirm_msg_page'] : '';
					checked('same',$formproperties['confirm_msg_page']); ?>
					checked="checked" /> <label for="awp_contact_samepage">Same Page</label>
					<input type="radio" value="other" id="awp_contact_otherpage"
					name="awp_contactform_confirm_msg_page"
					<?php checked('other',$formproperties['confirm_msg_page']); ?> /> <label
					for="awp_contact_otherpage">Other page</label> <br /> <br /> <select
					id="awp_contactform_confirmmsg_pageid"
					name="awp_contactform_confirmmsg_pageid"
					<?php if($formproperties['confirm_msg_page'] != 'other') echo 'style="display:none;"';?>>
					<?php
					$pages = get_pages();
					foreach ($pages as $pagg) {
						?>
						<option value="<?php echo esc_attr($pagg->ID); ?>"
						<?php 
						$formproperties['confirm_msg_pageid'] = (array_key_exists('confirm_msg_pageid',$formproperties)) ? $formproperties['confirm_msg_pageid'] : '';
						selected($pagg->ID, $formproperties['confirm_msg_pageid']); ?>>
							<?php echo esc_attr($pagg->post_title); ?>
						</option>
						<?php
					}
					?>
				</select>
				</td>
				</td>
			</tr>
			<tr valign="top" id="awp_contactform_confirmationmsg_tr"
			<?php if($formproperties['confirm_msg_page'] == 'other') echo 'style="display:none;"';?>>
				<th valign="top"><label for="awp_contactform_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description">This message will shown in your
						website page, once contact form submitted.</span>
				</th>
				<td valign="top">
					<div style="width: 620px;">
					<?php 
					$formproperties['confmsg'] = (array_key_exists('confmsg',$formproperties)) ? $formproperties['confmsg'] : '';
					//the_editor($formproperties['confmsg'],'awp_contactform_confirmationmsg','',FALSE);
					wp_editor($formproperties['confmsg'], 'awp_contactform_confirmationmsg', array());
					?>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_contactform_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
					<br> <span valign="top" class="description">Style class provided
						here will override template style. Please refer Apptivo plugin
						help section for class name to be used.</span>
				</th>
				<td valign="top"><textarea name="awp_contactform_customcss"
						id="awp_contactform_customcss" size="100" cols="40" rows="10">
						<?php 
						$formproperties['css'] = (array_key_exists('css',$formproperties)) ? $formproperties['css'] : '';
						echo esc_attr($formproperties['css']);?>
					</textarea> <span style="margin: 10px;">*Developers Guide - <a
						href="<?php echo awp_developerguide('contactform-customcss');?>"
						target="_blank">Contact Form CSS.</a> </span>
				</td>

			</tr>
			<tr valign="top">
				<th><label id="awp_contactform_submit_type"
					for="awp_contactform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
					<br> <span valign="top" class="description"></span>
				</th>

				<td valign="top"><input type="radio" value="submit"
					id="awp_cont_btn" name="awp_contactform_submit_type"
					<?php 
					$formproperties['submit_button_type'] = (array_key_exists('submit_button_type',$formproperties)) ? $formproperties['submit_button_type'] : '';
					checked('submit',$formproperties['submit_button_type']); ?>
					checked="checked" /> <label for="awp_cont_btn">Button</label> <input
					type="radio" value="image" id="awp_cont_img"
					name="awp_contactform_submit_type"
					<?php checked('image',$formproperties['submit_button_type']); ?> /> <label
					for="awp_cont_img">Image</label>
				</td>
			</tr>
			<tr valign="top">
				<th><label id="awp_contactform_submit_val"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
					<br> <span valign="top" class="description"></span>
				</th>
				<td valign="top"><input type="text"
					name="awp_contactform_submit_value"
					id="awp_contactform_submit_value"
					value="<?php 
					$formproperties['submit_button_val'] = (array_key_exists('submit_button_val',$formproperties)) ? $formproperties['submit_button_val'] : '';
					echo esc_attr($formproperties['submit_button_val']);?>" size="52" />
					<span id="contact_upload_img_button" style="display: none;"> <input
						id="contact_upload_image" type="button" value="Upload Image"
						class="button-primary" /> <br /> <?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
				</span>
				</td>
			</tr>
			<?php if(isset($newsletter_categories) && !empty($newsletter_categories)) { ?>
			<tr valign="top">
				<th valign="top"><label for="awp_contactform_targetlist"><?php _e("Apptivo Target List", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description">Select the Apptivo Target List
						category to which this Form submitted has to be subscribed.</span>
				</th>
				<td valign="top"><select id="awp_contactform_targetlist"
					name="awp_contactform_targetlist"
					onchange="contactform_selectCategory('awp_contactform_targetlist');">
						<option value="">None</option>
						<?php if(count($newsletter_categories)=="1" && is_object($newsletter_categories)) { ?>
						<option
							value="<?php echo  esc_attr($newsletter_categories->targetListId); ?>"
							<?php selected($newsletter_categories->targetListId, $formproperties['targetlist']) ?>>
							<?php echo  esc_attr($newsletter_categories->targetListName); ?>
						</option>
						<?php } else {?>
						<?php foreach($newsletter_categories as $category){  ?>
						<option value="<?php echo  esc_attr($category->id); ?>"
						<?php
						$formproperties['targetlist'] = (array_key_exists('targetlist',$formproperties)) ? $formproperties['targetlist'] : '';
						selected($category->id, $formproperties['targetlist']) ?>>
							<?php
							$category->name = (isset($category->name) && !empty($category->name)) ? $category->name : '';
							echo  esc_attr($category->name); ?>
						</option>
						<?php }  }?>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_newsletterform_customcss">Provide subscribe
						option to user?:</label> <br> <span class="description"
					valign="top">if select yes means subscribe option display to user
						else subscribe user automatically.</span>
				</th>
				<td valign="top"><?php
				if(strlen(trim($formproperties['targetlist'])) == 0 )
				{
					$disbleAction = 'disabled="disabled"';
				}
				$disbleAction = (isset($disbleAction) && !empty($disbleAction)) ? $disbleAction : '';
				?> <input <?php echo esc_attr($disbleAction); ?> type="radio"
					name="subscribe_option" id="subscribe_option_yes" value="yes"
					<?php
					$formproperties['subscribe_option'] = (array_key_exists('subscribe_option',$formproperties)) ? $formproperties['subscribe_option'] : '';
					checked('yes', $formproperties['subscribe_option']); ?> /> <label
					for="subscribe_option_yes">Yes</label> <input
					<?php echo esc_attr($disbleAction); ?> type="radio" name="subscribe_option"
					id="subscribe_option_no" value="no"
					<?php checked('no', $formproperties['subscribe_option']); ?> /> <label
					for="subscribe_option_no">No</label>
				</td>
			</tr>

			<tr valign="top">
				<th><label for="awp_subscribe_to_newsletter"><?php _e('Subscribe to Newsletter   (Display Text)','apptivo-businesssite'); ?>
				</label> <br> <span class="description" valign="top"></span>
				</th>
				<td valign="top"><input <?php echo esc_attr($disbleAction); ?> type="text"
					size="52"
					value="<?php
					$formproperties['subscribe_to_newsletter_displaytext'] = (array_key_exists('subscribe_to_newsletter_displaytext',$formproperties)) ? $formproperties['subscribe_to_newsletter_displaytext'] : '';
					echo htmlentities($formproperties['subscribe_to_newsletter_displaytext']); ?>"
					id="awp_subscribe_to_newsletter" name="awp_subscribe_to_newsletter">
				</td>
			</tr>

			<?php } ?>
			<?php if(_isCurl())  { ?>
			<tr valign="top">
				<th valign="top"><label for="awp_contact_associates"><?php _e("Associate Lead With:", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $associateOption	=	array("No Need","Customer"); ?>
					<select name="awp_contact_associates" id="awp_contact_associates">
					<?php foreach ($associateOption as $associateValues ) { ?>
						<option value="<?php echo esc_attr($associateValues)?>"
						<?php
						$formproperties['contact_associates'] = (array_key_exists('contact_associates',$formproperties)) ? $formproperties['contact_associates'] : '';
						selected($associateValues, $formproperties['contact_associates']); ?>>
							<?php echo esc_attr($associateValues)?>
						</option>
						<?php }  ?>
				</select>
				</td>
			</tr>

			<tr valign="top">
				<th valign="top" style="float: left;"><label
					for="awp_contact_createassociate"><?php _e("Create new customer and associate with Lead:", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $createOption	=	array("Do not create"=>"donot","Create New customer"=>"customer"); ?>
					<select name="awp_contact_createassociate" id="awp_contact_createassociate">
					<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
						<option value="<?php echo esc_attr($createdValue)?>"
						<?php 
						$formproperties['contact_create_associates'] = (array_key_exists('contact_create_associates',$formproperties)) ? $formproperties['contact_create_associates'] : '';
						selected($createdValue, $formproperties['contact_create_associates']); ?>>
							<?php echo esc_attr($createdKey)?>
						</option>
						<?php }  ?>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th valign="top" style="float: left;"><label
					for="awp_contact_select_assignee"><?php _e("Select Assignee [ Employee/ Team ]:", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top">
					<?php $createOption = array("Employee"=>"employee","Team"=>"team"); ?>
					<select name="awp_contact_select_assignee" id="awp_contact_select_assignee">
						<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
						<option value="<?php echo esc_attr($createdValue)?>"
						<?php
						$formproperties['contact_assignee_type'] = (array_key_exists('contact_assignee_type',$formproperties)) ? $formproperties['contact_assignee_type'] : '';
						selected($createdValue, $formproperties['contact_assignee_type']); ?>>
							<?php echo esc_attr($createdKey)?>
						</option>
						<?php }  
							$show_assignee_employee_style = '';
							$show_assignee_team_style = '';							
						?>
					</select>
					<?php if($formproperties['contact_assignee_type'] == 'team') { $show_assignee_employee_style = 'style="display:none"'; } else { $show_assignee_team_style = ' style="display:none"';}?>
					<select <?php echo esc_attr($show_assignee_employee_style); ?>
					class="awp_contact_select_assignee" name="awp_contact_select_assignee_employee" id="awp_contact_select_assignee_employee" onchange="document.getElementById('select_assignee_name_val').value=this.options[this.selectedIndex].text">
					<?php foreach ($assignee_employee_list as $createdKey => $createdValue ) { ?>
						<option value="<?php echo esc_attr($createdValue)?>"
						<?php 
						$formproperties['contact_assignee_type_id'] = (array_key_exists('contact_assignee_type_id',$formproperties)) ? $formproperties['contact_assignee_type_id'] : '';
						selected($createdValue, $formproperties['contact_assignee_type_id']); ?>><?php echo esc_attr($createdKey)?></option>
						<?php }  ?>
					</select>
					<select <?php echo esc_attr($show_assignee_team_style); ?> class="awp_contact_select_assignee" name="awp_contact_select_assignee_team" id="awp_contact_select_assignee_team" onchange="document.getElementById('select_assignee_name_val').value=this.options[this.selectedIndex].text">
					<?php foreach ($assignee_team_list as $createdKey => $createdValue ) { ?>
						<option value="<?php echo esc_attr($createdValue)?>"
						<?php selected($createdValue, $formproperties['contact_assignee_type_id']); ?>><?php echo esc_attr($createdKey)?></option>
						<?php }  ?>
					</select>
					<input type="hidden" id="select_assignee_name_val" name="select_assignee_name_val" value="<?php echo esc_attr($contact_assignee_name);?>" />
				</td>
			</tr>
			<?php } ?>
			<tr valign="top">
				<th valign="top" style="float: left;"><label
					for="awp_contact_width_type"><?php _e("Form Outer Width :", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $createOption	=	array("Full Width (100%)"=>"100%","Half Width (50%)"=>"50%"); ?>
					<select name="awp_contact_width_type"
					id="awp_contact_width_type">
					<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
						<option value="<?php echo esc_attr($createdValue)?>"
						<?php $formproperties['contact_width_type'] = (array_key_exists('contact_width_type',$formproperties)) ? $formproperties['contact_width_type'] : '';
						selected($createdValue, $formproperties['contact_width_type']); ?>>
							<?php echo esc_attr($createdKey)?>
						</option>
						<?php }  ?>
				</select>
				</td>
			</tr>
		</tbody>
	</table>

	<br>
	<?php
	echo "<h3>" . __( 'Contact Form Fields', 'awp_contactform' ) . "</h3>";?>


	<div style="margin: 10px;">
		<span class="description">Select and configure list of fields from
			below table to show in your contact form.</span> <span
			style="margin-left: 30px;">*Developers Guide - <a
			href="<?php echo awp_developerguide('contactform-basicconfig');?>"
			target="_blank">Basic Contact Form Config.</a> </span>
	</div>

	<br>
	<table width="900" cellspacing="0" cellpadding="0"
		id="contact_form_fields" name="contact_form_fields"
		style="border-collapse: collapse;">
		<tbody>
			<tr>
				<th></th>
			</tr>
			<tr align="center"
				style="background-color: rgb(223, 223, 223); font-weight: bold;"
				class="widefat">

				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text - The label name should be Apptivo field label name. (For customfields only)','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);min-width:150px;"><?php _e('Option Values','apptivo-businesssite'); ?>
				</td>
			</tr>
			<tr>
				<th></th>
			</tr>
			<?php $pos = 0;
			$index_key = 0;
			foreach( $this->get_master_fields() as $fieldsmasterproperties )
			{
				$enabled=0;
				$fieldExists=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				$fieldExistFlag="";
				if(isset($fields) && !empty($fields))
				{
					$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');
				}

				if(trim($fieldExistFlag)!=="")
				{
					$enabled=1;
					$fieldData=array("fieldid"=>$fieldid,
						"fieldname"=>$fieldsmasterproperties['fieldname'],
						"show"=>$enabled,
						"required"=>$fields[$fieldExistFlag]['required'],
						"showtext"=>$fields[$fieldExistFlag]['showtext'],
						"type"=>$fields[$fieldExistFlag]['type'],
						"validation"=>$fields[$fieldExistFlag]['validation'],
						"options"=>$fields[$fieldExistFlag]['options'],
						"order"=>$fields[$fieldExistFlag]['order']);
				}else{
					if($fieldid=='lastname' || $fieldid=='email')
					{
						$enabled =1;
						$required =1;
					}
					else if($fieldid=='captcha'){
						$required =1;
					}

					$fieldData=array("fieldid"=>$fieldid,
						"fieldname"=>$fieldsmasterproperties['fieldname'],
						"show"=>$enabled,
						"required"=>$required,
						"showtext"=>$fieldsmasterproperties['defaulttext'],
						"type"=>"",
						"validation"=>"",
						"options"=>"",
						"order"=>"");
				}
				$pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
				?>
				<tr>
					<td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;"><?php echo esc_attr($fieldData['fieldname'])?>
					</td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					<?php  if($enabled) { ?> checked="checked"
					<?php } if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email' || $fieldData['fieldid']=='leadSource'){?>
						disabled="disabled" <?php } ?> type="checkbox"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_show"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_show" size="30"
						class="custom_fld" rel="<?php echo esc_attr($fieldData['fieldid'])?>"> <?php if($index_key > 18 ) :?>
						<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_newest"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_newest" value="" /> <?php endif; $index_key++; ?>
					</td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					<?php
					if(!$enabled) { ?> disabled="disabled"
					<?php }
					else if($fieldData['required'] ) { ?> checked="checked"
					<?php }?> type="checkbox"
					<?php if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'|| $fieldData['fieldid']=='captcha'){?>
						disabled="disabled" <?php } ?>
						id="<?php echo esc_attr($fieldData['fieldid'])?>_require"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_require" size="30"></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
						type="text" class="num"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_order"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_order"
						value="<?php echo esc_attr($fieldData['order']); ?>" size="3" maxlength="2"
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> type="text"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_text"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_text"
						value="<?php echo esc_attr($fieldData['showtext']); ?>"></td>

					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
					$name_postfix="type";

					//echo "<pre> fieldid :-".$fieldid;print_r($configDatas);echo "</pre>";
					if($pos===false){
						?> 
						<input type="hidden"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_type"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_type"
						<?php if($fieldid=="country" || $fieldid=="leadStatus" || $fieldid=="leadType" || $fieldid=="leadSource" || $fieldid=="leadRank" || $fieldid=="addressTypes" ){
							?> value="select"
							<?php }else if($fieldid=="labels"){ ?> value="multiselect"
							<?php }else if($fieldid=="comments"){ ?> value="textarea"
							<?php }else if($fieldid=="captcha"){ ?> value="captcha"
							<?php }else{?> value="text" <?php }?>> 
							<input
							<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6"
						readonly="readonly" type="text"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_typehiddentext"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_typehiddentext"
						<?php if($fieldid=="country" || $fieldid=="leadStatus" || $fieldid=="leadType" || $fieldid=="leadSource" || $fieldid=="leadRank" || $fieldid=="addressTypes" ){
							?> value="Select"
							<?php }else if($fieldid=="labels"){ ?> value="multiselect"
							<?php }else if($fieldid=="comments"){ ?> value="Textarea"
							<?php }else if($fieldid=="captcha"){ ?> value="Captcha"
							<?php } else{ ?> value="Text box" <?php }?>> <?php
							$name_postfix="type_select";
					}else{ ?>
					
						<select name="<?php echo esc_attr($fieldData['fieldid'])?>_type"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_type"
						<?php

						if($pos===false) {?> readonly="readonly"
						<?php }
						if(!$enabled || ($pos===false)) { ?>
						disabled="disabled" <?php } ?>
						onChange="contactform_showoptionstextarea('<?php echo esc_attr($fieldData['fieldid'])?>');">
						<?php foreach( $this->get_master_fieldtypes() as $masterfieldtypes )
						{ ?>

							<option value="<?php echo esc_attr($masterfieldtypes['fieldtype']);?>"
							<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?>
								selected="selected" <?php }?>><?php echo esc_attr($masterfieldtypes['fieldtypeLabel']);?></option>
							<?php }?>

						</select>
					<?php }	?>
					
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
						<?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield"); ?>
						<?php if($pos===false){ ?>
						<?php if($fieldid=="telephonenumber"){ ?>
							<select name="<?php echo esc_attr($fieldData['fieldid'])?>_validation"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_validation">
							<option value="string"
							<?php if($fieldData['validation']=="string"){ ?>
								selected="selected" <?php } ?>>Number and String</option>
							<option value="number"
							<?php if($fieldData['validation']=="number"){ ?>
								selected="selected" <?php } ?>>Number</option>

							</select>
							<?php }else{ ?>
								<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_validation"
						name="<?php echo esc_attr($fieldData['fieldid'])?>_validation"
							<?php if($fieldid=="email"){ ?> value="email"
							<?php }else if($fieldid!="telephonenumber"){ ?> value="none"
							<?php }?>> <input <?php if(!$enabled) { ?> disabled="disabled"
							<?php } ?> size="6" readonly="readonly" type="text"
						id="<?php echo esc_attr($fieldData['fieldid']) ?>_validationhidden"
						name="<?php echo esc_attr($fieldData['fieldid']) ?>_validationhidden"
						<?php if($fieldid=="email"){ ?> value="Email Id"
							<?php } else if($fieldid!="telephonenumber"){ ?> value="None"
							<?php } ?>> 
							<?php }
					}else if($fieldData!="telephonenumber"){ ?>
						<select name="<?php echo esc_attr($fieldData['fieldid'])?>_validation"
						id="<?php echo esc_attr($fieldData['fieldid'])?>_validation"
						<?php if(!$enabled){ ?> disabled="disabled"
						<?php }
						if($pos===false) {?> readonly="readonly" <?php }?>
						<?php
						$fieldData['validation'] = (isset($fieldData['validation']) && !empty($fieldData['validation'])) ? $fieldData['validation'] : '';
						if( ($fieldData['type'] != 'text' && (strtolower($fieldData['validation']) == 'none' || strtolower($fieldData['validation']) == ''))) { ?>
						disabled="disabled" <?php }?>>
						<?php foreach( $this->get_master_validations() as $masterfieldtypes )
						{ ?>
							<option value="<?php echo esc_attr($masterfieldtypes['validation']);?>"
							<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
								selected="selected" <?php }?>>
								<?php echo esc_attr($masterfieldtypes['validationLabel']);?>
							</option>
							<?php }?>
						</select> 
					<?php } ?>
					</td>

					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
					if($pos===false){
						//echo "fieldname :- ".$fieldData['fieldname']."<br>";
						if($fieldData['fieldname']!="Lead Status" && $fieldData['fieldname']!="Lead Type" && $fieldData['fieldname']!="Lead Source" && $fieldData['fieldname']!="Lead Rank" && $fieldData['fieldname']!="Address Type" && $fieldData['fieldname']!="Tags")
						{
							echo "N/A";
							//Not a custom field. Dont show any thing
						}
						else if($fieldData['fieldname']=="Lead Status" || $fieldData['fieldname']=="Lead Type" || $fieldData['fieldname']=="Lead Source" || $fieldData['fieldname']=="Lead Rank" || $fieldData['fieldname']=="Address Type" || $fieldData['fieldname']=="Tags")
						{
							$getConfig=get_option('awp_contactforms');
							for($i=0;$i<count($getConfig);$i++)
							{
								if($getConfig[$i]['name'] == $selectedcontactform)
								{
									$getConfig[$i]['contact_config'] = (array_key_exists('contact_config',$getConfig[$i])) ? $getConfig[$i]['contact_config'] : [];
									$selectedConfigdata=$getConfig[$i]['contact_config'];

								}

							}
							if(_isCurl())
							{
								$selectedConfigName="";
								global $labelOptions;
								$labelOptions = array();
								$configType		=	$fieldData['fieldid'];
								$configTypeName	= $configDatas->$configType;
								// echo "<pre> configTypeName :-";print_r($configTypeName);echo  "</pre>";

								$selectedConfigdata['awp_'.$configType.'_selected'] = (array_key_exists('awp_'.$configType.'_selected',$selectedConfigdata)) ? $selectedConfigdata['awp_'.$configType.'_selected'] : '';
								$selectedConfig = $selectedConfigdata['awp_'.$configType.'_selected'];
								// echo "<pre> selectedConfigdata :-";print_r($selectedConfigdata);echo  "</pre>";
								
								if($configType=="labels"){
									$select = "multiple";
									$style = "overflow-x:auto;";
								}else{
									$select = "";
									$style = "";
								}
								echo '<select name="absp_contact_config_'.esc_attr($configType).'" style="width:100%;'.$style.'" id="'.esc_attr($configType).'_Id" '.$select.'>';
								if($configType=="leadSource" || $configType=="leadType" || $configType=="leadRank" ||$configType=="addressTypes"){
										echo '<option value="0">Select One</option>';
								}
								// if($configType=="labels"){
								// 	echo '<option value="0">Select</option>';
								// }
								for($i=0;$i<count($configTypeName);$i++){
									if($configType=="leadType"){
										$configtypeId= $configTypeName[$i]->opportunityTypeId;
										$configName	 = $configTypeName[$i]->opportunityTypeName;
									}else if($configType=="leadSource"){
										$configtypeId= $configTypeName[$i]->code;
										$configName	 = $configTypeName[$i]->name;
									}else if($configType=="addressTypes"){
										$configtypeId= $configTypeName[$i]->code;
										$configName	 = $configTypeName[$i]->name;
									}else if($configType=="labels"){
										$configtypeId= $configTypeName[$i]->labelId;
										$configName	 = $configTypeName[$i]->labelName;
										$labelOptions[$configtypeId] = $configName;
									}
									else{
										$configtypeId= $configTypeName[$i]->lookupCode;
										$configName	 = $configTypeName[$i]->meaning;
									}

									if(isset($selectedConfig) && isset($configtypeId) && $selectedConfig == $configtypeId){
										$selectedConfigName	 = isset($configTypeName[$i]->meaning)?$configTypeName[$i]->meaning:'';
										if($configType=="leadType"){
											$selectedConfigName = isset($configTypeName[$i]->opportunityTypeName)?$configTypeName[$i]->opportunityTypeName:'';
										}
										if($configType=="addressTypes"){
											$selectedConfigName = isset($configTypeName[$i]->name)?$configTypeName[$i]->name:'';
										}
									}	
									if(isset($selectedConfig) && isset($configtypeId) && str_contains($selectedConfig,$configtypeId)){
										$selectedLabels = [];
										if($configType=="labels"){
											foreach(explode(', ',$selectedConfig) as $selectedLabel){
												// $selectedLabel = isset($configTypeName[$i]->labelId)?$configTypeName[$i]->labelId:'';
												array_push($selectedLabels, $selectedLabel);
											}
										}
									}
									if($configType!="labels"){
										echo '<option '.esc_attr(selected($selectedConfig,$configtypeId)).' value="'.esc_attr($configtypeId).'" rel=/"'.esc_attr($configName).'/">'.esc_attr($configName).'</option>';
									}
									if($configType=="labels"){
										if(isset($selectedLabels) && (in_array($configtypeId, $selectedLabels))){
											// Selected values
											echo '<option '.esc_attr(selected($selectedLabels,$configtypeId)).' class="multi-select-highlight" value="'.esc_attr($configtypeId).'" rel=/"'.esc_attr($configName).'/">'.esc_attr($configName).'</option>';
										}
										else{
											// Not - Selected values
											echo '<option value="'.esc_attr($configtypeId).'" rel=/"'.esc_attr($configName).'/">'.esc_attr($configName).'</option>';
										}
									}
								}
								echo '</select>';
								if($configType=="labels"){
									$selectedTextValues = array();
									$selectedValues = array();
									foreach (explode(', ',$selectedConfig) as $value) {
										//echo "<pre> selectedConfig labels1 :-".$value."</pre>";
										if (isset($labelOptions[$value])) {
											$selectedTextValues[] = $labelOptions[$value];
											$selectedValues[] = $value;
										}
									}
									//echo "<pre>selectedTextValues :- ";print_r($selectedTextValues);echo "</pre>";
									//echo "<pre>selectedValues :- ";print_r($selectedValues);echo "</pre>";
									echo "<input type='hidden' id='labelTextValue' value='".implode(', ', $selectedTextValues)."' />";
									echo "<input type='hidden' id='labelValue' value='".implode(', ', $selectedValues)."' />";
									echo "<div class='labelTextValue'>Choosen Tag: ".implode(", ", $selectedTextValues).'</div>';
								}
								if($selectedConfigName=="" && $configType!="leadSource"){
									if (isset($configTypeName[0]) && is_object($configTypeName[0])){
										$configTypeName[0]->name = (isset($configTypeName[0]->name) && !empty($configTypeName[0]->name)) ? $configTypeName[0]->name : '';
										$selectedConfigName=$configTypeName[0]->name;
									}
								}

								//if($configType=="leadSource"){$selectedConfigName="Select One";}
								echo '<input id="'.esc_attr($configType).'Id" type="hidden" class="absp_hidden_'.esc_attr($configType).'" value="'.htmlspecialchars(esc_attr($selectedConfigName)).'" name="awp_'.esc_attr($configType).'"/>';
								if($configType == 'labels'){
									echo '<input id="'.esc_attr($configType).'Id1" type="hidden" class="absp_hidden_'.esc_attr($configType).'" value="'.htmlspecialchars(esc_attr($selectedConfigName)).'" name="awp_value_'.esc_attr($configType).'"/>';
								}

							}
							
						}
					}
					else if(($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox") || ($fieldData['type']=="multiselect")){
						if(empty($fieldData['options'])){
							$fieldData['options'] = "";
						}
						?>
							<textarea style="width: 190px;" <?php if(!$enabled){ ?>
							disabled="disabled" <?php } ?>
							id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
							name="<?php echo esc_attr($fieldData['fieldid'])?>_options">
							<?php echo esc_attr($fieldData['options']); ?>
						</textarea> 
					<?php }else { ?> 
							<textarea disabled="disabled"
							style="display: none; width: 190px;"
							id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
							name="<?php echo esc_attr($fieldData['fieldid'])?>_options"></textarea>
					<?php } ?>
					</td>
				</tr>
			<?php  } ?>

		</tbody>
	</table>
	<?php
	$addtional_custom = get_option('awp_addtional_custom');
	if(empty($addtional_custom))
	{
		$cnt_custom_filed = 6;
	}else {
		$cnt_custom_filed = 6 + count($addtional_custom);
	}
	?>

	<p>
		<a rel="<?php echo esc_attr($cnt_custom_filed); ?>" href="javascript:void(0);"
			id="addcustom_field" name="addcustom_field">+Add Another Custom Field</a>
	</p>

	<p class="submit">
		<input <?php echo esc_attr($disabledForm); ?> type="submit"
			name="awp_contactform_settings" id="awp_contactform_settings"
			class="button-primary"
			value="<?php esc_attr_e('Save Configuration') ?>" />
	</p>
</form>

</div>

	<?php
	}
	}

	/**
	 * Add contact form scripts and styles, only when short code is present in page/posts
	 */
	function check_for_shortcode($posts) {
		$found=awp_check_for_shortcode($posts,'[apptivocontactform');
		if ($found){
			// load styles and scripts
			//$this->loadscripts();
			//$this->loadstyles();
                    add_action('wp_footer', abwpExternalScripts);
		}
		return $posts;
	}

	/**
	 * Load the CSS files
	 */

	function loadstyles() {

	}
	/**
	 * Load the JS files
	 */
	function loadscripts() {
            // add_action('wp_footer', abwpExternalScripts);
	}
        
	function getAllCountryList(){
		$awp_services_obj=new AWPAPIServices();
		$countrylist = $awp_services_obj->getAllCountries();
		return $countrylist;
	}


	function getNewsletterCategory(){
		$awp_services_obj=new AWPAPIServices();
		$category = $awp_services_obj->getTargetListcategory();
		return $category;
	}
}

/*
 *  Save Contact Status, Contact Type and Contact Priority
 *
 */

function contactOptions($save){
	if(_isCurl())
	{
		$lead_status=array();
		$lead_type=array();
		$lead_source=array();
		$lead_rank=array();
        $address_type=array();
		$label = array();
		//$contactConfigData = getAllContactConfigData();
		$contactConfigData = getContactConfigData();
		//echo "<pre> contactConfigData :- ";print_r($contactConfigData);echo "</pre>";exit;
		
		if($contactConfigData == ''){
			echo '<script> 
					alert("'.AWP_SERVICE_ERROR_MESSAGE.'");
					window.location.href = "'.str_replace('awp_contactforms', 'awp_general', esc_attr($_SERVER["REQUEST_URI"])).'";'.
					'</script>'; 
		}
                
        if(isset($contactConfigData->leadStatuses)){
            foreach ($contactConfigData->leadStatuses as $leadStatus){
				if(($leadStatus->isEnabled =='Y')){
					array_push($lead_status, $leadStatus);
				}
            }
         } 
                
        if(isset($contactConfigData->addressTypes)){
            foreach ($contactConfigData->addressTypes as $addressTypes){
				if(($addressTypes->isEnabled =='Y')){
					array_push($address_type, $addressTypes);
				}
            }
        }
		
		if(isset($contactConfigData->leadTypes)){
            foreach ($contactConfigData->leadTypes as $leadType){
				if(($leadType->isEnabled =='Y')){
					array_push($lead_type, $leadType);
				}
            }
		}
        if(isset($contactConfigData->leadSources)){
            foreach ($contactConfigData->leadSources as $leadSource){
				if(($leadSource->isEnabled =='Y')){
					array_push($lead_source, $leadSource);
				}
            }    
		}		
		if(isset($contactConfigData->leadRanks)){
            foreach ($contactConfigData->leadRanks as $leadRank){
				if(($leadRank->isEnabled =='Y')){
					array_push($lead_rank, $leadRank);
				}
            }
		}
		$lead_tags = array();
		if(isset($contactConfigData->labels)){
            foreach ($contactConfigData->labels as $labelsval){

				$lead_tags[] = $labelsval;

				/*if($labelsval->version == 0){
					$labelObj = new stdClass();
					$labelObj->id = $labelsval->id;
					$labelObj->labelId = $labelsval->labelId;
					$labelObj->labelName = $labelsval->labelName;
					//$labelObj->objectLabelId = $labelsval->objectLabelId;
					$labelObj->objectId = $labelsval->objectId;
					$labelObj->customAttributes = array();
					$labelObj->labels =  array();
					$labelObj->removeLabels =  array();
					$labelObj->addresses =  array();
					$labelObj->removeAddresses =  array();
					$labelObj->phoneNumbers =  array();
					$labelObj->removePhoneNumbers =  array();
					$labelObj->removeEmailAddresses =  array();
					$labelObj->metaAttributeMap = (object) array();
					$labelObj->metaObjectMap = (object) array();					
					$labelObj->version = $labelsval->version;
					$labelObj->toObjectIds =  array();
					$lead_tags[] = $labelObj;
				}elseif($labelsval->version == 1){
					$labelObj = new stdClass();
					$labelObj->id = $labelsval->id;
					$labelObj->labelId = $labelsval->labelId;
					$labelObj->labelName = $labelsval->labelName;
					//$labelObj->objectLabelId = $labelsval->objectLabelId;
					// Check if objectId is not empty before adding it to the labelObj
					if(!empty($labelsval->objectId)){
						$labelObj->objectId = $labelsval->objectId;
					}
					$labelObj->firmId = $labelsval->firmId;
					$labelObj->agId = $labelsval->agId;
					$labelObj->version = $labelsval->version;
					$lead_tags[] = $labelObj;
				}*/
            }
		}
		//$lead_assignee  	= $contactConfigData->assigneesList;
		$assignees = getAllEmployeesAndTeams();
		$empAssignees = $assignees->employeeData;	
		foreach($empAssignees as $emp){
			$assigneeObj = new stdClass();
			$assigneeObj->assigneeName = $emp->fullName;
			$assigneeObj->assigneeObjectId = APPTIVO_EMPLOYEE_OBJECT_ID;
			$assigneeObj->assigneeObjectRefId = $emp->employeeId;
			$lead_assignee[] = $assigneeObj;
		}
		$teamAssignees = $assignees->teamData;
		foreach($teamAssignees as $team){
			$assigneeObj = new stdClass();
			$team->name = (isset($team->name) && !empty($team->name)) ? $team->name : '';
			$assigneeObj->assigneeName = $team->name;
			$assigneeObj->assigneeObjectId = APPTIVO_TEAM_OBJECT_ID;
			$assigneeObj->assigneeObjectRefId = $team->teamId;
			$lead_assignee[] = 	$assigneeObj;
		}
		
		$contact_config		= array("leadStatus"=>$lead_status,"addressTypes"=>$address_type,"leadType"=>$lead_type,"leadSource"=>$lead_source,"leadAssignee"=>$lead_assignee,"leadRank"=>$lead_rank,"labels"=>$lead_tags);
		$contact_configDatas	= json_encode($contact_config);
		$_SESSION['contact_config'] = $contact_configDatas;
	}
	if($save=='save'){
		check_option('awp_contact_configdata',$contact_configDatas);
	}

	return $contact_configDatas;
}


/*function getContactFirstConfigData($leadType,$leadSource,$leadStatus,$checkleadRank,$contactForm){
	$firstConfig	=	get_option("awp_contact_configdata");
	$firstConfig	=	json_decode($firstConfig);
	$getConfig = get_option('awp_contactforms');
	
	for($i=0;$i<count($getConfig);$i++)
	{
		if($getConfig[$i]['name']==$contactForm)
		{
			$formConfig=$getConfig[$i]['contact_config'];
		}
	}
	if($leadType=="0")
	{
		foreach ($firstConfig->leadType as $leadType)
		{
			if($formConfig["awp_leadType_selected"]==$leadType->opportunityTypeId)
			{
				echo '<input type="hidden" name="type_name" value="'.esc_attr($leadType->opportunityTypeName).'"/>';
				echo '<input type="hidden" id="type" name="type" value="'.esc_attr($leadType->opportunityTypeId).'"/>';
				break;
			}
			 
		}
	}
	elseif($leadSource=="0")
	{
		foreach ($firstConfig->leadSource as $leadSource)
		{
			if($formConfig["awp_leadSource_selected"]==$leadSource->lookupCode)
			{
				echo '<input type="hidden" name="source_name" value="'.esc_attr($leadSource->meaning).'"/>';
				echo '<input type="hidden" id="source" name="source" value="'.esc_attr($leadSource->lookupCode).'"/>';
				break;
			}

		}
	}
	elseif($leadStatus=="0")
	{

		foreach ($firstConfig->$leadStatus as $leadStatus)
		{
			if($formConfig["awp_leadStatus_selected"]==$leadStatus->lookupCode)
			{
				echo '<input type="hidden" name="status_name" value="'.esc_attr($leadStatus->meaning).'"/>';
				echo '<input type="hidden" id="status" name="status" value="'.esc_attr($leadStatus->lookupCode).'"/>';
				break;
			}

		}
	}
	elseif($leadRank=="0")
	{

		foreach ($firstConfig->$leadRank as $leadRank)
		{
			if($formConfig["awp_leadRank_selected"]==$leadRank->lookupCode)
			{
				echo '<input type="hidden" name="rank_name" value="'.esc_attr($leadRank->meaning).'"/>';
				echo '<input type="hidden" id="rank" name="rank" value="'.esc_attr($leadRank->lookupCode).'"/>';
				break;
			}

		}
	}
}*/

add_action("admin_footer", "apptivo_business_contact_assignee_validation");

function apptivo_business_contact_assignee_validation(){ ?>
<script type="text/javascript">
    jQuery(document).ready(function($){

		/*Handled for Vulnerability Script Code Injection */
		$("#newcontactformname,#awp_subscribe_to_newsletter").blur(function(event) { 
		var text = $(this).val().replace(/[^\da-zA-Z0-9 ]/g,'');
		$(this).val(text);
		return true;
		});

		$("#awp_contactform_submit_value").blur(function(event) { 
			var text = $(this).val().replace(/[^\da-zA-Z0-9/:. ]/g,'');
			$(this).val(text);
			return true;
		});

    	var selected_associates = jQuery('#awp_contact_associates').val();
    	if(selected_associates=='No Need'){
    		jQuery("#awp_contact_createassociate option[value='donot']").attr("selected","selected");
    		jQuery("#awp_contact_createassociate").attr("disabled", "disabled");
    	}
        jQuery("#awp_contact_associates").change(function(){
        	var selected_associates = jQuery('#awp_contact_associates').val();
        	if(selected_associates=='No Need'){
        		jQuery("#awp_contact_createassociate option[value='donot']").attr("selected","selected");
        		jQuery("#awp_contact_createassociate").attr("disabled", "disabled");
        	}
        	if(selected_associates=='Customer'){
        		jQuery("#awp_contact_createassociate option[value='customer']").attr("selected","selected");
        		jQuery("#awp_contact_createassociate").removeAttr("disabled");
        	}
      });

        jQuery("#leadStatus_Id").change(function(){
        	jQuery("#leadStatusId").val(jQuery("#leadStatus_Id option:selected").text());
        });
        jQuery("#leadSource_Id").change(function(){
        	jQuery("#leadSourceId").val(jQuery("#leadSource_Id option:selected").text());
        });
        jQuery("#leadType_Id").change(function(){
        	jQuery("#leadTypeId").val(jQuery("#leadType_Id option:selected").text());
        });
        jQuery("#leadRank_Id").change(function(){
        	jQuery("#leadRankId").val(jQuery("#leadRank_Id option:selected").text());
        });
        jQuery("#addressTypes_Id").change(function(){
        	jQuery("#addressTypesId").val(jQuery("#addressTypes_Id option:selected").text());
        });

		// Initial Select Assignee [ Employee/ Team ]
		if(jQuery('#awp_contact_select_assignee').val() == 'team'){
			jQuery("#awp_contact_select_assignee_employee").hide();
			jQuery("#awp_contact_select_assignee_team").show();
		}else{
			jQuery("#awp_contact_select_assignee_employee").show();
			jQuery("#awp_contact_select_assignee_team").hide();
		}

		// Changing Select Assignee [ Employee/ Team ]
    	jQuery("#awp_contact_select_assignee").change(function(){        	
			var selected_atype = jQuery('#awp_contact_select_assignee').val();
			jQuery(".awp_contact_select_assignee").hide();
			jQuery("#awp_contact_select_assignee_"+selected_atype).show();
			if(selected_atype == 'team'){
				jQuery("#select_assignee_name_val").val(jQuery("#awp_contact_select_assignee_team option:selected").text());	
			}
			else{
				jQuery("#select_assignee_name_val").val(jQuery("#awp_contact_select_assignee_employee option:selected").text());		
			}					
		});
		jQuery("#labels_Id").change(function(){
			// Remove the class from all options
			jQuery('#labels_Id option').removeClass('multi-select-highlight');

			// Add the class to the selected options
			jQuery('#labels_Id option:selected').addClass('multi-select-highlight');

			var selectedOptions = jQuery("#labels_Id option:selected");
			var selectedText = [];
			selectedOptions.each(function(){
				selectedText.push(jQuery(this).text());
			});
			jQuery("#labelsId1").val(selectedText.join(", "));
			
			var selectedValText = [];
			selectedOptions.each(function(){
				selectedValText.push(jQuery(this).val());
			});
			jQuery("#labelsId").val(selectedValText.join(", "));
		});
    });

	// to show and hide the div of multi-select
	jQuery(document).ready(function(){
		// Initially check the value and show/hide the div accordingly
		checkLabelValue();

		// Check the value and show/hide the div when the input changes
		jQuery('#labelTextValue').change(function(){
			checkLabelValue();
		});
	});

	function checkLabelValue(){

		// in abscence of #labelTextValue
		if (jQuery('#labelTextValue').length === 0){
			return;
		}

		// Get the value of the hidden input
		var labelTextValue = jQuery('#labelTextValue').val().trim();

		// Show or hide the div based on the value
		if(labelTextValue.length != 0){
			jQuery('.labelTextValue').text('Choosen Tag: '+labelTextValue).show();
		}else{
			jQuery('.labelTextValue').hide();
		}
	}

	jQuery(document).ready(function(){
		var assignlabelText = jQuery(".labelTextValue").text();

		if (assignlabelText.trim() != '') {
			var assignLabelTextValue = jQuery("#labelTextValue").val();
			var assignLabelText1 = jQuery("#labelValue").val();
			
			jQuery("#labelsId").val(assignLabelText1); // Id Values
			jQuery("#labelsId1").val(assignLabelTextValue); // Text Values
		}
	});

    </script>
	<style>
		/*.multi-select-highlight{background-color: -internal-light-dark(rgb(206, 206, 206), rgb(84, 84, 84));}*/
		.multi-select-highlight{background-color: yellow;}
    </style>
<?php }

function enqueue_custom_jquery() {
	// Enqueue jQuery from WordPress core
	wp_enqueue_script('jquery');

	// Register your custom script that depends on jQuery
	wp_register_script('apptivo_business_plugin', AWP_PLUGIN_BASEURL . '/assets/js/apptivo-business-plugin.js', array('jquery'), '1.0', true);

	// Enqueue your custom script
	wp_enqueue_script('apptivo_business_plugin');
}

function custom_success_script($contactform_name) {
	// Only enqueue the inline script if the form was submitted successfully
	echo '<script type="text/javascript">
		jQuery(document).ready(function($){
			document.getElementById("success_'.esc_attr($contactform_name).'").scrollIntoView();

			setTimeout(function(){
				$("#success_'.esc_attr($contactform_name).'").fadeOut("slow");
			}, 7000);
		});
	</script>';
}
?>
