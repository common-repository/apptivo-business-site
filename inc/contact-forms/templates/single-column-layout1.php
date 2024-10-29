<?php
/*
 Template Name:Single Column Layout1
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$contactform['fields'];
$countrylist = (!empty($countrylist)) ? $countrylist : '';
$countries = $countrylist;
$css="";
$phone_validation="";
$checkleadType="0";
$checkleadSource = "0";
$checkleadStatus = "0";
$checkleadRank = "0";
$checkaddressTypes= "0";
$checktags = "0";
$form_outer_width=$contact_width_size;
$GLOBALS['contact_form_name']=$contactform['name'];
//$disabled="disabled"; // for disabling tag update by user
$disabled="";
for($i=0;$i<count($formfields);$i++)
{
	if($formfields[$i]["fieldid"]=="leadType")
	{
		$checkleadType= "1";
	}
	else if($formfields[$i]["fieldid"]=="leadSource")
	{
		$checkleadSource= "1";
	}
	else if($formfields[$i]["fieldid"]=="leadStatus")
	{
		$checkleadStatus= "1";
	}
	else if($formfields[$i]["fieldid"]=="leadRank")
	{
		$checkleadRank= "1";
	}
	else if($formfields[$i]["fieldid"]=="addressTypes")
	{
		$checkaddressTypes= "1";
	}
	else if($formfields[$i]["fieldid"]=="labels")
	{
		$checktags= "1";
	}
}
if( $contactform['css'] != '' )
{
	echo $css='<style type="text/css">'.esc_attr($contactform['css']).'</style>';
}
echo $stcss = '<style type="text/css">
.awp_contactform_maindiv_'.esc_attr($contactform['name']).'{width:'.esc_attr($form_outer_width).' !important;}
.absp_success_msg {color: green;font-weight: bold;padding: 10px 0;}    
.awformmain div,.awformmain label,.awformmain a,.awformmain span,.awformmain input,.awformmain textarea,.awformmain select{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.awformmain input[type="text"]{min-height:25px}
.awformmain {max-width:600px}
.awformmain select.required{color:#000}
 span.absp_contact_mandatory{color:red}
.awformmain .captcha .formrgt{float:left !important}
.awformmain label.error{color:red;width: 100% !important;}
.awformmain span.absp_mandatory{color: #F00; padding-left:5px}
.awformmain .formouter{float:left;width:50%}
.awformmain .formsection {overflow: hidden;padding: 1px;margin: 0 0 10px 0;}
.awdblclm .formsection,.awformmain .doublecolmn .formsection{width:50%;float:left;padding-right: 10px;}
.awdblclm .fullsection,.awformmain .formsection.fullsection{width:100% !important}
.awformmain .fullsection label{width:12.5% !important}
.awformmain .fullsection .formrgt{width:87.5% !important}
.awformmain .doublecolmn .fullsection label{width:100% !important}
.awformmain .doublecolmn .fullsection .formrgt{width:100% !important}
.awformmain .formsection div{margin: 0 0 5px 0;}
.awformmain .formsection label{width:35%;float:left;padding-right:10px;}
.awformmain .awsinglecolmn .formsection label,.doublecolmn .formsection label{width: 100%;float: left;padding-right: 10px;}
.formsection .formrgt {width: 65%;float: left;padding-right: 10px;}
.awsinglecolmn .formsection .formrgt ,.awformmain .doublecolmn .formsection .formrgt{width: 100%;float: left;padding-right: 10px;}
.awformmain .formsection label {padding:5px 0;}
.awformmain .formrgt div.formsect{width:100%;float:left}
.awformmain .formsect label {margin-left:5px;width:75% !important;font-weight:normal !important;padding-top:0px !important}
.awformmain .formsect label {
   margin-left: 5px;
   width: 11% !important;
   font-weight: normal !important;
   padding-top: 0px !important;
   white-space: nowrap;
}
.awformmain .fullsection label {width:90% !important;}
.awformmain .formsect input{margin-top:2px}
.awformmain .fltrgt{float:right}
.awformmain input{margin:0px;float:left}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea {border: 1px solid;width:100%;  margin: 0;}
.awformmain select {width:100%;min-height: 27px;margin:0;color:#000;}
.awformmain .threefield{width:33.3%;float:left}
.awformmain .pd0_10{padding:0 10px}
@media (max-width: 600px) {
.awformmain .formsection {margin: 0 0 10px 0;}
.awformmain .formsection label{width: 100%;float: left;margin: 0 0 5px 0;padding-bottom:5px}
.awformmain .formsection .formrgt,.awformmain .formsection {width: 100% !important;float: none;}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea,select {width: 100%;}
.awformmain .formsect label {margin-left: 5px !important;width: 90% !important;}
}
@media (max-width: 480px) {
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.76);}
}
.switch{position:relative;display:inline-block;width:60px;height:34px}.switch input{opacity:0;width:0;height:0}.switch1{width:100px!important;height:1px!important;border-top-style:hidden!important;border-right-style:hidden!important;border-left-style:hidden!important;border-bottom-style:hidden!important;background-color:#f5efe0!important}#errorfield{width:38%!important;margin-top:0}#error_field{width:38%!important;margin-top:-5px}.errorfield{width:38%!important;margin-top:0}.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}.slider:before{position:absolute;content:"";height:26px;width:26px;left:4px;bottom:4px;background-color:#fff;-webkit-transition:.4s;transition:.4s}input:checked+.slider{background-color:#2196F3}input:focus+.slider{box-shadow:0 0 1px #2196F3}input:checked+.slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px)}.slider.round{border-radius:34px}.slider.round:before{border-radius:50%}
.formsection.captcha label{display:none;}
</style>';
if( ! function_exists('validator_js_call_contactform_layout1_s') ) :
function validator_js_call_contactform_layout1_s(){

echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){

if(jQuery(".email_exists").length>0){
jQuery(".field").each(function(){
field_val = jQuery(this).attr("field_value");
jQuery(this).val(field_val);
});
jQuery(".field1").each(function(){
field_val = jQuery(this).attr("field_value");
jQuery(this).text(field_val);
});
jQuery(".field2").each(function(){
field_val = jQuery(this).attr("field_value");
this_val = jQuery(this).val();
if(field_val==this_val){
jQuery(this).prop("checked",true);
}
});
jQuery(".field3").each(function(){
field_val = jQuery(this).attr("field_value");
this_val = jQuery(this).val();
if(field_val.includes(this_val)){
jQuery(this).prop("checked",true);
}
});
}

	jQuery("#telephonenumber_id").blur(function(){
		 jQuery("#telephonenumber_id").val(function(i, val) {	
		ph = val.replace(/-/g,"");
		   val = ph.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
		   return val;
		   });
		});

 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

var statusName= jQuery("#leadStatus option:selected").text();
      jQuery("#status_name").val(statusName);

var typeName= jQuery("#leadType option:selected").text();
      jQuery("#type_name").val(typeName);

var rankName= jQuery("#leadRank option:selected").text();
      jQuery("#rank_name").val(rankName);

var addressTypesName= jQuery("#leadRank option:selected").text();
      jQuery("#addressTypes_name").val(addressTypesName);

var statusId=jQuery("#leadStatus option:selected").attr("rel");
      jQuery("#status_id").val(statusId);
var typeId=jQuery("#leadType option:selected").attr("rel");
      jQuery("#type_id").val(typeId);
var rankId=jQuery("#leadRank option:selected").attr("rel");
      jQuery("#rank_id").val(rankId);
var addressTypesId=jQuery("#leadRank option:selected").attr("rel");
      jQuery("#addressTypes_id").val(addressTypesId);

jQuery("#leadStatus").change(function(){
      var fieldId= jQuery("option:selected", this).attr("rel");
      var fieldName=jQuery(this).find("option:selected").text();
      jQuery("#status_id").val(fieldId);
      jQuery("#status_name").val(fieldName);
        });
jQuery("#leadType").change(function(){
      var fieldId= jQuery("option:selected", this).attr("rel");
      var fieldName=jQuery(this).find("option:selected").text();
      jQuery("#type_id").val(fieldId);
      jQuery("#type_name").val(fieldName);
        });
jQuery("#leadRank").change(function(){
      var fieldId= jQuery("option:selected", this).attr("rel");
      var fieldName=jQuery(this).find("option:selected").text();
      jQuery("#rank_id").val(fieldId);
      jQuery("#rank_name").val(fieldName);
      });
jQuery("#addressTypes").change(function(){
	var fieldId= jQuery("option:selected", this).attr("rel");
	var fieldName=jQuery(this).find("option:selected").text();
	jQuery("#addressTypes_id").val(fieldId);
	jQuery("#addressTypes_name").val(fieldName);
});
jQuery("#labels").change(function(){
	var selectedOptions = jQuery("#labels option:selected");
	var selectedText = [];
	var selectedValues = [];
	selectedOptions.each(function() {
		selectedValues.push(jQuery(this).val());
		selectedText.push(jQuery(this).text());
	});
	jQuery("#label_id").val(selectedValues.join(", "));
	jQuery("#label_name").val(selectedText.join(", "));
});
jQuery("#country").change(function(){
		var fieldId=jQuery(this).find("option:selected").attr("value");
		var fieldName=jQuery(this).find("option:selected").text();
		jQuery("#country_id").val(fieldId);
      	jQuery("#country_name").val(fieldName);
});

jQuery("#'.esc_attr($GLOBALS['contact_form_name']).'_contactforms").validate({
	rules:{
			telephonenumber: {phoneUS: true},
			hiddenRecaptcha: {
				required: function (){
					// Check if the hiddenRecaptcha field has the token
					if(jQuery("#recaptcha_token").val() == ""){
						return true; // No token means reCAPTCHA not executed, return validation error
					}else{
						return false; // Token is present, validation passes
					}
				}
			}
		},
	submitHandler: function(form) {
		jQuery("button[type=submit], input[type=submit]").attr("disabled",true);
		form.submit();
	}
});
});
//document.getElementById("labels").disabled = true;
</script>';
}
endif;
add_action('wp_footer',"validator_js_call_contactform_layout1_s",100);
if(isset($submitformname)){
	if($submitformname==$contactform['name'] && $successmsg!=""){
		// Enqueue jQuery and the custom script when the form is submitted successfully
		add_action('wp_enqueue_scripts', 'enqueue_custom_jquery');

		// Add inline success script
		custom_success_script($contactform['name']);
	}
}
/* For Render Lead Status,Lead Type,Lead Rank */
	$firstConfig = get_option("awp_contact_configdata");
	$firstConfig = json_decode($firstConfig);

	$getConfig=get_option('awp_contactforms');

	for($i=0;$i<count($getConfig);$i++)
	{
		if($getConfig[$i]['name']==$contactform['name'])
		{
			$formConfig=$getConfig[$i]['contact_config'];
		}
	}
