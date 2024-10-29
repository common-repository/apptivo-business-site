
jQuery(document).ready(function($) {
	
	
$("#customerName").change(function(){
	var selectedCustomerName = jQuery('#customerName option:selected').text();
    var selectedCustomerId = jQuery('#customerName option:selected').val();
    $("#customerHid").val(selectedCustomerName);
    $.ajax({
        type : "POST",
        dataType : "json",
        url : "/wp-admin/admin-ajax.php",
        data : {"action": "getContactsByCustomerId","id":selectedCustomerId},
        success: function(response) {
       	
      	 var options ;
      
      	 if(response.length > 0){
      		 options = '<option value="">Select One</option>';
      		 $.each( response, function( key, value ) {
       		  options += '<option value="' + response[key].contactId + '">' + response[key].contactName + '</option>';
       		});
       	 $("select#contactName").html(options);
       	 }else{
       		
       		 options = '<option value="">No Contacts Found</option>';
       		$("select#contactName").html(options)
       	 }
      	 
        }
    });  
    
    
});

$("#contactName").change(function(){
	var selectedContactName = jQuery('#contactName option:selected').text();
	  $("#contactHid").val(selectedContactName);
});

$("#updateCustomerName").change(function(){
	var selectedCustomerName = jQuery('#updateCustomerName option:selected').text();
    var selectedCustomerId = jQuery('#updateCustomerName option:selected').val();
    $("#updatecustomerHid").val(selectedCustomerName);
    $.ajax({
        type : "POST",
        dataType : "json",
        url : "/wp-admin/admin-ajax.php",
        data : {"action": "getContactsByCustomerId","id":selectedCustomerId},
        success: function(response) {
       	
      	 var options ;
      
      	 if(response.length > 0){
      		 options = '<option value="">Select One</option>';
      		 $.each( response, function( key, value ) {
       		  options += '<option value="' + response[key].contactId + '">' + response[key].contactName + '</option>';
       		});
       	 $("select#updatecontactName").html(options);
       	 }else{
       		
       		 options = '<option value="">No Contacts Found</option>';
       		$("select#updatecontactName").html(options)
       	 }
      	 
        }
    });  
    
    
});

$("#updatecontactName").change(function(){
	var selectedContactName = jQuery('#updatecontactName option:selected').text();
	  $("#updatecontactHid").val(selectedContactName);
})

	
	if($("#elementToResize").size() > 0 )
	{
	var w = document.body.offsetWidth;
	var wid = ( w < 950 ) ? w-170 : 950;
	var elem = document.getElementById("elementToResize"); 
	elem.style.width = wid+'px';
	}
	
	jQuery('#contact_upload_image').click(function() {
		 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		 tbframe_interval = setInterval(function() {
	            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image URL');
	            }, 2000);
		 return false;
		});
		if(jQuery("#contact_upload_image").size() > 0 ) {
	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');	
		 jQuery('#awp_contactform_submit_value').val(imgurl);
		 tb_remove();
		}
		}
            
		
	$('form#awp_contactform_new').submit(function(){
		 var contact_form = jQuery('#newcontactformname').val();
		 var contactform = jQuery.trim( contact_form );	
		 if(contactform == '')
		 {
			 $('#newcontactformname').css('border-color', '#f00');
			 $('#message').remove(); 
			 $('#errormessage').remove();	
			 $('.contactform_err').before('<div id="errormessage" class="updated"><p style="color:#f00;font-weight:bold;" >Contact Form Name cannot be empty.<p></div>');
			 return false;
		 }else {
			 $('#errormessage').remove();		 
			 $('#newcontactformname').css('border-color', '#CCCCCC');
			 return true;
		 }		
	});
	
	$('form#awp_newsletterform_new').submit(function(){
		 var newsletter_form = jQuery('#newnewsletterformname').val();
		 var newsletterform = jQuery.trim( newsletter_form );	
		 if(newsletterform == '')
		 {
			 jQuery('#newnewsletterformname').css('border-color', '#f00');
			 jQuery('#message').remove(); 
			 jQuery('#errormessage').remove();	
			 jQuery('.newsletterform_err').before('<div id="errormessage" class="updated"><p style="color:#f00;font-weight:bold;" >Newsletter Form Name cannot be empty. .<p></div>');
			 return false;
		 }else {
			 jQuery('#errormessage').remove();		 
			 jQuery('#newnewsletterformname').css('border-color', '#CCCCCC');
			 return true;
		 }
	});
	$('form#awp_cases_new_form').submit(function(){
		 var contact_form = jQuery('#newcasesformname').val();
		 var contactform = jQuery.trim( contact_form );	
		 if(contactform == '')
		 {
			 $('#newcasesformname').css('border-color', '#f00');
			 $('#message').remove(); 
			 $('#errormessage').remove();	
			 $('.casesform_err').before('<div id="errormessage" class="updated"><p style="color:#f00;font-weight:bold;" >Cases Form Name cannot be empty.<p></div>');
			 return false;
		 }else {
			 $('#errormessage').remove();		 
			 $('#newcasesformname').css('border-color', '#CCCCCC');
			 return true;
		 }		
	});

	   jQuery('input:radio[name=awp_contactform_confirm_msg_page]').change(function() {
	      if(jQuery('input:radio[name=awp_contactform_confirm_msg_page]:checked').val() == 'same')
	       	{
	       	jQuery('#awp_contactform_confirmmsg_pageid').hide();
	       	jQuery('#awp_contactform_confirmationmsg_tr').show();
	       	}	       	
	       else{ 
	      	jQuery('#awp_contactform_confirmmsg_pageid').show();
	      	jQuery('#awp_contactform_confirmationmsg_tr').hide();
	      	}
	    });

        jQuery('input:radio[name=awp_jobsform_confirm_msg_page]').change(function() {
	      if(jQuery('input:radio[name=awp_jobsform_confirm_msg_page]:checked').val() == 'same')
	       	{
	       	jQuery('#awp_jobsform_confirmmsg_pageid').hide();
	       	jQuery('#awp_jobsform_confirmationmsg_tr').show();
	       	}
	       else{
	      	jQuery('#awp_jobsform_confirmmsg_pageid').show();
	      	jQuery('#awp_jobsform_confirmationmsg_tr').hide();
	      	}
	    });
	 	                 
	   jQuery('input:radio[name=awp_contactform_submit_type]').change(function() {
	   jQuery('#awp_contactform_submit_value').val('');
	        if(jQuery('input:radio[name=awp_contactform_submit_type]:checked').val()=='submit')
	        {
	            jQuery('#awp_contactform_submit_val').text('Button Text');
	            jQuery("#contact_upload_img_button").hide();
	        }
	        else {
	          jQuery('#awp_contactform_submit_val').text('Button Image URL');
	          jQuery("#contact_upload_img_button").show();
	        }
	    });	    

	   $("input.num").keypress(function (e){
			  var charCode = (e.which) ? e.which : e.keyCode;
			  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			    return false;
			  }
			});
	if($("#addcustom_field").size() > 0 )
     {
		
		jQuery("#contactform_shortcode").focus(function(){
			this.select();
		});	
		
		 if(jQuery('input:radio[name=awp_contactform_submit_type]:checked').val()=='submit')
		    {
		           $('#awp_contactform_submit_val').text('Button Text');
		           $("#contact_upload_img_button").hide();
		    }else {
		          $('#awp_contactform_submit_val').text('Button Image URL');
		          $("#contact_upload_img_button").show();
		   }	
		 
	var contact_counter = $("#addcustom_field").attr("rel");
	jQuery("#addcustom_field").click(function ()
	{   
		jQuery('#contact_form_fields tr:last').after('<tr><td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;">Custom Field '+ contact_counter + '</td><td align="center" style="border: 1px solid rgb(204, 204, 204);">' + 
				'<input type="checkbox" id="customfield'+ contact_counter + '_show" name="customfield'+ contact_counter + '_show" size="30" rel="customfield'+ contact_counter + '" class="custom_fld"  class="custom_fld" onclick="contactform_enablefield(\'customfield'+contact_counter+'\')">' +
				'<input type="hidden" id="customfield'+ contact_counter + '_newest" name="customfield'+ contact_counter + '_newest" value=""></td>' + 
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="checkbox" id="customfield'+ contact_counter + '_require" name="customfield'+ contact_counter + '_require" size="30" disabled="">' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="text" class="num" id="customfield'+ contact_counter + '_order" name="customfield'+ contact_counter + '_order" value="" size="3" maxlength="2" disabled="">' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="text" id="customfield'+ contact_counter + '_text" name="customfield5_text" value="Custom Field'+ contact_counter + '" disabled="">' +
				'</td>' +				
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<select name="customfield'+ contact_counter + '_type" id="customfield'+ contact_counter + '_type" onchange="contactform_showoptionstextarea(\'customfield'+contact_counter+'\');" disabled="">' +
				'<option value="checkbox">Checkbox</option>' +
				'<option value="radio">Radio Option</option>' +
				'<option value="select">Select</option>' +
				'<option value="text">Textbox</option>' +
				'<option value="textarea">Textarea</option>' +
				'</select>' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<select name="customfield'+ contact_counter + '_validation" id="customfield'+ contact_counter + '_validation" disabled=""><option value="none">None</option>' +
				'<option value="email">Email ID</option>' +
				'<option value="number">Number</option>' +
				'</select>' +
				'</td>' +			
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<textarea style="display: block; width: 190px;" id="customfield'+ contact_counter + '_options" name="customfield'+ contact_counter + '_options" ></textarea>' +
				'</td>' +
				'</tr>');
		contact_counter++;
	});
     }
		
	$("input.custom_fld").click(function() {
	   var fieldid = $(this).attr('rel');
	   var checked=document.getElementById(fieldid+'_show').checked;
		var cfield_index=fieldid.indexOf('customfield');
		if(checked){
			document.getElementById(fieldid+'_text').disabled=!checked;
			document.getElementById(fieldid+'_order').disabled=!checked;
			document.getElementById(fieldid+'_require').disabled=!checked;
			document.getElementById(fieldid+'_validation').disabled=!checked;
			if(cfield_index==0){
				document.getElementById(fieldid+'_type').disabled=!checked;
				if (document.getElementById(fieldid+'_type').value == 'checkbox' ) 
				{
				document.getElementById(fieldid+'_validation').disabled="disabled";
				}
	            document.getElementById(fieldid+'_options').style.display="block";
				document.getElementById(fieldid+'_options').disabled=!checked;
			}
		}else{
			document.getElementById(fieldid+'_text').disabled="disabled";
			document.getElementById(fieldid+'_order').disabled="disabled";
			document.getElementById(fieldid+'_require').disabled="disabled";
			document.getElementById(fieldid+'_validation').disabled="disabled";
			if(cfield_index==0){
				document.getElementById(fieldid+'_type').disabled="disabled";
				document.getElementById(fieldid+'_options').disabled="disabled";	
			}
		}
	
	});
	
	

	$('#newsletter_image_button').click(function() {
		 formfield = jQuery('#upload_image').attr('name');
		 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		 tbframe_interval = setInterval(function() {
	            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image URL');
	            }, 2000);
		 return false;
		});
	
	if($("#newsletter_image_button").size() > 0 ) {
	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');		 
		 jQuery('#awp_newsletterform_submit_value').val(imgurl);
		 tb_remove();
		}
	}
	
	 jQuery("#newsletterform_shortcode").focus(function(){
	    	this.select();
	    });    	
	    if(jQuery('input:radio[name=awp_newsletterform_submit_type]:checked').val()=='submit')
	    {
	      jQuery('#awp_newsletterform_submit_val').text('Button Text');
	      jQuery("#newsletter_upload_img_button").hide();
	    }else {
	      jQuery('#awp_newsletterform_submit_val').text('Button Image URL');
	      jQuery("#newsletter_upload_img_button").show();
	    }
	          
	   jQuery('input:radio[name=awp_newsletterform_submit_type]').change(function() {
	    jQuery('#awp_newsletterform_submit_value').val('');
	        if(jQuery('input:radio[name=awp_newsletterform_submit_type]:checked').val()=='submit')
	        {
	            jQuery('#awp_newsletterform_submit_val').text('Button Text');
	            jQuery("#newsletter_upload_img_button").hide();
	        }else {
	          jQuery('#awp_newsletterform_submit_val').text('Button Image URL');
	          jQuery("#newsletter_upload_img_button").show();
	        }
	    });
	   
	   
	   jQuery('#testimonials_upload_images').click(function() {
			 formfield = jQuery('#upload_image').attr('name');
			 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
			 tbframe_interval = setInterval(function() {
		            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Image URL');
		            }, 2000);
			 return false;
			});
	   if($("#testimonials_upload_images").size() > 0 ) {
		window.send_to_editor = function(html) {		
			 imgurl = jQuery('img',html).attr('src');
			 jQuery('#awp_testimonials_imageurl').val(imgurl);
			 tb_remove();
			}
	   }
     
		jQuery("#testimonials_fullview_shortcode").focus(function(){
	    	this.select();
	    });
		jQuery("#testimonials_inlineview_shortcode").focus(function(){
	    	this.select();
	    }); 
		
		addTestimonials();
		jQuery("#captcha_show").click(function(){
			jQuery("#captcha_require").attr("checked","checked").attr("disabled","disabled");
		});
		if((jQuery("#awp_captcha_enable").val())=="")
			{
			jQuery("#captcha_show").attr("disabled", true).prop("checked", false).attr("title","Please Enable Recaptcha in Plugin settings.");
			}
		if((jQuery(".awp_targetlist").val())=="0")
			{
			jQuery('.newletter-add').remove(); jQuery('#newnewsletterformname').attr('disabled','true').attr("title","Please add target list in apptivo before configuring newsletter");
			}
			jQuery("#type_require,#priority_require").attr("disabled", true).prop("checked", true);
});

