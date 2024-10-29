<?php
/**
 * Apptivo Cases Apps Plugin
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_ASSETS_DIR.'/captcha/simple-captcha/simple-captcha.php';
require_once AWP_APPTIVO_DIR.'ConfigDataUtil.php';
require_once AWP_APPTIVO_DIR.'DataRetrieval.php';
class AWP_Cases extends AWP_Base
{
	function &instance()
	{
		static $instances = array();
		if (!isset($instances[0])){
			$class = __CLASS__;
			$instances[0] =  new $class();
		}
		return $instances[0];

	}

	function get_case_settings($formname){
		$formExists="";
		$cases_forms=array();
		$caseform=array();
		$formname=trim($formname);
		$cases_forms=get_option('awp_casesforms');
		if($formname=="")
		$formExists="";
		else if(!empty($cases_forms))
		$formExists = awp_recursive_array_search($cases_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$caseform=$cases_forms[$formExists];
		}
		return $caseform;
	}

	private $_plugin_activated = false;
	private $fields;
	private $validations;
	private $fieldtypes;

	function __construct(){
		$this->_plugin_activated = false;
		$settings=array();
		$this->_plugin_activated=false;
		$settings=get_option("awp_plugins");
		if(get_option("awp_plugins")!=="false"){
			if($settings["cases"])
			$this->_plugin_activated=true;
		}
		 
		$this->fields = array(
			array('fieldid' => 'subject','fieldname' => 'Subject','defaulttext' => 'Subject','must_require'=>1,'must'=>1,'showorder' => '1','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'description','fieldname' => 'Description','defaulttext' => 'Description','must_require'=>0,'must'=>0,'showorder' => '2','validation' => 'textarea','fieldtype' => 'textarea'),
			array('fieldid' => 'status','fieldname' => 'Status','defaulttext' => 'Status','must_require'=>1,'must'=>0,'showorder' => '3','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'priority','fieldname' => 'Priority','defaulttext' => 'Priority','must_require'=>1,'must'=>0,'showorder' => '4','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'type','fieldname' => 'Type','defaulttext' => 'Type','must_require'=>1,'must'=>0,'showorder' => '9','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'firstname','fieldname' => 'First Name','defaulttext' => 'First Name','must_require'=>0,'must'=>0,'showorder' => '5','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','must_require'=>1,'must'=>1,'showorder' => '6','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '7','must_require'=>1,'must'=>1,'validation' => 'email','fieldtype' => 'text'),
			array('fieldid' => 'phone','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','must_require'=>0,'must'=>0,'showorder' => '8','validation' => 'phonenumber','fieldtype' => 'text'),
			array('fieldid' => 'captcha','fieldname' => 'Captcha','defaulttext' => 'Captcha','must_require'=>1,'must'=>0,'showorder' => '10','validation' => 'text','fieldtype' => 'captcha'),
			array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '11','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '12','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '13','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '14','validation' => '','fieldtype' => 'radio'),
			array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '15','validation' => '','fieldtype' => 'checkbox')
		);

		$this->validations = array(
			array('validationLabel' => 'None','validation' => 'none'),
			array('validationLabel' => 'Email ID','validation' => 'email'),
			array('validationLabel' => 'Number','validation' => 'number')
		);

		$this->fieldtypes = array(
			array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
			array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
			array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
			array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
			array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
		);

	}

	/**
	 * Runs plugin
	 */
	function run(){
		if($this->_plugin_activated){
			add_shortcode('apptivocases',array(&$this,'apptivo_business_casesnew'));
			add_shortcode( 'apptivo_cases', array( &$this, 'apptivo_business_casesnew' ) );	// To View Old Plugin form

			$oldForm	= get_option('absp_cases_form_fields');
			if($oldForm!="")
			{
				$addOldform= array("name"=>"CasesFormOld");
				$form= array_merge($addOldform,$oldForm);
				$newform= get_option("awp_casesforms");
				foreach ($newform as $key => $val){
					if ($val['name'] === 'CasesFormOld') {
						$formpresent="present";
					}
				}
				if($newform!=""){
					$countform= count($newform);
					$form= array($form);
					$updatedForm= array_merge($newform,$form);
				}else{
					$updatedForm= array($form);
				}
				if($oldForm!="" && $formpresent==""){
					update_option("awp_casesforms",$updatedForm);
					//  delete_option("absp_cases_form_fields"); To delete old cases formfield data
				}
			}
		}
	}



	//validate_load_script
	function validate_load_script(){
		wp_enqueue_script("jquery");
		wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',false,false,true);
		wp_print_scripts('jquery_validation');

	}


	function get_cases_form_fields($formname){
		$caseform=array();
		$cases_forms=array();
		$formexist="";
		$casesformdetails=array();
		$formname=trim($formname);
		$formLabelName='';

		$cases_forms=get_option("awp_casesforms");
		$formexists=awp_recursive_array_search($cases_forms,$formname,'name');

		if(trim($formexists)!=""){
            $customfields="";
			$caseform=$cases_forms[$formexists];

			$formFields = $caseform['fields'];

			foreach($formFields as $fields){
				$fieldid=$fields['fieldid'];
				$pos=strpos($fieldid, "customfield");
				if($pos===false){}else{
					if(isset($_POST[$fieldid]) || isset($customfieldVal)){
			
						if( is_array($_POST[$fieldid])){

							$keys = array_keys($_POST[$fieldid]);
							$keys = array_map('sanitize_key', $keys);

							$values = array_values($_POST[$fieldid]);
							$values = array_map('sanitize_text_field', $values);

							$customfieldarray = array_combine($keys, $values);
							$customfieldVal = implode(",", (array)$customfieldarray);
						}
						else if(!is_array($_POST[$fieldid])){
							$customfieldVal = sanitize_text_field($_POST[$fieldid]);
						}

						if($customfieldVal != ''){
							$customfields .= "<br/><b>".$fields['showtext']."</b>:".stripslashes($customfieldVal);
							$customfieldArr[$formLabelName] = stripslashes($customfieldVal);
						}
					}

				}
			}
		 if(isset($customfields)){
		 $customfields .= "<br/><b>Requested IP</b>:".stripslashes(get_RealIpAddr());
		 }
		 if(!empty($customfields)){
		 	$parent1NoteId="";
		 	$awp_services_obj=new AWPAPIServices();
		 	$parent1details = nl2br($customfields);
		 	$noteDetails = $awp_services_obj->notes('Custom Fields',$parent1details,$parent1NoteId);
		 }
		 $string = '<script';
		 
		 $case = array();
		 $case['firmid'] = null;
		 $case['caseid'] = null;
		 if(isset($_POST['firstname'])){
		 $case['firstName'] = sanitize_text_field(trim($_POST['firstname']));
		 }
		 if(isset($_POST['lastname'])){
		 $case['lastName'] = sanitize_text_field(trim($_POST['lastname']));
		 }
		 if(isset($_POST['email'])){
		 $case['emailId'] = sanitize_email(trim($_POST['email']));
		 }
		 if(isset($_POST['phone'])){
		 $case['phoneNumber']=sanitize_text_field(trim($_POST['phone']));
		 }
		 $case['comments'] = NULL;
		 if(isset($_POST['description'])){
		 $case['description'] =  nl2br(trim(sanitize_text_field($_POST['description'])));
		 }
		 if(isset($_POST['type'])){
		 $case['type'] = sanitize_text_field(trim($_POST['type']));
		 }
		 if(isset($_POST['status'])){
		 $case['status'] = sanitize_text_field(trim($_POST['status']));
		 }
		 if(isset($_POST['priority'])){
		 $case['priority'] = sanitize_text_field(trim($_POST['priority']));
		 }
		 $case['account'] = NULL;
		 $case['productName'] = NULL;
		 if(isset($_POST['subject'])){
		 $case['subject'] = sanitize_text_field(trim($_POST['subject']));
		 }
		 $case['responseString']=null;
		 $case['noteDetails']=$noteDetails;
		 $case['userIdStr']=NULL;
		 
		 return $caseform;
		}

	}

	function apptivo_business_casesnew($atts){
		ob_start();
		$cases_fields_properties  = get_option('awp_casesforms');
		$stdcustomfields='';
		$value_present = false;
		if($atts==""){
			$atts=array("name" => "CasesFormOld");
		}

		extract(shortcode_atts(array('name'=>  ''), $atts));
		$formname=trim($name);
		$case_form=$this->get_cases_form_fields($formname);
		$cases_width_size=$case_form["properties"]["cases_width_type"];
		if(isset($_POST['awp_caseformname'])){
			$submitformname=sanitize_text_field($_POST['awp_caseformname']);
		}

		$success_message="";
		if( isset($_POST["awp_casesforms_submit"]) && $submitformname==$formname ){

			$customfields = '';
			$formFields = $case_form['fields'];
			if(get_option ('apptivo_business_recaptcha_mode')=="yes"){
				if((isset($_POST["simple_captcha"]) && empty($_POST["simple_captcha"]))){
					$value_present = true;
					$captcha_error = awp_messagelist("v3recaptcha_error");
				}
				if((isset($_POST["recaptcha_token"]) && empty($_POST["recaptcha_token"]))){
					$value_present = true;
					$captcha_error = awp_messagelist("v3recaptcha_error");
				}else{
					$option=get_option('apptivo_business_recaptcha_settings');
					$option=json_decode($option);
					if(empty($option->recaptcha_publickey) || empty($option->recaptcha_privatekey)){
						$value_present = true;
						$captcha_error = awp_messagelist("recaptcha_error");
					}
				}
			}
			if(!empty($_POST["simple_captcha"])){
				$awp_simple_captcha_challenge=sanitize_text_field($_POST['awp_simple_captcha_challenge']);
				if(isset($awp_simple_captcha_challenge)){
					$captcha_instance = new AWPSimpleCaptcha();
					$response = $captcha_instance->check($awp_simple_captcha_challenge, sanitize_text_field($_POST["simple_captcha"]));
					$captcha_instance->remove( $awp_simple_captcha_challenge );

					if($response != 1){
						$value_present = true;
						$captcha_error = awp_messagelist("recaptcha_error");
					}else{
						$captcha_error="";
						$success_message="";
					}
				}else{
					$captcha_instance = new AWPSimpleCaptcha();
					$captcha_instance->remove( $awp_simple_captcha_challenge );
					$captch_error = awp_messagelist("recaptcha_error");
				}
			}elseif (!empty($_POST["recaptcha_token"])){
				$captcha_error="";
				$response_field =   sanitize_text_field($_POST["recaptcha_token"]);
				$option=get_option('apptivo_business_recaptcha_settings');
				$option=json_decode($option);
				$private_key    =   $option->recaptcha_privatekey;
				$response =    captchaValidation($private_key, $response_field);
				if($response != 1){
					$value_present = true;
					$captcha_error = awp_messagelist("recaptcha_error");
				}else{
					$captcha_error="";
					$success_message="";
				}
			}

			foreach($formFields as $fields):
				if($fields['fieldid']=="firstname"):
					$firstName=sanitize_text_field(trim($_POST['firstname']));
					if($firstName!=''){
						$stdcustomfields.="<br/><b>".$fields['showtext']."</b>:".$firstName;
					}
				endif;

				if($fields['fieldid']=="lastname"):
					$lastName=sanitize_text_field(trim($_POST['lastname']));
					$stdcustomfields.="<br/><b>".$fields['showtext']."</b>:".$lastName;
				endif;

				if($fields['fieldid']=="phone"):
					$phone=sanitize_text_field(trim($_POST['phone']));
					if($phone!=''){
						$stdcustomfields.="<br/><b>".$fields['showtext']."</b>:".$phone;
					}
				endif;

				$fieldid=$fields['fieldid'];
				$pos=strpos($fieldid, "customfield");

				if($pos===false):
				else:
					if(is_array($_POST[$fieldid])){
						$keys = array_keys($_POST[$fieldid]);
						$keys = array_map('sanitize_key', $keys);
						$values = array_values($_POST[$fieldid]);
						$values = array_map('sanitize_text_field', $values);
						$customfieldarray = array_combine($keys, $values);

						$formLabelName = $fields['showtext'];
						$customfieldArr[$formLabelName] = $customfieldarray;
						$customfieldVal = stripslashes(implode(",", $customfieldarray));
					}else if(!is_array($_POST[$fieldid])){
						$customfieldVal = sanitize_text_field($_POST[$fieldid]);
					}
					if($customfieldVal != ''){
						$formLabelName = $fields['showtext'];
						$customfieldArr[$formLabelName] = stripslashes($customfieldVal);
						$customfields .= "<br/><b>".$fields['showtext']."</b>:".stripslashes($customfieldVal);
					}
				endif;
			endforeach;
			$stdcustomfields .= "<br/><b>Requested IP</b>:".stripslashes(get_RealIpAddr());
		
			$_SESSION['AddradioMismatchValues'] = '';
			$_SESSION['AddselectMismatchValues'] = '';
			$_SESSION['AddcheckMismatchValues'] = '';
			//Custom Field mapping with Apptivo Leads Config data
			$apptivoArr = array();
			$unmatchFields = array();
			$customAttributes = array();
			$configObj = new configData();
			$sections = $configObj->appConfigData(APPTIVO_CASE_V6_API,APPTIVO_CASES_OBJECT_ID);
			foreach($sections as $section){
				$sectionName = $section->getSecLabel();
				$attributes = $section->getAttributeList();
				foreach($attributes as $attribute){
					$labelName = $attribute->getAttributeLabel();
 					$fieldType = $attribute->getAttributeType();
					if($fieldType == 'Custom'){	
						$customfieldArr = (!empty($customfieldArr)) ? $customfieldArr : [];
						if(array_key_exists($labelName, $customfieldArr)){
							$apptivoArr[] = $labelName;
							$dataRetriObj = new DataRetrieval();
							$cust = $dataRetriObj->customAttribute($customAttributes, $sectionName, $labelName,$attribute, $customfieldArr);
							if(!empty($cust)){
								$customAttributes[] = $cust;
							}
						}
					}
				}
			}
			
						
			// Creating note text for unmatched fields
			$Addcustomfields = '';
			if(isset($customfieldArr)){
				foreach($customfieldArr as $customLabelName => $customValue){
					if(!in_array($customLabelName, $apptivoArr)){
						$Addcustomfields .= "<b>".$customLabelName."</b>:".trim($customValue)."<br/>";
					}
				}
			}
			//copying selected mismatched values to notes tab
			$AddcheckMismatchValues = '';
			if(isset($_SESSION['AddcheckMismatchValues']) && (is_array($_SESSION['AddcheckMismatchValues']) || is_object($_SESSION['AddcheckMismatchValues']))){
				foreach($_SESSION['AddcheckMismatchValues'] as $checkLablename => $checkval){
					foreach($checkval as $values){
						$AddcheckMismatchValues .= "<b>".$checkLablename."</b>:".trim($values)."<br/>";
					}
				}
			}
			$customfields = $stdcustomfields.'<br>'.$Addcustomfields.'<br>'.$_SESSION['AddradioMismatchValues'].'<br>'.$_SESSION['AddselectMismatchValues'].'<br>'.$AddcheckMismatchValues;
			
			$_SESSION['AddradioMismatchValues'] = '';
			$_SESSION['AddselectMismatchValues'] = '';
			$_SESSION['AddcheckMismatchValues'] = '';
			if(!empty($customfields)){
				$parent1NoteId="";
				$parent1details = nl2br($customfields);
				$awp_services_obj=new AWPAPIServices();
				$noteDetails = $awp_services_obj->notes('Custom Fields',$parent1details,$parent1NoteId);
			}

			$case = array();
			$case['firmid'] = null;
			$case['caseid'] = null;
			if(isset($_POST['firstname'])){
				$case['firstName'] = sanitize_text_field(trim($_POST['firstname']));
			}
			$case['lastName'] = sanitize_text_field(trim($_POST['lastname']));
			$case['emailId'] = sanitize_text_field(trim($_POST['email']));
			if(isset($_POST['phone'])){
				$case['phoneNumber']=sanitize_text_field(trim($_POST['phone']));
			}
			$case['comments'] = null;
			if(isset($_POST['description'])){
				$case['description'] =  nl2br(trim(sanitize_text_field($_POST['description'])));
			}
			$case['type'] = sanitize_text_field(trim($_POST['type']));
			$case['type_name'] = sanitize_text_field(trim($_POST['type_name']));

			$status_name = isset($_POST['status_name']) ? $_POST['status_name'] : '';
			$case['status'] = sanitize_text_field(trim($status_name));
			$status = isset($_POST['status']) ? $_POST['status'] : '';
			$case['status_name'] = sanitize_text_field(trim($status));

			//$case['status'] = sanitize_text_field(trim($_POST['status_name']));
			//$case['status_name']=sanitize_text_field(trim($_POST['status']));
			$case['priority'] = sanitize_text_field(trim($_POST['priority']));
			$case['priority_name']=sanitize_text_field(trim($_POST['priority_name']));
			$case['account'] = NULL;
			$case['productName'] = NULL;
			$case['subject'] = sanitize_text_field(trim($_POST['subject']));
			$case['responseString']=null;
			$case['noteDetails']=$noteDetails;
			$case['userIdStr']=null;
			/* create an array for method inputs */
		
			if(isset($case['firstName'])){
				$firstName=$case['firstName'];
			}
			if(isset($case['lastName'])){
				$lastName=$case['lastName'];
			}
			if(isset($case['phoneNumber'])){
				$phoneNumber=$case['phoneNumber'];
			}
			$caseStatus= $case['status'];
			$caseStatusId= $case['status_name'];
			$caseType  = $case['type_name'];
			$caseTypeId  = $case['type'];
			$casePriority= $case['priority_name'];
			$casePriorityId= $case['priority'];
			$emailId=$case['emailId'];
			$caseSummary= $case['subject'];
			$caseDescription="";
			if(isset($case['description'])){
				$caseDescription= $case['description'];
			}
			$contactAccountName="";
			$customerAccountName="";
			$caseNumber	=	'Auto generated number';
			if(strlen(trim($case['lastName']))==0 || strlen(trim($emailId))==0 || !filter_var($emailId, FILTER_VALIDATE_EMAIL)){
				if(empty($captcha_error))
					echo awp_messagelist('no_redirection');
			}
			elseif(empty ($captcha_error)){
			
				/* check manually and update the values */
				$submit_form_name = sanitize_text_field($_POST['awp_caseformname']);
				$case_formvalues =$this->get_cases_form_fields($submit_form_name);
				if($caseStatus=="" &&  $caseStatusId==""){$caseStatus=$case_formvalues["properties"]["case_status"];$caseStatusId=$case_formvalues["cases_config"]["awp_caseStatus_selected"];}
				if($casePriority=="" &&  $casePriorityId==""){$casePriority=$case_formvalues["properties"]["case_priority"];$casePriorityId=$case_formvalues["cases_config"]["awp_casePriority_selected"];}
				if($caseType=="" &&  $caseTypeId==""){$caseType=$case_formvalues["properties"]["case_type"];$caseTypeId=$case_formvalues["cases_config"]["awp_caseType_selected"];}

				$assigneeName		=   trim($case_formvalues["properties"]["case_assignee_name"]);
				$assigneeObjId		=	($case_formvalues["properties"]["case_assignee_type"] == 'team') ? APPTIVO_TEAM_OBJECT_ID : APPTIVO_EMPLOYEE_OBJECT_ID;
				$assigneeObjRefId	=	$case_formvalues["properties"]["case_assignee_type_id"];
				$caseAssociates 	=  	trim($case_formvalues["properties"]["case_associates"]);
				$createAssociates 	= 	trim($case_formvalues["properties"]["case_create_associates"]);

				/* Check wheather Team exist or not */
				if($assigneeName == 'No Team'){
					$assigneeName = "";
					$assigneeObjId = "";
					$assigneeObjRefId = "";
				}
				if($caseAssociates != "No Need"){
					$associates=$awp_services_obj->awpCaseAssocciates($emailId,$caseAssociates);
				}
				$contactAccountId = $contactAccountName = $customerAccountId = $customerAccountName ="";
				$associates = (!empty($associates)) ? $associates : [];
				if(count($associates) != ""){
					$contactAccountId = isset($associates["caseContactId"]) ? $associates["caseContactId"] : '';
					$contactAccountName = isset($associates["caseContact"]) ? $associates["caseContact"] : '';
					if(isset($associates["caseCustomerId"])){
						$customerAccountId	= $associates["caseCustomerId"];
					}
					if(isset($associates["caseCustomer"])){
						$customerAccountName = $associates["caseCustomer"];
					}
				}

				if($contactAccountId == "" && $createAssociates=="contact"){ 
					if($caseAssociates == "Both" || $caseAssociates == "Contact" || $caseAssociates == "Customer"){
						$createContactResponse=$awp_services_obj->createContact($firstName,$lastName,$assigneeName,$assigneeObjRefId,$assigneeObjId,$phoneNumber,$emailId);
						$contactAccountId= $createContactResponse['leadContactId'];
						$contactAccountName= $createContactResponse['leadContact'];
					}
				}

				if($customerAccountId =="" && $createAssociates == "customer")
				{
					if($caseAssociates =="Both" || $caseAssociates == "Customer" || $caseAssociates=="Contact")
					{
						$createCustomerResponse=$awp_services_obj->createCustomer($lastName,$assigneeName,$assigneeObjId,$assigneeObjRefId,$phoneNumber,$emailId);
						$customerAccountId=$createCustomerResponse['leadCustomerId'];
						$customerAccountName=$createCustomerResponse['leadCustomer'];
					}
				}

				$verification = check_blockip();
				if($verification){
					$success_message= awp_messagelist('IP_banned');
				}else{
					if($caseStatusId == ''){
						$oldCaseValue = json_decode(get_option('awp_cases_status'));
						$caseStatus = $oldCaseValue[0]->meaning;
						$caseStatusId = $oldCaseValue[0]->lookupId;
					}
                     
					if(!_isCurl()){
						$params = array (
							"arg0" => APPTIVO_BUSINESS_API_KEY,  
							"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
							"arg2" => $case,             
						);
						$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createCase',$params);
						//Custom success Message.
						$properties = $case_form['properties'];
						$success_message = $properties['confmsg'];
					}else{
						$awp_services_obj = new AWPAPIServices();
						$createCaseResponse= $awp_services_obj->createCases($caseNumber,$caseStatus,$caseStatusId,html_entity_decode($caseType),$caseTypeId,html_entity_decode($casePriority),$casePriorityId,$assigneeName,$assigneeObjId,$assigneeObjRefId,$caseSummary,$caseDescription,$customerAccountName,$customerAccountId,$contactAccountName,$contactAccountId,$emailId,$customAttributes);
						$caseId	= $createCaseResponse->csCase->caseId;
						$caseNumber	= $createCaseResponse->csCase->caseNumber;

						if($noteDetails!="" && $caseId !=""){
							$noteText=$noteDetails->noteText;
							$createNotesResponse=$awp_services_obj->saveNotes(APPTIVO_CASES_OBJECT_ID,$caseId,$caseNumber,$noteText);
						}
					}

					if($caseId !=''){
						$properties = $case_form['properties'];
						$success_message = $properties['confmsg'];
						if($success_message == ""){
							$success_message="Case Submitted Successfully";
						}
					}else{
						$success_message="<label class='error'>Please try again later</label><br />";
					}
		 	
					if(isset($response)){
						if(isset($response->return->statusCode) && $response->return->statusCode == 1000){
							if(strlen(trim($success_message)) != 0){
								$response->return->successMessage = $success_message;
							}else{
								$response->return->successMessage = $response->return->responseString;
							}
						}
					}
				}
			}
		}

	// Check if $properties is set and is an array
	if( isset($properties) && is_array($properties) ){
		// Check if the 'confirm_msg_page' key exists in the $properties array
		if( strlen(trim($success_message)) != 0 && isset($properties['confirm_msg_page']) && $properties['confirm_msg_page'] == 'other' ){
			$location = get_permalink($properties['confirm_msg_pageid']);
			wp_safe_redirect($location);
		}
	}


		if ($case_form){
			$cases_properties = $case_form['properties'];
			add_action('wp_footer', "abwpExternalScripts",1);

			$form_fields=$case_form['fields']; //Cases Fields
			usort($form_fields, "awp_sort_by_order");

			$cases_fields = array();
			$form_properties=$case_form['properties'];//Case Properties
			$form_fields[] = array("fieldid"=>"status");
			if(!_isCurl()){
				foreach( $form_fields as $FormFields){
					if($FormFields['fieldid'] == 'status'){
						$FormFields['options'] = 'NEW';
					}else if($FormFields['fieldid'] == 'type'){
						//Don't change
						$FormFields['options'] = 'Product Questions
						Technical Issues
						Product Purchases
						Partnership Opportunities
						Feature Request
						Feedback
						Report a problem
						Other';                            
					}else if($FormFields['fieldid'] == 'priority'){
						//Don't change
						$FormFields['options'] = 'High
						Low
						Medium'; 
					}
					$push_fields = true;
					if( $FormFields['type'] == 'select'  || $FormFields['type'] == 'radio' || $FormFields['type'] == 'checkbox' ){
						if(trim($FormFields['options']) == '' ){
							$push_fields = FALSE;
						}
					}

					if($push_fields){
						array_push($cases_fields,$FormFields);
					}
				}
			}else{
				$casesConfig  = get_option("awp_cases_configdata");
				$casesConfigDatas  = json_decode($casesConfig);
				foreach( $form_fields as $FormFields){
					if($FormFields['fieldid'] == 'status'){
							if(isset ($casesConfigDatas->caseStatus)){
								foreach ($casesConfigDatas->caseStatus as $casestatus){
									$status_type[]= $casestatus->meaning;
									$status_type_value[]=$casestatus->lookupId;
								}
													if(isset($status_type)){
														$formnames =implode("\n", $status_type);
													}
													if(isset($status_type_value)){
														$formvalues =implode("\n", $status_type_value);
													}
								$FormFields['options'] = $formnames;
								$FormFields['value']=$formvalues;
							}

						}else if($FormFields['fieldid'] == 'type'){
							//Don't change
							if(isset ($casesConfigDatas->caseType)){
								foreach ($casesConfigDatas->caseType as $casetype)
								{
									$type[]= $casetype->meaning;
									$type_value[]=$casetype->lookupId;
								}
													if(isset($type)){
														$formnames =implode("\n", $type);
													}
													if(isset($type_value)){
														$formvalues =implode("\n", $type_value);
													}
								$FormFields['options'] = $formnames;
								$FormFields['value']=$formvalues;
							}
						}else if($FormFields['fieldid'] == 'priority'){
							if(isset ($casesConfigDatas->casePriority)){
								foreach ($casesConfigDatas->casePriority as $casepriority)
								{
									$type[]= $casepriority->meaning;
									$type_value[]=$casepriority->lookupId;
								}
													if(isset($type)){
														$formnames =implode("\n", $type);
													}
													if(isset($type_value)){
														$formvalues =implode("\n", $type_value);
													}					
								$FormFields['options'] = $formnames;
								$FormFields['value']=$formvalues;
								unset($type);
								unset($type_value);
							}
						}
						$push_fields = true;
						if(isset($FormFields['type'])){
							if( $FormFields['type'] == 'select'  || $FormFields['type'] == 'radio' || $FormFields['type'] == 'checkbox' ){
								if(!is_array($form_fields)){
									if(trim($FormFields['options']) == '' ){
										$push_fields = FALSE;
									}
								}
							}
						}
						if($push_fields){
							array_push($cases_fields,$FormFields);
						}

				}
			}
			$template_type = $form_properties['tmpltype'];
			$template_layout = $form_properties['layout'];
			if($template_type=="awp_plugin_template") :
				$templatefile = AWP_CASES_TEMPLATEPATH."/".$template_layout; // Plugin templates
			else :
				$templatefile=TEMPLATEPATH."/cases/".$template_layout; // theme templates
			endif;

			//ob_start();
			//Cusom Css
			
			if( trim($form_properties['css']) != ''){
				echo '<style type="text/css">'.trim($form_properties['css']).'</style>';
			}
			//Include Template
			include $templatefile;
			$content = ob_get_clean();
			return $content;
		}else{
			echo awp_messagelist('casesform-display-page');
		}
	}

	function settings(){
		//Theme Templates
		$themetemplates = get_awpTemplates(TEMPLATEPATH.'/cases','Plugin');
		$plugintemplates=$this->get_plugin_templates();
		arsort($plugintemplates);

		if(isset($_POST['awp_cases_settings']) && !empty($_POST['awp_cases_settings'])){
			caseOptions('save');
			$casesConfigDetails	=array("awp_casePriority_selected"=>sanitize_text_field($_POST['absp_cases_config_casePriority']),"awp_caseType_selected"=>sanitize_text_field($_POST['absp_cases_config_caseType']),"awp_caseStatus_selected"=>sanitize_text_field($_POST['absp_cases_config_caseStatus']));
			$newformname=sanitize_text_field($_POST['awp_cases_name']);
			//Cases Form Propertieds.

			//template Type& Template Layout
			if(sanitize_text_field($_POST['awp_cases_templatetype'])=="awp_plugin_template"){
				$templatelayout=sanitize_text_field($_POST['awp_cases_plugintemplatelayout']);
			}else{
				$templatelayout=sanitize_text_field($_POST['awp_cases_themetemplatelayout']);
			}

			if(sanitize_text_field($_POST["awp_cases_select_assignee"]) == 'team'){
				$assignee_type_id = sanitize_text_field($_POST['awp_cases_select_assignee_team']);
			}else{
				$assignee_type_id = sanitize_text_field($_POST['awp_cases_select_assignee_employee']);
			}

			if(isset($_POST["awp_cases_createassociate"])){
				$cases_createassociate = sanitize_text_field($_POST['awp_cases_createassociate']);
			}else{
				$cases_createassociate = '';
			}
                
			$casesform_properties = array(
				'tmpltype' => sanitize_text_field($_POST['awp_cases_templatetype']),
                'layout' => $templatelayout,
                'confmsg' => stripslashes(sanitize_text_field($_POST['awp_cases_confirmationmsg'])),
                'confirm_msg_page' => sanitize_text_field($_POST['awp_cases_confirm_msg_page']),
                'confirm_msg_pageid' => sanitize_text_field($_POST['awp_cases_confirmmsg_pageid']),
                'css' => stripslashes(trim(sanitize_text_field($_POST['awp_cases_customcss']))),
                'submit_button_type' => sanitize_text_field($_POST['awp_cases_submit_type']),
                'submit_button_val' => sanitize_text_field($_POST['awp_cases_submit_value']),
                'case_associates' => sanitize_text_field($_POST['awp_cases_associates']),
                'case_create_associates' => $cases_createassociate,
                'case_assignee_type' => sanitize_text_field($_POST['awp_cases_select_assignee']),
                'case_assignee_type_id' => $assignee_type_id,
                'case_assignee_name' => trim(sanitize_text_field($_POST['select_assignee_name_val'])),
                'case_priority' => sanitize_text_field($_POST['awp_casePriority']),
                'case_type' => sanitize_text_field($_POST['awp_caseType']),
                'case_status' => sanitize_text_field($_POST['awp_caseStatus']),
				'cases_width_type' => sanitize_text_field($_POST['awp_cases_width_type'])
            );

            //New Custom fields
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 15;
			for($i=6;$i<20;$i++){
				if( !empty($_POST['customfield'.$i.'_newest']) ){
					$addtional_custom = array(
						'fieldid' => 'customfield'.$i.'',
						'fieldname' => 'Custom Field '.$i.'',
						'defaulttext' => 'Custom Field'.$i.'',
						'showorder' => $addtional_order,
						'validation' => '',
						'fieldtype' => 'select'
					);
					$addtional_order++;
					array_push($stack, $addtional_custom);
				}else{
					break;
				}
			}
			
			if(!empty($stack)){
				update_option('awp_addtional_custom_cases',$stack);
			}
			
			//General Cases form fields

			//For Additional custom fields.
			$addtional_custom = get_option('awp_addtional_custom_cases');
			$master_field = array();
			if(!empty($addtional_custom)){
				$master_field = array_merge($this->fields,$addtional_custom);
			}else{
				$master_field = $this->fields;
			}
		
			$casesformfields=array();
			foreach( $master_field as $fieldsmasterproperties ){
				$enabled=0;
				$contactformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];

				if(!empty($_POST[$fieldid.'_order'])){
					$displayorder = sanitize_text_field($_POST[$fieldid.'_order']);
				}else{
					$displayorder = $fieldsmasterproperties['showorder'];
				}

				if(!empty($_POST[$fieldid.'_text'])){
					$displaytext = sanitize_text_field($_POST[$fieldid.'_text']);
				}else{
					$displaytext = $fieldsmasterproperties['defaulttext'];
				}

				$fieldsmasterproperties['must'] = (!empty($fieldsmasterproperties['must'])) ? $fieldsmasterproperties['must'] : [];
				if($fieldsmasterproperties['must']){
					$enabled = 1;
					$required = 1;
				}else if($fieldid=='captcha'){
					$enabled = sanitize_text_field($_POST[$fieldid.'_show']) ?? '';
					$required = 1;
				}else{
					$_POST[$fieldid.'_require'] = (!empty($_POST[$fieldid.'_require'])) ? $_POST[$fieldid.'_require'] : [];
					$_POST[$fieldid.'_show'] = (!empty($_POST[$fieldid.'_show'])) ? sanitize_text_field($_POST[$fieldid.'_show']) : [];
					$enabled = $_POST[$fieldid.'_show'];
					$required = sanitize_text_field($_POST[$fieldid.'_require']);
				}

				if($enabled){
					$_POST[$fieldid.'_validation'] = (array_key_exists($fieldid.'_validation',$_POST)) ? sanitize_text_field($_POST[$fieldid.'_validation']) : "none";
					$_POST[$fieldid.'_options'] = (!empty($_POST[$fieldid.'_options'])) ? sanitize_textarea_field($_POST[$fieldid.'_options']) : "";
					$casefield=$this->createformfield_array($fieldid,$displaytext,$required,sanitize_text_field($_POST[$fieldid.'_type']),$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
					array_push($casesformfields, $casefield);
				}
			}

			if(!empty($casesformfields)){
				$newcaseformdetails=array('name'=>$newformname,'properties'=>$casesform_properties,'fields'=>$casesformfields,'cases_config'=>$casesConfigDetails);
				$cases_forms=array();
				$cases_forms=get_option('awp_casesforms');
				$formExists="";

				if(!empty($cases_forms))
				$formExists = awp_recursive_array_search($cases_forms,$newformname,'name' );

				if(trim($formExists)!=="" ){

					unset($cases_forms[$formExists]);
					array_push($cases_forms, $newcaseformdetails);
					sort($cases_forms);

					update_option('awp_casesforms',$cases_forms);
					$cases_forms=get_option('awp_casesforms');
					$updatemessage= "Cases Form '".$newformname."' settings updated. Use Shortcode '[apptivocases name=\"".$newformname."\"]' in your page to use this form.";
				}
			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for Case Form.</span>";
			}
			$selectedcasesform=$newformname;
		}

		$absp_cases_fields_properties = get_option('awp_casesforms');
		if( isset($absp_cases_fields_properties['fields']) && isset($absp_cases_fields_properties['properties']) ){
			$fields=$absp_cases_fields_properties['fields']; //Cases Fields
			$formproperties=$absp_cases_fields_properties['properties'];//Case Properties
		}
		echo '<div class="wrap"><h2>Apptivo Cases Form</h2></div>';
		echo '<div class="casesform_err"></div>';
		checkCaptchaOption();

		if(!$this->_plugin_activated){
			$disabledForm = 'disabled="disabled"';
			echo "Cases form is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general&tab=plugins'>Apptivo General Settings</a>.";
		}
		echo awp_flow_diagram('cases',1);

		//saving the cases form name
		$cases_forms=array();
		$casesformdetails=array();
		add_option('awp_casesforms');
		$cases_forms=get_option('awp_casesforms');

		if(!empty($_POST['newcasesformname'])){
			$newcasesformname =   sanitize_text_field($_POST['newcasesformname']);
			$newcasesformname = preg_replace('/\s+/', '_', $newcasesformname);
			$newcasesformname = preg_replace('/[^\w]/', '', $newcasesformname);
			$newcasesformname=trim($newcasesformname);
			if($newcasesformname!='' && $newcasesformname!="CasesFormOld"){
				$casesform=array();
				$casesform=$this->get_case_settings($newcasesformname);
				if( count($casesform)==0 ){
					$newcasesformname_array =array("name"=>$newcasesformname);
					$newcasesform=array($newcasesformname_array);
					if( empty($cases_forms) ){
						update_option('awp_casesforms',$newcasesform);
					}else{
						array_push($cases_forms, $newcasesformname_array);
						update_option('awp_casesforms',$cases_forms);
					}
					$cases_forms=get_option('awp_casesforms');
					$casesform=$this->get_case_settings($newcasesformname);
					$selectedcasesform=$newcasesformname;
					$updatemessage= "Cases Form created. Please configure settings using the below Configuration section.";
				}else{
					$updatemessage= "<span style='color:#f00;'>Form already exists. To change configuration, please select the form from below configuration section.</span>";
				}
			}else if($newcasesformname=="CasesFormOld"){
				$updatemessage= "Form Name already exixts";
			}else{
				$updatemessage= "Form Name cannot be empty.";
			}
		}

		echo '<div class="wrap">';
		// header
		//if updatemessage is not empty display the div
		$updatemessage = (!empty($updatemessage)) ? $updatemessage : '';
		if(trim($updatemessage)!=""){ ?>
			<div id="message" class="updated">
				<p>
				<?php echo esc_attr($updatemessage);?>
				</p>
			</div>
		<?php }

		if(isset($_POST['awp_caseform_select_form'])){
			$selectedcasesform =  trim( sanitize_text_field($_POST['awp_caseform_select_form']));
			if($selectedcasesform!=''){
				$caseform=array();
				$caseform=$this->get_case_settings($selectedcasesform);
				if( empty($caseform)){
					//echo "Selected form configuration doestn exist.";
				}else{
					$caseformdetails=$caseform;
				}
			}
		}
		$_POST['delformname'] = (!empty($_POST['delformname'])) ? sanitize_text_field($_POST['delformname']) : [];
		if($_POST['delformname']){   //Delete Form Name:
			if(strlen(trim($_POST['delformname'])) != 0){
				$formname = sanitize_text_field($_POST['delformname']);
				$cases_forms=get_option('awp_casesforms');
				$formExists = awp_recursive_array_search($cases_forms,$formname,'name' );
				if(isset($formExists)){
					unset($cases_forms[$formExists]);
				}
				$case_sort_form = array();
				foreach( $cases_forms as $cases_forms_tosort ){
					array_push($case_sort_form,$cases_forms_tosort);
				}
				update_option('awp_casesforms', $case_sort_form);
				$updatemessage= 'Case Form "'.$formname.'" Deleted Successfully.';
			}
		}

		?>

		<form name="awp_cases_new_form" id="awp_cases_new_form" method="post" action="">
			<p>
			<?php _e("Cases Form Name", 'apptivo-businesssite' ); ?>
				<span style="color: #f00;">*</span>&nbsp;&nbsp;
				<input type="text" name="newcasesformname" id="newcasesformname" size="20" maxlength="50" value="">
			</p>

			<p>
			<?php $disabledForm = (!empty($disabledForm)) ? $disabledForm : ''; ?>
				<input <?php echo esc_attr($disabledForm);?> type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Add New') ?>" />
			</p>
		</form>

		<?php if(!empty($cases_forms)){ ?>

			<br><hr />
			<?php
			echo "<h2>" . __( 'Cases Form Configuration', 'awp_casesforms' ) . "</h2>";
			?>

			<?php
			$selectedcasesform = (!empty($selectedcasesform)) ? $selectedcasesform : '';
			if(trim($selectedcasesform)=="" && $cases_forms!= ""){
				$selectedcasesform=$cases_forms[0]['name'];
			}
			$caseformdetails=$this->get_case_settings($selectedcasesform);

			if(count($caseformdetails)>0){
				$caseformdetails['name'] = (!empty($caseformdetails['name'])) ? $caseformdetails['name'] : [];
				$caseformdetails['fields'] = (!empty($caseformdetails['fields'])) ? $caseformdetails['fields'] : [];
				$caseformdetails['properties'] = (!empty($caseformdetails['properties'])) ? $caseformdetails['properties'] : [];
				$selectedcasesform=$caseformdetails['name'];
				$fields=$caseformdetails['fields'];
				$formproperties=$caseformdetails['properties'];
			}
			?>

			<table class="form-table">
				<tbody>
				<?php
				if(empty($formproperties['tmpltype'])){  //To check Cases form settings are save or not.
					echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for case form.</span>';
				}
				if($selectedcasesform=="CasesFormOld") {
					$oldFormDel	= "";
				}else{ 
					$oldFormDel="";
				}
				?>
					<tr valign="top">
						<th valign="top"><label for="awp_caseform_select_form"><?php _e("Case Form", 'apptivo-businesssite' ); ?>:</label></th>
						<td valign="top">
							<form name="awp_cases_selection_form" method="post" action=""
							style="float: left;">
								<select name="awp_caseform_select_form" id="awp_caseform_select_form" onchange="this.form.submit();">
									<?php for($i=0; $i<count($cases_forms); $i++){ ?>
									<option value="<?php echo esc_attr($cases_forms[$i]['name'])?>"
									<?php if(trim($selectedcasesform)==$cases_forms[$i]['name'])
									echo "selected='true'";?>>
									<?php echo esc_attr($cases_forms[$i]['name'])?>
									</option>
									<?php } ?>
								</select>

							</form> &nbsp;&nbsp;&nbsp;&nbsp;
							<?php if($this->_plugin_activated && $oldFormDel==""){ ?>
							<form name="awp_contact_delete_form" method="post" action=""
							style="float: left; padding-left: 30px;">
							<a
							href="javascript:contact_confirmation('<?php echo esc_attr($selectedcasesform); ?>')">Delete</a>
							<input type="hidden" name="delformname" id="delformname"
							value="<?php echo esc_attr($selectedcasesform) ?>" />
							</form>
							<?php } ?>
						</td>
					</tr>
				</tbody>
			</table>

			<?php
				$case_assignee_name = "";
				if(_isCurl()){
					$configDatas	= caseOptions($save=null);  
					// $configDatas	= get_option("awp_cases_configdata");
					$configDatas	= json_decode($configDatas);
					if(isset($configDatas->caseAssignee)){
						foreach ($configDatas->caseAssignee as $assigne_key => $assigne_value){
							if($assigne_value->assigneeObjectId == APPTIVO_EMPLOYEE_OBJECT_ID){
								$assignee_employee_list[$assigne_value->assigneeName] = $assigne_value->assigneeObjectRefId;
							}
							else if($assigne_value->assigneeObjectId == APPTIVO_TEAM_OBJECT_ID){
								$assignee_team_list[$assigne_value->assigneeName] = $assigne_value->assigneeObjectRefId;
							}

							if(isset($formproperties['case_assignee_name'])){
								if($assigne_value->assigneeName == $formproperties['case_assignee_name']){
									$case_assignee_name = $assigne_value->assigneeName;
								}
							}
						}
						$case_assignee_default_name = $configDatas->caseAssignee[0]->assigneeName;
					}else{
						$case_assignee_default_name = '';
					}
					if(!isset($assignee_employee_list)){
						$assignee_employee_list["No Employee"] = '';
					}
					if(!isset($assignee_team_list)){
						$assignee_team_list["No Team"] = '';
					}
				}
                                
				if($case_assignee_name == ""){
					$case_assignee_name = $case_assignee_default_name;
				}
			?>
			<form name="awp_cases_settings_form" method="post" action="">
				<table class="form-table">

					<?php if(!empty($formproperties['tmpltype'])){ ?>
						<tr valign="top">
							<th valign="top"><label for="cases_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
								<br> <span class="description"><?php _e('Copy and Paste this shortcode in your page to display the cases form.','apptivo-businesssite'); ?>
							</span>
							</th>
							<td valign="top"><span id="awp_cases_shortcode"
								name="awp_cases_shortcode"> <input style="width: 300px;"
									type="text" id="cases_shortcode" name="cases_shortcode"
									readonly="true"
									value='[apptivocases name="<?php echo esc_attr($selectedcasesform)?>"]' /> </span>
							</td>
						</tr>
					<?php } ?>
						<tr valign="top">
							<th valign="top"><label for="awp_cases_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label></th>
							<td valign="top">
								<input type="hidden" id="awp_cases_name" name="awp_cases_name" value="<?php echo esc_attr($selectedcasesform);?>">
								<select name="awp_cases_templatetype" id="awp_cases_templatetype" onchange="cases_change_template();">
									<?php $formproperties['tmpltype'] = (!empty($formproperties['tmpltype'])) ? $formproperties['tmpltype'] : ''; ?>
									<option value="awp_plugin_template" <?php selected($formproperties['tmpltype'],'awp_plugin_template'); ?>>Plugin Templates</option>
									<?php if(!empty($themetemplates)) : ?>
										<option value="theme_template" <?php selected($formproperties['tmpltype'],'theme_template'); ?>>Templates from Current Theme</option>
									<?php endif; ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th valign="top"><label for="awp_cases_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label></th>
							<td valign="top">
								<?php  if( sizeof($plugintemplates) > 0 ) : ?>
								<select name="awp_cases_plugintemplatelayout" id="awp_cases_plugintemplatelayout" <?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?>>
									<?php foreach (array_keys( $plugintemplates ) as $template ){ ?>
									<option value="<?php echo esc_attr($plugintemplates[$template]);?>"
										<?php $formproperties['layout'] = (!empty($formproperties['layout'])) ? $formproperties['layout'] : ''; ?>
										<?php selected($formproperties['layout'],$plugintemplates[$template]); ?>>
										<?php echo esc_attr($template);?>
									</option>
									<?php }  ?>
								</select>
								<?php else: 
									echo 'No templates available';
								endif; ?>
								<select name="awp_cases_themetemplatelayout" id="awp_cases_themetemplatelayout" <?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?>>
									<?php foreach (array_keys( $themetemplates ) as $template ) : ?>
									<option value="<?php echo esc_attr($themetemplates[$template]);?>"
									<?php selected($formproperties['layout'],$themetemplates[$template]);?>>
									<?php echo esc_attr($template);?>
									</option>
									<?php endforeach;?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th><label for="awp_cases_customcss"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label></th>
							<td valign="top">
								<input type="radio" value="same" id="same_page" name="awp_cases_confirm_msg_page" <?php $formproperties['confirm_msg_page'] = (!empty($formproperties['confirm_msg_page'])) ? $formproperties['confirm_msg_page'] : ''; ?><?php checked('same',$formproperties['confirm_msg_page']); ?> checked="checked" /><label for="same_page"> Same Page</label>
								<input type="radio" value="other" id="other_page" name="awp_cases_confirm_msg_page" <?php checked('other',$formproperties['confirm_msg_page']); ?> /> <label for="other_page"> Other page</label> <br /> <br />
								<select id="awp_cases_confirmmsg_pageid" name="awp_cases_confirmmsg_pageid" <?php if($formproperties['confirm_msg_page'] != 'other') echo 'style="display:none;"';?>>
									<?php 
									$pages = get_pages();
									foreach ($pages as $pagg){ ?>
										<?php $formproperties['confirm_msg_pageid'] = (!empty($formproperties['confirm_msg_pageid'])) ? $formproperties['confirm_msg_pageid'] : ''; ?>
										<option value="<?php echo esc_attr($pagg->ID); ?>"
										<?php selected($pagg->ID, $formproperties['confirm_msg_pageid']); ?>>
										<?php echo esc_attr($pagg->post_title); ?>
										</option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<?php $formproperties['confirm_msg_page'] = (!empty($formproperties['confirm_msg_page'])) ? $formproperties['confirm_msg_page'] : ''; ?>
						<tr valign="top" id="awp_cases_confirmationmsg_tr"
						<?php if($formproperties['confirm_msg_page'] == 'other') echo 'style="display:none;"';?>>
							<th valign="top"><label for="awp_cases_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label> <br> <span class="description">This message will shown in your website page, once cases form submitted.</span> </th>
							<td valign="top">
								<div style="width: 620px;">
								<?php $formproperties['confmsg'] = (!empty($formproperties['confmsg'])) ? $formproperties['confmsg'] : ''; ?>
								<?php
								//the_editor($formproperties['confmsg'],'awp_cases_confirmationmsg','',FALSE);
								wp_editor($formproperties['confmsg'], 'awp_cases_confirmationmsg', array());
								?>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<th><label for="awp_cases_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label> <br> <span valign="top" class="description">Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.</span> </th>
							<?php $formproperties['css'] = (!empty($formproperties['css'])) ? $formproperties['css'] : ''; ?>
							<td valign="top">
								<textarea name="awp_cases_customcss" id="awp_cases_customcss" size="100"  cols="40" rows="10"> <?php echo esc_attr($formproperties['css']);?> </textarea>
							</td>
						</tr>
						<tr valign="top">
							<th><label id="awp_cases_submit_type" for="awp_cases_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
							<br> <span valign="top" class="description"></span> </th>
							<?php $formproperties['submit_button_type'] = (!empty($formproperties['submit_button_type'])) ? $formproperties['submit_button_type'] : ''; ?>
							<td valign="top"><input type="radio" value="submit" id="submit_button" name="awp_cases_submit_type" <?php checked('submit',$formproperties['submit_button_type']); ?> checked="checked" /> <label for="submit_button">Button</label> <input type="radio" value="image" id="submit_image" name="awp_cases_submit_type" <?php checked('image',$formproperties['submit_button_type']); ?> /> <label for="submit_image">Image</label>
							</td>
						</tr>
						<tr valign="top">
							<th><label for="awp_cases_submit_val" id="awp_cases_submit_val"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label> <br> <span valign="top" class="description"></span> </th>
							<?php $formproperties['submit_button_val'] = (!empty($formproperties['submit_button_val'])) ? $formproperties['submit_button_val'] : ''; ?>
							<td valign="top"> <input type="text" name="awp_cases_submit_value" id="awp_cases_submit_value" value="<?php echo esc_attr($formproperties['submit_button_val']);?>" size="52" /> <span id="upload_img_button" style="display: none;"> <input id="cases_upload_image" type="button" value="Upload Image" class="button-primary" /> <br /> <?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
							</span>
							</td>
						</tr>
					<?php if(_isCurl()){ ?>
						<tr valign="top">
							<th valign="top"> <label for="awp_cases_associates"> <?php _e("Associate Case With:", 'apptivo-businesssite' ); ?> </label> </th>
							<td valign="top"><?php $associateOption	= array("No Need","Contact","Customer","Both"); ?>
								<select name="awp_cases_associates" id="awp_cases_associates">
									<?php $formproperties['case_associates'] = (!empty($formproperties['case_associates'])) ? $formproperties['case_associates'] : ''; ?>
									<?php foreach ($associateOption as $associateValues ){ ?>
										<option value="<?php echo esc_attr($associateValues);?>" <?php selected($associateValues, $formproperties['case_associates']); ?> ><?php echo esc_attr($associateValues);?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th valign="top" style="float: left;"> <label
							for="awp_cases_createassociate"><?php _e("Create new customer/contact and associate with case:", 'apptivo-businesssite' ); ?> </label> </th>
							<td valign="top"> <?php $createOption = array("Do not create"=>"0","Create New customer"=>"customer","Create New contact"=>"contact"); ?>
								<select name="awp_cases_createassociate" id="awp_cases_createassociate">
									<?php $formproperties['case_create_associates'] = (!empty($formproperties['case_create_associates'])) ? $formproperties['case_create_associates'] : ''; ?>
									<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
									<option value="<?php echo esc_attr($createdValue);?>" <?php selected($createdValue, $formproperties['case_create_associates']); ?>><?php echo esc_attr($createdKey);?></option>
									<?php }  ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th valign="top" style="float: left;"><label for="awp_cases_select_assignee"> <?php _e("Select Assignee [ Employee/ Team ]:", 'apptivo-businesssite' ); ?> </label> </th>
							<td valign="top">
							<?php $createOption = array("Employee"=>"employee","Team"=>"team"); ?>
							<select name="awp_cases_select_assignee" id="awp_cases_select_assignee">
								<?php $formproperties['case_assignee_type'] = (!empty($formproperties['case_assignee_type'])) ? $formproperties['case_assignee_type'] : ''; ?>
								<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
								<option value="<?php echo esc_attr($createdValue);?>"
								<?php selected($createdValue, $formproperties['case_assignee_type']); ?>><?php echo esc_attr($createdKey);?></option>
								<?php }  ?>
								<?php $show_assignee_employee_style = (!empty($show_assignee_employee_style)) ? $show_assignee_employee_style : ''; ?>
							</select>
							<?php
							$show_assignee_employee_style = '';
							$show_assignee_team_style = '';
							if($formproperties['case_assignee_type'] == 'team'){
								$show_assignee_employee_style = 'style="display:none"'; 
							}else{
								$show_assignee_team_style = ' style="display:none"';
							} ?>
							<select <?php echo esc_attr($show_assignee_employee_style); ?> class="awp_cases_select_assignee" name="awp_cases_select_assignee_employee" id="awp_cases_select_assignee_employee" onchange="document.getElementById('select_assignee_name_val').value=this.options[this.selectedIndex].text">
								<?php $formproperties['case_assignee_type_id'] = (!empty($formproperties['case_assignee_type_id'])) ? $formproperties['case_assignee_type_id'] : ''; ?>
								<?php foreach( $assignee_employee_list as $createdKey => $createdValue ){ ?>
								<option value="<?php echo esc_attr($createdValue)?>" <?php selected($createdValue, $formproperties['case_assignee_type_id']); ?>><?php echo esc_attr($createdKey);?></option>
								<?php }  ?>
							</select>
							<select <?php echo esc_attr($show_assignee_team_style); ?> class="awp_cases_select_assignee" name="awp_cases_select_assignee_team" id="awp_cases_select_assignee_team" onchange="document.getElementById('select_assignee_name_val').value=this.options[this.selectedIndex].text">
								<?php foreach( $assignee_team_list as $createdKey => $createdValue ){ ?>
								<option value="<?php echo esc_attr($createdValue)?>" <?php selected($createdValue, $formproperties['case_assignee_type_id']); ?>><?php echo esc_attr($createdKey);?></option>
								<?php }  ?>
							</select>
							<input type="hidden" id="select_assignee_name_val" name="select_assignee_name_val" value="<?php echo esc_attr($case_assignee_name);?>" />
							</td>
						</tr>
					<?php } ?>
						<tr valign="top">
							<th valign="top" style="float: left;"><label for="awp_cases_width_type"><?php _e("Form Outer Width :", 'apptivo-businesssite' ); ?> </label> </th>
							<td valign="top"><?php $createOption	=	array("Full Width (100%)"=>"100%","Half Width (50%)"=>"50%"); ?>
								<select name="awp_cases_width_type" id="awp_cases_width_type">
									<?php $formproperties['cases_width_type'] = (!empty($formproperties['cases_width_type'])) ? $formproperties['cases_width_type'] : ''; ?>
									<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
									<option value="<?php echo esc_attr($createdValue)?>"
									<?php selected($createdValue, $formproperties['cases_width_type']); ?>>
									<?php echo esc_attr($createdKey);?>
									</option>
									<?php }  ?>
								</select>
							</td>
						</tr>
	
				</table>

				<?php
					//For Additional custom fields.
					$addtional_custom = get_option('awp_addtional_custom_cases');
					$master_field = array();
					if(!empty($addtional_custom)){
						$master_field = array_merge($this->fields,$addtional_custom);
					}else{
						$master_field = $this->fields;
					}
				?>

				<table width="900" cellspacing="0" cellpadding="0" id="cases_form_fields" name="cases_form_fields" style="border-collapse: collapse;"> <br />
					<h3>Cases Form Fields</h3>
					<div style="margin: 10px;">Select and configure list of fields from below table to show in your cases form.</div>
					<tbody>
						<tr>
							<th></th>
						</tr>
			<tr align="center" style="background-color: rgb(223, 223, 223); font-weight: bold;" class="widefat">
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"> <?php _e('Field Name','apptivo-businesssite'); ?> </td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"> <?php _e('Show','apptivo-businesssite'); ?> </td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"> <?php _e('Require','apptivo-businesssite'); ?> </td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"> <?php _e('Display Order','apptivo-businesssite'); ?> </td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"> <?php _e('Display Text - The lable name should be Apptivo field lable name. (For customfields only)','apptivo-businesssite'); ?> </td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?> </td>
				<td align="center" style="width: 100px; border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?> </td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?> </td>
			</tr>
			<tr>
				<th></th>
			</tr>
			<?php

			$pos = 0;
			$index_key = 0;
			foreach( $master_field as $fieldsmasterproperties ){
				$enabled=0;$required=0;
				$fieldExists=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				
				$fieldsmasterproperties['must'] = (!empty($fieldsmasterproperties['must'])) ? $fieldsmasterproperties['must'] : '';
				if($fieldsmasterproperties['must']){
					$enabled =1;
					$required =1;
				}
	
				if($fieldid == 'captcha'){
					$required = 1;
				}

				if(!empty($fields)){
					$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');
				}
				$fieldExistFlag = (isset($fieldExistFlag) && $fieldExistFlag !== '') ? $fieldExistFlag : '';
				if(trim($fieldExistFlag) !== ""){
					$enabled=1;
					$fieldsmasterproperties['must_require'] = (array_key_exists('must_require',$fieldsmasterproperties)) ? $fieldsmasterproperties['must_require'] : 0;
					$fieldData=array("fieldid"=>$fieldid,
									"fieldname"=>$fieldsmasterproperties['fieldname'],
									"show"=>$enabled,
									"required"=>$fields[$fieldExistFlag]['required'],
									"showtext"=>$fields[$fieldExistFlag]['showtext'],
									"type"=>$fields[$fieldExistFlag]['type'],
									"must_require"=> $fieldsmasterproperties['must_require'],
									"validation"=>$fields[$fieldExistFlag]['validation'],
									"options"=>$fields[$fieldExistFlag]['options'],
									"order"=>$fields[$fieldExistFlag]['order']
								);
				}else{
					$fieldsmasterproperties['must_require'] = (!empty($fieldsmasterproperties['must_require'])) ? $fieldsmasterproperties['must_require'] : '';
					$fieldData=array("fieldid"=>$fieldid,
									"fieldname"=>$fieldsmasterproperties['fieldname'],
									"show"=>$enabled,
									"required"=>$required,
									"showtext"=>$fieldsmasterproperties['defaulttext'],
									"type"=> $fieldsmasterproperties['fieldtype'],
									"must_require"=> $fieldsmasterproperties['must_require'],
									"validation"=>"",
									"options"=>"",
									"order"=>$fieldsmasterproperties['showorder']
								);

				}
				$pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
				?>
				<tr>
					<!--  Field Name -->
					<td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;"><?php echo esc_attr($fieldData['fieldname']);?>

						<?php if($index_key > 13 ) : ?> <input type="hidden" id="<?php echo esc_attr($fieldData['fieldid']);?>_newest" name="<?php echo esc_attr($fieldData['fieldid']);?>_newest" value="dd" /> <?php endif; $index_key++; ?>

					</td>

					<!--  Field To Show -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
						<input <?php  if($enabled) { ?> checked="checked" <?php }  if($fieldsmasterproperties['must'] && $fieldData['fieldname']!="Type" && $fieldData['fieldname']!="Priority") { ?> disabled="disabled" <?php }  ?> type="checkbox" id="<?php echo esc_attr($fieldData['fieldid']);?>_show" name="<?php echo esc_attr($fieldData['fieldid']);?>_show" size="30" onclick="casesform_enablefield('<?php echo esc_attr($fieldData['fieldid']);?>')">
					</td>

					<!--  Field To Require -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
						<input <?php if($fieldData['required']  ) { ?> checked="checked" <?php }?> <?php if(!$enabled || ($fieldData['must_require'])) { ?> disabled="disabled" <?php } ?> type="checkbox" id="<?php echo esc_attr($fieldData['fieldid']);?>_require" name="<?php echo esc_attr($fieldData['fieldid']);?>_require" size="30">
					</td>

					<!--  Display Order -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
						<input type="text" style="text-align: center;" onkeypress="return isNumberKey(event)"
						id="<?php echo esc_attr($fieldData['fieldid']);?>_order" name="<?php echo esc_attr($fieldData['fieldid']);?>_order" value="<?php echo esc_attr($fieldData['order']); ?>" size="3" maxlength="2" <?php if(!$enabled) { ?> disabled="disabled" <?php } ?>>
					</td>

					<!--  Display Text -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
						<input <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> type="text" id="<?php echo esc_attr($fieldData['fieldid']);?>_text" name="<?php echo esc_attr($fieldData['fieldid'])?>_text" value="<?php echo esc_attr($fieldData['showtext']); ?>">
					</td>

					<!--  Field Type -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
						<?php 
						$name_postfix="type"; 
						if($pos===false){ ?>
							<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid']);?>_type" name="<?php echo esc_attr($fieldData['fieldid']);?>_type" value="<?php echo esc_attr($fieldData['type']); ?>">
							<input <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly" type="text" id="<?php echo esc_attr($fieldData['fieldid']);?>_typehiddentext" name="<?php echo esc_attr($fieldData['fieldid']);?>_typehiddentext" value="<?php echo esc_attr($fieldData['type']); ?>"> <?php $name_postfix="type_select";
						}else{ ?>
							<select name="<?php echo esc_attr($fieldData['fieldid']);?>_type" id="<?php echo esc_attr($fieldData['fieldid']);?>_type" <?php if($pos===false){ ?> readonly="readonly" <?php } if(!$enabled || ($pos===false)){ ?> disabled="disabled" <?php } ?> onChange="casesform_showoptionstextarea('<?php echo esc_attr($fieldData['fieldid']);?>');">
								<?php foreach( $this->fieldtypes as $masterfieldtypes ){ ?>
								<option value="<?php echo esc_attr($masterfieldtypes['fieldtype']);?>" <?php if($masterfieldtypes['fieldtype']==$fieldData['type']){ ?>
								selected="selected" <?php }?>> <?php echo esc_attr($masterfieldtypes['fieldtypeLabel']);?> </option>
								<?php }?>

							</select>
						<?php } ?>
					</td>

					<!-- Validation Type -->
					<td align="center" style="width: 100px; border: 1px solid rgb(204, 204, 204);"><?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield"); ?> 
						<?php if($pos===false){ ?>
							<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid']);?>_validation" name="<?php echo esc_attr($fieldData['fieldid']);?>_validation" <?php if($fieldid=="email"){ ?> value="email" <?php }else if($fieldid=="phone"){ ?> value="phonenumber" <?php }else{ ?> value="none" <?php }?>>
							<input style="width: 100px;" <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly" type="text" id="<?php echo esc_attr($fieldData['fieldid']);?>_validationhidden" name="<?php echo esc_attr($fieldData['fieldid']);?>_validationhidden" <?php if($fieldid=="email"){ ?> value="Email Id"
							<?php }else if($fieldid=="phone"){ ?> value="Phone Number"
							<?php }else{ ?> value="None" <?php }?>>
						<?php }else{ ?>
							<select name="<?php echo esc_attr($fieldData['fieldid']);?>_validation" id="<?php echo esc_attr($fieldData['fieldid']);?>_validation" <?php if(!$enabled ) { ?> disabled="disabled" <?php }
						if( ($fieldData['type'] != 'text' && (strtolower($fieldData['validation']) == 'none' || strtolower($fieldData['validation']) == ''))) {?>
						disabled="disabled" <?php }?>>
						<?php foreach( $this->validations as $masterfieldtypes )
						{ ?>
							<option value="<?php echo esc_attr($masterfieldtypes['validation']);?>"
							<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
								selected="selected" <?php }?>>
								<?php echo esc_attr($masterfieldtypes['validationLabel']);?>
							</option>
							<?php }?>
					</select> <?php }  ?>
					</td>
					<!-- Options Values -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
					if($pos===false){
						if($fieldData['fieldname']!="Type" && $fieldData['fieldname']!="Priority" && $fieldData['fieldname']!="Status")
						{
							echo "N/A";
							//Not a custom field. Dont show any thing
						}
						else if($fieldData['fieldname']=="Type" || $fieldData['fieldname']=="Priority" || $fieldData['fieldname']=="Status")
						{
							$getConfig=get_option('awp_casesforms');
							for($i=0;$i<count($getConfig);$i++)
							{
								if($getConfig[$i]['name'] == $selectedcasesform)
								{
									$getConfig[$i]['cases_config'] = (!empty($getConfig[$i]['cases_config'])) ? $getConfig[$i]['cases_config'] : [];
									$selectedConfigdata=$getConfig[$i]['cases_config'];
									
								}

							}
							if(_isCurl())
							{
								$selectedConfigName="";
								$configType		=	"case".$fieldData['fieldname'];
								$configTypeName	= $configDatas->$configType;
								$selectedConfigdata['awp_'.$configType.'_selected'] = (!empty($selectedConfigdata['awp_'.$configType.'_selected'])) ? $selectedConfigdata['awp_'.$configType.'_selected'] : '';				
								$selectedConfig = $selectedConfigdata['awp_'.$configType.'_selected'];
								
								echo '<select name="absp_cases_config_'.esc_attr($configType).'" style="width:100%;" id="'.esc_attr($configType).'_Id">';
								for($i=0;$i<count($configTypeName);$i++)
								{
									$configtypeId= $configTypeName[$i]->lookupId;
									$configName	 = $configTypeName[$i]->meaning;
									if($selectedConfig == $configtypeId){
										$selectedConfigName	 = $configTypeName[$i]->meaning;
									}
									echo '<option '.esc_attr(selected($selectedConfig,$configtypeId)).' value="'.esc_attr($configtypeId).'" rel="'.esc_attr($configName).'">'.esc_attr($configName).'</option>';
								}
								echo '</select>';
								if($selectedConfigName==""){$selectedConfigName=$configTypeName[0]->meaning;}                                                        
								echo '<input id="'.esc_attr($configType).'Id" type="hidden" class="absp_hidden_'.esc_attr($configType).'" value="'. htmlspecialchars(esc_attr($selectedConfigName)).'" name="awp_'.esc_attr($configType).'"/>';
							}
							else
							{
								if($fieldData['fieldname']=="Type")
								{
									$configName	=	array("Product Questions","Technical Issues","Product Purchases","Partnership Opportunities","Feature Request","Feedback","Report a problem","Other");
									$configType		=	"case".$fieldData['fieldname'];
								}
								if($fieldData['fieldname']=="Priority")
								{
									$configName	=	array("High","Medium","Low");
									$configType		=	"case".$fieldData['fieldname'];
								}
								if($fieldData['fieldname']=="Status")
								{
									$configName	=	array("New");
									$configType		=	"case".$fieldData['fieldname'];
								}
								echo '<select name="absp_cases_config_'.esc_attr($configType).'" style="width:100%;">';
								for($i=0;$i<count($configName);$i++)
								{
									echo '<option '.esc_attr(selected($selectedConfig,$configName[$i])).' value="'.esc_attr($configName[$i]).'">'.esc_attr($configName[$i]).'</option>';
								}
								echo '</select>';
								echo '<input type="hidden" class="absp_hidden_'.esc_attr($configType).'" value="" />';
								unset($configName);
							}
						}
					}
					else if( $enabled && ( ($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")) ){
						if(empty($fieldData['options'])){
							$fieldData['options'] = "";
						}?>
						<textarea style="width: 190px;" <?php if(!$enabled){ ?>
							disabled="disabled" <?php } ?>
							id="<?php echo esc_attr($fieldData['fieldid']);?>_options"
							name="<?php echo esc_attr($fieldData['fieldid']);?>_options">
							<?php echo esc_attr($fieldData['options']); ?>
						</textarea> <?php }else {?> <textarea disabled="disabled"
							style="display: none; width: 190px;"
							id="<?php echo esc_attr($fieldData['fieldid']);?>_options"
							name="<?php echo esc_attr($fieldData['fieldid']);?>_options"></textarea> <?php }?>
					</td>
				</tr>
			<?php  } ?>

		</tbody>
	</table>
	<?php
	$addtional_custom = get_option('awp_addtional_custom_cases');
	if(empty($addtional_custom))
	{
		$cnt_custom_filed = 6;
	}else {
		$cnt_custom_filed = 6 + count($addtional_custom);
	}
	?>
	<p>
		<a rel="<?php echo esc_attr($cnt_custom_filed); ?>" href="javascript:void(0);"
			id="cases_addcustom_field" name="cases_addcustom_field">+Add Another
			Custom Field</a>
	</p>
	<p class="submit">
		<input
		<?php if(!$this->_plugin_activated): echo 'disabled="disabled"'; endif; ?>
			type="submit" name="awp_cases_settings" id="awp_cases_settings"
			class="button-primary"
			value="<?php esc_attr_e('Save Configuration','apptivo business site') ?>" />
	</p>
</form>

		<?php
	}
	}
	//GEt Plugin Templates.
	function get_plugin_templates()
	{
		$default_headers = array(
		'Template Name' => 'Template Name'		
		);
		$templates = array();
		$dir_contact = AWP_CASES_TEMPLATEPATH;
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

		if( trim($type) != 'text' && trim($type) != 'textarea')
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

}

