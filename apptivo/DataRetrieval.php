<?php
class DataRetrieval{
	
	
	public function customAttribute($customAttributes,$sectionName,$labelName,$attribute, $fieldArr){
			
			$rightData = $attribute->getRightData();
 		 	$attributeId = $attribute->getAttributeId();
			$attributeTagName = $rightData[0]->tagName;
			$attributeType = $rightData[0]->tag;
			$rightarr = new stdClass();
			
			if($attributeType == 'referenceField'){
				$refAppObect = $rightData[0]->referenceAppObject;
				$refAppObjId = $refAppObect->objectId;
				$refAppAttrId = $refAppObect->attributeId;
			}
			
			if($attributeType == 'select'){
				$options = $rightData[0]->optionValueList;
				if(array_key_exists($labelName, $fieldArr)){
					$apptivoselectOptionObject = array();
					foreach ( $options as $option ) {
						$apptivoselectOptionObject[trim($option->optionObject)] = $option->optionId;
					}
					 
					 
					if(array_key_exists(trim($fieldArr[$labelName]),$apptivoselectOptionObject)){
							$selectedValue = trim($fieldArr[$labelName]);
							$selectedValueId = $apptivoselectOptionObject[trim($fieldArr[$labelName])];
					}else{
							$_SESSION['AddselectMismatchValues'] = "<b>".$labelName."</b>:".stripslashes($fieldArr[$labelName]);
					}
					 
				}
			}
 		 	else if($attributeType == 'number' || $attributeType == 'input' || $attributeType == 'date' || $attributeType == 'currency' || $attributeType == 'textarea'){
 		 		if(array_key_exists($labelName,$fieldArr)){
				$selectedValue = $fieldArr[$labelName];
				}
			}
			
			else if($attributeType == 'radio'){
				if(array_key_exists($labelName,$fieldArr)){
					$selectedValue =  $fieldArr[$labelName];
					$apptivoradioOptionObject = array();
						foreach($attribute->rightData as $rightarr){
							$apptivoradioOptionObject[trim($rightarr->options[0])] = $rightarr->tagId;
						}
						
						
						if(array_key_exists(trim($selectedValue),$apptivoradioOptionObject)){
							$attributeTagId = $apptivoradioOptionObject[trim($selectedValue)];	
						}else{
							$_SESSION['AddradioMismatchValues'] = "<b>".$labelName."</b>:".stripslashes($selectedValue);
						}
													
				}
			}
			else if($attributeType == 'check'){
				$checkvalArr = array();
				if(array_key_exists($labelName,$fieldArr)){
					$selectedValue =  $fieldArr[$labelName];
					$selectedValue = explode(',',$selectedValue);
					$attributeTagId = isset($rightarr->tagId)?$rightarr->tagId:'';
					$attrArr = array ();
					$checkMismatchval = array();
					$apptivoOptionObject  = array();
					
					foreach ( $attribute->rightData as $opt ) {
						$apptivoOptionObject[trim($opt->options[0])] = $opt->tagId;
					}
						
					
					foreach ( $selectedValue as $addon ) {
									
								if(array_key_exists(trim($addon),$apptivoOptionObject)){
									
									$attrVal = new stdClass();
									$attrVal->attributeId = $apptivoOptionObject[trim($addon)];
									$attrVal->attributeValue = $addon;
									$attrVal->shape = "";
									$attrVal->color = "";
									$attrArr[] = $attrVal;
									
								}else{
									$checkMismatchval[$labelName][] = trim($addon);
									$_SESSION['AddcheckMismatchValues'] = $checkMismatchval;
								}
					}
					
					
					$selectedValue = $attrArr;
					
				}
		
			}else if($attributeType == 'multiSelect'){
				$checkvalArr = array();
				if(array_key_exists($labelName,$fieldArr)){
					$selectedValue =  $fieldArr[$labelName];
					$selectedValue = explode(',',$selectedValue);
					$rightarr->tagId = (property_exists($rightarr,'tagId')) ? $rightarr->tagId : '';
					$attributeTagId = $rightarr->tagId;
					$attrArr = array ();
					$checkMismatchval = array();
					$apptivoOptionObject  = array();


					foreach($selectedValue as $addon){
						
						if(!empty($addon)){
							
							foreach($attribute->rightData as $opt){
								
								$attrVal = new stdClass();
								
								foreach($opt->optionValueList as $value){
									$val = $value->optionObject;
									
									if( $val !== null && $addon !== null && trim(strtoupper($val)) == trim(strtoupper($addon)) ){
										$attrVal->attributeId = $value->optionId;
										$attrVal->attributeValue = $addon;
										$attrVal->shape = "";
										$attrVal->color = "";
										$attrArr[] = $attrVal;
									}else{
										$checkMismatchval[$labelName][] = trim($addon);
										$_SESSION['AddcheckMismatchValues'] = $checkMismatchval;
									}
								}
							}
						}
					}

					$selectedValue = $attrArr;
				}
		
			}
			else if($attributeType == 'referenceField'){
			
				if(array_key_exists($labelName,$fieldArr[$sectionName])){
					$selectedValue = $fieldArr[$sectionName][$labelName];
					if($refAppAttrId == 'contact_phone_attr' || $refAppAttrId == 'email_attr' ){
						if($refAppphoneType != ''){
							$phoneEmailType = $refAppphoneType;
						}
					
						if($refAppemailType != ''){
							$phoneEmailType = $refAppemailType;
						}
					}			
				}		  
			  
			}
			
			else if($attributeType == 'fileUpload'){
				if(array_key_exists($labelName,$fieldArr)){
					$selectedValue = $fieldArr[$labelName];
				}				
			}
			
			elseif ($attributeType == 'simpleTextarea'){
			if(array_key_exists($labelName,$fieldArr)){
					$selectedValue = $fieldArr[$labelName];
				}
			}
			
			
			$refAppfieldType = "phoneEmail";
			
			if(isset($selectedValue) && $selectedValue != ''){
				$sectionId = isset($sectionId)?$sectionId:'';
				$currencyCode = isset($currencyCode)?$currencyCode:'';
				$phoneEmailType = isset($phoneEmailType)?$phoneEmailType:'';
				$refAppObjId = isset($refAppObjId)?$refAppObjId:'';
				$refAppAttrId = isset($refAppAttrId)?$refAppAttrId:'';
				$selectedValueId = isset($selectedValueId)?$selectedValueId:'';
				$attributeTagId = isset($attributeTagId)?$attributeTagId:'';
				$customAttributeArr = $this->getcustomAttrJson($sectionId,$attributeId,$attributeTagName, $attributeType ,$selectedValue,$selectedValueId,$attributeTagId,$currencyCode,$phoneEmailType,$refAppfieldType,$refAppObjId,$refAppAttrId);
				return $customAttributeArr;
			}
	
	}
	
	
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
	public function getcustomAttrJson($sectionId,$attrId,$tagName,$attrtype,$attrVal,$attrValId,$tagId,$currencyCode,$phoneEmailType,$refAppfieldType,$refAppObjId,$refAppAttrId){		

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
		}else if($attrtype == 'textarea'){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']=$attrVal;
			$customArr['customAttributeType']="textarea";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr[$tagName]=$attrVal;
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
		}else if($attrtype == 'input'){
			
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']=$attrVal;
			$customArr['customAttributeType']="input";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr[$tagName]=$attrVal;
			return $customArr;
		}else if($attrtype == 'date'){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']=$attrVal;
			$customArr['customAttributeType']="date";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr[$tagName]=$attrVal;
			return $customArr;
		}else if($attrtype == 'select'){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']=$attrVal;
			$customArr['customAttributeType']=$attrtype;
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr[$tagName]=$attrVal;
			$customArr['customAttributeValueId'] = $attrValId;
			return $customArr;
			
		}else if($attrtype == 'radio' && $tagId != '' && $tagId != null){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']=trim($attrVal);
			$customArr['customAttributeType']="radio";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$attrValues = array();
			$attributes = new stdClass();
			$attributes->attributeId = $tagId;
			$attributes->attributeValue = trim($attrVal);
			$attributes->shape = "";
			$attributes->color = "";
			$attrValues[] = $attributes;
			$customArr['attributeValues'] = $attrValues;
			$customArr['customAttributeValueId'] = $tagId;
			$customArr[$tagName] = trim($attrVal);
			$customArr['color'] = "" ;
			$customArr['shape'] = "" ;
			return $customArr;
		}else if($attrtype == 'check'){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']="";
			$customArr['customAttributeType']="check";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr['fieldType'] =  "NUMBER";
			$customArr[$tagName] = "";
			$customArr['attributeValues'] = $attrVal;
			return $customArr;
		
		}else if($attrtype == 'multiSelect'){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']="";
			$customArr['customAttributeType']="multiSelect";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr['fieldType'] =  "NUMBER";
			$customArr[$tagName] = "";
			$customArr['attributeValues'] = $attrVal;
			return $customArr;
		
		}else if($attrtype == 'referenceField'){
			//if($refAppfieldType == 'phoneEmail'){
			$customArr['customAttributeId']=$attrId;
			$customArr['customAttributeValue']="";
			$customArr['customAttributeType']="referenceField";
			$customArr['customAttributeTagName']=$tagName;
			$customArr['customAttributeName']=$tagName;
			$customArr['fieldType'] =  "phoneEmail";
			$customArr[$tagName] = "";
			$customArr['attributeId']= $refAppAttrId;
			$customArr['objectId']=$refAppObjId;
			$customArr['customAttributeValue1']=$attrVal;
			$customArr['customAttributeValue2']=$phoneEmailType;
			$customArr['refFieldObjectRefName']="";
			return $customArr;
			//}
			
		}
		

		
	}


	
	public function standardAttribute($selectedValue,$sectionName, $labelName,$attribute, $fieldArr) {
		
		$leadJson = new stdClass();

		$leadJson->firstName = $firstName;
		$leadJson->lastName = $lastName;
		//$leadJson->wayToContact = $wayTocontact;
		//$leadJson->easyWayToContact = $easyWayToContact;
		$leadJson->leadStatus = 1;
		$leadJson->leadStatusMeaning = "New";

		$leadJson->leadSource = $leadSourceCode;
		$leadJson->leadSourceMeaning = $leadSource;

		$leadJson->accountId = $customerId;
		$leadJson->accountName=$customerName;
		$leadJson->potentialAmount = $loanAmt;

		if( $plpurchaseoptions == 'Yes' || $refinoptions == 'Yes' ||  $optionsRadiosRehab == 'Yes' || $optionsRadiosOther == 'Yes'){
			$leadJson->assigneeObjectRefName = "Carlos Yanez";
			$leadJson->assigneeObjectRefId = 44808;
			$leadJson->assigneeObjectId = "8";

			if($empid == 'team'){
				$leadJson->assigneeObjectRefName = "Sales Team";
				$leadJson->assigneeObjectRefId = 10852;
				$leadJson->assigneeObjectId = "91";
			}
		
		}else{
			$leadJson->assigneeObjectRefName = "Sales Team";
			$leadJson->assigneeObjectRefId = 10852;
			$leadJson->assigneeObjectId = "91";
		}
	
		// if($empid == 'team'){
		// 	$leadJson->assigneeObjectRefName = "Loan Officer";
		// 	$leadJson->assigneeObjectRefId = 10903;
		// 	$leadJson->assigneeObjectId = "8";

		// 	$leadJson->referredById = 10903;
		// 	$leadJson->referredByName = "Loan Officer";
		// }else{
		// 	$leadJson->assigneeObjectRefName = $empname;
		// 	$leadJson->assigneeObjectRefId = $empid;
		// 	$leadJson->assigneeObjectId = "8";

		// 	$leadJson->referredById = $empid;
		// 	$leadJson->referredByName =$empname;
		// }
	
		$leadJson->description = $description;

		$phoneNumbers = array();
		$pNum = new stdClass();
		if($phone != ''){
			$pNum->phoneNumber=$phone;
			$pNum->phoneType="Mobile";
			$pNum->phoneTypeCode="PHONE_MOBILE";
			$pNum->id="lead_phone_input";
			$leadJson->phoneNumbers[] = $pNum;
		}

		$leadJson->emailAddresses = array();
		$eAddr = new stdClass();
		if($email != ''){
			$eAddr->emailAddress=$email;
			$eAddr->emailTypeCode="HOME";
			$eAddr->emailType="Home";
			$eAddr->id="cont_email_input";
			$leadJson->emailAddresses[] = $eAddr;
		}
		
		$leadJson->addresses = array();
		$laddr = new stdClass();
		if($countryText != ''){
			$laddr->addressAttributeId="address_section_attr_id";
			$laddr->countryId=$countryId;
			$laddr->addressTypeCode="1";
			$laddr->addressType="Billing Address";
			$laddr->addressLine1=$address1;
			$laddr->addressLine2=$address2;
			$laddr->city=$city;
			$laddr->state=$state;
			$laddr->stateCode=$stateCode;
			$laddr->zipCode=$zipCode;
			$laddr->county="";
			$laddr->country=$countryText;
			$laddr->countryName=$countryText;
			$laddr->countryCode=$coutryCode;
			$laddr->deliveryInstructions=null;
			$laddr->addressGroupName="Address1";
			$leadJson->addresses[] = $laddr;
		}
	}
}
?>
