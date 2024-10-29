<?php
@ini_set('display_errors', 0);
require_once ('ConfigSection.php');
require_once ('ConfigSectionAttribute.php');
require_once ('AttributeRight.php');
require_once ('Config.php');
 class configData {

  // For getting App Config Data based on the objectId
    public function appConfigData($apiUrl,$objId){
	$params = array (
			"a" => "getConfigData",
			"objectId"=>$objId,
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" =>APPTIVO_ACCESS_KEY
	);
	$response = getRestAPICall1( 'POST', $apiUrl, $params );

	$webLayout = json_decode($response->webLayout);
	
	$mySecObj = array();
	$mySecAttrObj = array();
	$sections = $webLayout->sections;
	if(isset($sections)){
	foreach ( $sections as $section ) {
		$secObj = $this->JsonToSectionObject($section);
		array_push($mySecObj,$secObj);
		
	}
  }
	
	return $mySecObj;
 }
 
 
 /*
 * userData for assignee
 */
public function getuserData()
{

	$api_url = APPTIVO_API_URL . 'app/commonservlet';
	$params = array (
			"a" => "getUserData",
			"apiKey" => APPTIVO_API_KEY,
			"accessKey" =>APPTIVO_ACCESS_KEY
	);
	$response = getRestAPICall1( 'POST', $api_url, $params );
	return $response;
}
 
 

 
 //Converting the webLayout to sectionObjects
public function JsonToSectionObject($sectionData){
	
	
	$mySecAttrObj = array();
	$attrObj = array();
	$sectionObj = new Section();
	$sectionObj->setType($sectionData->type);
	$sectionData->sectionType = (!empty($sectionData->sectionType)) ? $sectionData->sectionType : '';
	$sectionObj->setSectionType($sectionData->sectionType);
	$sectionObj->setSecLabel($sectionData->label);
	$sectionObj->setSecId($sectionData->id);
	$sectionObj->setSecisEnabled($sectionData->isEnabled);
	
	
	
	foreach ( $sectionData->attributes as $attribute ) {
		$attribute->attributeId = (!empty($attribute->attributeId)) ? $attribute->attributeId : '';
		if (strpos($attribute->attributeId , 'custom_drag_drop_placeholder_id') !== 0) {
			$attrObj = $this->JsonToAttributeObject($attribute);
			array_push($mySecAttrObj, $attrObj);
		}
		
		if (strpos($attribute->attributeId, 'address_attribute') == 0) {
			$attribute->addressList = (!empty($attribute->addressList)) ? $attribute->addressList : '';
			$addressList = $attribute->addressList;
			if(isset($addressList)&& !empty($addressList)){
			foreach($addressList as $addressAttr){
				$attrObj = $this->JsonToAttributeObject($addressAttr);
				array_push($mySecAttrObj, $attrObj);
			}
			}
		}
		
	}
	
		
		

	
	$sectionObj->setAttributeList($mySecAttrObj);
	
	return $sectionObj;
	
	
}

 //Getting attributeObjects
public function JsonToAttributeObject($attribute){

	
	
		$attributeObj = new AttributeNewCustom();
	
		$attribute->attributeId = (!empty($attribute->attributeId)) ? $attribute->attributeId : '';
		$attribute->type = (!empty($attribute->type)) ? $attribute->type : '';
		$attribute->label = (!empty($attribute->label)) ? $attribute->label : new stdclass(); 
		$attribute->label->modifiedLabel = (!empty($attribute->label)) ? ((!empty($attribute->label->modifiedLabel)) ? $attribute->label->modifiedLabel :'') : '';
		
	$attributeObj->setAttributeId($attribute->attributeId); 
	$attributeObj->setAttributeType($attribute->type);  
	$attributeObj->setAttributeLabel($attribute->label->modifiedLabel);
	$attribute->isEnabled = (!empty($attribute->isEnabled)) ? $attribute->isEnabled : '';
	$attributeObj->setisEnabled($attribute->isEnabled);
	$attribute->right = (!empty($attribute->right)) ? $attribute->right : '';
	$referenceObj = (!empty($referenceObj)) ? $referenceObj : new stdclass();
	$associatedObj = (!empty($associatedObj)) ? $associatedObj : new stdclass();
	$attrRightObj = $this->JsonToAttributeRightObject($attribute->right,$referenceObj,$associatedObj);
	$attributeObj->setRightData($attrRightObj);
	
	
	if(isset($attribute->referenceObject) && isset($attribute->associatedField)){
		$referenceObj = $attribute->referenceObject;
		$associatedObj = $attribute->associatedField;
		$attrRightObj = $this->JsonToAttributeRightObject($attribute->right,$referenceObj,$associatedObj);
		$attributeObj->setRightData($attrRightObj);
	}
	
	
	
	
	
	return $attributeObj;
	
	
}
 

//Geting right Array objects
public function JsonToAttributeRightObject($attrRightObj,$referenceObj,$associatedObj){
	
	$attributeRightObj = new AttributeRight();
	$right = array();
	if(isset($attrRightObj) && $attrRightObj != ""){
	foreach($attrRightObj as $attr){
		//if(isset($attr->optionValueList) || isset($attr->attributeTag)){
			$attr->tag = (!empty($attr->tag)) ? $attr->tag : '';
			$attr->tagName = (!empty($attr->tagName)) ? $attr->tagName : '';
			$attr->tagId = (!empty($attr->tagId)) ? $attr->tagId : '';
			$attr->optionValueList = (!empty($attr->optionValueList)) ? $attr->optionValueList : '';
			$attr->attributeTag = (!empty($attr->attributeTag)) ? $attr->attributeTag : '';
			$attr->options = (!empty($attr->options)) ? $attr->options : '';
		$attributeRightObj->setAttrRightArr($attr->tag,$attr->tagName,$attr->tagId,$attr->optionValueList,$attr->attributeTag,$attr->options);  
		if($attr->tag == 'referenceField'){
			$referenceObj->objectId = (!empty($referenceObj->objectId)) ? $referenceObj->objectId : null;
			$associatedObj->referenceAttributeId = (!empty($associatedObj->referenceAttributeId)) ? $associatedObj->referenceAttributeId : null;
			$refAppObj = new stdClass();
			$attributeRightObj->setRefAppId($referenceObj->objectId);
			$attributeRightObj->setRefAppAttributeId($associatedObj->referenceAttributeId);
			$refAppObj->objectId = $referenceObj->objectId;
			$refAppObj->attributeId = $associatedObj->referenceAttributeId;
			$attr->referenceAppObject = $refAppObj;
		}
		
		array_push($right, $attr);
		//}
	}
	}
	
	return $right;
}


public function JsonToAttributeRefAppObject($attributeObj, $referenceObj,$associatedObj){
	$attributeRightObj = new AttributeRight();
	$refApp = array();
	$attributeRightObj->setAttributeRefApp($referenceObj->objectId,$associatedObj->referenceAttributeId);
	array_push($refApp,$attributeRightObj);
	return $refApp;
}

 
}
?>