function isNumberKey(evt)
{
var charCode = (evt.which) ? evt.which : event.keyCode
if (charCode > 31 && (charCode < 48 || charCode > 57))
  return false;

return true;
}

function contactform_selectCategory(fldid)
{
	var catId = document.getElementById(fldid).value;
	if(catId == '')
	{
		document.getElementById('subscribe_option_yes').disabled="disabled";
		document.getElementById('subscribe_option_no').disabled="disabled";
		document.getElementById('awp_subscribe_to_newsletter').disabled="disabled";
	}else {
		document.getElementById('subscribe_option_yes').disabled=false;
		document.getElementById('subscribe_option_no').disabled=false;	
		document.getElementById('awp_subscribe_to_newsletter').disabled=false;		
	}
}

function change_contact_Template()
{
	if(document.getElementById('awp_contactform_templatetype').value == 'theme_template' )
	{  
		document.getElementById('awp_contactform_plugintemplatelayout').style.display = "none";
		document.getElementById('awp_contactform_themetemplatelayout').style.display = "block";
	}
	else {
		document.getElementById('awp_contactform_plugintemplatelayout').style.display = "block";
		document.getElementById('awp_contactform_themetemplatelayout').style.display = "none";
	}

}
function contactform_showoptionstextarea(fieldid){
	var type=document.getElementById(fieldid+'_type').value;
	if ((type=="select") || (type=="radio") || (type=="checkbox") || (type=="multiselect")){
		document.getElementById(fieldid+'_options').disabled=false;
		document.getElementById(fieldid+'_options').style.display ="block";
		document.getElementById(fieldid+'_validation').disabled="disabled";
	}else{
		document.getElementById(fieldid+'_validation').disabled="disabled";
		document.getElementById(fieldid+'_options').disabled="disabled";
		document.getElementById(fieldid+'_options').style.display ="none";
		if(type=="text")
		{
			document.getElementById(fieldid+'_validation').disabled=false;
		}
	}
}