/*
 *  Save Case Status, Case Type and Case Priority
 *
 */

function caseOptions($save)
{
	if(_isCurl())
	{
		$case_status=array();
		$case_priority=array();
		$case_type=array();
		//$casesConfigData	= getAllCasesConfigData();
		$casesConfigData	= getCasesConfigData();
		if($casesConfigData == ''){
			echo '<script> 
			alert("'.AWP_SERVICE_ERROR_MESSAGE.'"); 
			window.location.href = "'.str_replace('awp_cases', 'awp_general', esc_attr($_SERVER["REQUEST_URI"])).'";'.
			'</script>'; 
		}
		if(isset($casesConfigData->statuses)){
			foreach ($casesConfigData->statuses as $caseStatus){
				$caseStatus->disabled = (!empty($caseStatus->disabled)) ? $caseStatus->disabled : '';
				if($caseStatus->disabled !='Y'){
					$caseStatus->lookupId = $caseStatus->statusId;
					$caseStatus->meaning = $caseStatus->statusName;
					array_push($case_status, $caseStatus);
				}
			}
		}
		if(isset($casesConfigData->priorities)){
			foreach ($casesConfigData->priorities as $casePriority){
				$casePriority->disabled = (!empty($casePriority->disabled)) ? $casePriority->disabled : '';
				if($casePriority->disabled !='Y'){
					$casePriority->lookupId = $casePriority->id;
					$casePriority->meaning = $casePriority->name;
					array_push($case_priority, $casePriority);
				}
			}
		}
		if(isset($casesConfigData->types)){
			foreach ($casesConfigData->types as $caseType){
				$caseType->disabled = (!empty($caseType->disabled)) ? $caseType->disabled : '';
				if($caseType->disabled !='Y'){
					$caseType->lookupId = $caseType->typeId;
					$caseType->meaning = $caseType->typeName;
					array_push($case_type, $caseType);
				}
			}    
		}
		
		//$case_assignee  	= $casesConfigData->assigneesList;
		$assignees = getAllEmployeesAndTeams();
		$empAssignees = $assignees->employeeData;
		$teamAssignees = $assignees->teamData;
		
		foreach($empAssignees as $emp){
			$assigneeObj = new stdClass();
			$assigneeObj->assigneeName = $emp->fullName;
			$assigneeObj->assigneeObjectId = APPTIVO_EMPLOYEE_OBJECT_ID;
			$assigneeObj->assigneeObjectRefId = $emp->employeeId;
			$case_assignee[] = $assigneeObj;
		}
		
		foreach($teamAssignees as $team){
			$assigneeObj = new stdClass();
			$team->name = (!empty($team->name)) ? $team->name : '';
			$assigneeObj->assigneeName = $team->name;
			$assigneeObj->assigneeObjectId = APPTIVO_TEAM_OBJECT_ID;
			$assigneeObj->assigneeObjectRefId = $team->teamId;
			$case_assignee[] = 	$assigneeObj;
		}
		
		$case_config		= array("caseStatus"=>$case_status,"casePriority"=>$case_priority,"caseType"=>$case_type,'caseAssignee'=>$case_assignee);
		$case_configDatas	= json_encode($case_config);
	}
	if($save=='save'){
		check_option('awp_cases_configdata',$case_configDatas);
	}
	return $case_configDatas;
}

