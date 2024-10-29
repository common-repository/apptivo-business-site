<?php
/**
 * Error messages and Guide Link
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */    
function awp_messagelist($key=''){   
	$awp_errormessage_list = array(
        "contactform-display-page" 				=> '<span class="error_message">Contact form is not configured.</span>',
	    "casesform-display-page" 				=> '<span class="error_message">Cases form is not configured.</span>',
	    "casesform-e100" 				        => 'Your request was not sent.Please try again after 10 mins.',
	    "caseform-captcha_error"                =>'Please enter correct Verification code',
		"newsletter-display-page" 				=> '<span class="error_message">Newsletter form is not configured.</span>',
        "testimonialsconfigure-display-page"  	=> '<span class="error_message">Testimonials is not configured.</span>',
		"testimonials-display-page" 			=> '<span class="error_message">Testimonials are not found.</span>',
		"testimonials-form-page" 			    => '<span class="error_message">Testimonial form is not configured.</span>',
        "jobapplicant-form-display-page"		=> '<span class="error_message">Job applicant form is not configured.</span>',
        "joblists-noresults-display-page"    	=> '<span class="error_message">No jobs are found</span>',
        "joblists-display-page"             	=> '<span class="error_message">Job List page is not configured.</span>',
        "jobdescription-display-page" 			=> '<span class="error_message">Selected jobs are not found.</span>',
        "jobsearch-form-display-page"    		=> '<span class="error_message">Job Search form is not configured.</span>',
		"contactlead-display-page"     			=> '<span class="error_message">Your message was not sent. Please try again after 10 mins</span>',
		"newslettertarget-display-page"     	=> '<span class="error_message">Your Subscription was not sent. Please try again after 10 mins</span>',
	    "newsletter-target-error"               => '<span class="error_message">Your Subscription was not Submitted. Please try again after 10 mins.</span>',
		"validate-searchJobsBySearchText"       => '<span class="error_message">Search Results are not found.Please try again after 10 mins.</span>',
		"validate-getAllJobsWithStatus"         => '<span class="error_message">Jobs are not found.Please try again after 10 mins.</span>',
		"jobapplicant-display-page"             => '<span class="error_message">Your request was not sent.Please try again after 10 mins.</span>',
		"validate-getJobsByNo"                  => '<span class="error_message">Selected jobs are not found.Please try again after 10 mins.</span>',
		"jobsearch-noresult"                    => '<span class="error_message">No jobs are found. Please modify your search and try again</span>',
	    "IP_banned"                             => '<span class="error_message">Your IP Banned.</span>',
		"recaptcha_error"                       => '<span class="error_message" style="color:red;">Please enter correct Verification code.</span>',
		"v2recaptcha_error"                     => '<span class="error_message" style="color:red;">Please select the recaptcha</span>',
		"v3recaptcha_error"                     => '<span class="error_message" style="color:red;">Please select the recaptcha</span>',
		"soap_error" 	                        => '<div class="updated" id="errormessage"><p style="color:#f00;font-weight:bold;text-align:center;"> SOAP extension required to run Apptivo Business Site CRM  plugin. </p></div>',
	    "no_redirection"                        => '<span class="absp_error">Please enter the valid details.</span>',
		"fatal_error"							=> '<div class="awp_updated" id="errormessage"><p style="color:#f00;font-weight:bold;text-align:center;"> Apptivo Business Site CRM  plugin- Requires php version 5.3.5 - 5.5 to run.</p></div>'
    );
	
	//return $awp_errormessage_list[$key];
	return (string) $awp_errormessage_list[$key];	
}