function contact_confirmation(formname) {
	var answer = confirm('Are you sure want to delete contact form: "'+formname+'"?');
	if (answer){
		document.getElementById('delformname').value = formname;
		document.awp_contact_delete_form.submit();		
	}
	else{
		document.getElementById('delformname').value = '';
	}
}

function contactform_enablefield(fieldid)
{
	 var checked=document.getElementById(fieldid+'_show').checked;
		var cfield_index=fieldid.indexOf('customfield');
		if(checked){
			document.getElementById(fieldid+'_text').disabled=!checked;
			document.getElementById(fieldid+'_order').disabled=!checked;
			document.getElementById(fieldid+'_require').disabled=!checked;
			document.getElementById(fieldid+'_validation').disabled=!checked;
			if(cfield_index==0){
				document.getElementById(fieldid+'_type').disabled=!checked;
				if (document.getElementById(fieldid+'_type').value == 'checkbox' ) 
				{
				document.getElementById(fieldid+'_validation').disabled="disabled";
				}
	            document.getElementById(fieldid+'_options').style.display="block";
				document.getElementById(fieldid+'_options').disabled=!checked;
			}
		}else{
			document.getElementById(fieldid+'_text').disabled="disabled";
			document.getElementById(fieldid+'_order').disabled="disabled";
			document.getElementById(fieldid+'_require').disabled="disabled";
			document.getElementById(fieldid+'_validation').disabled="disabled";
			if(cfield_index==0){
				document.getElementById(fieldid+'_type').disabled="disabled";
				document.getElementById(fieldid+'_options').disabled="disabled";	
			}
		}
}

function changeTemplateNewsletter()
{
if(document.getElementById('awp_newsletterform_templatetype').value == 'theme_template' )
{  
	document.getElementById('awp_newsletterform_plugintemplatelayout').style.display = "none";
	document.getElementById('awp_newsletterform_themetemplatelayout').style.display = "block";
}
else {
	document.getElementById('awp_newsletterform_plugintemplatelayout').style.display = "block";
	document.getElementById('awp_newsletterform_themetemplatelayout').style.display = "none";
}

}

function enablefield(fieldid){
    var checked=document.getElementById(fieldid+'_show').checked;
if(checked){
		document.getElementById(fieldid+'_require').disabled=!checked;
		document.getElementById(fieldid+'_order').disabled=!checked;
		document.getElementById(fieldid+'_text').disabled=!checked;
}else{
	  document.getElementById(fieldid+'_require').disabled="disabled";
	  document.getElementById(fieldid+'_order').disabled="disabled";
	  document.getElementById(fieldid+'_text').disabled="disabled";
}
}