/* Get Cases Config Data with default  */

function getCasesConfig(){
	if(_isCurl()){
		$getCasesConfig=getCaseConfigureData();
		$autoGenerateCheck=$getCasesConfig->autoGenerate;
		$configAssigneeName=$getCasesConfig->assigneeName;
		$configAssigneeId=$getCasesConfig->assigneeId;
		
	}
}

function getFirstConfigData($type,$priotity,$status,$caseForm)
{
	$firstConfig	=	get_option("awp_cases_configdata");
	$firstConfig	=	json_decode($firstConfig);
	$getConfig=get_option('awp_casesforms');
	for($i=0;$i<count($getConfig);$i++)
	{
		if($getConfig[$i]['name']==$caseForm)
		{
			$formConfig=$getConfig[$i]['cases_config'];
		}
	}
	if($type=="0")
	{
		foreach ($firstConfig->caseType as $casetype)
		{
			if($formConfig["awp_caseType_selected"]==$casetype->lookupId)
			{
				echo '<input type="hidden" name="type_name" value="'.esc_attr($casetype->meaning).'"/>';
				echo '<input type="hidden" id="type" name="type" value="'.esc_attr($casetype->lookupId).'"/>';
				break;
			}
			 
		}
	}
	elseif($priotity=="0")
	{
		foreach ($firstConfig->casePriority as $casePriority)
		{
			if($formConfig["awp_casePriority_selected"]==$casePriority->lookupId)
			{
				echo '<input type="hidden" name="priority_name" value="'.esc_attr($casePriority->meaning).'"/>';
				echo '<input type="hidden" id="priority" name="priority" value="'.esc_attr($casePriority->lookupId).'"/>';
				break;
			}

		}
	}
	elseif($status=="0")
	{

		foreach ($firstConfig->caseStatus as $caseStatus)
		{
			if($formConfig["awp_caseStatus_selected"]==$caseStatus->lookupId)
			{
				echo '<input type="hidden" name="status_name" value="'.esc_attr($caseStatus->meaning).'"/>';
				echo '<input type="hidden" id="status" name="status" value="'.esc_attr($caseStatus->lookupId).'"/>';
				break;
			}

		}
	}
}



