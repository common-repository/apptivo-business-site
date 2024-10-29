<?php

/*
  Template Name: Pop-up - Testimonials Form
  Template Type: Shortcode
 */



$formfields = array();
$formfields = $testimonialform['fields'];
$countrylist = (!empty($countrylist)) ? $countrylist : '';
$countries = $countrylist;
$css = "";
$option= get_option('awp_testimonialforms');
if ($testimonialform['css'] != '') {
    echo $css = '<style type="text/css">' . esc_attr($testimonialform['css']) . '</style>';
}

echo $jscript = '<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#' . esc_attr($testimonialform['name']) . '_testimonialforms").validate({
    rules: {
        telephonenumber: { phoneUS: true},
        hiddenRecaptcha: {
                required: function () {
                	if (grecaptcha.getResponse() == "") {
                       return true;
                    }else {
                        return false;
                    }
                }
            }
       },
    submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';
$_REQUEST['status'] = (array_key_exists('status',$_REQUEST)) ? sanitize_text_field($_REQUEST['status']) : '';
$status_msg = $_REQUEST['status'];
if ($status_msg == "Success") {
    echo $jscript = '<script type="text/javascript">
            jQuery(document).ready(function(){
                document.getElementById("success_testimonial").scrollIntoView();
            });
        </script>';
}
?>
<?php

