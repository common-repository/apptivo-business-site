<?php

/* Apptivo Lead functionality for Contacts form */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/labelDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/noteDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/LeadDetails.php';

//require_once AWP_ASSETS_DIR.'/captcha/simple-captcha/simple-captcha.php';
/**
 * Class AWPAPIServices
 */
Class AWPAPIServices {

    /**
     * To Get All Countries name and country code.
     *
     * @return unknown
     */
    public function getAllCountries() {
        if (_isCurl()) {
            $params = array(
                "a" => "getLocations",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
			
            $response = getRestAPICall("POST", APPTIVO_SIGNUP_API, $params);
			
        } else {
          /*  $params = array(
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getsoapCall(APPTIVO_BUSINESS_SERVICES, 'getAllCountries', $params);
			*/
			error_log("line 38 AWIServices.php", 3, "curlenable.log");
			error_log("curl not enabled please enable curl", 3, "curl_enable.log");
			$params = array(
                "a" => "getLocations",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
			
            $response = getRestAPICall("POST", APPTIVO_SIGNUP_API, $params);
            $response = $response->return;
        }
        return $response;
    }

    
    
	/*
	 * userData for assignee
	 */
	function getAssigneeDetails(){

		$api_url = APPTIVO_API_URL . 'app/commonservlet';
		$params = array(
			"a" => "getUserData",
			"apiKey" => APPTIVO_BUSINESS_API_KEY,
			"accessKey" =>APPTIVO_BUSINESS_ACCESS_KEY
		);
		$response = getRestAPICall( 'POST', $api_url, $params );
		return $response;
	}
    
    
    
	function searchCustomerByemail($email){
		$params = array (
			'a' => 'getAllByAdvancedSearch',
			'searchData' => '{"emailAddresses":[{"emailAddress":"'.$email.'","id":"cont_email_input"}]}',
			'apiKey' => APPTIVO_BUSINESS_API_KEY,
			'accessKey' => APPTIVO_BUSINESS_ACCESS_KEY
		);
		$customer = getRestAPICall1( 'POST', APPTIVO_CUSTOMER_V6_API, $params );
        	//error_log("searchCustomerByemail customer :- ".json_encode($customer));
		if($customer->countOfRecords > 0){
			return $customer->data[0];
		}else if($customer->countOfRecords == 0){
            $customer = array();
			return $customer;	
        }
	
    }

    public function getStateCodeByCountryId($countryId,$state)
    {
        $api_url = APPTIVO_API_URL.'app/commonservlet';
        $params = array(
            'a' => 'getAllStatesByCountryId',
            'countryId' => $countryId,
            'apiKey' => APPTIVO_BUSINESS_API_KEY,
            'accessKey' => APPTIVO_BUSINESS_ACCESS_KEY
            );
        
        $response = getRestAPICall( 'POST', $api_url, $params );
        $stateCode = "";
        $stateArr = $response->responseObject;
        foreach($stateArr as $statearr)
        {
            if((strtolower($state)) == (strtolower($statearr->stateName)))
            {
                $stateCode = $statearr->stateCode;
            }
        }
        return $stateCode;
    }
    
    /**
     * To Get All Target Lists from Apptivo.
     *
     * @return unknown
     */
    public function getTargetListcategory() {
        if (_isCurl()) {
       
        	  /*   $params = array(
                "a" => "getTargetList",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getRestAPICall("POST", APPTIVO_TARGETS_API, $params);
            $response = $response->aaData; */
          $params = array(
                "a" => "getAllTargetLists",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getRestAPICall("POST", APPTIVO_TARGETS_V6_API, $params);
           
			$response = $response->data; 
        } else {
            $params = array(
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $response = getsoapCall(APPTIVO_BUSINESS_SERVICES, 'getAllTargetLists', $params);
            $response = $response->return->targetList;
        }
        return $response;
    }

    /**
     * To SaveContact Lead details.
     *
     * @param unknown_type $firstName
     * @param unknown_type $lastName
     * @param unknown_type $emailId
     * @param unknown_type $jobTitle
     * @param unknown_type $company
     * @param unknown_type $address1
     * @param unknown_type $address2
     * @param unknown_type $city
     * @param unknown_type $state
     * @param unknown_type $zipCode
     * @param unknown_type $bestWayToContact
     * @param unknown_type $countryCode
     * @param unknown_type $leadSource
     * @param unknown_type $phoneNumber
     * @param unknown_type $comments
     * @param unknown_type $noteDetails
     * @return unknown
     */
    public function saveLeadDetails($firstName, $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $address, $addressId, $countryId, $countryName,$countryCode, $leadSource, $leadSourceId, $phoneNumber, $comments, $noteDetails, $targetlistid, $customerAccountId, $customerAccountName, $contact_status, $contact_type, $contact_rank, $contact_status_id, $contact_type_id, $contact_rank_id, $contact_label_id, $assigneeName, $assigneeObjId, $assigneeObjRefId, $customAttributes) {
        $customassigneevalues = '';
        $customfieldvalues ='';
        if ($contact_type_id != '') {
            $customassigneevalues = '"leadTypeName":"' . $contact_type . '","leadTypeId":' . $contact_type_id . ',';
        }
        if ($contact_status_id != '') {
            $customassigneevalues .= '"leadStatus":"' . $contact_status_id . '","leadStatusMeaning":"' . $contact_status . '",';
        }
        if ($contact_rank_id != '') {
            $customassigneevalues .= '"leadRank":"' . $contact_rank_id . '","leadRankMeaning":"' . $contact_rank . '",';
        }
        if ($assigneeObjRefId != '') {
            $customassigneevalues .= '"assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectRefId":' . $assigneeObjRefId . ',"assigneeObjectId":' . $assigneeObjId . ',';
        }
        if ($customerAccountId != '') {
            $customassigneevalues .= '"accountName":"' . $customerAccountName . '","accountId":' . $customerAccountId . ',';
        }

        $contactConfigData = getContactConfigData();

        $lead_tags = array();        
        if(isset($contactConfigData->labels)){
            $selectedTagValues = array();
            if($contact_label_id != ''){
                // selected tagid
                foreach(explode(',', sanitize_text_field($contact_label_id)) as $contact_tag){
                    //available tagid
                    foreach ($contactConfigData->labels as $labelsval){                        
                        $selectedTagValues[] = $contact_tag;
                        // comparing whether the selected tagid is in available tagid
                        if($labelsval->labelId == $contact_tag){

                            $lead_tags[] = $labelsval;

                            // check the label version
                            /*if($labelsval->version == 0){
                                $labelObj = new stdClass();
                                $labelObj->id = isset($labelsval->id)?$labelsval->id:'';
                                $labelObj->labelId = isset($labelsval->labelId)?$labelsval->labelId:'';
                                $labelObj->labelName = isset($labelsval->labelName)?$labelsval->labelName:'';
                                //$labelObj->objectLabelId = isset($labelsval->objectLabelId)?$labelsval->objectLabelId:'';
                                $labelObj->objectId = isset($labelsval->objectId)?$labelsval->objectId:'';
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
                                $labelObj->version = isset($labelsval->version)?$labelsval->version:'';
                                $labelObj->toObjectIds =  array();
                                $lead_tags[] = $labelObj;
                            }elseif($labelsval->version == 1){
                                $labelObj = new stdClass();
                                $labelObj->id = isset($labelsval->id)?$labelsval->id:'';
                                $labelObj->labelId = isset($labelsval->labelId)?$labelsval->labelId:'';
                                $labelObj->labelName = isset($labelsval->labelName)?$labelsval->labelName:'';
                                //$labelObj->objectLabelId = isset($labelsval->objectLabelId)?$labelsval->objectLabelId:'';
                                
                                // Check if objectId is not empty before adding it to the labelObj
                                // Checking for setting for Lead App Tags or Global Tags
                                if (!empty($labelsval->objectId)) {
                                    $labelObj->objectId = isset($labelsval->objectId)?$labelsval->objectId:'';
                                }
                                
                                $labelObj->firmId = isset($labelsval->firmId)?$labelsval->firmId:'';
                                $labelObj->agId = isset($labelsval->agId)?$labelsval->agId:'';
                                $labelObj->version = isset($labelsval->version)?$labelsval->version:'';
                                $lead_tags[] = $labelObj;
                            }*/

                        }
                    }
                }
            }
        }

        /* custom attribute */
        if($customAttributes != ''){
        	$customfieldvalues .= '"customAttributes":'.json_encode($customAttributes).',';
        }
        $stateCode = "";

        if((!empty($state)) && (($countryId == 176) || ($countryId == 26) || ($countryId == 150))){
            $stateCode = $this->getStateCodeByCountryId($countryId,$state);
        }

        $leads = '{'.$customfieldvalues.'"firstName":"' . addslashes($firstName ?? '') . '","lastName":"' . addslashes($lastName ?? '') . '","jobTitle":"' . addslashes($jobTitle ?? '') . '","easyWayToContact":"' . $bestWayToContact . '","wayToContact":"' . $bestWayToContact . '","leadSource":"' . $leadSourceId . '","leadSourceMeaning":"' . $leadSource . '",' . $customassigneevalues . '"description":"' . addslashes($comments ?? '') . '","companyName":"' . addslashes($company ?? '') . '","phoneNumbers":[{"phoneNumber":"' . addslashes($phoneNumber ?? '') . '","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","id":"lead_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business","id":"cont_email_input"}],"labels":'.json_encode($lead_tags).',"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"' . $addressId . '","addressGroupName": "Address1","deliveryInstructions": null,"addressType":"' . $address . '","addressLine1":"' . addslashes($address1 ?? '') . '","addressLine2":"' . addslashes($address2 ?? '') . '","city":"' . addslashes($city ?? '') . '","state":"' . addslashes($state ?? '') . '","stateCode":"' . addslashes($stateCode ?? '') . '","zipCode":"' . addslashes($zipCode ?? '') . '","countryId":' . $countryId . ',"countryCode":"' . $countryCode . '","countryName":"' . addslashes($countryName ?? '') . '"}]}';
	  
	    $params = array(
            //"a" => "createLead",
         	"a" => "save",
            "leadData" => $leads,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY,
            "isDuplicateCheck"=>"Y"
        );

        //error_log("saveLeadDetails params :- ".json_encode($params));

        //$response = getRestAPICall("POST", APPTIVO_LEAD_API,$params);
        $response = getRestAPICall("POST", APPTIVO_LEAD_V6_API,$params);
        return $response;
    }

    /* Save Notes to Objects */
    public function saveNotes($objectId,$objRefId,$objectRefName,$noteText) {
        $noteTextDetails = '{"noteText":"' . $noteText . '","objectId": '.$objectId.',"objectRefId": "'.$objRefId.'","associations":[{"objectId": '.$objectId.',"objectRefId": "'.$objRefId.'","objectRefName":"'.$objectRefName.'","parentObjectRefId": null,"parentObjectId": 7}]}';
        $param = array(
            "a" => "createNote",
            "noteDetails" => "$noteTextDetails",
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $notesResponse = getRestAPICall("POST", APPTIVO_NOTES_V6_API, $param);
        $noteid = $notesResponse->noteId;
        return $noteid;
    }
    
    /**
     * Save Notes Details TargetList */
    public function createTargetListNotes($comments, $notesLabel,$targetId,$objectId,$objRefId,$objectRefName) {
        $commentText = "<b>" . $notesLabel . " : </b>" . $comments;
        $noteTextDetails = '{"noteText":"' . $commentText . '","objectId": '.$objectId.',"objectRefId": "'.$objRefId.'","associations":[{"objectId": '.$objectId.',"objectRefId": "'.$objRefId.'","objectRefName":"'.$objectRefName.'","parentObjectRefId": null,"parentObjectId": 7}]}';
        $param = array(
            "a" => "createNote",
            "noteDetails" => "$noteTextDetails",
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        
        $noteResponse = getRestAPICall("POST", APPTIVO_NOTES_V6_API, $param);
        return $noteResponse;
    }

    /**
     * Notes Details..
     *
     * @param unknown_type $label
     * @param unknown_type $nodeDetails
     * @param unknown_type $noteId
     * @return unknown
     */
    public function notes($label, $nodeDetails, $noteId) {
        $labelDetails = new AWP_labelDetails($labelId = null, $label);
        $notetextDetails = new AWP_noteDetails($labelDetails, $noteId, addslashes($nodeDetails));
        return $notetextDetails;
    }

    /* Save Case Details */

    public function createCases($caseNumber, $caseStatus, $caseStatusId, $caseType, $caseTypeId, $casePriority, $casePriorityId, $assigneeName, $assigneeObjId, $assigneeObjRefId, $caseSummary, $caseDescription, $customerAccountName, $customerAccountId, $contactAccountName, $contactAccountId, $emailId,$customAttributes) {

        $customassigneevalues = '';
        $customfieldvalues='';
        if ($caseStatusId != '') {
            $customassigneevalues = '"caseStatus":"' . htmlspecialchars($caseStatus) . '","caseStatusId":"' . $caseStatusId . '",';
        }
        if ($caseTypeId != '') {
            $customassigneevalues .= '"caseType":"' . htmlspecialchars($caseType) . '","caseTypeId":"' . $caseTypeId . '",';
        }
        if ($casePriorityId != '') {
            $customassigneevalues .= '"casePriority":"' . htmlspecialchars($casePriority) . '","casePriorityId":"' . $casePriorityId . '",';
        }
        if ($assigneeObjRefId != '') {
            $customassigneevalues .= '"assignedObjectRefName":"' . htmlspecialchars($assigneeName) . '","assignedObjectId":' . $assigneeObjId . ',"assignedObjectRefId":' . $assigneeObjRefId . ',';
        }
        if ($customerAccountId != '') {
            $customassigneevalues .= '"caseCustomer":"' . $customerAccountName . '","caseCustomerId":' . $customerAccountId . ',';
        }
        if ($contactAccountId != '') {
            $customassigneevalues .= '"caseContact":"' . $contactAccountName . '","caseContactId":' . $contactAccountId . ',';
        }
        /* custom attribute */
        if($customAttributes != ''){
        	$customfieldvalues .= '"customAttributes":'.json_encode($customAttributes).',';
        }
        $caseData = '{'.$customfieldvalues.'"caseNumber":"' . stripslashes(trim($caseNumber)) . '",'.$customassigneevalues.'"caseSummary":"' . addslashes($caseSummary) . '","description":"' . addslashes($caseDescription) . '","caseEmail":"' . $emailId . '","addresses":[]}';
       /* $params = array(
            "a" => "createCase",
            "caseData" => $caseData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_CASES_API, $params);*/
        $params = array(
            "a" => "save",
            "caseData" => $caseData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_CASE_V6_API, $params);
        return $response;
    }

    

    /*
     * To associate Cases with Contact and Customer
     *
     */

    public function awpContactAssociates($emailId, $option) {
        $associatesDetails = array();
        $customerAccountId = "";
        $customerAccountName = "";

        if ($option == "Customer") {
            
        	/*$searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"-1","emailType":"","id":"cont_email_input"}]}';
            $customerParams = array(
                "a" => "getAllCustomersByAdvancedSearch",
                "objectId" => APPTIVO_CUSTOMER_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_API, $customerParams);

            if (isset($customerResponse->customers)) {
                foreach ($customerResponse->customers as $key => $customerData) {
                    if (isset($customerData->emailAddresses)) {
                        foreach ($customerData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $customerAccountId = $customerData->customerId;
                                $customerAccountName = $customerData->customerName;
                            }
                        }
                    }
                }
            }*/
            
        $searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","id":"cont_email_input"}]}';
        	
        	$customerParams = array(
                "a" => "getAllByAdvancedSearch",
                "objectId" => APPTIVO_CUSTOMER_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
               
            $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_V6_API, $customerParams);
            
             if (isset($customerResponse->data) && $customerResponse->countOfRecords > 0) {
                foreach ($customerResponse->data as $key => $customerData) {
                    if (isset($customerData->emailAddresses)) {
                        foreach ($customerData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $customerAccountId = $customerData->customerId;
                                $customerAccountName = $customerData->customerName;
                            }
                        }
                    }
                }
            }
            $associatesDetails['leadCustomerId'] = $customerAccountId;
            $associatesDetails['leadCustomer'] = $customerAccountName;
            if ($option == "Customer") {
                return $associatesDetails;
            }
        }
        return $associatesDetails;
    }

    /* Create Customer along with Address for Association with Leads */

    function createLeadCustomer($lastName,$assigneeName,$assigneeObjId,$assigneeObjRefId,$phoneNumber,$emailId,$address1, $address2, $city, $state, $zipCode, $address, $addressId, $countryId, $countryName, $countryCode){

        $createCustomerDetails = array();

        $stateCode = "";
        if((!empty($state)) && (($countryId == 176) || ($countryId == 26) || ($countryId == 150))){
            $stateCode = $this->getStateCodeByCountryId($countryId,$state);
        }

        $customerData = '{"customerName":"' . $lastName . '","customerNumber":"Auto generated number","assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectId":' . $assigneeObjId . ',"assigneeObjectRefId":' . $assigneeObjRefId . ',"phoneNumbers":[{"phoneNumber":"' . $phoneNumber . '","phoneTypeCode":"PHONE_BUSINESS","phoneType":"Business","id":"cust_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business", "id": "cont_email_input"}],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"' . $addressId . '","addressGroupName": "Address1","deliveryInstructions": null,"addressType":"' . $address . '","addressLine1":"' . addslashes($address1 ?? '') . '","addressLine2":"' . addslashes($address2 ?? '') . '","city":"' . addslashes($city ?? '') . '","state":"' . addslashes($state ?? '') . '","stateCode":"' . addslashes($stateCode ?? '') . '","zipCode":"' . addslashes($zipCode ?? '') . '","countryId":' . $countryId . ',"countryCode":"' . $countryCode . '","countryName":"' . addslashes($countryName ?? '') . '"}]}';

        $customerParams = array(
            "a" => "save",
            "customerData" => $customerData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $customerResponse = getRestAPICall1("POST", APPTIVO_CUSTOMER_V6_API, $customerParams);

        if ($customerResponse->customer->customerId != ""){
            $customerAccountId = $customerResponse->customer->customerId;
            $customerAccountName = $customerResponse->customer->customerName;
        }

        $createCustomerDetails['leadCustomerId'] = $customerAccountId;
        $createCustomerDetails['leadCustomer'] = $customerAccountName;

        return $createCustomerDetails;
    }



    /* Create Associate Customer */

    function createCustomer($lastName, $assigneeName, $assigneeObjId, $assigneeObjRefId, $phoneNumber, $emailId) {

        $createCustomerDetails = array();


        $customerData = '{"customerName":"' . $lastName . '","customerNumber":"Auto generated number","assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectId":' . $assigneeObjId . ',"assigneeObjectRefId":' . $assigneeObjRefId . ',"phoneNumbers":[{"phoneNumber":"' . $phoneNumber . '","phoneTypeCode":"PHONE_BUSINESS","phoneType":"Business","id":"cust_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business", "id": "cont_email_input"}]}';
        
        /*  $customerParams = array(
            "a" => "createCustomer",
            "customerData" => $customerData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_API, $customerParams);*/
        
	    $customerParams = array(
            "a" => "save",
            "customerData" => $customerData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $customerResponse = getRestAPICall1("POST", APPTIVO_CUSTOMER_V6_API, $customerParams);
        //error_log("customerParams customerResponse :- ".json_encode($customerResponse));
		
        if ($customerResponse->customer->customerId != ""){
            $customerAccountId = $customerResponse->customer->customerId;
            $customerAccountName = $customerResponse->customer->customerName;
        }
        
        $createCustomerDetails['leadCustomerId'] = $customerAccountId;
        $createCustomerDetails['leadCustomer'] = $customerAccountName;

        return $createCustomerDetails;
    }

    /* Create Contact */

    function createContact($firstName, $lastName, $assigneeName, $assigneeObjRefId, $assigneeObjId, $phoneNumber, $emailId) {

        $createContactDetails = array();
       $contactData = '{"firstName":"' . $firstName . '","lastName":"' . $lastName . '","assigneeObjectRefName":"' . $assigneeName . '","assigneeObjectRefId":' . $assigneeObjRefId . ',"assigneeObjectId":' . $assigneeObjId . ',"phoneNumbers":[{"phoneNumber":"' . $phoneNumber . '","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","id":"contact_phone_input"}],"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"BUSINESS","emailType":"Business","id":"cont_email_input"}],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"1","addressType":"Billing Address","addressLine1":"","addressLine2":"","city":"","stateCode":"","state":"","zipCode":"","countryId":176,"countryName":"United States","countryCode":"US"}],"syncToGoogle":"Y"}';
       
     /*  $contactParams = array("a" => "saveContact",
            "contactData" => $contactData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $contactResponse = getRestAPICall("POST", APPTIVO_CONTACTS_API, $contactParams);*/
        
        $contactParams = array("a" => "save",
            "contactData" => $contactData,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        
        $contactResponse = getRestAPICall("POST", APPTIVO_CONTACT_V6_API, $contactParams);

        if ($contactResponse->contact->contactId != "") {
            $contactAccountId = $contactResponse->contact->contactId;
            $contactAccountName = $contactResponse->contact->fullName;
        }
        $createContactDetails['leadContactId'] = $contactAccountId;
        $createContactDetails['leadContact'] = $contactAccountName;
        return $createContactDetails;
    }

    /*
     * To associate Cases with Contact and Customer
     *
     */

    function awpCaseAssocciates($emailId, $option) {
        $contactResponse = new stdClass();
        $associatesDetails = array();
        $customerAccountId = "";
        $contactAccountId = "";
        $contactAccountName = "";
        $customerAccountName = "";
        if ($option == "Contact" || $option == "Both") {
        	  $searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","id":"cont_email_input"}]}';
          /*  $searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"-1","emailType":"","id":"cont_email_input"}]}';
            $contactParams = array(
                "a" => "getAllContactsByAdvancedSearch",
                "objectId" => APPTIVO_CONTACT_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $contactResponse = getRestAPICall("POST", APPTIVO_CONTACTS_API, $contactParams); */
            
            $contactParams = array(
                "a" => "getAllByAdvancedSearch",
                "objectId" => APPTIVO_CONTACT_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $contactResponse = getRestAPICall("POST", APPTIVO_CONTACT_V6_API, $contactParams);
            

            /*if (isset($contactResponse->contacts)) {
                foreach ($contactResponse->contacts as $key => $contactData) {
                    if (isset($contactData->emailAddresses)) {
                        foreach ($contactData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $contactAccountId = $contactData->contactId;
                                $contactAccountName = $contactData->fullName;
                            }
                        }
                    }
                }
            }*/
            
        if (isset($contactResponse->data) && $contactResponse->countOfRecords > 0) {
                foreach ($contactResponse->data as $key => $contactData) {
                    if (isset($contactData->emailAddresses)) {
                        foreach ($contactData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $contactAccountId = $contactData->contactId;
                                $contactAccountName = $contactData->fullName;
                            }
                        }
                    }
                }
            }
            
            $associatesDetails['caseContactId'] = isset($contactAccountId)?$contactAccountId:'';
            $associatesDetails['caseContact'] = isset($contactAccountName)?$contactAccountName:'';
            if ($option == "Both" && isset($contactResponse->contacts[0]->accountId) && $contactResponse->contacts[0]->accountId != "") {
                $associatesDetails['caseCustomerId'] = $contactResponse->contacts[0]->accountId;
                $associatesDetails['caseCustomer'] = $contactResponse->contacts[0]->accountName;
                return $associatesDetails;
            }
            if ($option == "Contact") {
                return $associatesDetails;
            }
        }
        if ($option == "Customer" || $option == "Both") {

        	
        	/*$searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","emailTypeCode":"-1","emailType":"","id":"cont_email_input"}]}';
            $customerParams = array(
                "a" => "getAllCustomersByAdvancedSearch",
                "objectId" => APPTIVO_CUSTOMER_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
            $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_API, $customerParams);

            if (isset($customerResponse->customers)) {
                foreach ($customerResponse->customers as $key => $customerData) {
                    if (isset($customerData->emailAddresses)) {
                        foreach ($customerData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $customerAccountId = $customerData->customerId;
                                $customerAccountName = $customerData->customerName;
                            }
                        }
                    }
                }
            }*/
        	
        	$searchData = '{"emailAddresses":[{"emailAddress":"' . $emailId . '","id":"cont_email_input"}]}';
        	
        	$customerParams = array(
                "a" => "getAllByAdvancedSearch",
                "objectId" => APPTIVO_CUSTOMER_OBJECT_ID,
                "startIndex" => "0",
                "numRecords" => "1",
                "sortColumn" => "_score",
                "sortDir" => "desc",
                "searchData" => $searchData,
                "multiSelectData" => "{}",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
            );
               
            $customerResponse = getRestAPICall("POST", APPTIVO_CUSTOMER_V6_API, $customerParams);
            
             if (isset($customerResponse->data) && $customerResponse->countOfRecords > 0) {
                foreach ($customerResponse->data as $key => $customerData) {
                    if (isset($customerData->emailAddresses)) {
                        foreach ($customerData->emailAddresses as $key1 => $emailData) {
                            if ($emailData->emailAddress == $emailId) {
                                $customerAccountId = $customerData->customerId;
                                $customerAccountName = $customerData->customerName;
                            }
                        }
                    }
                }
            }
            $associatesDetails['caseCustomerId'] = $customerAccountId;
            $associatesDetails['caseCustomer'] = $customerAccountName;
            if ($option == "Customer") {
                return $associatesDetails;
            }
        }
        return $associatesDetails;
    }
}
?>