function newsletter_confirmation(formname) {
	var answer = confirm('Are you sure want to delete Newsletter form name: "'+formname+'"');
	if (answer){
		document.getElementById('delformname').value = formname;
		document.awp_newsletter_deletion_form.submit();    		
	}
	else{
		document.getElementById('delformname').value = '';
	}
}
function validatetestimonialsforms()
{    
	 var error = '';
	 var testimonials_name = jQuery('#awp_testimonials_name').val();
	 //var testimonilasname = jQuery.trim( testimonials_name );
	 var customerName = jQuery('#customerName').val();
	 var awp_testimonials_link = jQuery('#awp_testimonials_website').val();
	 var awpTestimonialsLink = jQuery.trim( awp_testimonials_link );
     var awp_testimonialsemail = jQuery.trim( jQuery('#awp_testimonials_email').val() );
	
     var editor = tinymce.get( 'awp_testimonials_cnt');
	 editor.save()
	
	 var testimonials_content = jQuery('#awp_testimonials_cnt').val();
	 var testimonialscontent = jQuery.trim( testimonials_content );

	 if(customerName == '')
	 {
		 jQuery('#customerName').css('border-color', '#f00'); 
	 }else {
		 jQuery('#customerName').css('border-color', '#CCCCCC');
	 }
	 if(testimonialscontent == '')
	 {  
		 jQuery('#awp_testimonials_cnt_ifr').css('border', '1px solid #f00');
	 }else {
		 jQuery('#awp_testimonials_cnt_ifr').css('border', 'none');
	 }
	 		
	 if(customerName == '' || testimonialscontent == '')
	 {
		 jQuery('#message').remove(); 
		 jQuery('#errormessage').remove();	
		 jQuery('.testimonilas_err').before('<div id="errormessage" class="updated"><p style="color:#f00;font-weight:bold;" >Please fill the mandatory fields.<p></div>');
		 return false;
	 }else {
		 jQuery('#errormessage').remove();		 
		 jQuery('#awp_testimonials_name').css('border-color', '#CCCCCC');
		 if(awpTestimonialsLink == '')
         {  
			 jQuery('#awp_testimonials_website').css('border-color', '#CCCCCC');			
         }else {
        	 error += isValidURL_testimonials(awpTestimonialsLink);
         }
		 error = jQuery.trim( error );
		 if( error == '')	
		 { 
			 if( !validate_testimonials_email(awp_testimonialsemail) )
			 {
				 return false;
			 }
			 return true;
		 }else 
		 { 
		   return false; 
		  }
	 }
	

}

function validate_testimonials_email(awp_testimonialsemail)
{
	if(awp_testimonialsemail == '')
    { jQuery('#awp_testimonials_email').css('border-color', '#CCCCCC');
        return true;
    }else {
   	 var is_valid_address = validateEmail(awp_testimonialsemail);
   	 if(!is_valid_address)
   	 {
   		 jQuery('#message').remove(); 
   		 jQuery('#errormessage').remove();
   		 jQuery('#awp_testimonials_email').css('border-color', '#f00');
   		 jQuery('.testimonilas_err').before('<div id="errormessage" class="updated"><p style="color:#f00;font-weight:bold;" >Please enter valid email address.<p></div>');
   		 return false;
   	 }else{
   		 return true;
   	 }
   	 
    }
}

function validateEmail(email) 
{
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if( !emailReg.test( email ) ) {
		return false;
	} else {
		return true;
	}

}

function delete_testimonials(status)
{
	if(status != 1)
       {
           
		alert('Testiomonials Plugin is currently disabled.');
           return false;
       }
	var answer = confirm('Are you sure want to delete Testimonials?');
	if (answer){
		return true;
	}
	else{
		return false;
	}
 }
function testimonials_change_template()
{
    if(document.getElementById('awp_testimonials_templatetype').value == 'theme_template' )
    {
        document.getElementById('awp_testimonials_plugintemplatelayout').style.display = "none";
        document.getElementById('awp_testimonials_themetemplatelayout').style.display = "block";
    }
    else {
        document.getElementById('awp_testimonials_plugintemplatelayout').style.display = "block";
        document.getElementById('awp_testimonials_themetemplatelayout').style.display = "none";
    }

}
function isValidURL_testimonials(url){
	var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if(RegExp.test(url)){
    	     jQuery('#awp_testimonials_website').css('border-color', '#CCCCCC'); 
             return '';
    }else{
    	 jQuery('#message').remove(); 
    	 jQuery('#errormessage').remove();	
    	 jQuery('.testimonilas_err').before('<div id="errormessage" class="updated"><p style="color:#f00;font-weight:bold;" >Please enter a valid URL.<p></div>');
		 jQuery('#awp_testimonials_website').css('border-color', '#f00'); 
    	 return 'Invalid Url';
    }
}

