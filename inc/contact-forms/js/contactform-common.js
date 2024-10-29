function isNumberKey(evt)
{
	$shortcode
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;

   return true;
}
function changeTemplate()
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

function contactform_enablefield(fieldid){
	var checked=document.getElementById(fieldid+'_show').checked;
	var cfield_index=fieldid.indexOf('customfield');
	if(checked){
		document.getElementById(fieldid+'_text').disabled=!checked;
		document.getElementById(fieldid+'_order').disabled=!checked;
		document.getElementById(fieldid+'_require').disabled=!checked;
		document.getElementById(fieldid+'_validation').disabled=!checked;
		if(cfield_index==0){
			document.getElementById(fieldid+'_type').disabled=!checked;
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

function contactfors_showoptionstextarea(fieldid){
	var y=document.getElementById(fieldid+'_type').value
	alert(y);
}