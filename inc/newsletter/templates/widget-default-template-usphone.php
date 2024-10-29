<?php
/*
 Template Name: Default Template Us Phone
 Template Type: Widget
 */
echo html_entity_decode(esc_attr($before_widget));
if(!empty($newsletterformfields)){
	if ($instance['title']) echo html_entity_decode(esc_attr($before_title)) . apply_filters('widget_title', $instance['title']) . $after_title;
	
	if( $instance['widget_style'] != '' )
	{
	 echo $css='<style type="text/css">'.esc_attr($instance['widget_style']).'</style>';
	}
        wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));
	wp_print_scripts('jquery_validation');
	echo $jscript='<script type="text/javascript">
			jQuery(document).ready(function(){
			jQuery("#'.esc_attr($newsletterform['name']).'_newsletter_widget").validate({
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
			});'
                          ;
if($successmsg!="" && $newsletterform['properties']['confmsg']!="")
{
    echo ' document.getElementById("success_'.esc_attr($newsletterform['name']).'").scrollIntoView();
});
</script>';
}
else
{
    echo ' }); </script>';
}
	if($successmsg!=""){
            echo '<div id="awp_focusmsg">';
            echo  '<div id="success_'.esc_attr($newsletterform['name']).'" class="absp_success_msg success_'.esc_attr($newsletterform['name']).'">'.esc_attr($successmsg)."</div>";
            echo '</div>';
	}
	
	do_action('apptivo_business_newsletter_widget_before_form');//Before Newsletter form
	echo '<style type="text/css"> .absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error,.error_message{color:red;font-weight:bold;padding-bottom:5px;}</style>';
	echo '<form id="'.esc_attr($newsletterform['name']).'_newsletter_widget" name="'.esc_attr($newsletterform['name']).'_newsletter_widget" action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="post">';
	echo '<input type="hidden" value="'.esc_attr($newsletterform['name']).'" name="awp_newsletterwidgetname" id="awp_newsletterwidgetname">';
	echo '<input type="hidden" value="'.esc_attr($newsletterproperties['category']).'" name="newsletter_category" id="newsletter_category">';
	echo '<div class="awp_newsletter_widget_maindiv_'.esc_attr($newsletterform['name']).'">';
	foreach($newsletterformfields as $field)
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
				echo   '<textarea  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_textarea'.esc_attr($validateclass).' size="50"></textarea>';
				break;
				
			case "select":
				echo   '<select  name="'.esc_attr($fieldid).'" id="'.esc_attr($fieldid).'" value=""  class="absp_newsletter_select'.esc_attr($validateclass).'">';
				foreach( $optionvalues as $optionvalue )
				{
					echo   '<option value="'.esc_attr($optionvalue).'">'.esc_attr($optionvalue).'</option>';
				}
				echo   '</select>';
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

	echo '</div>';
	echo '<input type="hidden" name="newsletterform_widget" />';
	if($newsletterproperties['submit_button_type']=="submit" &&($newsletterproperties['submit_button_val'])!="")
	{
		$button_value = 'value="'.$newsletterproperties['submit_button_val'].'"';
	} else {
		if(strlen(trim($newsletterproperties['submit_button_val'])) == 0)
		{
			$imgSrc = awp_image('submit_button');
		}else {

			$imgSrc = $newsletterproperties['submit_button_val'];
		}
		 
		$button_value = 'src="'.$imgSrc.'"';
	}
	
	do_action('apptivo_business_newsletter_widget_before_submit_query'); //Before_Submit_Query
	
	echo '<br /><input type="'.esc_attr($newsletterproperties['submit_button_type']).'"  '.html_entity_decode(esc_attr($button_value)).' class="absp_newsletter_button_submit" name="'.esc_attr($newsletterform['name']).'_newsletterform_widget_submit"  id="'.esc_attr($newsletterform['name']).'newsletterform_widget_submit" />';
	echo '</form>';

	do_action('apptivo_business_newsletter_widget_after_form');//After Newsletter form
}
echo html_entity_decode(esc_attr($after_widget));
wp_reset_query();
?>