if(isset($submitformname)){
	if($submitformname==$contactform['name'] && $successmsg!=""){
		echo '<div id="success_'.esc_attr($contactform['name']).'" class="absp_success_msg success_'.esc_attr($contactform['name']).'">'.$successmsg."</div>";
	}
}
if(isset($captch_error)){
	if($captch_error!="" && $submitformname==$contactform['name']){
		echo '<div id="error'.esc_attr($contactform['name']).'" class="absp_error error_'.esc_attr($contactform['name']).'">'.$captch_error."</div>";
	}
}
do_action('apptivo_business_contact_'.$contactform['name'].'_before_form'); //Before submit form

echo '<form id="'.esc_attr($contactform['name']).'_contactforms" class="abswpcfm awp_contact_form" name="'.esc_attr($contactform['name']).'_contactforms" action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="post">';
$form_sessionid = uniqid();$_SESSION['nogdog'] = $form_sessionid;
echo '<input type="hidden" value="'.esc_attr($contactform['name']).'" name="awp_contactformname" id="awp_contactformname">';
echo '<div class="awformmain awsinglecolmn awp_contactform_maindiv_'.esc_attr($contactform['name']).'">';
foreach($formfields as $field)
{
	if ( !is_array($field)){
		continue;
	}
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=strtolower($field['type']);
	$options=$field['options'];
	$optionvalues=array();

	if($validation=="string"){
		$phone_validation="_string";
	}else{
		$phone_validation= "";
	}

	if($required){
		$mandate_property='"mandatory="true"';
		$validateclass=" required";
	}else{
		$mandate_property="";
		$validateclass="";
	}

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
	}
	if($fieldid == 'captcha'){
		$captcha_class = 'captcha';
	}else{
		$captcha_class = '';
	}
	echo '<div class="formsection '.esc_attr($captcha_class).'">';
	if($showtext != "")
	{
		echo '<label>';
		if($required)
		echo '<span class="absp_contact_mandatory">*</span>';
		echo esc_attr($showtext) . '</label>';
	}
	echo '<div class="formrgt">';

	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" || $fieldtype=="multiselect" ){
		if(trim($options)!=""){
			$option_values=preg_split("[\n]",trim($options));//Split the String line by line.
			$optionvalues = array();
			if($fieldtype == "select"){
				$optionvalues[] = "-- Select One --";
			}
			foreach($option_values as $values) :
			$optionvalues[] = trim($values);
			endforeach;

		}
	}

	if($value_present){
		$postValue = isset($_REQUEST[$fieldid]) ? sanitize_text_field($_REQUEST[$fieldid]) : '';
	}else{
		$postValue="";
	}
	$_REQUEST[$fieldid] = (!empty($_REQUEST[$fieldid])) ? sanitize_text_field($_REQUEST[$fieldid]) : '';
	$field_value = $_REQUEST[$fieldid];

	switch($fieldtype){
		case "text":
			echo '<input type="text" name="'.esc_attr($fieldid.$phone_validation).'" id="'.esc_attr($fieldid).'_id" value="'.esc_attr($postValue).'"  class="absp_contact_input_text'.esc_attr($validateclass).' field" field_value="'.esc_attr($field_value).'">';
			break;
			
		case "textarea":
			echo '<textarea  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value="'.esc_attr($postValue).'"  class="absp_contact_textarea'.esc_attr($validateclass).' field1" size="50" field_value="'.esc_attr($field_value).'"></textarea>';
			break;
				
		case "select":
			if($fieldid == 'country'){
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_contact_select'.esc_attr($validateclass).' field" field_value="'.esc_attr($field_value).'">';

				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');

				foreach($countries as $country)
				{
					$country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
					$selected = ($country_Code == trim($country->countryId))?'selected="selected"':'';
					echo  '<option value="'.esc_attr($country->countryId).'" '.esc_attr($selected).'>'.esc_attr($country->countryName).'</option>';
				}
				echo  '</select>';
				echo '<input type="hidden" id="country_id" name="country_id" value="'.esc_attr($countries[0]->countryId).'"/>';
				echo '<input type="hidden" id="country_name" name="country_name" value="'.esc_attr($countries[0]->countryName).'"/>';
			}
			elseif ($fieldid == 'leadStatus'){
				$configValues=$formConfig["awp_leadStatus_selected"];
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'"  class="absp_contact_select'.esc_attr($validateclass).'">';
				
				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');
				
				foreach ($firstConfig->leadStatus as $leadStatus)
				{
				$selected = ( $configValues == trim($leadStatus->lookupCode ))?'selected="selected"':''; 
				echo '<option value="'.htmlspecialchars(esc_attr($leadStatus->lookupCode)).'" '.esc_attr($selected).' rel="'.htmlspecialchars(esc_attr($leadStatus->lookupCode)).'">'.esc_attr($leadStatus->meaning).'</option>';
				
			    }
				echo '</select>';
				echo '<input type="hidden" id="status_id" name="status_id" value="'.esc_attr($configValues).'"/>';
				echo '<input type="hidden" id="status_name" name="status_name" value="'.esc_attr($leadStatus->meaning).'"/>';
			}elseif ($fieldid == 'leadType'){
				$configValues=$formConfig["awp_leadType_selected"];
				if(!$required){
					$notrequired="Select One";
				}
				if($configValues=="0"){
					$validateclass=" required";
				}
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'"  class="absp_contact_select'.esc_attr($validateclass).'">';
				if($configValues=="0"){
					$notrequired = (!empty($notrequired)) ? $notrequired : '';
					echo '<option value="'.esc_attr($notrequired).'">Select One</option>';
				}
				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');
				foreach ($firstConfig->leadType as $leadType)
				{
					$selected = ( $configValues == trim($leadType->opportunityTypeId ))?'selected="selected"':'';
					echo '<option value="'.esc_attr($leadType->opportunityTypeId).'" '.esc_attr($selected).' rel="'.esc_attr($leadType->opportunityTypeId).'">'.esc_attr($leadType->opportunityTypeName).'</option>';
				}
				echo '</select>';
				echo '<input type="hidden" id="type_id" name="type_id" value="'.esc_attr($configValues).'"/>';
				echo '<input type="hidden" id="type_name" name="type_name" value="'.esc_attr($leadType->opportunityTypeName).'"/>';
			}elseif ($fieldid=='leadRank'){
				$configValues=$formConfig["awp_leadRank_selected"];
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'"  class="absp_contact_select'.esc_attr($validateclass).'">';
				
				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');
				foreach ($firstConfig->leadRank as $leadRank)
				{
					$selected = ( $configValues == trim($leadRank->lookupCode ))?'selected="selected"':'';
					echo '<option value="'.htmlspecialchars(esc_attr($leadRank->lookupCode)).'" '.esc_attr($selected).' rel="'.htmlspecialchars(esc_attr($leadRank->lookupCode)).'">'.esc_attr($leadRank->meaning).'</option>';
				}
				echo '</select>';
				echo '<input type="hidden" id="rank_id" name="rank_id" value="'.esc_attr($configValues).'"/>';
				echo '<input type="hidden" id="rank_name" name="rank_name" value="'.esc_attr($leadRank->meaning).'"/>';
			}elseif ($fieldid=='addressTypes'){
				$configValues=$formConfig["awp_addressTypes_selected"];
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'"  class="absp_contact_select'.esc_attr($validateclass).'">';
				
				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');
				foreach ($firstConfig->addressTypes as $addressTypes)
				{
					$selected = ( $configValues == trim($addressTypes->code ))?'selected="selected"':'';
					echo '<option value="'.htmlspecialchars(esc_attr($addressTypes->code)).'" '.esc_attr($selected).' rel="'.htmlspecialchars(esc_attr($addressTypes->code)).'">'.esc_attr($addressTypes->name).'</option>';
				}
				echo '</select>';
				echo '<input type="hidden" id="addressTypes_id" name="addressTypes_id" value="'.esc_attr($configValues).'"/>';
				echo '<input type="hidden" id="addressTypes_name" name="addressTypes_name" value="'.esc_attr($addressTypes->name).'"/>';
			}else{
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_contact_select'.esc_attr($validateclass).'">';
				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');
				foreach( $optionvalues as $optionvalue )
				{
					$selected = (trim($postValue) == trim($optionvalue))?'selected="selected"':'';
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0 && $optionvalue == '-- Select One --'){
						echo   '<option value="" '.esc_attr($selected).'>'.esc_attr($optionvalue).'</option>';
					}else if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0 && $optionvalue!= '-- Select One --')
					{
						echo  '<option value="'.esc_attr($optionvalue).'">'.esc_attr($optionvalue).'</option>';
					}
				}
				echo  '</select>';
			}
			break;

		case "radio":
			$i=0;$opt=0;
			echo '<div class="absp_radioval">';
			foreach( $optionvalues as $optionvalue )
			{
				$selected = (trim($postValue) == trim($optionvalue))?'checked="checked"':'';
				
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo '<div class="formsect"><input type="radio" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid.$opt).'" value="'.esc_attr($optionvalue).'"  class="absp_contact_input_radio '.esc_attr($validateclass).' field2" '.esc_attr($selected).' field_value="'.esc_attr($field_value).'"> <label for="'.esc_attr($fieldid.$opt).'">'.esc_attr($optionvalue).'</label></div>';
				}
				$opt++;
			}
			echo '<label for="'.esc_attr($fieldid).'" generated="true" id="error_field" class="error" style="display: none;">This field is required.</label></div>';
			break;

		case "checkbox":
		$field_value1 =  json_encode($field_value);
			echo '<div class="formsect absp_checkval">';
			$i=0;$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				$selected ="";
				if( !empty($postValue)){
				foreach($postValue as $value){
					if(trim($value) == trim($optionvalue)){
						$selected='checked="checked"';
					}
				}
				}
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
				
					echo '<div class="formsect"><label class="switch">
					<input type="checkbox" name="'.esc_attr($fieldid).'[]" id="'.esc_attr($fieldid.$opt).'" value="'.esc_attr($optionvalue).'" class="absp_contact_input_checkbox '.esc_attr($validateclass).' field3" '.esc_attr($selected).' field_value='.esc_attr($field_value1).'><span class="slider round" > </span></label><input type="text" class="switch1" value="'.esc_attr($optionvalue).'" disabled="disabled"></div>';
					$i++;$opt++;
				}
			}
			echo '<BR><label for="'.esc_attr($fieldid).'[]" generated="true" id="errorfield" class="error errorfield" style="display: none;">This field is required.</label></div>';
			break;

		case "multiselect":
			if ($fieldid=='labels'){
				$configValues=$formConfig["awp_labels_selected"];
				//echo "<pre> multiselect configValues :-";print_r($configValues);echo "</pre>";
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'"  class="absp_contact_select'.esc_attr($validateclass).'" multiple readonly '.$disabled.'>';
				
				do_action ('apptivo_business_contact_'.$contactform['name'].'_'.$fieldid.'_default_option');
				$selectedLabelNameArray = array();

				// Make the Multi-Select using the $firstConfig array
				foreach ($firstConfig->labels as $labelsval){
					$labelId = trim($labelsval->labelId);
					// Check if the label ID is in the $configValues array
					if (in_array($labelId, explode(', ', $configValues))) {
						$selected = 'selected="selected"';
						$selectedLabelNameArray[] = $labelsval->labelName;
					}else{
						$selected = '';
					}
					echo '<option value="' . htmlspecialchars(esc_attr($labelId)) . '" ' . esc_attr($selected) . ' rel="' . htmlspecialchars(esc_attr($labelId)) . '">' . esc_attr($labelsval->labelName) . '</option>';
				
				}
				echo '</select>';
				echo '<input type="hidden" id="label_id" name="label_id" value="'.esc_attr($configValues).'"/>';
				echo '<input type="hidden" id="label_name" name="label_name" value="'.esc_attr(implode(', ', $selectedLabelNameArray)).'"/>';
			}else{

				$field_value1 =  json_encode($field_value);

				echo '<div class="formsect absp_checkval">';
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
					$selected ="";
					if( !empty($postValue)){
						foreach($postValue as $value){
							if(trim($value) == trim($optionvalue)){
								$selected='checked="checked"';
							}
						}
					}
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0){
						echo '<div class="formsect"><input type="checkbox" name="'.esc_attr($fieldid).'[]" id="'.esc_attr($fieldid.$opt).'" value="'.esc_attr($optionvalue).'"  class="absp_contact_input_checkbox '.esc_attr($validateclass).' field3"  '.esc_attr($selected).' field_value='.esc_attr($field_value1).'><label for="'.esc_attr($fieldid.$opt).'">'.esc_attr($optionvalue).'</label></div>';

						$i++;$opt++;
					}
				}
				echo '<label for="'.esc_attr($fieldid).'[]" generated="true" id="error_field" class="error" style="display: none;">This field is required.</label></div>';
			}
			break;

		case "captcha":
			awp_captcha($fieldid, $postValue, $validateclass);
			break;

	}
	echo '</div>' . '</div>';
}
if($contactform['subscribe_option']=='yes') :
$subscribe_to_newsletter = ($contactform['subscribe_to_newsletter_displaytext'] != '')?$contactform['subscribe_to_newsletter_displaytext']:'Subscribe to Newsletter';
echo '<div class="formsection"><div class="formsect"><input type="checkbox" name="subscribe" id="subscribe" /><label>'.esc_attr($subscribe_to_newsletter).'</label></div></div>';
endif;


