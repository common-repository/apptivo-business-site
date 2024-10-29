<?php
/*
 Template Name:Single Column Layout1_US Phone
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
        #'.esc_attr($newsletterform['name']).'_newsletter_phone1,#'.esc_attr($newsletterform['name']).'_newsletter_phone2,#'.esc_attr($newsletterform['name']).'_newsletter_phone3 {width: 26%;float: left;margin-right: 5%;}
        span.error_message,label.error {color:red;width:100%;float:left}
        .absp_error{color:red !important;}
        .absp_success_msg {color: green;font-weight: bold;padding: 10px 0;}
	.abswpnfm input, .abswpnfm textarea, .abswpnfm select {width:95%;}
        .abswpnfm select {padding:6px;}
        .abswpnfm input[type="button"], .abswpnfm input[type="reset"], .abswpnfm input[type="submit"], .abswpnfm input[type="image"] {width:auto;margin-top: 5px}
        .abswpnfm input[type="image"] {border:none}
    	@media screen and (max-width:900px){
		.awp_newsletter_maindiv_'.$contactform_name.' .form_left_part {width:100%;float:left;}
		.awp_newsletter_maindiv_'.$contactform_name.' .form_rgt_part{width:100%;float:left;margin-top:5px;}
		#recaptcha_widget_div{zoom:0.79;-moz-transform: scale(0.76);}
		}
		@media screen and (max-width:360px){
		#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
		}
      </style>';
echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#'.esc_attr($newsletterform['name']).'_newsletter").validate({
    rules: {
        '.esc_attr($newsletterform['name']).'_newsletter_phone1: { minlength: 3},
        '.esc_attr($newsletterform['name']).'_newsletter_phone2: { minlength: 3 },
        '.esc_attr($newsletterform['name']).'_newsletter_phone3: { minlength: 4 }
    },
    groups: {
        '.esc_attr($newsletterform['name']).'_newsletter_phone: "'.esc_attr($newsletterform['name']).'_newsletter_phone1 '.esc_attr($newsletterform['name']).'_newsletter_phone2 '.esc_attr($newsletterform['name']).'_newsletter_phone3"
    },
messages: {
'.esc_attr($newsletterform['name']).'_newsletter_phone1: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        },
'.esc_attr($newsletterform['name']).'_newsletter_phone2: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        },
'.esc_attr($newsletterform['name']).'_newsletter_phone3: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        }
        },
   errorPlacement: function(error, element) {
         if (element.attr("name") == "'.esc_attr($newsletterform['name']).'_newsletter_phone1" || element.attr("name") == "'.esc_attr($newsletterform['name']).'_newsletter_phone2" || element.attr("name") == "'.esc_attr($newsletterform['name']).'_newsletter_phone3")
         error.insertAfter("#'.esc_attr($newsletterform['name']).'_newsletter_phone3");
       else
        error.insertAfter(element);
   },
   submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';

if($submitformname==$newsletterform['name'] && $successmsg!=""){
	echo '<script type="text/javascript">
	jQuery(document).ready(function(){
	document.getElementById("success_'.esc_attr($newsletterform['name']).'").scrollIntoView();
	});
	</script>';
	echo  '<div id="success_'.esc_attr($newsletterform['name']).'" class="absp_success_msg success_'.esc_attr($newsletterform['name']).'">'.esc_attr($successmsg)."</div>";
}

do_action('apptivo_business_newsletter_'.$newsletterform['name'].'_before_form');
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
			if($fieldid != 'newsletter_phone')
			{
				echo '<input type="text" name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_input_text'.esc_attr($validateclass).'">';
			}
			else
			{
				echo '<input maxlength="3" size="3" type="text" name="'.esc_attr($newsletterform['name']).'_'.esc_attr($fieldid).'1" id="'.esc_attr($newsletterform['name']).'_'.esc_attr($fieldid).'1" value=""  class="absp_newsletter_input_text'.esc_attr($validateclass).'">';
				echo '&nbsp;&nbsp;&nbsp;<input maxlength="3" size="3" type="text" name="'.esc_attr($newsletterform['name']).'_'.esc_attr($fieldid).'2" id="'.esc_attr($newsletterform['name']).'_'.esc_attr($fieldid).'2" value=""  class="absp_newsletter_input_text'.esc_attr($validateclass).'">';
				echo '&nbsp;&nbsp;&nbsp;<input maxlength="4" size="4" type="text" name="'.esc_attr($newsletterform['name']).'_'.esc_attr($fieldid).'3" id="'.esc_attr($newsletterform['name']).'_'.esc_attr($fieldid).'3" value=""  class="absp_newsletter_input_text'.esc_attr($validateclass).'">';
			}
			break;
		case "textarea":
			echo  '<textarea  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_textarea'.esc_attr($validateclass).'" size="50"></textarea>';
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

do_action('apptivo_business_newsletter_'.$newsletterform['name'].'_before_submit_query');

echo '<input type="'.esc_attr($newsletterform['submit_button_type']).'" class="absp_newsletter_button_submit awp_newsletter_submit_'.esc_attr($newsletterform['name']).'" '.html_entity_decode(esc_attr($button_value)).' name="awp_newsletter_submit_'.esc_attr($newsletterform['name']).'"  id="awp_newsletter_submit_'.esc_attr($newsletterform['name']).'" />';
echo '</div>';
echo '</form>';
do_action('apptivo_business_newsletter_'.$newsletterform['name'].'_after_form');
?>