jQuery(document).ready(function($) {
	$("#jobs_shortcode").focus(function(){
    	this.select();
    });
	
	jQuery("#job_applicantform_shortcode").focus(function(){
    	this.select();
    });
	
	jQuery("#job_description_shortcode").focus(function(){
    	this.select();
    });
	
	jQuery("#job_searchform_shortcode").focus(function(){
    	this.select();
    });
	
	
	jQuery('#jsearch_upload_image').click(function() {
		 formfield = jQuery('#upload_image').attr('name');
		 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		 tbframe_interval = setInterval(function() {
	            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image URL');
	            }, 2000);
		 return false;
		});
	 if($("#jsearch_upload_image").size() > 0 ) {
	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');		 
		 jQuery('#awp_jobsearchform_submit_value').val(imgurl);
		 tb_remove();
		}
	 }
	
	jQuery('#japplicant_upload_image').click(function() {
		 formfield = jQuery('#upload_image').attr('name');
		 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		 tbframe_interval = setInterval(function() {
	            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image URL');
	            }, 2000);
		 return false;
		});
	 if($("#japplicant_upload_image").size() > 0 ) {
	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery('#awp_jobsform_submit_val').val(imgurl);
		 tb_remove();
		}
	 }
	
	
	 if(jQuery('input:radio[name=awp_jobsform_submit_type]:checked').val()=='submit')
	    {
	          jQuery('#awp_jobsform_submit_value').text('Button Text');
	          jQuery("#japp_upload_img_button").hide();
	    }else{
	    	  jQuery('#awp_jobsform_submit_value').text('Button Image URL');
	          jQuery("#japp_upload_img_button").show();
	    }
	    
	   jQuery('input:radio[name=awp_jobsform_submit_type]').change(function() {
	        if(jQuery('input:radio[name=awp_jobsform_submit_type]:checked').val()=='submit')
	        {
	            jQuery('#awp_jobsform_submit_value').text('Button Text');
	            jQuery("#japp_upload_img_button").hide();
	            jQuery('#awp_jobsform_submit_val').val('');
	        }else{
	          jQuery('#awp_jobsform_submit_value').text('Button Image URL');
	        jQuery("#japp_upload_img_button").show();
	        }
	    });
	   

	    
	    if(jQuery('input:radio[name=awp_jobsearchform_submit_type]:checked').val()=='submit')
	    { 
	      jQuery('#awp_jobsearchform_submit_val').text('Button Text');
	      jQuery("#jsearch_upload_img_button").hide();
	    } else {
	      jQuery('#awp_jobsearchform_submit_val').text('Button Image URL');
	      jQuery("#jsearch_upload_img_button").show();
	    }
	   jQuery('input:radio[name=awp_jobsearchform_submit_type]').change(function() {
		   jQuery('#awp_jobsearchform_submit_value').val('');
	        if(jQuery('input:radio[name=awp_jobsearchform_submit_type]:checked').val()=='submit')
	        {    
	            jQuery('#awp_jobsearchform_submit_val').text('Button Text');
	            jQuery("#jsearch_upload_img_button").hide();
	        }
	        else
	        {
	          jQuery('#awp_jobsearchform_submit_val').text('Button Image URL');
	          jQuery("#jsearch_upload_img_button").show();
	        }
	    }); 
	   
	
	   jQuery('#awp_jobdesc_templatetype').change(function() {

			if(document.getElementById('awp_jobdesc_templatetype').value == 'theme_template' )
			{  
				document.getElementById('awp_jobdesc_template').style.display = "none";
				document.getElementById('awp_jobdesc_theme_template').style.display = "block";
			}
			else {
				document.getElementById('awp_jobdesc_template').style.display = "block";
				document.getElementById('awp_jobdesc_theme_template').style.display = "none";
			}
			
			});

		jQuery('#awp_joblists_templatetype').change(function() {

			if(document.getElementById('awp_joblists_templatetype').value == 'theme_template' )
			{  
				document.getElementById('awp_joblist_template').style.display = "none";
				document.getElementById('awp_joblists_theme_template').style.display = "block";
			}
			else {
				document.getElementById('awp_joblist_template').style.display = "block";
				document.getElementById('awp_joblists_theme_template').style.display = "none";
			}
			
			});
                      
            jQuery('#jlist_upload_image').click(function() {
			 formfield = jQuery('#upload_image').attr('name');
			 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
			 tbframe_interval = setInterval(function() {
		            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image URL');
		            }, 2000);
			 return false;
			});
		if($("#jlist_upload_image").size() > 0 ) {
		window.send_to_editor = function(html) {
			 imgurl = jQuery('img',html).attr('src');		 
			 jQuery('#awp_joblist_submit_value').val(imgurl);
			 tb_remove();
			}
		}
		
	    if(jQuery('input:radio[name=awp_joblist_submit_type]:checked').val()=='submit')
	    {		   
	            jQuery('#awp_joblist_submit_val').text('Button Text');
	            jQuery("#jlist_upload_img_button").hide();
	    } else {
	          jQuery('#awp_joblist_submit_val').text('Button Image URL');
	          jQuery("#jlist_upload_img_button").show();
	    }
       
	   jQuery('input:radio[name=awp_joblist_submit_type]').change(function() {
		   jQuery('#awp_joblist_submit_value').val('');
	        if(jQuery('input:radio[name=awp_joblist_submit_type]:checked').val()=='submit')
	            {	        	 
	            jQuery('#awp_joblist_submit_val').text('Button Text');
	            jQuery("#jlist_upload_img_button").hide();
	            }
	        else
	        {		      
	          jQuery('#awp_joblist_submit_val').text('Button Image URL');
	          jQuery("#jlist_upload_img_button").show();
	        }
	    });
	   
		
 if($("#job_addcustom_field").size() > 0 ){
	var jcounter = document.getElementById("job_addcustom_field").getAttribute("rel");
	jQuery("#job_addcustom_field").click(function ()
	{   
		jQuery('#hrjobs_form_fields tr:last').after('<tr><td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;">Custom Field '+ jcounter + '</td><td align="center" style="border: 1px solid rgb(204, 204, 204);">' + 
				'<input type="checkbox" id="customfield'+ jcounter + '_show" name="customfield'+ jcounter + '_show" size="30" onclick="hrjobsform_enablefield(\'customfield'+jcounter+'\')">' +
				'<input type="hidden" id="customfield'+ jcounter + '_newest" name="customfield'+ jcounter + '_newest" value=""></td>' + 
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="checkbox" id="customfield'+ jcounter + '_require" name="customfield'+ jcounter + '_require" size="30" disabled="">' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="text" onkeypress="return isNumberKey(event)" id="customfield'+ jcounter + '_order" name="customfield'+ jcounter + '_order" value="" size="3" maxlength="2" disabled="">' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="text" id="customfield'+ jcounter + '_text" name="customfield5_text" value="Custom Field'+ jcounter + '" disabled="">' +
				'</td>' +				
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<select name="customfield'+ jcounter + '_type" id="customfield'+ jcounter + '_type" onchange="hrjobsform_showoptionstextarea(\'customfield'+jcounter+'\');" disabled="">' +
				'<option value="checkbox">Checkbox</option>' +
				'<option value="radio">Radio Option</option>' +
				'<option value="select">Select</option>' +
				'<option value="text">Textbox</option>' +
				'<option value="textarea">Textarea</option>' +
				'</select>' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<select name="customfield'+ jcounter + '_validation" id="customfield'+ jcounter + '_validation" disabled=""><option value="none">None</option>' +
				'<option value="email">Email ID</option>' +
				'<option value="number">Number</option>' +
				'</select>' +
				'</td>' +			
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<textarea style="display: block; width: 190px;" id="customfield'+ jcounter + '_options" name="customfield'+ jcounter + '_options" ></textarea>' +
				'</td>' +
				'</tr>');
		jcounter++;
	});
 }
});

