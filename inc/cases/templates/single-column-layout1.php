<?php
/*
 Template Name:Single Column Layout
 Template Type: Shortcode
*/
$formfields=array();
$formfields=$case_form['fields'];
if(isset($countrylist)){
	$countries = $countrylist;
}
$css="";
$count=1;
$checkType="0";
$checkPriority = "0";
$checkStatus = "0";
$form_outer_width=$cases_width_size;
$GLOBALS['case_form_name']=$case_form['name'];

if(is_array($formfields)){
	for($i=0;$i<count($formfields);$i++){
		if(isset($formfields[$i]["fieldid"])){
			if($formfields[$i]["fieldid"]=="type"){
				$checkType= "1";
			}
			else if($formfields[$i]["fieldid"]=="priority"){
				$checkPriority= "1";
			}else if($formfields[$i]["fieldid"]=="status"){
				$checkStatus= "1";
			}
		}
	}
}

if( isset($case_form['properties']['css']) && $case_form['properties']['css'] != '' ){
	echo $css='<style type="text/css">'.esc_attr($case_form['properties']['css']).'</style>';
}
if( ! function_exists('validator_js_call_case_layout1') ) :
	function validator_js_call_case_layout1(){
		echo $jss ='<script type="text/javascript">
		jQuery(document).ready(function()
		{

			jQuery("#phone_id").blur(function(){
				jQuery("#phone_id").val(function(i, val){
					ph = val.replace(/-/g,"");
					val = ph.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
					return val;
				});
			});
			
			var priorityName= jQuery("#priority option:selected", this).text();
			jQuery("#priority_name").val(priorityName);
			var typeName= jQuery("#type option:selected", this).text();
			jQuery("#type_name").val(typeName);
			jQuery("#priority").change(function(){
				var fieldName= jQuery("option:selected", this).text();
				jQuery("#priority_name").val(fieldName);
			});
			jQuery("#type").change(function(){
				var fieldName= jQuery("option:selected", this).text();
				jQuery("#type_name").val(fieldName);
			});
			jQuery("#status").change(function(){
				var fieldName= jQuery("option:selected", this).text();
				jQuery("#status_name").val(fieldName);
			});
			jQuery("#'.esc_attr($GLOBALS['case_form_name']).'_casesforms").validate({
				rules:{
					hiddenRecaptcha:{
						required: function(){
							// Check if the hiddenRecaptcha field has the token
							if(jQuery("#recaptcha_token").val() == ""){
								return true; // No token means reCAPTCHA not executed, return validation error
							}else{
								return false; // Token is present, validation passes
							}
						}
					}
				},
				submitHandler: function(form){
					jQuery("button[type=submit], input[type=submit]").attr("disabled",true);
					form.submit();
				}
			});';
			if(isset($success_message) && $success_message!="" && $case_form['properties']['confmsg']!=""){
				echo ' document.getElementById("success_'.esc_attr($case_form['name']).'").scrollIntoView();
			});
			</script>';
			}else{
				echo ' }); </script>';
			}
	}
endif;
add_action('wp_footer',"validator_js_call_case_layout1",100);
$contactform = (!empty($contactform)) ? $contactform : [];
$contactform['name'] = (array_key_exists('name',$contactform)) ? $contactform['name'] : '';
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
@media (max-width: 768px) {
.awformmain .formsection {margin: 0 0 10px 0;}
.awformmain .formsection label{width: 100%;float: left;margin: 0 0 5px 0;padding-bottom:5px}
.awformmain .formsection .formrgt,.awformmain .formsection {width: 100% !important;float: none;}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea,select {width: 100%;}
.awformmain .formsect label {margin-left: 5px !important;width: 90% !important;}
}
@media (max-width: 480px) {
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.76);}
}
.formsection.captcha label{display:none;}
</style>';

		if(isset($value_present)){
			$postValue = sanitize_text_field($_REQUEST);
		}else{
			$postValue="";
		}
do_action('apptivo_business_cases_before_form'); //Before Form
if(isset($captcha_error)!=""){
	// Remove HTML tags but preserve inline CSS
	$error_message = strip_tags($captcha_error, '<span><style><color>');
	// Decode HTML entities
	$error_message = htmlspecialchars_decode($error_message);
	echo '<div class="absp_error error">'.$error_message.'</div>';
}
echo  '<div id="success_'.esc_attr($case_form['name']).'" class="absp_success_msg success_'.esc_attr($case_form['name']).'">'.$success_message."</div>";
echo  '<form id="'.esc_attr($case_form['name']).'_casesforms" name="'.esc_attr($case_form['name']).'_caseforms" action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="post">';
echo '<input type="hidden" value="'.esc_attr($case_form['name']).'" name="awp_caseformname" id="awp_caseformname">';
echo '<div class="awformmain absp_business_main cases_outfrm_'.esc_attr($case_form['name']).'">';
if(get_option ('apptivo_business_recaptcha_mode')=="yes")
{
	$option=get_option('apptivo_business_recaptcha_settings');
	$option=json_decode($option);
}
$i=0;
$count=1;
foreach($cases_fields as $field){
    if (isset($field['showtext'])){
		$i=$i+1;

		if($field['fieldid']=='captcha'){
			$captcha_class = 'captcha';
		}else{
			$captcha_class = '';
		}

		echo '<div class="formsection '.esc_attr($captcha_class).'">';
		$mandatory_symbol = ' ' . awp_mandatoryfield($field, $before = '<span class="absp_contact_mandatory">', $after = '</span>', $mandatory_symbol = '*');
		echo awp_create_labelfield(esc_attr($field['showtext']), '', '', '<label>', html_entity_decode(esc_attr($mandatory_symbol)) . '</label>'); /* Label Field */
		cases_textfield($form_properties, $field, isset($countries), isset($valuepresent), '<div class="formrgt">', '</div>', false, $i, true, 'cases', $postValue, $case_form['name']); /* Text Field */
		echo '</div>';
		$count++;
    }
}
getFirstConfigData($checkType,$checkPriority,$checkStatus,$case_form['name']);
do_action('apptivo_business_cases_before_submit_query'); //Before submit Query
echo '<input type="hidden" name="awp_casesforms_submit" />';
$submit = cases_submit_type($form_properties,"apptivo_casesform",'','','', $i+1); //SubMit Button Type
echo '<div class="formsection"><label><span>&nbsp;</span></label><div class="formrgt">'.html_entity_decode(esc_attr($submit)).'</div></div>';
echo  '</div></form>';

do_action('apptivo_business_cases_after_form'); //After Form
?>