$divId = '"awp-mask"';
echo '<style type="text/css">
body{ font:bold 12px Arial, Helvetica, sans-serif;margin:0;padding:0;min-width:960px;color:#bbbbbb;}
.awp_testimonial_form input.required,.awp_testimonial_form select{color:#000;font-weight:normal;}
#awp-testimonial-box{width:100%;position:absolute;}
.absp_main_box{width:650px;background-color:#FFF;margin:0 auto;}
#awp-mask {display: none;background: #808285;position: fixed; left: 0; top: 0;z-index: 10;width: 100%; height: 100%;opacity: 0.8;z-index: 999;}
.awp-testimonial-popup{display:none;xbackground: #FFFFFF;padding: 10px;xborder: 2px solid #ddd;float: left;font-size: 1.2em;position: fixed;top: 50%; left: 50%;z-index: 99999;box-shadow: 0px 0px 20px #999;-moz-box-shadow: 0px 0px 20px #999; /* Firefox */-webkit-box-shadow: 0px 0px 20px #999; /* Safari, Chrome */border-radius:3px 3px 3px 3px;-moz-border-radius: 3px; /* Firefox */ -webkit-border-radius: 3px; /* Safari, Chrome */}
img.awp_btn_close {float: right;margin: -28px -28px 0 0;}
fieldset.awp-popup-field {border:none;color:#000000;font-weight: normal;width: 500px;margin: 0 auto;padding: 20px;}
fieldset.awp-popup-field label{display: block;margin: 0 auto;}
.absp_testimonial_mandatory{padding: 0px !important;float: none !important;}
.absp_testimonial_label{display: block;width: 250px;float: left;top: 28px;}
.absp_testimonial_input_text,textarea,fieldset.awp-popup-field .recaptcha_source{width: 250px;float: right;}
#awp_testimonialform_submit{display: block;margin: 0 auto;clear:both;}
fieldset.awp-popup-field input[type="submit"]{display: block;margin: 0 auto;padding: 5px 10px 5px 10px;clear: both;position:relative;top:10px;}
fieldset.awp-popup-field .recaptcha_source{padding: 10px;}
.required{color:#000000;}
label.error{float: right;width: 240px;clear: both;color:#D72128;}
fieldset.awp-popup-field b{text-align:center;display:block;text-align:center;}
.awp-testimonial-popup textarea{height:125px;}
.awp_fullview{clear:both;}
.awp-testimonial-window{text-decoration:none;}
.awp-popup-post{width:610px;}
.absp_testimonial_label{position:relative;top:22px;}
.awp_tmpltype_btn_img{max-width:130px !important;max-height:30px !important;}
.absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}
.absp_error{color:red;font-weight:bold;padding-bottom:5px;}
@media screen and (max-width:1000px){
#awp-testimonial-box{width:100% !important;margin-left:0 !important;margin-top:0px !important;left:0px !important;}
.absp_main_box{width:90%;}
.absp_main_box{background-color:#FFF;}
}
@media screen and (max-width:800px){
#awp-testimonial-box{width:100% !important;margin-left:0 !important;margin-top:0px !important;left:0px !important;}
.absp_main_box{width:80%;}
fieldset.awp-popup-field, .absp_testimonial_label{width:100% !important;float:left;background-color:#FFF;}
fieldset.awp-popup-field label{width:100%;float:left;}
.absp_testimonial_input_text, textarea, fieldset.awp-popup-field .recaptcha_source{width:100%;float:left;}
.absp_main_box{background-color:#FFF;}
.awp_btn_close{position:relative;left:30px;}
.absp_testimonial_label{position:relative;top:0px;}
.awp-testimonial-popup textarea{width:100% !important;}
#recaptcha_widget_div{zoom:0.79;-moz-transform: scale(0.76);}
}
#login-box .form_section:nth-child(4) .form_left_part{display:none;}
footer{clear:both;}
</style>';
echo "<script type='text/javascript'>
jQuery(document).ready(function() {
	jQuery('a.awp-testimonial-window').click(function() {
                var loginBox = jQuery(this).attr('href');
                jQuery(loginBox).fadeIn(300);
                var popMargTop = (jQuery(loginBox).height() + 24) / 2;
		var popMargLeft = (jQuery(loginBox).width() + 24) / 2;
                jQuery(loginBox).css({
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
                jQuery('body').append('<div id=$divId></div>');
		jQuery('#awp-mask').fadeIn(300);
                return false;
	});
                jQuery('a.awp-close-window, #awp-mask').on('click', function() {
                jQuery('#awp-mask , .awp-testimonial-popup').fadeOut(300 , function() {
		jQuery('#awp-mask').remove();
	});
	return false;
	});
});
</script>";
?>
<?php

if ($submitformname == $testimonialform['name'] && $successmsg != "") {
    echo '<div id="success_' . esc_attr($testimonialform['name']) . '" class="absp_success_msg success_' . esc_attr($testimonialform['name']) . '">' . esc_attr($successmsg) . "</div>";
}

do_action('apptivo_business_testimonial_applicant_' . $testimonialform['name'] . '_before_fprm'); //Before submit form

if ($testimonialform['tmpl_button_type'] == "submit" && ($testimonialform['tmpl_button_val']) != "") {
    $button_value = $testimonialform['tmpl_button_val'];
    echo '<div class="awp-popup-post"><a href="#awp-testimonial-box" class="awp-testimonial-window"><button class="awp_tmpltype_btn">'.html_entity_decode(esc_attr($button_value)).'</button></a></div>';
} else {
    $button_value = $testimonialform['tmpl_button_val'];
    echo '<div class="awp-popup-post"><a href="#awp-testimonial-box" class="awp-testimonial-window"><img class="awp_tmpltype_btn_img" src="'.html_entity_decode(esc_attr($button_value)).'"/></a></div>';
}


if (isset($_REQUEST['status'])) {
    $status_msg = sanitize_text_field($_REQUEST['status']);
    if ($status_msg == "Success") {
        echo "<div style='text-align: center;'><b id='success_testimonial' class='absp_success_msg'>Testimonial Added Successfully</b></div> <br/>";
    } else if ($status_msg == "Please enter correct Verification code") {
        $postValues= $_SESSION['POST_VALUES'];
    	echo '<script type="text/javascript">
       jQuery(document).ready(function() {
       jQuery("a.awp-testimonial-window").click();
       jQuery("fieldset.awp-popup-field label").first().before("<b> Please enter correct Verification code </b>");
       });
        </script>';
    } else {
        echo "<div style='text-align: center;'><b id='success_testimonial' class='absp_success_msg'> Error while submitting Testimonials </b></div>";
    }
}
$testimonialId = (!empty($testimonialId)) ? $testimonialId : '';
$testimonialNo = (!empty($testimonialNo)) ? $testimonialNo : '';
echo "<div class='content'>";
echo "<div class='awp_fullview'>" . do_shortcode('[apptivo_testimonials_fullview]') . "</div>";
echo "</div>";
echo '<form id="' . esc_attr($testimonialform['name']) . '_testimonialforms" class="awp_testimonial_form" name="' . esc_attr($testimonialform['name']) . '" action="' . SITE_URL . '/?page=awp_testimonials" method="post">';
echo '<input type="hidden" value="' . esc_attr($testimonialform['name']) . '" name="awp_testimonialformname" id="awp_testimonialformname">';
echo '<input type="hidden" value="' . esc_attr($testimonialId) . '" name="testimonialId" id="testimonialId"><input type="hidden" value="' . esc_attr($testimonialNo) . '" name="testimonialNo" id="testimonialNo">';
echo '<input type="hidden" value="' . esc_attr($testimonialform['name']) . '" name="awp_testimonialformname" id="awp_testimonialformname">';
echo "<div class='container'>
<div id='content'>
<div id='awp-testimonial-box' class='awp-testimonial-popup'>
<div class='absp_main_box'>
<a href='javascript:void(0);' class='awp-close-window'><img src='".awp_image('close-popup')." ' class='awp_btn_close' title='Close Window' alt='Close' /></a>
<fieldset class='textbox awp-popup-field'>";

foreach ($formfields as $field) {
    $fieldid = $field['fieldid'];
    $showtext = $field['showtext'];
    $validation = $field['validation'];
    $required = $field['required'];
    $fieldtype = $field['type'];
    $options = $field['options'];
    $optionvalues = array();

    if ($required) {
        $mandate_property = '"mandatory="true"';
        $validateclass = " required";
    } else {
        $mandate_property = "";
        $validateclass = "";
    }

    switch ($validation) {
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
    echo '<label class="' . esc_attr($showtext) . '">
                <span class="absp_testimonial_label">';
    if($required || $fieldtype=="captcha")
        echo '<span class="absp_testimonial_mandatory">*</span>';

    echo esc_attr($showtext) . "</span>";

    if ($fieldtype == "select" || $fieldtype == "radio" || $fieldtype == "checkbox") {
        if (trim($fieldid) == 'industry') {
            $optionvalues = $options;
            $fieldtype = 'select';
        } else if (trim($options) != "") {

            $option_values = split("[\n]", trim($options)); //Split the String line by line.

            $optionvalues = array();
            foreach ($option_values as $values) :
                $optionvalues[] = trim($values);
            endforeach;
        }
    }
    $postValues[$fieldid] = (!empty($postValues[$fieldid])) ? $postValues[$fieldid] : '';
 switch ($fieldtype) {
        case "text":
            echo '<input type="text" name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid) . '" value="'.esc_attr($postValues[$fieldid]).'"  class="absp_testimonial_input_text' . esc_attr($validateclass) . '"></label>';
            break;
        case "textarea":
            echo '<textarea  name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid) . '" class="absp_testimonial_textarea' . esc_attr($validateclass) . '" size="50">'.esc_attr($postValues[$fieldid]).' </textarea></label>';
            break;
        case "select":
            if ($fieldid == 'country') {
                echo '<select  name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid) . '" value=""  class="absp_testimonial_select' . esc_attr($validateclass) . '">';
                foreach ($countries as $country) {
                    echo '<option value="' . esc_attr($country->countryCode) . '">' . esc_attr($country->countryName) . '</option>';
                }
                echo '</select></label>';
            } else if ($fieldid == 'industry') {
                if (!empty($optionvalues)) {
                    echo '<select  name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid) . '" value=""  class="absp_testimonial_select' . esc_attr($validateclass) . '">';
                    foreach ($optionvalues as $optionvalue) {
                        if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {
                            $options = explode("::", $optionvalue);
                            echo '<option value="' . esc_attr($options[0]) . '">' . esc_attr($options[1]) . '</option>';
                        }
                    }
                    echo '</select></label>';
                } else {
                    echo '<select  name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid) . '" value=""  class="absp_testimonial_select' . esc_attr($validateclass) . '">';
                    echo '<option value="0">Default</option>';
                    echo '</select></span></label>';
                }
            } else {
                echo '<select  name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid) . '" value=""  class="absp_testimonial_select' . esc_attr($validateclass) . '">';
                foreach ($optionvalues as $optionvalue) {
                    if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {
                        echo '<option value="' . esc_attr($optionvalue) . '">' . esc_attr($optionvalue) . '</option>';
                    }
                }
                echo '</select></label>';
            }
            break;
        case "file":
            echo '<input type="file" id="file_upload" name="file_upload" />';
            echo '<input type="hidden" name="upload" id="uploadfile_docid" value="" class="absp_testimonial_input_text' . esc_attr($validateclass) . '"  /></label>';
            break;
        case "radio":
            $i = 0;
            $opt = 0;
            foreach ($optionvalues as $optionvalue) {
                if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {

                    echo '<div class="awp_custom_fields"><input type="radio" name="' . esc_attr($fieldid) . '" id="' . esc_attr($fieldid . $opt) . '" value="' . esc_attr($optionvalue) . '"  class="absp_testimonial_input_radio ' . esc_attr($validateclass) . '">&nbsp&nbsp
					<label class="awp_custom_lbl" for="' . esc_attr($fieldid . $opt) . '">' . esc_attr(trim($optionvalue)) . '</label> </div></label>';
                    $i++;
                    $opt++;
                }
            }
            break;
        case "checkbox":
            $i = 0;
            $opt = 0;
            foreach ($optionvalues as $optionvalue) {
                if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {

                    echo '<div class="awp_custom_fields">
					<input type="checkbox" name="' . esc_attr($fieldid) . '[]" id="' . esc_attr($fieldid . $opt) . '" value="' . esc_attr($optionvalue) . '"  class="absp_testimonial_input_checkbox ' . esc_attr($validateclass) . '">&nbsp&nbsp<label class="awp_custom_lbl" for="' . esc_attr($fieldid . $opt) . '">' . esc_attr(trim($optionvalue)) . '</label>
                                            </div></label>';
                    $i++;
                    $opt++;
                }
            }
            break;
        case "captcha":
            awp_reCaptcha();
            echo "</label>";
            break;
    }
}

/* redirection URL */
$actual_link = SITE_URL.$_SERVER['REQUEST_URI'];

$_SESSION['request_link'] = $actual_link;



echo '<input type="hidden" name="awp_testimonialform_submit"/>';
if ($testimonialform['submit_button_type'] == "submit" && ($testimonialform['submit_button_val']) != "") {
    $button_value = 'value="' . $testimonialform['submit_button_val'] . '"';
} else {
    if ($testimonialform['submit_button_val'] == '' || empty($testimonialform['submit_button_val'])) :
        $testimonialform['submit_button_val'] = awp_image('submit_button');
    endif;
    $button_value = 'src="' . $testimonialform['submit_button_val'] . '"';
}

do_action('apptivo_business_testimonial_applicant_' . $testimonialform['name'] . '_before_submit_query'); //Before Submit Query

echo '<input type="' . esc_attr($testimonialform['submit_button_type']) . '" class="absp_testimonial_button_submit awp_testimonialform_submit_' . esc_attr($testimonialform['name']) . '" ' . html_entity_decode(esc_attr($button_value)) . ' name="awp_testimonialform_submit" id="awp_testimonialform_submit" />';
echo '</fieldset>
</div>
</div>
</div>
</div>
</form>';
?>





