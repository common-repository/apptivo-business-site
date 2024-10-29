<?php 
/**
 * Apptivo Business Site CRM Configuration
 * @package Apptivo Business Site CRM
*/
require_once AWP_PLUGIN_BASEPATH . '/apptivo-businesssite-plugin.php';
define('AWP_DEFAULT_ITEM_SHOW',5);
define('AWP_DEFAULT_MORE_TEXT','More..');
define("AWP_SAVE_CONTACT",1);
//Disable Plugins
//define('AWP_CONTACTFORM_DISABLE',1);
//define('AWP_NEWSLETTER_DISABLE',1);
//define('AWP_TESTIMONIALS_DISABLE',1);
//define('AWP_JOBS_DISABLE',1);
//define('AWP_CASES_DISABLE',1);
/*
 User updateable define statements ends here..
 Changing define statements below will make plugin to not work properly.
 * */
// Site Url
define('SITE_URL', site_url());
//Plugin Version


//Plugin folders
define('AWP_LIB_DIR', AWP_PLUGIN_BASEPATH . '/lib');
define('AWP_ASSETS_DIR', AWP_PLUGIN_BASEPATH . '/assets');
if(!defined('AWP_INC_DIR')){ define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc'); }
define('AWP_PLUGINS_DIR', AWP_LIB_DIR . '/Plugin');
define('AWP_WIDGETS_DIR', AWP_LIB_DIR . '/widgets');

//plugin template folder
define('AWP_CONTACTFORM_TEMPLATEPATH',AWP_INC_DIR.'/contact-forms/templates');
define('AWP_CASES_TEMPLATEPATH',AWP_INC_DIR.'/cases/templates');
define('AWP_NEWSLETTER_TEMPLATEPATH',AWP_INC_DIR.'/newsletter/templates');
define('AWP_TESTIMONIALS_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates');
define('AWP_TESTIMONIALS_FORM_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates/frontend');
define('AWP_JOBSFORM_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobapplicant');
define('AWP_JOBSEARCHFORM_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobsearch');
define('AWP_JOBDESCRIPTION_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobdescription');
define('AWP_JOBLISTS_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/joblists');
define('AWP_APPTIVO_DIR', AWP_PLUGIN_BASEPATH.'apptivo/');
//Default Template
define('AWP_TESTIMONIALS_DEFAULT_TEMPLATE','default-testimonials.php');
define('AWP_NEWSLETTER_WIDGET_DEFAULT_TEMPLATE','widget-default-template-usphone.php');
//Apptivo API URL's
//Dont change this unless specified, changing to incorrect values will make plugins to not work properly.
//check curl installed or not
function _is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
		if (!function_exists('Apptivo_plugin_curl_notice_error')) {
	function Apptivo_plugin_curl_notice_error() {
		
		
		$class = 'notice notice-error';
		$message = __( 'We found that you have not enabled the CURL. To use IP Deny, Cases, Contact Forms, Newsletter, Testimonials, and Jobs options, please enable CURL. ', 'sample-text-domain' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	}
	add_action( 'admin_notices', 'Apptivo_plugin_curl_notice_error' );
	
        return false;
    }
}
if (_is_curl_installed()) {
$version = curl_version();
$versionCheck = version_compare($version['version'],"7.34.0");
if($versionCheck== -1){
define('APPTIVO_API_URL','https://api.apptivo.com/');
define('TLSV2_SUPPORT',false);
}
else
{
define('APPTIVO_API_URL','https://api2.apptivo.com/');
define('TLSV2_SUPPORT',true);
}
if(TLSV2_SUPPORT == false){
	if (!function_exists('Apptivo_plugin_notice_error')) {
	function Apptivo_plugin_notice_error() {
		$version = curl_version();
		$class = 'notice notice-error';
		$message = __( 'Apptivo Notification: We found that you are running older version of CURL (Older version: '.$version[version].')  which does not support TLS 1.2. Apptivo stops supporting API calls older than TLS 1.2. Read <a href="https://www.apptivo.com/blog/disabling-ssl-3-0-and-tls-1-1/">https://www.apptivo.com/blog/disabling-ssl-3-0-and-tls-1-1/</a> for more details', 'sample-text-domain' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	}
	add_action( 'admin_notices', 'Apptivo_plugin_notice_error' );
}
define('APPTIVO_BUSINESS_SERVICES', APPTIVO_API_URL.'app/services/v1/BusinessSiteServices?wsdl');
//define('APPTIVO_BUSINESS_INDEX', APPTIVO_API_URL.'ts/services/AppJobWebService?wsdl');
//Apptivo V4 API's
define('APPTIVO_LEAD_SOURCE_API',APPTIVO_API_URL.'app/dao/lead');
define('APPTIVO_LEAD_API', APPTIVO_API_URL.'app/dao/leads');
define('APPTIVO_CASES_API', APPTIVO_API_URL. 'app/dao/case');
//define('APPTIVO_CUSTOMER_API', APPTIVO_API_URL. 'app/dao/customers');
define('APPTIVO_CONTACTS_API', APPTIVO_API_URL. 'app/dao/contacts');
define('APPTIVO_NOTES_API', APPTIVO_API_URL.'app/dao/note');
define('APPTIVO_TESTIMONIALS_STATUS_API', APPTIVO_API_URL. 'app/dao/testimonial');
define('APPTIVO_TARGETS_API',APPTIVO_API_URL.'app/dao/targets');
define('APPTIVO_SIGNUP_API',APPTIVO_API_URL.'app/dao/signup');
define("APPTIVO_CANDIDATE_API",APPTIVO_API_URL."app/dao/candidates");
define("APPTIVO_COMMON_API",APPTIVO_API_URL."app/commonservlet");
define("APPTIVO_DOCUMENT_API",APPTIVO_API_URL."app/dao/document");


//Apptivo V6 API's
define('APPTIVO_CASE_V6_API', APPTIVO_API_URL.'app/dao/v6/cases');
define('APPTIVO_LEAD_V6_API', APPTIVO_API_URL.'app/dao/v6/leads');
define('APPTIVO_CUSTOMER_V6_API', APPTIVO_API_URL.'app/dao/v6/customers');
define('APPTIVO_CONTACT_V6_API', APPTIVO_API_URL.'app/dao/v6/contacts');
define('APPTIVO_NOTES_V6_API', APPTIVO_API_URL.'app/dao/note');
define('APPTIVO_EMPLOYEES_V6_API', APPTIVO_API_URL.'app/dao/v6/employees');
define('APPTIVO_TARGETS_V6_API',APPTIVO_API_URL.'app/dao/v6/targets');
define('APPTIVO_TESTIMONIALS_V6_API',APPTIVO_API_URL.'app/dao/v6/testimonials');
define("APPTIVO_CANDIDATE_V6_API",APPTIVO_API_URL."app/dao/v6/candidates");
define("APPTIVO_RECRUITMENT_V6_API",APPTIVO_API_URL."app/dao/v6/recruitment");
define("APPTIVO_APPSETTINGS_V5_API",APPTIVO_API_URL."app/dao/v5/appsettings");



define('APPTIVO_LEAD_OBJECT_ID','4');
define('APPTIVO_CASES_OBJECT_ID','59');
define('APPTIVO_CUSTOMER_OBJECT_ID','3');
define('APPTIVO_CONTACT_OBJECT_ID','2');
define('APPTIVO_EMPLOYEE_OBJECT_ID','8');
define('APPTIVO_TEAM_OBJECT_ID','91');
define('APPTIVO_JOBS_OBJECT_ID','135');
define('APPTIVO_NOTE_OBJECT_ID','19');
define('APPTIVO_TARGET_OBJECT_ID','56');
define('APPTIVO_RECRUITMENT_OBJECT_ID','134');
define('AWP_SERVICE_ERROR_MESSAGE', '\nThere is an issue with your API/Access keys. Please double check them within the general settings page, if you don’t see any issue contact us at support@apptivo.com.');




//client
$apptivo_api_key = get_option('apptivo_apikey');
$apptivo_accesskey = get_option('apptivo_accesskey');
define ( 'APPTIVO_API_KEY', trim($apptivo_api_key));
define ( 'APPTIVO_ACCESS_KEY', trim($apptivo_accesskey));
	

//define ( 'APPTIVO_LEAD_API', APPTIVO_API_URL . 'app/dao/v6/leads' );
define ( 'APPTIVO_CUSTOMER_API', APPTIVO_API_URL . 'app/dao/v6/customers' );
define ( 'APPTIVO_CONTACT_API', APPTIVO_API_URL . 'app/dao/v6/contacts' );
define ( 'APPTIVO_OPPORTUNITY_API', APPTIVO_API_URL . 'app/dao/v6/opportunities' );
define ( 'APPTIVO_PROJECT_API', APPTIVO_API_URL . 'app/dao/v6/projects' );
define ( 'APPTIVO_EMPLOYEE_API', APPTIVO_API_URL . 'app/dao/v6/employees' );

define ( 'OPPORTUNITY_OBJECT_ID', 11 );
define ( 'PROJECT_OBJECT_ID', 88 );
define('LEAD_OBJECT_ID',4);
define('EMP_OBJECT_ID',8);
}
/*
 * To alert the user running curl disabled
 */
else
{
	error_log('next');

}