function validatecreatejobs()
{   
	 jQuery('#message').remove();
	 var editor = tinymce.get( 'content');
	 editor.save();	 
	 var job_title = jQuery('#jobs_title').val();
	 var jobtitle = jQuery.trim( job_title );	
	 var job_content = jQuery('#content').val();	
	 var jobcontent = jQuery.trim( job_content );
	 if(jobtitle == '')
	 {
		 jQuery('#jobs_title').css('border-color', '#f00'); 
	 }else {
		 jQuery('#jobs_title').css('border-color', '#CCCCCC');
	 }
	 if(jobcontent == '')
	 {  
		
		 jQuery('#content_ifr').css('border', '1px solid #f00');
	 }else {
		 jQuery('#content_ifr').css('border', 'none');
	 }
	 if(jobtitle != '' && jobcontent != '')
	 {    jQuery('#message').remove();
		  return true;
	 }	 
	 jQuery('.wrap h2').after('<div id="message" style="width:80%;" class="updated"><p style="color:#f00;font-weight:bold;" >Please fill the mandatory fields.<p></div>');
	 return false;
}
function validateupdatejobs()
{   
	 jQuery('#message').remove();
	 var editor = tinymce.get( 'editcontent');
	 editor.save();
	 var job_title = jQuery('#jobs_title').val();
	 var jobtitle = jQuery.trim( job_title );	
	 var job_content = jQuery('#editcontent').val();
	 var jobcontent = jQuery.trim( job_content );
	 if(jobtitle == '')
	 {
		 jQuery('#jobs_title').css('border-color', '#f00'); 
	 }else {
		 jQuery('#jobs_title').css('border-color', '#CCCCCC');
	 }
	 if(jobcontent == '')
	 {  
		
		 jQuery('#editcontent_ifr').css('border', '1px solid #f00');
	 }else {
		 jQuery('#editcontent_ifr').css('border', 'none');
	 }
	 if(jobtitle != '' && jobcontent != '')
	 {    jQuery('#message').remove();
		  return true;
	 }	 
	 jQuery('.wrap h2').after('<div id="message" style="width:80%;" class="updated"><p style="color:#f00;font-weight:bold;">Please fill the mandatory fields.<p></div>');
	 return false;
}

function jsearch_form_enablefield(fieldid){
	var checked=document.getElementById(fieldid+'_show').checked;
	var cfield_index=fieldid.indexOf('customfield');
	if(checked){
		document.getElementById(fieldid+'_text').disabled=!checked;
		document.getElementById(fieldid+'_order').disabled=!checked;
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled=!checked;
            document.getElementById(fieldid+'_options').style.display="block";
			document.getElementById(fieldid+'_options').disabled=!checked;
		}
	}else{
		document.getElementById(fieldid+'_text').disabled="disabled";
		document.getElementById(fieldid+'_order').disabled="disabled";
		
		
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled="disabled";
			document.getElementById(fieldid+'_options').disabled="disabled";	
		}
	}
}
function jsearch_form_showoptionstextarea(fieldid){
	var type=document.getElementById(fieldid+'_type').value;
	if ((type=="select") || (type=="radio") || (type=="checkbox")){
		document.getElementById(fieldid+'_options').disabled=false;
		document.getElementById(fieldid+'_options').style.display ="block";
		document.getElementById(fieldid+'_validation').disabled="disabled";
	}else{
		document.getElementById(fieldid+'_validation').disabled="disabled";
		document.getElementById(fieldid+'_options').disabled="disabled";
		document.getElementById(fieldid+'_options').style.display ="none";
		if(type=="text")
		{
			document.getElementById(fieldid+'_validation').disabled=false;
		}
	}
}
function change_searchform_Template()
{
	if(document.getElementById('awp_jobsearchform_templatetype').value == 'theme_template' )
	{  
		document.getElementById('awp_jobsearchform_plugintemplatelayout').style.display = "none";
		document.getElementById('awp_jobsearchform_themetemplatelayout').style.display = "block";
	}
	else {
		document.getElementById('awp_jobsearchform_plugintemplatelayout').style.display = "block";
		document.getElementById('awp_jobsearchform_themetemplatelayout').style.display = "none";
	}
}
function japplicant_change_template()
{
	if(document.getElementById('awp_jobsform_templatetype').value == 'theme_template' )
	{  
		document.getElementById('awp_jobsform_plugintemplatelayout').style.display = "none";
		document.getElementById('awp_jobsform_themetemplatelayout').style.display = "block";
	}
	else {
		document.getElementById('awp_jobsform_plugintemplatelayout').style.display = "block";
		document.getElementById('awp_jobsform_themetemplatelayout').style.display = "none";
	}
}
function hrjobsform_enablefield(fieldid){
	var checked=document.getElementById(fieldid+'_show').checked;
	var cfield_index=fieldid.indexOf('customfield');
	if(checked){
		document.getElementById(fieldid+'_text').disabled=!checked;
		document.getElementById(fieldid+'_order').disabled=!checked;
		document.getElementById(fieldid+'_require').disabled=!checked;
		document.getElementById(fieldid+'_validation').disabled=!checked;
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled=!checked;
			if (document.getElementById(fieldid+'_type').value == 'checkbox' ) 
			{
			document.getElementById(fieldid+'_validation').disabled="disabled";
			}
            document.getElementById(fieldid+'_options').style.display="block";
			document.getElementById(fieldid+'_options').disabled=!checked;
		}
	}else{
		document.getElementById(fieldid+'_text').disabled="disabled";
		document.getElementById(fieldid+'_order').disabled="disabled";
		document.getElementById(fieldid+'_require').disabled="disabled";
		document.getElementById(fieldid+'_validation').disabled="disabled";
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled="disabled";
			document.getElementById(fieldid+'_options').disabled="disabled";	
		}
	}
}
function hrjobsform_showoptionstextarea(fieldid){
	var type=document.getElementById(fieldid+'_type').value;
	if ((type=="select") || (type=="radio") || (type=="checkbox")){
		document.getElementById(fieldid+'_options').disabled=false;
		document.getElementById(fieldid+'_options').style.display ="block";
		document.getElementById(fieldid+'_validation').disabled="disabled";
	}else{
		document.getElementById(fieldid+'_validation').disabled="disabled";
		document.getElementById(fieldid+'_options').disabled="disabled";
		document.getElementById(fieldid+'_options').style.display ="none";
		if(type=="text")
		{
			document.getElementById(fieldid+'_validation').disabled=false;
		}
	}
}