add_action("admin_footer", "apptivo_business_cases_assignee_validation");

function apptivo_business_cases_assignee_validation() {
	?>
<script type="text/javascript">

    jQuery(document).ready(function($){

		/*Handled for Vulnerability Script Code Injection */
		$("#newcasesformname,#awp_cases_confirmationmsg").blur(function(event) { 
			var text = $(this).val().replace(/[^-\da-zA-Z0-9 ]/g,'');
			$(this).val(text);
			return true;
		});

		$("#awp_cases_submit_value").blur(function(event) { 
			var text = $(this).val().replace(/[^-\da-zA-Z0-9/:.]/g,'');
			$(this).val(text);
			return true;
		});

    	var selected_associates = jQuery('#awp_cases_associates').val();
    	if(selected_associates=='No Need'){
    		jQuery("#awp_cases_createassociate option[value=0]").attr("selected","selected");
    		jQuery("#awp_cases_createassociate").attr("disabled", "disabled");
    	}
    	else{
    		jQuery("#awp_cases_createassociate").removeAttr("disabled");
    		
    	}
    	jQuery("#awp_cases_associates").change(function(){
        	var selected_associates = jQuery('#awp_cases_associates').val();
        	if(selected_associates=='No Need'){
        		jQuery("#awp_cases_createassociate option[value=0]").attr("selected","selected");
        		jQuery("#awp_cases_createassociate").attr("disabled", "disabled");
        	}
        	else{
        		jQuery("#awp_cases_createassociate").removeAttr("disabled");
        		
        	}
        			
        });
        
        jQuery("#caseStatus_Id").change(function(){
        	jQuery("#caseStatusId").val(jQuery("#caseStatus_Id option:selected").text());
        });
        jQuery("#casePriority_Id").change(function(){
        	jQuery("#casePriorityId").val(jQuery("#casePriority_Id option:selected").text());
        });
        jQuery("#caseType_Id").change(function(){
        	jQuery("#caseTypeId").val(jQuery("#caseType_Id option:selected").text());
        });

		// Initial Select Assignee [ Employee/ Team ]
		jQuery("#awp_cases_select_assignee_employee").show();
		jQuery("#awp_cases_select_assignee_team").hide();

		// Changing Select Assignee [ Employee/ Team ]
    	jQuery("#awp_cases_select_assignee").change(function(){
			var selected_atype = jQuery('#awp_cases_select_assignee').val();
			jQuery(".awp_cases_select_assignee").hide();
			jQuery("#awp_cases_select_assignee_"+selected_atype).show();
			if(selected_atype == 'team'){
				jQuery("#select_assignee_name_val").val(jQuery("#awp_cases_select_assignee_team option:selected").text());	
			}
			else{
				jQuery("#select_assignee_name_val").val(jQuery("#awp_cases_select_assignee_employee option:selected").text());		
			}					
		});
    });
    </script>
<?php } ?>
