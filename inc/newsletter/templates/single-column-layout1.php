<?php
/*
 Template Name:Single Column Layout1
 Template Type: Shortcode
 */
$formfields=array();
$formfields = $newsletterform['fields'];
if( $newsletterform['css'] != '' )
{
	echo $css='<style type="text/css">'.esc_attr($newsletterform['css']).' .required{color:#000;font-weight:normal;}</style>';
}
$contactform_name = ( isset($contactform) && isset($contactform['name'])) ? esc_attr($contactform['name']) : '';
echo '<style type="text/css">
        .required{color:#000;font-weight:normal;}
        .form_section {padding-bottom:10px}
        .abswpnfm textarea#comments { margin: 0; }
        span.error_message,label.error {color:red;width:100%;float:left}
        .absp_error{color:red !important;}
        .absp_success_msg {color: green;font-weight: bold;padding: 10px 0;}
	.abswpnfm input, .abswpnfm textarea, .abswpnfm select {width:95%;}
        .abswpnfm select {padding:6px;}
        .abswpnfm input[type="button"], .abswpnfm input[type="reset"], .abswpnfm input[type="submit"], .abswpnfm input[type="image"] {width:auto;margin-top: 5px}
        .abswpnfm input[type="image"] {border:none}
    	@media screen and (max-width:900px){
		.awp_newsletter_maindiv_'.$contactform_name.' .form_left_part {width:100%;float:left;}
		.awp_newsletter_maindiv_'.$contactform_name.' .form_rgt_part{width:100%  ;float:left  ;margin-top:5px;}
		#recaptcha_widget_div{zoom:0.79;-moz-transform: scale(0.76);}
		}
		@media screen and (max-width:360px){
		#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
		}
      </style>';


echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.esc_attr($newsletterform['name']).'_newsletter").validate({
    rules: {
        newsletter_phone: { phoneUS: true}
       },
    submitHandler: function(form) {
      form.submit();
    }
});
});

</script>';
if($submitformname==$newsletterform['name'] && $successmsg!=""){
 echo $jscript='<script type="text/javascript">
            jQuery(document).ready(function(){
document.getElementById("success_'.esc_attr($newsletterform['name']).'").scrollIntoView();
            });
        </script>';
    echo  '<div id="success_'.esc_attr($newsletterform['name']).'" class="absp_success_msg success_'.esc_attr($newsletterform['name']).'">'.esc_attr($successmsg)."</div>";
}

do_action('apptivo_business_newsletter_'.$newsletterform['name'].'_before_form'); //After Form
echo '<style type="text/css"> .absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error,.error_message{color:red;font-weight:bold;padding-bottom:5px;}</style>';
echo  '<form class="abswpnfm" id="'.esc_attr($newsletterform['name']).'_newsletter" name="'.esc_attr($newsletterform['name']).'_newsletter" action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="post">';
echo '<input type="hidden" value="'.esc_attr($newsletterform['name']).'" name="awp_newsletterformname" id="awp_newsletterformname">';
echo '<input type="hidden" value="'.esc_attr($newsletterform['category']).'" name="newsletter_category" id="newsletter_category">';
echo '<div class="awp_newsletter_maindiv_'.esc_attr($newsletterform['name']).'">';
foreach($formfields as $field)
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['fieldtype'];
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

	echo '<div class="form_section">'.
				'<div class="form_left_part">'.
					'<span class="absp_newsletter_label">';
	if($required)
	echo '<span class="absp_newsletter_mandatory">*</span>';
	echo esc_attr($showtext).'</span>'.
                       '</div>'.
                       '<div class="form_rgt_part">';

	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
		if(trim($options)!=""){
			$optionvalues=split(",", $options);
		}
	}
	switch($fieldtype)
	{
		case "text":
			echo '<input type="text" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_input_text'.esc_attr($validateclass).'">';
			break;
		case "textarea":
			echo  '<textarea  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_textarea'.esc_attr($validateclass).'"></textarea>';
			break;
		case "select":
			echo  '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_select'.esc_attr($validateclass).'">';
			foreach( $optionvalues as $optionvalue )
			{
				echo  '<option value="'.esc_attr($optionvalue).'">'.esc_attr($optionvalue).'</option>';
			}
			echo  '</select>';
			break;
		case "radio":
			$i=0;
			foreach( $optionvalues as $optionvalue )
			{
				if($i>0)
				echo '<br>';
				echo '<label for="'.esc_attr($fieldid).'">'.esc_attr($optionvalue).'</label><input type="radio" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value="'.esc_attr($optionvalue).'"  class="absp_newsletter_input_radio '.esc_attr($validateclass).'">';
			}
			break;
		case "checkbox":
			$i=0;
			foreach( $optionvalues as $optionvalue )
			{
				if($i>0)
				echo '<br>';
				echo '<label for="'.esc_attr($fieldid).'">'.esc_attr($optionvalue).'</label><input type="checkbox" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value="'.esc_attr($optionvalue).'"  class="absp_newsletter_input_checkbox '.esc_attr($validateclass).'">';
				$i++;
			}
			break;
	}
	echo '</div>'.'</div>';
}
echo '<input type="hidden" name="awp_newsletterform_submit"/>';
if($newsletterform['submit_button_type']=="submit" &&($newsletterform['submit_button_val'])!=""){
	$button_value = 'value="'.$newsletterform['submit_button_val'].'"';
}
else{
	if(strlen(trim($newsletterform['submit_button_val'])) == 0)
	{
		$imgSrc = awp_image('submit_button');
	}else {

		$imgSrc = $newsletterform['submit_button_val'];
	}
	 
	$button_value = 'src="'.$imgSrc.'"';
}

do_action('apptivo_business_newsletter_'.$newsletterform['name'].'_before_submit_query');//Before Submit Query

echo '<input type="'.esc_attr($newsletterform['submit_button_type']).'" class="absp_newsletter_button_submit awp_newsletter_submit_'.esc_attr($newsletterform['name']).'" '.html_entity_decode(esc_attr($button_value)).' name="awp_newsletter_submit_'.esc_attr($newsletterform['name']).'"  id="awp_newsletter_submit_'.esc_attr($newsletterform['name']).'" />';
echo '</div>';
echo '</form>';

do_action('apptivo_business_newsletter_'.$newsletterform['name'].'_after_form'); //After Form
?>