jQuery(document).ready(function($) {
	jQuery(".error").fadeOut(10000, "linear");      		
		 jQuery('select#ip_type').change(function() {
			 if(jQuery('select#ip_type').val() == 'Range') {
				 jQuery('#single_ip').hide();
				 jQuery('#range_ip').show();
				 jQuery('#ip_address1').val('');
				 jQuery('#ip_address2').val('');
	        	}else{
	        		jQuery('#ip_address').val('');
	        		jQuery('#range_ip').hide();
	        		jQuery('#single_ip').show();
	        	}
		 });
		 
	if(jQuery('select#ip_type').val() == 'Range') {
		 jQuery('#single_ip').hide();
		 jQuery('#range_ip').show();
   		
   	}else{
   		jQuery('#range_ip').hide();
   		jQuery('#single_ip').show();
   	}
});	
function delete_ipbanned(ipid)
{
  	var answer = confirm('Are you sure want to delete?');
	if (answer){ 
		 jQuery.ajax({
		     type: "POST",
		     url: "/wp-admin/admin-ajax.php",
		     data: 'action=delete_ipbannedaccount&ip_id='+ipid,
		     success: function(message) {
		     var msg = message;
		     msg = msg.split('::');
			 if(msg[0] == 'Success') {
	 			jQuery("#tr_"+msg[1]).remove();	
	 			var style='border: medium none; background: none repeat scroll 0% 0% transparent; color: red; font-weight: bold; font-size: 16px; display: block;';
	 			jQuery(".error,.del_error").hide();
	 			jQuery(".wrap").first().append('<div class="del_error" style="'+style+'"> Successfully Deleted </div>');
	 			jQuery(".del_error").fadeOut(8000);
	 					 				
		     }
		     else {
		    	jQuery("#node").html('<b style="color:red;">'+message+'</b><br />');         
		     }
		    
		     }
		 });	 		
	}	 	
 }
jQuery(document).ready(function($) {
	
	jQuery("#cases_shortcode").click(function(){
		this.select();
	});	
	 if($("#cases_addcustom_field").size() > 0 ){
	var counter = document.getElementById("cases_addcustom_field").getAttribute("rel");
	jQuery("#cases_addcustom_field").click(function ()
	{   
		jQuery('#cases_form_fields tr:last').after('<tr><td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;">Custom Field '+ counter + '</td><td align="center" style="border: 1px solid rgb(204, 204, 204);">' + 
				'<input type="checkbox" id="customfield'+ counter + '_show" name="customfield'+ counter + '_show" size="30" onclick="casesform_enablefield(\'customfield'+counter+'\')">' +
				'<input type="hidden" id="customfield'+ counter + '_newest" name="customfield'+ counter + '_newest" value=""></td>' + 
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="checkbox" id="customfield'+ counter + '_require" name="customfield'+ counter + '_require" size="30" disabled="">' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="text" onkeypress="return isNumberKey(event)" id="customfield'+ counter + '_order" name="customfield'+ counter + '_order" value="" size="3" maxlength="2" disabled="">' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<input type="text" id="customfield'+ counter + '_text" name="customfield5_text" value="Custom Field'+ counter + '" disabled="">' +
				'</td>' +				
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<select name="customfield'+ counter + '_type" id="customfield'+ counter + '_type" onchange="casesform_showoptionstextarea(\'customfield'+counter+'\');" disabled="">' +
				'<option value="checkbox">Checkbox</option>' +
				'<option value="radio">Radio Option</option>' +
				'<option value="select">Select</option>' +
				'<option value="text">Textbox</option>' +
				'<option value="textarea">Textarea</option>' +
				'</select>' +
				'</td>' +
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<select name="customfield'+ counter + '_validation" id="customfield'+ counter + '_validation" disabled=""><option value="none">None</option>' +
				'<option value="email">Email ID</option>' +
				'<option value="number">Number</option>' +
				'</select>' +
				'</td>' +			
				'<td align="center" style="border: 1px solid rgb(204, 204, 204);">' +
				'<textarea style="display: block; width: 190px;" id="customfield'+ counter + '_options" name="customfield'+ counter + '_options" ></textarea>' +
				'</td>' +
				'</tr>');
		counter++;
	});
	}
	
	jQuery('#cases_upload_image').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		tbframe_interval = setInterval(function() {
            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image URL');
            }, 2000);
		return false;
		});
	if($("#cases_upload_image").size() > 0 ) {
	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery('#awp_cases_submit_value').val(imgurl);
		 tb_remove();
		}
	}
		
	jQuery('input:radio[name=awp_cases_confirm_msg_page]').change(function() {
	      if(jQuery('input:radio[name=awp_cases_confirm_msg_page]:checked').val() == 'same')
	       	{
	       	jQuery('#awp_cases_confirmmsg_pageid').hide();
	       	jQuery('#awp_cases_confirmationmsg_tr').show();
	       	}	       	
	       else{ 
	      	jQuery('#awp_cases_confirmmsg_pageid').show();
	      	jQuery('#awp_cases_confirmationmsg_tr').hide();
	      	}
	    });
				
	    if(jQuery('input:radio[name=awp_cases_submit_type]:checked').val()=='submit')
	    {
	           jQuery('#awp_cases_submit_val').text('Button Text');
	           jQuery("#upload_img_button").hide();
	    }else {	   
	          jQuery('#awp_cases_submit_val').text('Button Image URL');
	          jQuery("#upload_img_button").show();
	   }	     
	 	                 
	   jQuery('input:radio[name=awp_cases_submit_type]').change(function() {
	   jQuery('#awp_cases_submit_value').val('');
	        if(jQuery('input:radio[name=awp_cases_submit_type]:checked').val()=='submit')
	        {
	            jQuery('#awp_cases_submit_val').text('Button Text');
	            jQuery("#upload_img_button").hide();
	        }
	        else {
	          jQuery('#awp_cases_submit_val').text('Button Image URL');
	          jQuery("#upload_img_button").show();
	        }
	    });
	   
	   if(jQuery('input:radio[name=awp_testimonialform_submit_type]:checked').val()=='submit')
	    {
	           jQuery('#awp_testimonialform_submit_value').text('Button Text');
	           jQuery("#testimonialform_upload_img_button").hide();
	    }else {	   
	          jQuery('#awp_testimonialform_submit_value').text('Button Image URL');
	          jQuery("#testimonialform_upload_img_button").show();
	   }	     
	 	                 
	   jQuery('input:radio[name=awp_testimonialform_submit_type]').change(function() {
	   jQuery('#awp_testimonialform_submit_val').val('');
	        if(jQuery('input:radio[name=awp_testimonialform_submit_type]:checked').val()=='submit')
	        {
	            jQuery('#awp_testimonialform_submit_value').text('Button Text');
	            jQuery("#testimonialform_upload_img_button").hide();
	        }
	        else {
	          jQuery('#awp_testimonialform_submit_value').text('Button Image URL');
	          jQuery("#testimonialform_upload_img_button").show();
	        }
	    });
	   
});
function cases_change_template()
{
	if(document.getElementById('awp_cases_templatetype').value == 'theme_template' )
	{  
		document.getElementById('awp_cases_plugintemplatelayout').style.display = "none";
		document.getElementById('awp_cases_themetemplatelayout').style.display = "block";
	}
	else {
		document.getElementById('awp_cases_plugintemplatelayout').style.display = "block";
		document.getElementById('awp_cases_themetemplatelayout').style.display = "none";
	}
}
function casesform_enablefield(fieldid){
	var checked=document.getElementById(fieldid+'_show').checked;
	var cfield_index=fieldid.indexOf('customfield');
	if(checked){
		document.getElementById(fieldid+'_text').disabled=!checked;
		document.getElementById(fieldid+'_order').disabled=!checked;
		document.getElementById(fieldid+'_require').disabled=!checked;
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled=!checked;
			if (document.getElementById(fieldid+'_type').value == 'checkbox' ) 
			{
			document.getElementById(fieldid+'_validation').disabled="disabled";
			}
            document.getElementById(fieldid+'_options').style.display="block";
			document.getElementById(fieldid+'_options').disabled=!checked;
		}
	}else{
		document.getElementById(fieldid+'_text').disabled="disabled";
		document.getElementById(fieldid+'_order').disabled="disabled";
		document.getElementById(fieldid+'_require').disabled="disabled";
		document.getElementById(fieldid+'_validation').disabled="disabled";
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled="disabled";
			document.getElementById(fieldid+'_options').disabled="disabled";	
		}
	}
	if(fieldid=="type" || fieldid=="priority")
		{
		jQuery("#type_require,#priority_require").attr("disabled", true).prop("checked", true);
		}
}

