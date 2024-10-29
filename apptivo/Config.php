<?php 
require_once AWP_INC_DIR .'/config.php';

/*get firm dertails */
function getfirmdetails()
{
	$api_url = APPTIVO_API_URL.'app/appgencommon';
	$params = array (
			"a" => "getFirmDetails",
			
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	
	return $response;
	
}


/* get all US states list */
function getStateByCountryId($countryId){
	$api_url = APPTIVO_API_URL.'app/commonservlet';
	$params = array (
			"a" => "getAllStatesByCountryId",
			"countryId" =>$countryId,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response->responseObject;
}




/*
 * Lead configData
 */
function getleadConfigData(){

	$api_url = APPTIVO_API_URL . 'app/dao/v6/leads';
	$params = array (
			"a" => "getConfigData",
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" =>APPTIVO_ACCESS_KEY
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;

}


function createContact($customerName, $customerId, $fullName, $firstName, $lastName, $email, $phone, $countryText, $countryId, $coutryCode,$assigneeName,$assigneeId){
	
	$countryText = $countryText;
    $countryId = $countryId;
    $coutryCode =$coutryCode;
   // $state = ($state) ? $state : "";
   // $stateCode = ($stateCode) ? $stateCode : "";
	$firstname = $firstName;
	$lastname = $lastName;
	$fullname = $firstName . ' ' . $lastName;
	$email = $email;
	$phone = $phone;
	$address =$address; 
	//$address1 = ($address1) ? $address1 : "";  
    $city = $city;
    
	$accountname =$customerName;
	$accountId =$customerId;
	
	$contactJson = new stdClass ();
	$contactJson->firstName = $firstname;
	$contactJson->lastName = $lastname;
	$contactJson->accountName = $customerName;
	$contactJson->accountId = $customerId;
	
	//$assignee = getAssigneeDetails ();
	$contactJson->assigneeObjectRefName = $assigneeName;
	$contactJson->assigneeObjectRefId = $assigneeId;
	$contactJson->assigneeObjectId = "8";
	
	$contactJson->phoneNumbers = array ();
	$pNumber = new stdClass ();
	if ($phone != '') {
		$pNumber->phoneNumber = $phone;
		$pNumber->phoneType = "Business";
		$pNumber->phoneTypeCode = "PHONE_BUSINESS";
		$pNumber->id = "contact_phone_input";
		$contactJson->phoneNumbers [] = $pNumber;
	}
	
	$contactJson->emailAddresses = array ();
	$emailAddr = new stdClass ();
	if ($email != '') {
		$emailAddr->emailAddress = $email;
		$emailAddr->emailTypeCode = "BUSINESS";
		$emailAddr->emailType = "Business";
		$emailAddr->id = "cont_email_input";
		$contactJson->emailAddresses [] = $emailAddr;
	}
	
	

	$contactJson->addresses = array();
	$contaddr = new stdClass();
	if($countryText != ''){
		$contaddr->addressAttributeId="address_section_attr_id";
		$contaddr->countryId=$countryId;
		$contaddr->addressTypeCode="1";
		$contaddr->addressType="Billing Address";
		$contaddr->addressLine1=$address;
		$contaddr->addressLine2=$address1;
		$contaddr->city=$city;
		$contaddr->state=$state;
		$contaddr->stateCode=$stateCode;
		$contaddr->zipCode=$zipCode;
		$contaddr->county="";
		$contaddr->country=$countryText;
		$contaddr->countryName=$countryText;
		$contaddr->countryCode=$coutryCode;
		$contaddr->deliveryInstructions=null;
		$contaddr->addressGroupName="Address1";
		$contactJson->addresses[] = $contaddr;
	}
	

	
	$contactJson->customAttributes = $customAttributes;
	$contactJson = json_encode ( $contactJson );
	
	$api_url = APPTIVO_API_URL . 'app/dao/v6/contacts';
	$params = array (
			"a" => "save",
			"contactData" => $contactJson,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	
	
	$response = getRestAPICall1 ( 'POST', $api_url, $params );
	return $response;
	
}

/* check customer existing */
function checkIsexistingCustomer($fullName){
	
	$api_url = APPTIVO_API_URL . 'app/dao/customer';
	$params = array (
			"a" => "configurationCustomerDetails",
			"customerName" => $fullName,
			"customerNumber" => "Auto generated number",
			"objectId" => 3,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	
	
	$response = getRestAPICall1 ( 'POST', $api_url, $params );
	return $response;
}


function createCustomer($fullName, $firstname, $lastname, $email, $phone,$company,$countryText, $countryId, $coutryCode,$assigneeName,$assigneeId){
	
	$firstname = $firstName;
	$lastname = $lastName;
	$fullname = $fullName;
	$email = $email;
	
    $customerJson = new stdClass ();
	$customerJson->customerName = $company;
    //$customerJson->firstname= $firstname;
    //$customerJson->lastname= $lastname;
	//$assignee = getAssigneeDetails();
	
	$customerJson->assigneeObjectRefName = $assigneeName;
	$customerJson->assigneeObjectRefId = $assigneeId;
	$customerJson->assigneeObjectId = "8";
	
	
	$customerJson->emailAddresses = array ();
	$emailAddr = new stdClass ();
	if ($email != '') {
		$emailAddr->emailAddress = $email;
		$emailAddr->emailTypeCode = "BUSINESS";
		$emailAddr->emailType = "Business";
		$emailAddr->id = "cont_email_input";
		$customerJson->emailAddresses [] = $emailAddr;
	}
	
	
	$customerJson->phoneNumbers = array ();
	$pNumber = new stdClass ();
	if ($phone != '') {
		$pNumber->phoneNumber = $phone;
		$pNumber->phoneType = "Business";
		$pNumber->phoneTypeCode = "PHONE_BUSINESS";
		$pNumber->id = "contact_phone_input";
		$customerJson->phoneNumbers [] = $pNumber;
	}
	
	
	$customerJson = json_encode ( $customerJson );
	$api_url = APPTIVO_API_URL . 'app/dao/v6/customers';
	$params = array (
			"a" => "save",
			"customerData" => $customerJson,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	
	$response = getRestAPICall1 ( 'POST', $api_url, $params );
	return $response;
	
}

//add_action ( 'wp_ajax_createLead', 'createLead' );
//add_action ( 'wp_ajax_nopriv_createLead', 'createLead' );


/**
 * Generating the json for custom attributes based on that 
 * @param unknown_type $attrId
 * @param unknown_type $tagName
 * @param unknown_type $attrtype
 * @param unknown_type $attrVal
 * @param unknown_type $attrValId
 * @param unknown_type $tagId
 * @param unknown_type $currencyCode
 */
function customAttrJson($attrId,$tagName,$attrtype,$attrVal,$attrValId,$tagId,$currencyCode){
		
	$customArr = array();
	if($attrtype == 'number'){
		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']="number";
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$customArr[$tagName]=$attrVal;
		$customArr['numberValue'] = $attrVal;
		return $customArr;
	}
	
	else if($attrtype == 'currency'){
		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']="currency";
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$customArr[$tagName]=$attrVal;
		$customArr['currencyCode'] = $currencyCode;
		return $customArr;
	}
	
	else if($attrtype == 'input'){

		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']="input";
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$customArr[$tagName]=$attrVal;
		return $customArr;
	}
	
	else if($attrtype == 'date'){
		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']="date";
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$customArr[$tagName]=$attrVal;
		return $customArr;
	}
	
	else if($attrtype == 'select'){
		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']=$attrtype;
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$customArr[$tagName]=$attrVal;
		$customArr['customAttributeValueId'] = $attrValId;
		return $customArr;
		
	}
	else if($attrtype == 'radio'){
		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']="radio";
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$attrValues = array();
		$attributes = new stdClass();
		$attributes->attributeId = $tagId;
		$attributes->attributeValue = $attrVal;
		$attributes->shape = "";
		$attributes->color = "";
		$attrValues[] = $attributes;
		$customArr['attributeValues'] = $attrValues;
		$customArr['customAttributeValueId'] = $tagId;
		$customArr[$tagName] = $attrVal;
		$customArr['color'] = "" ;
		$customArr['shape'] = "" ;
		return $customArr;
	}
	
}

function customAttrSearchJson($attrId,$tagName,$attrtype,$attrVal,$attrValId,$tagId,$currencyCode){
$customArr = array();
	if($attrtype == 'check'){
		$customArr['customAttributeId']=$attrId;
		$customArr['customAttributeValue']=$attrVal;
		$customArr['customAttributeType']="check";
		$customArr['customAttributeTagName']=$tagName;
		$customArr['customAttributeName']=$tagName;
		$attrValues = array();
		$attributes = new stdClass();
		$attributes->attributeId = $tagId;
		$attributes->attributeValue = $attrVal;
		$attrValues[] = $attributes;
		$customArr['attributeValues'] = $attrValues;
		return $customArr;
	}
}

function getAllProjects($acountId,$customerName){
	$api_url = APPTIVO_API_URL . 'app/dao/v6/projects';
	$params = array (
			"a" => "getAllByAdvancedSearch",
			"startIndex"=>0,
			"numRecords"=>50,
			"searchData"=>'{"customerName": "'.$customerName.'","customerId": '.$acountId.'}',
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" =>APPTIVO_ACCESS_KEY
	);

	//print_r($params);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;
}


/*
 * create lead in apptivo
 */
function createLeadcall($leadJson){
	$api_url = APPTIVO_API_URL . 'app/dao/v6/leads';
	$params = array (
			"a" => "save",
			"leadData"=>$leadJson,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" =>APPTIVO_ACCESS_KEY
	);

	//print_r($params);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;

}


/*
 * Opportunity ConfigData
 */
function getOppConfigData() {
	$api_url = APPTIVO_API_URL . 'app/dao/v6/opportunities';
	$params = array (
			"a" => "getConfigData",
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1 ( 'POST', $api_url, $params );
	return $response;
}


/*
 * Opportunity ConfigData
 */
function getPropertyConfigData() {
	$api_url = APPTIVO_API_URL . 'app/dao/v6/properties';
	$params = array (
			"a" => "getConfigData",
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1 ( 'POST', $api_url, $params );
	return $response;
}

/*
 * getProjectById
 */
function getProjectById($projectId) {
	$api_url = APPTIVO_API_URL . 'app/dao/v6/projects';
	$params = array (
			"a" => "getById",
			"projectId" => $projectId,
			"includeBudget"=>"true",
			"includeMilestones"=>"true",
			"includeSubProjects"=>"true",
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	
	$response = getRestAPICall1( "POST", $api_url, $params );
	return $response;
}


/*
 * Projects getAllDocumentsByObjectIdObjectRefId
 */
function getAllProjectDocuments($projectId) {
	$api_url = APPTIVO_API_URL . 'app/dao/document';
	$params = array (
			"a" => "getAllDocumentsByObjectIdObjectRefId",
			"iDisplayStart" => 0,
			"numRecords" => 50,
			"objRefId" => $projectId,
			"objectId" => 88,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	
	$response = getRestAPICall1( "POST", $api_url, $params );
	return $response;
}


/*
 * getAllOpportunityNotes
 */
function getAllProjectNotes($projectId) {
	$api_url = APPTIVO_API_URL . 'app/dao/note';
	$params = array (
			"a" => "getNotes",
			"isFrom" => "App",
			"noteData" => '{}',
			"selectedObjectId" => 88,
			"selectedObjectRefId" => $projectId,
			"startIndex" => 0,
			"numRecords" => 10,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;
}

/*
 * Download URL
 */
function get_download_url($id) {
	$api_url = APPTIVO_API_URL . 'app/dao/document';
	$params = array (
			"a" => "getDocumentDownloadUrl",
			"id" => $id,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;
}

/*get All employees */
function getEnabledEmployees($enabledEmployees){
	$api_url = APPTIVO_API_URL . 'app/dao/v6/employees';
	$params = array (
			"a" => "getAllByAdvancedSearch",
			"iDisplayLength" => 50,
			"iDisplayStart" => 0,
			"numRecords"=> 50,
			//"selectedLetter"=>'All',
			"filterAdvData" => '{"departmentIds":[],"jobTitleIds":[],"locationIds":[],"workShiftIds":[],"categoryIds":[],"resourceTypeIds":[]}',
			"searchData"=>'{"customAttributes":['.$enabledEmployees.'],"assigneeObjectRefId":0,"description":"","labels":[],"maritalStatus":[],"status":[],"tagIds":[],"managerName":null,"managerId":null,"addresses":[{"addressAttributeId":"address_section_attr_id","addressGroupName":"Address1","state":"","stateCode":"","stateId":null,"stateName":""}],"phoneNumbers":[]}',
			"startIndex"=>0,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1 ( 'POST', $api_url, $params );
	return $response;
}


/*
 * Upload document to the Opportunity
 */
function uploadDocument1( $leadId, $file_name, $file_ext, $file_size, $base64) {
	$api_url = APPTIVO_API_URL . 'app/dao/document';
	$params = array (
			"a" => "uploadDoc",
			"objectId" => LEAD_OBJECT_ID,
			"objectRefId" => $leadId,
			"docName" => $file_name,
			"docTitle" => $file_name,
			"docType" => $file_ext,
			"docSize" => $file_size,
			"encodedDocStr" => $base64,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" => APPTIVO_ACCESS_KEY 
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	
	return $response;
}

function addContactsToLead($leadId,$contactId){

	$api_url = APPTIVO_API_URL . 'app/dao/v6/leads';
	$params = array (
			"a" => "addContacts",
			"contactIds"=>'{"id":['.$contactId.']}',
			"leadId"=>$leadId,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" =>APPTIVO_ACCESS_KEY
	);
	//print_r($params);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;
}




/*
 * API method - Curl call
 */
if(!function_exists('getRestAPICall1')){
	function getRestAPICall1($method, $url, $data = false) {
		$proxysettings = array();
		//$proxysettings = get_option('awp_proxy_settings');
				error_log("\n"  ,  3, 'Restapicall.log');
				error_log(date("Y/m/d h:i:sa   " ) ,  3, 'Restapicall.log');
				error_log("\n"  ,  3, 'Restapicall.log');

				error_log("data ="  ,  3, 'Restapicall.log');
				error_log("\n"  ,  3, 'Restapicall.log');
				
				error_log(json_encode($data, TRUE), 3, 'Restapicall.log');
				error_log("\n"  ,  3, 'Restapicall.log');
	
		
		// $uuu = $url. '?'.http_build_query($data);
		// logging url params to given log file 
		// error_log($uuu, 3, 'Restapicall.log'); 
		error_log("\n"  ,  3, 'Restapicall.log');

		/* Used Remote GET Function*/
		// $response = wp_remote_get($uuu);

		$response = wp_remote_post($url,array(
			'method' => $method,
			'body'=> $data,
			'timeout'=> 120, // Added for this error cURL error 28: Operation timed out after 5012 milliseconds with 15991 bytes received
			'headers'=> ['Content-Type' => 'application/x-www-form-urlencoded;   charset=utf-8',],
				//'httpversion' => '1.1',
				'sslverify'   => false,
		));

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			error_log("Something went wrong issue:".$error_message);
		}	

		$remoteresponse= wp_remote_retrieve_body($response);
				
		$result = json_decode($remoteresponse);
		
		return $result;
	}
}

?>