echo '<input type="hidden" name="awp_contactform_submit"/>';
echo '<input type="hidden" name="awp_contactform_value" value="" />';
if($contactform['submit_button_type']=="submit" &&($contactform['submit_button_val'])!=""){
	$button_value = 'value="'.$contactform['submit_button_val'].'"';
}
else{
	if(strlen(trim($contactform['submit_button_val'])) == 0)
	{
		$imgSrc = awp_image('submit_button');
	}else{
		$imgSrc = $contactform['submit_button_val'];
	}

	$button_value = 'src="'.$imgSrc.'"';
}

do_action('apptivo_business_contact_' . $contactform['name'] . '_before_submit_query');//Before Submit Query

echo '<div class="formsection"><div class="formrgt"><input type="'.esc_attr($contactform['submit_button_type']).'" class="absp_contact_button_submit awp_contactform_submit_'.esc_attr($contactform['name']).'" '.html_entity_decode(esc_attr($button_value)).' name="awp_contactform_submit_'.esc_attr($contactform['name']).'"  id="awp_contactform_submit_'.esc_attr($contactform['name']).'" /></div></div>';
echo '</div>';
echo '</form><p>&nbsp;</p>';
do_action('apptivo_business_contact_' . $contactform['name'] . '_after_form');//After submit Form
?>