function casesform_showoptionstextarea(fieldid){
	var type=document.getElementById(fieldid+'_type').value;

	if ((type=="select") || (type=="radio") || (type=="checkbox")){
		document.getElementById(fieldid+'_options').disabled=false;
		document.getElementById(fieldid+'_options').style.display ="block";
		document.getElementById(fieldid+'_validation').disabled="disabled";
	}else{
		document.getElementById(fieldid+'_validation').disabled="disabled";
		document.getElementById(fieldid+'_options').disabled="disabled";
		document.getElementById(fieldid+'_options').style.display ="none";
		if(type=="text")
		{
			document.getElementById(fieldid+'_validation').disabled=false;
		}
	}
}
function genralform_enablefield(fld)
{
	var checked=document.getElementById(fld).checked;
	if(checked){
		document.getElementById('hostname_portno').disabled=!checked;
		document.getElementById('awp_memcache_test').disabled=!checked;
	}
	else {
		document.getElementById('hostname_portno').disabled="disabled";
		document.getElementById('awp_memcache_test').disabled="disabled";
	}
}
function proxy_enablefield(fld){
        var checked=document.getElementById(fld).checked;
	if(checked){
		document.getElementById('proxy_hostname_portno').disabled=!checked;
		document.getElementById('proxy_loginuser_pwd').disabled=!checked;
	}
	else {
		document.getElementById('proxy_hostname_portno').disabled="disabled";
		document.getElementById('proxy_loginuser_pwd').disabled="disabled";
	}
}
function cmp_sitekey()
{   
	var prev_apiKey = jQuery.trim( jQuery('#prev_api_key').val() );
	var current_apikey = jQuery.trim( jQuery('#api_key').val() );
	var accessKey = jQuery.trim( jQuery('#access_key').val() );

	if( current_apikey == '' || accessKey == '') //To chk site key is empty
	{
		if(current_apikey == '' && accessKey == '') {
		jQuery('#api_key').css('border', '1px solid #f00');
		jQuery('#access_key').css('border','1px solid #f00');
		alert("API Key and Access Key can not be empty.");
		}else if(current_sitekey == '' && accessKey != '') {
		jQuery('#api_key').css('border', '1px solid #f00');
		jQuery('#access_key').css('border', '1px solid #dfdfdf');
		alert("API Key can not be empty.");
		}else if(current_sitekey != '' && accessKey == '') {
		jQuery('#api_key').css('border', '1px solid #dfdfdf');
		jQuery('#access_key').css('border', '1px solid #f00');
		alert("Access Key can not be empty.");
			}
		return false;
	}else if( prev_apiKey == '' ) //To chk Previous site key is empty
	{
		return true;
	}
	else if( current_apikey != prev_apiKey ) 
	{
		var answer = confirm('Are you sure  change the API Key?');
		if (answer){ 
			return true;
		}
		else{
			jQuery('#api_key').val(prev_apiKey);
			jQuery('#api_key').css('border', '1px solid #dfdfdf');
			jQuery('#access_key').css('border', '1px solid #dfdfdf');  
			return false;
		}
	}else   //To chk both site keys are equal or not.
	{
		jQuery('#update_site_inf').val('no');   //This Site Key already configured.  update_site_inf value is set 'no'
		return true; 
		}
	
 }
 function addTestimonials()
 {
     var templateName= jQuery('#awp_testimonialform_plugintemplatelayout option:selected').text();
     var templateFile= jQuery('#awp_testimonialform_plugintemplatelayout option:selected').val();

     if(templateFile=='popup-template.php' || templateFile=="toggle-template.php")
         {
     jQuery(".testimonials_button").show();
     jQuery(".testimonials_button_option").show();
         }
     else
         {
             jQuery(".testimonials_button").hide();
             jQuery(".testimonials_button_option").hide();
         }
 }
function uploadImage(imageUrl)
{
		 formfield = jQuery('#upload_image').attr('name');
                 tb_show('Upload Image', 'media-upload.php?type=image&amp;TB_iframe=true');
		 tbframe_interval = setInterval(function() {
	            jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use as Button Image');
	            }, 2000);
		 window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
          jQuery('#uploaded_img_val').val(imgurl);
		 tb_remove();
		}
}

function awp_captcha_change(){
    if(document.getElementById("awp_captcha_type").value=="recaptcha"){
 	   document.getElementById("recaptcha_table").style.display="block";
 	   document.getElementById("color_table").style.display="none";
    }else if(document.getElementById("awp_captcha_type").value=="simplecaptcha"){
 	   document.getElementById("recaptcha_table").style.display="none";
 	  document.getElementById("color_table").style.display="block";
    }
}