function awp_developerguide($key=''){
	$developer_guide = array(
		"api-key"                           => 'https://answers.apptivo.com/questions/10413/where-can-i-find-my-api-key-and-access-key',
		"purchase-sitekey"                  => 'https://runapptivo.apptivo.com/purchasing-an-apptivo-wordpress-site-key-8272.html',
		"contactform" 				 		=> 'https://runapptivo.apptivo.com/contact-forms-overview-7942.html',
		"contactform-shortcode" 	 		=> 'https://runapptivo.apptivo.com/contact-forms-front-end-integration-7956.html',
		"contactform-template" 		 		=> 'https://runapptivo.apptivo.com/contact-forms-advanced-configuration-7959.html',
		"contactform-customcss"  	 		=> 'https://runapptivo.apptivo.com/contact-forms-advanced-configuration-7959.html',
		"contactform-basicconfig"   		=> 'https://runapptivo.apptivo.com/contact-forms-basic-setup-7945.html',
		
		"newsletter" 				 		=> 'https://runapptivo.apptivo.com/newsletter-overview-7989.html',
		"newsletter-shortcode"       		=> 'https://runapptivo.apptivo.com/newsletter-front-end-integration-7993.html',
		"newsletter-template"       		=> 'https://runapptivo.apptivo.com/newsletter-advanced-configuration-7997.html',
		"newsletter-customcss"       		=> 'https://runapptivo.apptivo.com/newsletter-advanced-configuration-7997.html',
		"newsletter-basicconfig"     		=> 'https://runapptivo.apptivo.com/newsletter-basic-setup-7991.html',
		
		"testimonilas"               		=> 'https://runapptivo.apptivo.com/testimonials-overview-8009.html',
		"testimonilas-fullview-shortcode"   => 'https://runapptivo.apptivo.com/testimonials-front-end-integration-8013.html',
		"testimonilas-fullview-customcss"   => 'https://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
		"testimonilas-fullview-template"   	=> 'https://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
		"testimonilas-inline-shortcode"   	=> 'https://runapptivo.apptivo.com/testimonials-front-end-integration-8013.html',
		"testimonilas-inline-customcss"   	=> 'https://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
		"testimonilas-inline-template"   	=> 'https://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
		"testimonials-basic-config"         => 'https://runapptivo.apptivo.com/testimonials-basic-setup-8011.html',
		
		"jobs"   							=> 'https://runapptivo.apptivo.com/jobs-overview-8041.html',
		"job-applicant-shortcode"           => 'https://runapptivo.apptivo.com/jobs-front-end-integration-8045.html',
		"job-applicant-template"            => 'https://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
		"job-applicant-customcss"           => 'https://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
		"job-applicant-basicconfig"         => 'https://runapptivo.apptivo.com/jobs-basic-setup-8043.html',
		"job-searchform-shortcode"          => 'https://runapptivo.apptivo.com/jobs-front-end-integration-8045.html',
		"job-searchform-template"           => 'https://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
		"job-searchform-customcss"          => 'https://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
		"job-searchform-basicconfig"        => 'https://runapptivo.apptivo.com/jobs-basic-setup-8043.html'
	);
	//return $developer_guide[$key];
	return (string) $developer_guide[$key];
}


function awp_flow_diagram($key='',$imgtag=false){
	$flow_diagrams = array(
		"contactform" 	=> AWP_PLUGIN_BASEURL."/assets/images/contact.png",
		"newsletter" 	=> AWP_PLUGIN_BASEURL."/assets/images/newsletter.png",
		"testimonials"  => AWP_PLUGIN_BASEURL."/assets/images/testimonials.png",
		"jobs"   		=> AWP_PLUGIN_BASEURL."/assets/images/jobs.png",
		"cases"   		=> AWP_PLUGIN_BASEURL."/assets/images/cases.png"
	);
	if($imgtag):
		return '<img src="'.$flow_diagrams[$key].'" alt="" />'; 
	endif;	
	//return $flow_diagrams[$key];
	return (string) $flow_diagrams[$key];
}

function awp_image($key='',$imgtag=false){
	$images = array(
		"submit_button"       => AWP_PLUGIN_BASEURL."/assets/images/submit.jpeg",
		"testimonials_icon"   => AWP_PLUGIN_BASEURL."/assets/images/testimonials_icon.gif",
		"jobs_icon"           => AWP_PLUGIN_BASEURL."/assets/images/jobs_icon.jpeg",
		"edit_icon"           => AWP_PLUGIN_BASEURL."/assets/images/edit.jpeg",
		"delete_icon"         => AWP_PLUGIN_BASEURL."/assets/images/del.jpeg",
		"approve_icon"        => AWP_PLUGIN_BASEURL."/assets/images/approve.png",
		"reject_icon"         => AWP_PLUGIN_BASEURL."/assets/images/reject.gif",
		"apptivo_icon"        => AWP_PLUGIN_BASEURL."/assets/images/apptivo.png",
		"success"             => AWP_PLUGIN_BASEURL."/assets/images/success.gif",
		"success-off"         => AWP_PLUGIN_BASEURL."/assets/images/success-off.gif",
		"close-popup"         => AWP_PLUGIN_BASEURL."/assets/images/close_pop.png"
	);
	if($imgtag):
		return '<img src="'.$images[$key].'" alt="" />'; 
	endif;
	//return $images[$key];
	return (string) $images[$key];	 
}
