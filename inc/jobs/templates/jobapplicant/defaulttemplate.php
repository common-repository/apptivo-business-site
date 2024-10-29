<?php
/*
 Template Name:Default Template
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$hrjobsform['fields'];
$countries = $countrylist;
$allJobs = $allJobs[0]->data;
$allIndustries = getAllIndustriesV6()->industries;

$css="";

if( $hrjobsform['css'] != '' )
{
echo $css='<style type="text/css">'.esc_attr($hrjobsform['css']).'</style>';
}

echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){



jQuery("#country").change(function(){
		var fieldId=jQuery(this).find("option:selected").attr("value");
		var fieldName=jQuery(this).find("option:selected").text();
		jQuery("#country_id").val(fieldId);
      	jQuery("#country_name").val(fieldName);

});


jQuery("#industry").change(function(){
		var fieldId=jQuery(this).find("option:selected").attr("value");
		jQuery("#industryId").val(fieldId);
      	

});


jQuery("#jobidwithnumber").change(function(){
		var fieldId=jQuery(this).find("option:selected").text();
		jQuery("#positionName").val(fieldId);
});





 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.esc_attr($hrjobsform['name']).'_hrjobsforms").validate({
    rules: {
        telephonenumber: { phoneUS: true}
       },
    submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';
if($submitformname==$hrjobsform['name'] && $successmsg!="")
{
    echo $jscript='<script type="text/javascript">
            jQuery(document).ready(function(){
                document.getElementById("success_'.esc_attr($hrjobsform['name']).'").scrollIntoView();
            });
        </script>';
}
$form_outer_width = (!empty($form_outer_width)) ? $form_outer_width : '';
$contactform = (!empty($contactform)) ? $contactform : [];
$contactform['name']= (array_key_exists('name',$contactform)) ? $contactform['name'] : '';
echo $stcss = '<style type="text/css">
.awp_contactform_maindiv_'.esc_attr($contactform['name']).'{width:'.esc_attr($form_outer_width).' !important;}
.absp_success_msg {color: green;font-weight: bold;padding: 10px 0;}   
.absp_error,.error_message{color:red;font-weight:bold;margin-bottom: 15px; width: 100%; }
.awformmain{max-width: 600px;}
.awformmain .formrgt object{height:40px}
.awformmain div,.awformmain label,.awformmain a,.awformmain span,.awformmain input,.awformmain textarea,.awformmain select{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.awformmain input[type="text"]{min-height:25px}
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
.awformmain .fullsecsub .formrgt,.awformmain .fullsecsub .formrgt input{float:right;margin-right: 10px;}
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
.awformmain .fullsection label {width:90% !important;}
.awformmain .formsect input{margin-top:2px}
.awformmain .fltrgt{float:right}
.awformmain input{margin:0px;float:left}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea {border: 1px solid;width:100%;  margin: 0;}
.awformmain select {width:100%;min-height: 27px;margin:0;color:#000;}
.awformmain .threefield{width:33.3%;float:left}
.awformmain .pd0_10{padding:0 10px}
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.80);}
@media (max-width: 768px) {
.awformmain .formsection {margin: 0 0 10px 0;}
.awformmain .formsection label{width: 100%;float: left;margin: 0 0 5px 0;padding-bottom:5px}
.awformmain .formsection .formrgt,.awformmain .formsection {width: 100% !important;float: none;}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea,select {width: 100%;}
.awformmain .formsect label {margin-left: 5px !important;width: 90% !important;}
}
@media (max-width: 480px) {
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
}
</style>';

if($submitformname==$hrjobsform['name'] && $successmsg!=""){
	echo  '<div id="success_'.esc_attr($hrjobsform['name']).'" class="absp_success_msg success_'.esc_attr($hrjobsform['name']).'">'.esc_attr($successmsg)."</div>";
}

do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_before_form'); //Before submit form
if($hrjobsform['confmsg_pagemode']=="same"){
echo  '<form id="'.esc_attr($hrjobsform['name']).'_hrjobsforms" class="awp_hrjobs_form abswpjfm" name="'.esc_attr($hrjobsform['name']).'_hrjobsforms" action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">';
}
elseif($hrjobsform['confmsg_pagemode']=="other")
{
    $page_redirect  =    $hrjobsform['confmsg_pageid'];
    $post = get_post( $page_redirect);
    $page_action    =   $post->post_name;
    echo  '<form id="'.esc_attr($hrjobsform['name']).'_hrjobsforms" class="awp_hrjobs_form abswpjfm" name="'.esc_attr($hrjobsform['name']).'_hrjobsforms" action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">';
}
echo  '<input type="hidden" value="'.esc_attr($jobId).'" name="jobId" id="jobId"><input type="hidden" value="'.esc_attr($jobNo).'" name="jobNo" id="jobNo">';
echo '<input type="hidden" value="'.esc_attr($hrjobsform['name']).'" name="awp_jobsformname" id="awp_jobsformname">';
echo '<div class="awformmain awp_jobsform_maindiv_'.esc_attr($hrjobsform['name']).'">';
if(trim($jobId) == '')
{
	if( count($allJobs) >= 1)
	{
		echo  '<div class="formsection"><label><span>Job</span></label>
	<div class="formrgt">';
		echo   '<select class="awp_select joblists" value="" id="jobidwithnumber" name="jobidwithnumber">
             <option value="0">Select</option>';
		foreach( $allJobs as $jobslists )
		{
			if( strlen(trim($jobslists->title)) < 20)
			{
				$jobTitle = $jobslists->title;
			}else { $jobTitle = substr($jobslists->title,0,20).'...'; }
			echo  '<option value="'.esc_attr($jobslists->positionId).'::'.esc_attr($jobslists->positionNumber).'">'.esc_attr($jobTitle).'</option>';

		}
			
		echo  '</select>';
		echo '<input type="hidden" id="positionName" name="positionName" value="">';
		echo  '</div></div>';
	}
}
foreach($formfields as $field)
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['type'];
	$options=$field['options'];
	$optionvalues=array();

	if($required){
		$mandate_property='"mandatory="true"';
		$validateclass=" required";
	}
	else{
		$mandate_property="";
		$validateclass="";
	}

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
        
        if($fieldid=='captcha')
	{
		$captcha_class = 'captcha';
	}
	else{
		$captcha_class = '';
	}
	echo '<div class="formsection '.esc_attr($captcha_class).'"><label><span>';
	if($required)
	echo '<span class="absp_contact_mandatory">*</span>';
	echo esc_attr($showtext).'</span></label><div class="formrgt">';
        
	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
		if(trim($fieldid) == 'industry')
		{
			$optionvalues=$options;
			$fieldtype = 'select';
		} else if(trim($options)!=""){
				
			$option_values=preg_split("[\n]",trim($options));//Split the String line by line.

			$optionvalues = array();
			foreach($option_values as $values) :
			$optionvalues[] = trim($values);
			endforeach;
				
		}
	}
	switch($fieldtype)
	{
		case "text":
			echo '<input type="text" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_jobapplicant_input_text'.esc_attr($validateclass).'">';
			break;
		case "textarea":
			echo  '<textarea  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_jobapplicant_textarea'.esc_attr($validateclass).' size="50"></textarea>';
			break;
			
		case "select":
			if($fieldid == 'country')
			{
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="awp_select'.esc_attr($validateclass).'">';
				
				do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_'.$fieldid.'_default_option');
				$defaultcountryId = $countries[0]->countryId.'_'.$countries[0]->countryCode;
					$defaultcountryName = $countries[0]->countryName;
				
				foreach($countries as $country)
				{
					echo  '<option value="'.esc_attr($country->countryId).'_'.esc_attr($country->countryCode).'">'.esc_attr($country->countryName).'</option>';
				}
				echo  '</select>';
				echo '<input type="hidden" id="country_id" name="country_id" value="'.esc_attr($defaultcountryId).'">
				<input type="hidden" id="country_name" name="country_name" value="'.esc_attr($defaultcountryName).'">
				';
			}
			else  if($fieldid == 'industry')
			{   $industryId="";
				if(!empty($optionvalues))
				{
					echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_jobapplicant_select'.esc_attr($validateclass).'">';
					
					do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_'.$fieldid.'_default_option');
					
					foreach( $optionvalues as $optionvalue )
					{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
						$options = explode("::",$optionvalue);
						echo  '<option value="'.esc_attr($options[0]).'">'.esc_attr($options[1]).'</option>';
					}
					}
					echo  '</select>';
					echo '<input type="hidden" id="industryId" name="industryId" value="">';
				}else if(isset($allIndustries) && !empty($allIndustries)){
					$optionvalues = $allIndustries; 
					echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_jobapplicant_select'.esc_attr($validateclass).'">';
					foreach( $optionvalues as $optionvalue )
					{  if(!empty($optionvalue->industryName) && strlen(trim($optionvalue->industryName)) != 0)
					{
						//$options = explode("::",$optionvalue);
						echo  '<option value="'.esc_attr($optionvalue->industryId).'">'.esc_attr($optionvalue->industryName).'</option>';
					}
					}
					echo  '</select>';
					echo '<input type="hidden" id="industryId" name="industryId" value="">';
				}else {
					 
					echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_jobapplicant_select'.esc_attr($validateclass).'">';
					echo  '<option value="0">Default</option>';
					echo  '</select>';
				}
			} else {
				echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_jobapplicant_select'.esc_attr($validateclass).'">';
				
				do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_'.$fieldid.'_default_option');
				
				foreach( $optionvalues as $optionvalue )
				{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo  '<option value="'.esc_attr($optionvalue).'">'.esc_attr($optionvalue).'</option>';
				}
				}
				echo  '</select>';
			}
			break;
			
		case "file":

			echo '<input type="file" id="file_upload" name="file_upload" class="absp_jobapplicant_input_text'.esc_attr($validateclass).'" />';
			//echo  '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_jobapplicant_input_text'.$validateclass.'"  />';

			break;
			
		case "radio":
			$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
						
					echo '<div class="formsect"><input type="radio" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid.$opt).'" value="'.esc_attr($optionvalue).'"  class="absp_jobapplicant_input_radio '.esc_attr($validateclass).'"/><label class="awp_custom_lbl" for="'.esc_attr($fieldid.$opt).'">'.esc_attr($optionvalue).'</label> </div>';
				}
				$opt++;
			}
			break;
			
		case "checkbox":

			$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo '<div class="formsect"><input type="checkbox" name="'.esc_attr($fieldid).'[]" id="'.esc_attr($fieldid.$opt).'" value="'.esc_attr($optionvalue).'"  class="absp_jobapplicant_input_checkbox '.esc_attr($validateclass).'"/><label class="awp_custom_lbl" for="'.esc_attr($fieldid.$opt).'">'.esc_attr(trim($optionvalue)).'</label></div>';
				}
				$opt++;
			}
			break;
	}
	echo '</div>'.'</div>';
}
 
echo '<div class="formsection"><label class="jobapplicant_form_left emtydv">&nbsp;</label> <div class="jobscnt_rgstr_line"  style="display:none">'.
				'<div class="formrgt jobscnt_submit">';
echo '<input type="hidden" name="awp_hrjobsform_submit"/>';
echo '<input type="hidden" name="awp_document_key" id="awp_document_key"/>';
if($hrjobsform['submit_button_type']=="submit" &&($hrjobsform['submit_button_val'])!=""){
	$button_value = 'value="'.$hrjobsform['submit_button_val'].'"';
}
else{
	if($hrjobsform['submit_button_val'] == '' || empty($hrjobsform['submit_button_val'])) :
	$hrjobsform['submit_button_val'] = awp_image('submit_button');
	endif;
	$button_value = 'src="'.$hrjobsform['submit_button_val'].'"';
}

do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_before_submit_query');//Before Submit Query
echo '</div></div>';
echo '<div class="formrgt"><input type="'.esc_attr($hrjobsform['submit_button_type']).'" class="absp_jobapplicant_button_submit awp_hrjobsform_submit_'.esc_attr($hrjobsform['name']).'" '.html_entity_decode(esc_attr($button_value)).' name="awp_jobsform_submit" id="awp_jobsform_submit" /></div></div>';



echo '</div>';

echo '</form>';

do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_after_form');//After submit Form
?>