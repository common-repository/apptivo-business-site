<?php
/**
 * Apptivo Jobs Plugin( Job Lists, Job Description, Job Search and Job Applicant )
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_INC_DIR . '/config.php';
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/jobApplicantDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/appParam.php';
require_once AWP_INC_DIR . '/apptivo_services/jobDetails.php';
/**
 * Class awp_jobsForms
 */

class AWP_Jobs extends AWP_Base
{
	var $_plugin_activated = false;
	/**
	 * PHP5 constructor
	 */
	function __construct()
	{
		$settings=array();
		$this->_plugin_activated=false;
		$settings=get_option("awp_plugins");
		if(get_option("awp_plugins")!=="false"){
			if($settings["jobs"])
			$this->_plugin_activated=true;
		}
	}

	/**
	 * Returns plugin instance
	 *
	 * @return awp_jobsForms
	 */
	function &instance()
	{
		static $instances = array();

		if (!isset($instances[0])) {
			$class = __CLASS__;
			$instances[0] =  new $class();
		}

		return $instances[0];
	}
	/**
	 * Runs plugin
	 */
	function run()
	{
		if($this->_plugin_activated){
			add_action( 'widgets_init',array(&$this, 'register_widget' )); //initialize widget
			add_action('the_posts',array(&$this,'check_for_shortcode'));
			add_shortcode('apptivo_job_applicantform',array(&$this,'jobapplicantform')); //Job applicant Form.
			add_shortcode('apptivo_jobs',array(&$this,'listofjobs')); //List Of Jobs.
			add_shortcode('apptivo_job_searchform',array(&$this,'jobsearchform'));//Job Search Form.
			add_shortcode('apptivo_job_description',array(&$this,'jobdescription')); //Job description.
		}
	}
	/**
	 * Registering widget
	 *
	 */
	function register_widget()
	{
		register_widget( 'JobSearch_Widget' ); // Job Search Form Widget.
		register_widget( 'JobList_Widget' ); // Job Lists Widget.
	}

	/**
	 * Jobs Form shortcode handler
	 */
	function jobapplicantform($atts){
		ob_start();
		$jobidwith_Number = FALSE;
		extract(shortcode_atts(array('name'=>  ''), $atts));
		$formname=trim($name);
		$content="";
		$successmsg="";
		$hrjobsform=$this->get_jobapplicantform_fields($formname);		
		$status = array('0' => 'Approved', '1' => 'New');
        $allJobs = getAlljobs(999,0,'false',$status);
		//$allJobs = getAllHrjobs(999,0,'false',$status)->jobDetails;
		
		$allJobs = awp_convertObjToArray($allJobs);
		$_POST['jobId'] =(array_key_exists('jobId',$_POST)) ? $_POST['jobId'] : null;
		$_POST['jobNo'] =(array_key_exists('jobNo',$_POST)) ? $_POST['jobNo'] : null;
		$jobId = sanitize_text_field($_POST['jobId']);
		$jobNo = sanitize_text_field($_POST['jobNo']);
		if(!empty($hrjobsform['fields'])) {
			foreach($hrjobsform['fields'] as $field){
				if($field['fieldid']=="country"){
					$countrylist = $this->getAllCountryList();
					break;
				}
			}
		}
		$_POST['awp_jobsformname'] = (!empty($_POST['awp_jobsformname'])) ?  $_POST['awp_jobsformname'] : '';
		$submitformname = sanitize_text_field($_POST['awp_jobsformname']);
		if(isset($_POST['awp_jobsformname']) && !empty($_POST['awp_jobsformname']) && $submitformname==$formname)
		{
			if(trim($jobId) == '' && isset($_POST['jobidwithnumber']))
			{
				$jobid_Number = sanitize_text_field($_POST['jobidwithnumber']);
				$jobid_No = explode('::',$jobid_Number);
				$jobId = $jobid_No[0] ?? '';
				$jobNo = $jobid_No[1] ?? '';
				$jobidwith_Number = TRUE;
			}
			$successmsg=$this->save_applicantjobs($submitformname,$jobId,$jobNo);
		}
		
		if( strlen(trim($successmsg)) != 0 && $hrjobsform['confmsg_pagemode'] == 'other' ){
			$location = get_permalink($hrjobsform['confmsg_pageid']);
			wp_safe_redirect($location);
		}
		
		if( $jobidwith_Number ){
			$jobId = '';
		}

		if(!empty($hrjobsform)){
			include $hrjobsform['templatefile'];
		}else{
			echo awp_messagelist('jobapplicant-form-display-page');
		}
		$content = ob_get_clean();
		return $content;
	}


	function jobsearchform($atts){

		ob_start();
		$jobsearchForm_Submit = FALSE;
		extract(shortcode_atts(array('name'=>  '','resulttype' => ''), $atts));
		$result_type = trim($resulttype);
		$formname=trim($name);
		$jobsSettings = get_option('awp_jobs_settings');
		$target_pageid = $jobsSettings['description_page'];
		$content="";
		$successmsg="";
		$jobsearchform=$this->get_jobsearch_field($formname);
		$_POST['awp_job_seachformname'] = (array_key_exists('awp_job_seachformname',$_POST)) ? $_POST['awp_job_seachformname'] : null;
		$submitformname= sanitize_text_field($_POST['awp_job_seachformname']);
		if(isset($_POST['awp_job_seachformname']) && !empty($_POST['awp_job_seachformname']) && $submitformname==$formname){   // Shortcode Form Submit.
			$keywords = sanitize_text_field($_POST['keywords']);
			if( $keywords == 'Keyword' ){ $keywords = ''; }
			$industry = isset($_POST['industry_select']) ? sanitize_text_field($_POST['industry_select']) : '';
			$_POST['jobtype'] = (!empty($_POST['jobtype'])) ? $_POST['jobtype'] : '';
			/* Santize for Array Values */
			if( isset($_POST['jobtype']) && $_POST['jobtype'] !== '' ){
				$jobTypeValues = $_POST['jobtype'];
				$keys = [];
				$JobTypearray = [];

				// Insert the value into the array
				$JobTypearray[] = $jobTypeValues;
			}else{
				$keys = [];
				$jobTypeValue = [];
				$JobTypearray = [];
			}
			/* Santize for Array Values */

			$jobtype = $JobTypearray;
			if(!is_array($jobtype)){
				$jobtype =array($jobtype);
			}
			 
			/*$response = serchByJobs($keywords, $industry, $jobtype);
			$jobDetails_Response =  $response->return->jobDetails;
			$JobSearchResults = awp_convertObjToArray($jobDetails_Response);
			*/
			
			/*if(is_empty($jobtype)){
				$jobtype = "";
			}*/
			if($industry == '0'){
				$industry = "";
			}
			$response = searchJob($keywords, $industry, $jobtype);
			$jobDetails_Response =  $response->data;
			$JobSearchResults = awp_convertObjToArray($jobDetails_Response);
			$jobsearchForm_Submit = TRUE;

		}
		 $_POST['job_seachformname_widget'] =(array_key_exists('job_seachformname_widget',$_POST)) ? $_POST['job_seachformname_widget'] : null;
		$widgetFormname= sanitize_text_field($_POST['job_seachformname_widget']);
		if(!empty($_POST['job_seachformname_widget'])){          // Widget Form Submit.
			$keywords = sanitize_text_field($_POST['keywords']);
			if( $keywords == 'Keyword' ) { $keywords = ''; }
			$industry = sanitize_text_field($_POST['industry']);

		/* Santize for Array Values */
		$widgetformkeys = array_keys($_POST['jobtype']);
		$widgetformkeys = array_map('sanitize_key', $widgetformkeys);
		$widgetvalues = array_values($_POST['jobtype']);
		$widgetvalues = array_map('sanitize_text_field', $widgetvalues);
		$widgetformJobArray = array_combine($widgetformkeys, $widgetvalues);
		/* Santize for Array Values */

			$jobtype = $widgetformJobArray;
			$maxcount = sanitize_text_field($_POST['maxcnt']);
			if(!is_array($jobtype))
			{
				$jobtype =array($jobtype);
			}
			 
			$response = serchByJobs($keywords, $industry, $jobtype);
			$jobDetails_Response =  $response->return->jobDetails;
			$JobSearchResults = awp_convertObjToArray($jobDetails_Response);
			$jobsearchForm_Submit = TRUE;

		}
		//To check custiom field (except fieldid=keywords) option is empty or not.
		//To avoid Empty page.
		$jobsearch_field = $jobsearchform['fields'];
		$job_searchform_display = true;
		if(count($jobsearch_field) == 1)
		{
			if( $jobsearch_field[0]['fieldid'] != 'keywords' )
			{
				if( empty($jobsearch_field[0]['options'] ))
				{
					$job_searchform_display = false;
				}
			}
		}
		if($jobsearchForm_Submit && $response->status == 'SUCCESS' && $response->countOfRecords == 0 )
		{
			echo awp_messagelist('jobsearch-noresult').'<br />'; //Display error Message.
			$jobsearchForm_Submit = FALSE;
			 
		}else if(isset($response->status) && $jobsearchForm_Submit && $response->status != 'SUCCESS')
		{
			echo awp_messagelist('validate-searchJobsBySearchText'); //Display Apptivo Validation Error.(E.g Invalide SiteKey and others..)
			$jobsearchForm_Submit = FALSE;
		}
		if(!empty($jobsearchform) && $job_searchform_display)
		{
			include $jobsearchform['templatefile'];
			 
		}else {
			echo awp_messagelist('jobsearch-form-display-page'); //Display error Message (E.g forms are not available..).
		}
		$content = ob_get_clean();
		return $content;
	}

	function jobdescription($atts)
	{
		ob_start();
		extract(shortcode_atts(array('applicantpage'=>  ''), $atts));
		$jobNo= sanitize_text_field($_GET['vacancyno']) ?? ''; //Request Job vacancy number
		//$jobDetail = jobdescriptionByNumber($jobNo); //Get job details of selected job number
		$jobDetail = jobdescriptionByNumberV6($jobNo); //Get job details of selected job number
		if($jobDetail->status == 'SUCCESS'){
					$jobDescription = $jobDetail->data[0]->description;
			
		}else{
			$jobDescription = "";
		}
		
		
		$jobs_settings = get_option('awp_jobs_settings');
		$applicantFormList = get_option('awp_jobsforms');
		$applicantformName = $jobs_settings['applicant_form'];
		$templateName = $jobs_settings['desc_template_name'];
		$template_type = $jobs_settings['jobdescription_template_type'];
		//added template files
		if($template_type == 'theme_template') :
		$template_File = TEMPLATEPATH."/jobs/jobdescription/".$templateName; //Job Description theme template
		else:
		$template_File = AWP_JOBDESCRIPTION_TEMPLATEPATH."/".$templateName; //Job Description plugin template.
		endif;
			

		if(!empty($applicantFormList))
		{
			foreach($applicantFormList as $applicantform)
			{
				if($applicantformName ==  $applicantform['name'])
				{
					$applicantpageUrl = $applicantform['properties']['jobapplicant_page'];
				}
			}
		}

		if($jobs_settings['submit_type'] == 'image')
		{
			$value = '';
			if(strlen(trim($jobs_settings['submit_val'])) != 0)
			{
				$imageSrc = 'src="'.$jobs_settings['submit_val'].'"';
			} else {
				$imageSrc = 'src="'.awp_image('submit_button').'"';
			}
		} else {
			$imageSrc = '';
			if(strlen(trim($jobs_settings['submit_val'])) != 0)
			{
				$value = 'value="'.$jobs_settings['submit_val'].'"';
			} else {
				$value = 'value="Submit"';
			}
		}
			
		if(!empty($templateName) && strlen(trim($jobDescription)) != 0 ){
			include $template_File;
		}else if(empty($templateName)) {
			echo awp_messagelist('jobdescription-display-page'); //Display error Message.
		}else { echo awp_messagelist('validate-getJobsByNo');  }

		$content = ob_get_clean();
		return $content;

		?>

		<?php
	}
	/**
	 * Displaying List of Jobs.
	 */
	function listofjobs()
	{
		ob_start();
		$jobs_settings = get_option('awp_jobs_settings');
		// $jobs_settings['itemsperpage'] = (array_key_exists('itemsperpage',$jobs_settings)) ? $jobs_settings['itemsperpage'] : '';
		// $jobs_settings['readmoretext'] = (array_key_exists('readmoretext',$jobs_settings)) ? $jobs_settings['readmoretext'] : '';
		//$maxCount = sanitize_text_field($jobs_settings['itemsperpage']) ?? '';
		//$readMoreText = sanitize_text_field($jobs_settings['readmoretext']) ?? '';
		$maxCount = (isset($jobs_settings['itemsperpage'])) ? sanitize_text_field($jobs_settings['itemsperpage']) : '';
		$readMoreText = (isset($jobs_settings['readmoretext'])) ? sanitize_text_field($jobs_settings['readmoretext']) : '';
		$target_pageid = sanitize_text_field($jobs_settings['description_page']) ?? '';
		$templateName = sanitize_text_field($jobs_settings['list_template_name']) ?? '';
		$template_type = sanitize_text_field($jobs_settings['joblist_template_type']) ?? '';

		//added template files
		if($template_type == 'theme_template') :
		$template_File = TEMPLATEPATH."/jobs/joblists/".$templateName; //Job Lists theme template
		else:
		$template_File = AWP_JOBLISTS_TEMPLATEPATH."/".$templateName; //Job Lists plugin template.
		endif;

		$status = array('0' => 'Approved', '1' => 'New');
		//$getalljobs_response = getAllHrjobs(999,0,'false',$status);
		$getalljobs_response = getAlljobs(999,0,'false',$status);
		
		//$allJobs = $getalljobs_response->jobDetails;
		$allJobs = $getalljobs_response->data;
		$allJobs = awp_convertObjToArray($allJobs);

		
		/*if( $getalljobs_response->statusCode != '1000')
		{error_log('temp if');
		}else */
		if(!empty($templateName) && $getalljobs_response->countOfRecords != 0 )
		{	
			include $template_File;
		}else if(empty($templateName)){
			echo awp_messagelist('joblists-display-page');
		}else {
			echo awp_messagelist('joblists-noresults-display-page');
		}
		$content = ob_get_clean();
		return $content;

	}


	/**
	 * Save Jobs from submitted
	 */
	function save_applicantjobs($formname,$jobId,$jobNo){
		 
		$hrjobsform=$this->get_jobapplicantform_fields($formname);
		if(!empty($hrjobsform)){
			$hrjobsformfields=$hrjobsform['fields'];
			//Process the $_POST here..
			$submittedformvalues=array();
			$submittedformvalues['name']=$hrjobsform['name'];
			$customfields="";
			foreach($hrjobsformfields as $field)
			{ 
				if(!empty($field)){
				$fieldid=$field['fieldid'];
				
				if($fieldid=="company"){
					$companyName=stripslashes(trim(sanitize_text_field($_POST['company'])));
					if($companyName !=''){
						$customfields.="<br/><b>".$field['showtext']."</b>:".$companyName;
					}
				}
				$pos=strpos($fieldid, "customfield");
				if($pos===false){
					if($fieldid=='telephonenumber'){
						$_POST['telephonenumber1'] = (array_key_exists('telephonenumber1',$_POST)) ? sanitize_text_field($_POST['telephonenumber1']) : '';
						$telephone1=$_POST['telephonenumber1'];
						if(isset($telephone1)){
							$_POST['telephonenumber2'] = (array_key_exists('telephonenumber2',$_POST)) ? sanitize_text_field($_POST['telephonenumber2']) : '';
							$_POST['telephonenumber3'] = (array_key_exists('telephonenumber3',$_POST)) ? sanitize_text_field($_POST['telephonenumber3']) : '';
							$submittedformvalues[$fieldid]= $telephone1.sanitize_text_field($_POST['telephonenumber2']).sanitize_text_field($_POST['telephonenumber3']);
						}
						else{
							$submittedformvalues[$fieldid]= sanitize_text_field($_POST[$fieldid]);
						}
					}else if($fieldid=='upload')
					{
						$submittedformvalues[$fieldid]= sanitize_text_field($_FILES['file_upload']['name']);
						$documentId = sanitize_text_field(trim($_POST['awp_document_key']));
					}
					else{
						$submittedformvalues[$fieldid]= stripslashes(sanitize_text_field($_POST[$fieldid]));
					}

				}else  if($fieldid != 'industry'){
					if(!empty(trim($customfields)))
					{
						if(is_array($_POST[$fieldid]))
						{
							$CustomArr = sanitize_text_field($_POST[$fieldid]);

							$customfieldVal= "";
							for($i=0; $i<count($CustomArr); $i++)
							{
								$customfieldVal .= ($i==(count($CustomArr)-1))?$CustomArr[$i]:$CustomArr[$i].", ";
							}

						}else {
							$customfieldVal = sanitize_text_field($_POST[$fieldid]);
						}
						if($customfieldVal != '') :
						$customfields.="<br/><b>".$field['showtext']."</b>:&nbsp;".stripslashes($customfieldVal);
						endif;
					}
					else
					{
						if(is_array($_POST[$fieldid]))
						{
							$CustomArr = sanitize_text_field($_POST[$fieldid]);
							$customfieldVal= "";
							for($i=0; $i<count($CustomArr); $i++)
							{
								$customfieldVal .= ($i==(count($CustomArr)-1))?$CustomArr[$i]:$CustomArr[$i].", ";
							}
								
						}else {
							$customfieldVal = sanitize_text_field($_POST[$fieldid]);
						}
						if($customfieldVal != '') :	
						$customfields .= "<br/><b>".$field['showtext']."</b>:".stripslashes($customfieldVal);
						endif;
					}
				}else {
					$submittedformvalues['industry']= sanitize_text_field($_POST['industry']);
				}
			}
			}
				
			if(!empty(trim($customfields)))
			$submittedformvalues['notes']=$customfields;
			$firstName = sanitize_text_field($submittedformvalues['firstname']) ?? '';
			$lastName = sanitize_text_field($submittedformvalues['lastname']) ?? '';
			$emailId = sanitize_email($submittedformvalues['email']) ?? '';
			$jobTitle = isset($submittedformvalues['jobtitle']) ? sanitize_text_field($submittedformvalues['jobtitle']) : '';
			$company =  isset($submittedformvalues['company']) ? sanitize_text_field($submittedformvalues['company']) : '';
			$address1 = isset($submittedformvalues['address1']) ? sanitize_text_field($submittedformvalues['address1']) : '';
			$address2 = sanitize_text_field($submittedformvalues['address2']) ?? '';
			$city = sanitize_text_field($submittedformvalues['city']) ?? '';
			$submittedformvalues['state'] = (array_key_exists('state',$submittedformvalues)) ? $submittedformvalues['state'] : '';
			$provinceAndState = sanitize_text_field($submittedformvalues['state']) ?? '';
			$postalCode = sanitize_text_field($submittedformvalues['zipcode']) ?? '';
			$submittedformvalues['bestway'] = (array_key_exists('bestway',$submittedformvalues)) ? $submittedformvalues['bestway'] : '';
			$bestWayTohrjobs = sanitize_text_field($submittedformvalues['bestway']) ?? '';
			$country = sanitize_text_field($submittedformvalues['country']) ?? '';
			$countryIdCode = sanitize_text_field($_POST['country_id']) ?? '';
			$countryName = sanitize_text_field($_POST['country_name']) ?? '';
			$leadSource = sanitize_text_field($submittedformvalues['name']) ?? '';
			$phoneNumber = isset($submittedformvalues['telephonenumber']) ? sanitize_text_field($submittedformvalues['telephonenumber']) : '';
			$comments = isset($submittedformvalues['comments']) ? sanitize_text_field($submittedformvalues['comments']) : '';
			$coverletter = isset($submittedformvalues['jobtitle']) ? sanitize_text_field($submittedformvalues['coverletter']) : '';
			$skills = isset($submittedformvalues['Skills']) ? sanitize_text_field($submittedformvalues['Skills']) : '';
			$upload_docid = isset($submittedformvalues['upload']) ? sanitize_text_field($submittedformvalues['upload']) : '';
			$submittedformvalues['industryId'] = (array_key_exists('industryId',$submittedformvalues)) ? $submittedformvalues['industryId'] : '';
			$industry = sanitize_text_field($submittedformvalues['industry']) ?? '';
			$industryId = sanitize_text_field($submittedformvalues['industryId']) ?? '';
			$positionName = isset($submittedformvalues['positionName']) ? sanitize_text_field($_POST['positionName']) : '';
			
			$positionIdNumber = isset($submittedformvalues['jobidwithnumber']) ? sanitize_text_field($_POST['jobidwithnumber']) : '';
			$positionIdNumber = explode("::",$positionIdNumber);
			$positionId = $positionIdNumber[0] ?? '';
			//$positionIdNumber[1] = (array_key_exists(1,$positionIdNumber)) ? $positionIdNumber[1] : '';
 			$positionNumber = $positionIdNumber[1] ?? '';

			$_POST['awp_industry_id'] = (array_key_exists('awp_industry_id',$_POST)) ? sanitize_text_field($_POST['awp_industry_id']) : '';
			if($_POST['awp_industry_id']!=''){  $industryId=sanitize_text_field($_POST['awp_industry_id']); }
			$noteDetails = sanitize_textarea_field($submittedformvalues['notes']) ?? '';
            if(!empty($noteDetails)){
				$parent1NoteId = (!empty($parent1NoteId)) ? $parent1NoteId : '';
				$parent1details = nl2br($noteDetails);
				$awp_services_obj=new AWPAPIServices();
                $noteDetails = $awp_services_obj->notes('Custom Fields',$parent1details,$parent1NoteId);
			}
			if(!empty($emailId)){
				//$response = createJobApplicant($addressId, $address1, $address2, $applicantId, $applicantNumber, $city, $comments, $country, $countyAndDistrict, $emailId, $jobTitle, $expectedSalary, $firstName, $industryId, $jobApplicantId, $jobId,$jobNo, $lastName, $middleName,null, $phoneNumber, $postalCode, $provinceAndState, $coverletter, $resumeDetails, $resumeFileName, $resumeId, $skills,$upload_docid);
			     
				if($upload_docid != ""){
					$file_parts = explode('.', $upload_docid);
					$file_ext = strtolower(end($file_parts));
					$file_name = $_FILES ['file_upload'] ['name'];
					$file_size= $_FILES['file_upload']['size'];
					$file_tmp= $_FILES['file_upload']['tmp_name'];
					//$data = file_get_contents($file_tmp);
					//$base64 = base64_encode($data);
					//$add_document=uploadDocument($file_name,$file_ext,$file_size,$base64);
					//$docId = $add_document->documentId;
					//$docName = $add_document->documentName;

					// Example: Check for allowed file types and maximum file size
					$allowed_extensions = array('pdf', 'doc', 'docx');
					$max_file_size = 10 * 1024 * 1024; // 10 MB

					if(in_array($file_ext, $allowed_extensions) && $file_size <= $max_file_size){
						$data = file_get_contents($file_tmp);
						$base64 = base64_encode($data);
						$add_document=uploadDocument($file_name,$file_ext,$file_size,$base64);
						$docId = $add_document->documentId;
						$docName = $add_document->documentName;
					}else{
						// Handle file validation errors here
						echo 'Invalid file type or size. Allowed only "pdf", "doc", "docx" file extention of size less than 10MB';
					}
                }
				$docId = isset($docId)?$docId:'';
				$docName = isset($docName)?$docName:'';
				$response = saveCandidate($docId,$docName,$firstName,$lastName,$emailId,$phoneNumber,$upload_docid,$coverletter,$jobTitle,$comments,$industry,$industryId,$address1,$address2,$city,$provinceAndState,$postalCode,$country,$countryIdCode,$countryName,$skills);

				
                //$jobApplicantId	= $response->applicantId;
                $jobApplicantId	= $response->data->candidateId;
                $jobApplicantName	= $response->data->fullName;
                
                if($jobApplicantId != "" && $positionId !=""){
                	$assoResponse =  associateCandidatesToPosition($jobApplicantId,$positionId);
                }
           
				if($noteDetails!= "" && $jobApplicantId !=""){
					$noteText=$noteDetails->noteText;
		 			$createNotesResponse=$awp_services_obj->saveNotes(APPTIVO_JOBS_OBJECT_ID,$jobApplicantId,$jobApplicantName,$noteText);
		 		}
			}
			if(isset($jobApplicantId) && !empty($jobApplicantId)){
				if(!empty($hrjobsform['confmsg'])){
					$confmsg = $hrjobsform['confmsg'];
				}else{
					$confmsg="Job applicant uploaded Successfully";
				}
			}else if($response == 'E_IP'){
				echo awp_messagelist('IP_banned');
			}
				
		}
		return $confmsg;
	}

	/**
	 * Get hrjobsform and its fields to render in page which is using shortcode
	 */
	function get_jobapplicantform_fields($formname){
		$formExists="";
		$hrjobs_forms=array();
		$hrjobsform=array();
		$hrjobsformdetails=array();
		$formname=trim($formname);

		$hrjobs_forms=get_option('awp_jobsforms');

		if($formname=="")
		$formExists="";
		else if(!empty($hrjobs_forms))
		$formExists = awp_recursive_array_search($hrjobs_forms,$formname,'name' );
			
		if(trim($formExists)!=="" ){
			$hrjobsform=$hrjobs_forms[$formExists];
			//build hrjobsformdetails array
			$hrjobsformdetails['name']=$hrjobsform['name'];
				
			//add properties
			$hrjobsformproperties=$hrjobsform['properties'];
			$hrjobsformdetails['tmpltype']=$hrjobsformproperties['tmpltype'];
			$hrjobsformdetails['layout']=$hrjobsformproperties['layout'];
			$hrjobsformdetails['confmsg']= stripslashes($hrjobsformproperties['confmsg']);
            $hrjobsformdetails['confmsg_pagemode']= $hrjobsformproperties['confirm_msg_page'];
			$hrjobsformdetails['confmsg_pageid']= $hrjobsformproperties['confirm_msg_pageid'];
			$hrjobsformproperties['targetlist'] = (array_key_exists('targetlist',$hrjobsformproperties)) ? $hrjobsformproperties['targetlist'] : null;
			$hrjobsformdetails['targetlist']=$hrjobsformproperties['targetlist'];
			$hrjobsformdetails['css']=stripslashes($hrjobsformproperties['css']);
			$hrjobsformdetails['submit_button_type']=$hrjobsformproperties['submit_button_type'];
			$hrjobsformdetails['submit_button_val']=$hrjobsformproperties['submit_button_val'];
			//inclde templates.
			if($hrjobsformproperties['tmpltype']=="awp_plugin_template") :
			$templatefile=AWP_JOBSFORM_TEMPLATEPATH."/".$hrjobsformproperties['layout']; //Job Applicant form plugin template
			else :
			$templatefile=TEMPLATEPATH."/jobs/jobapplicant/".$hrjobsformproperties['layout']; //Job Applicant form theme template
			endif;
				
			$hrjobsformdetails['templatefile']=$templatefile;
			//add fields
			$hrjobsformfields=$hrjobsform['fields'];
			if(!empty($hrjobsformfields)){
				usort($hrjobsformfields, "awp_sort_by_order");
				$newhrjobsformfields=$hrjobsformfields;
				$hrjobsformdetails['fields']=$newhrjobsformfields;
			}
		}
		return $hrjobsformdetails;
	}


	function get_jobsearch_field($formname){

		$formExists="";
		$jobsearch_forms=array();
		$jobsearchform=array();
		$jobsearchformdetails=array();
		$formname=trim($formname);

		$jobsearch_forms=get_option('awp_jobsearchforms');

		if($formname==""){
			$formExists="";
		}
		else if(!empty($jobsearch_forms)){
			$formExists = awp_recursive_array_search($jobsearch_forms,$formname,'name' );
		}
			
		if(trim($formExists)!=="" ){
			$jobsearchform=$jobsearch_forms[$formExists];
			//build hrjobsformdetails array
			$jobsearchformdetails['name']=$jobsearchform['name'];
			//add properties
			$jobsearchformproperties=$jobsearchform['properties'];
			$jobsearchformdetails['tmpltype']=$jobsearchformproperties['tmpltype'];
			$jobsearchformdetails['layout']=$jobsearchformproperties['layout'];
			$jobsearchformdetails['confmsg']= stripslashes($jobsearchformproperties['confmsg']);

			$jobsearchformproperties['targetlist'] = (array_key_exists('targetlist',$jobsearchformproperties)) ? $jobsearchformproperties['targetlist'] : '';

			$jobsearchformdetails['targetlist']=$jobsearchformproperties['targetlist'];
			$jobsearchformdetails['css']=stripslashes($jobsearchformproperties['css']);
			$jobsearchformdetails['submit_button_type']=$jobsearchformproperties['submit_button_type'];
			$jobsearchformdetails['submit_button_val']=$jobsearchformproperties['submit_button_val'];
			$jobsearchformdetails['target_pageurl']=$jobsearchformproperties['target_pageurl'];
			$jobsearchformdetails['jobapplicant_pageurl']=$jobsearchformproperties['jobapplicant_pageurl'];
			//Include job serach template
			if($jobsearchformproperties['tmpltype']=="awp_plugin_template") :
			$templatefile=AWP_JOBSEARCHFORM_TEMPLATEPATH."/".$jobsearchformproperties['layout']; //Job search form plugin templates
			else :
			$templatefile=TEMPLATEPATH."/jobs/jobsearch/".$jobsearchformproperties['layout'];	//Job search form theme templates
			endif;
			$jobsearchformdetails['templatefile']=$templatefile;
			//add fields
			$jobsearchformfields=$jobsearchform['fields'];
			if(!empty($jobsearchformfields)){
				usort($jobsearchformfields, "awp_sort_by_order");
				$newhrjobsformfields=$jobsearchformfields;
				$jobsearchformdetails['fields']=$newhrjobsformfields;
			}
		}
		return $jobsearchformdetails;
	}

	/**
	 * Get hrjobs form settings by form name to render in Admin
	 */
	function get_settings($formname,$type){
		$formExists="";
		$hrjobs_forms=array();
		$hrjobsform=array();
		$formname=trim($formname);
		if( $type == 'jobsearch')
		{
			$hrjobs_forms=get_option('awp_jobsearchforms');
		}
		else {
			$hrjobs_forms=get_option('awp_jobsforms');
		}

		if($formname=="")
		$formExists="";
		else if(!empty($hrjobs_forms))
		$formExists = awp_recursive_array_search($hrjobs_forms,$formname,'name' );
			
		if(trim($formExists)!=="" ){
			$hrjobsform=$hrjobs_forms[$formExists];
		}
		return $hrjobsform;
	}

	/**
	 * Return master fields lists supported by Apptivo hrjobs Form
	 */
	function get_master_fields()
	{
		$fields = array(
		array('fieldid' => 'firstname','fieldname' => 'First Name','defaulttext' => 'First Name','showorder' => '1','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','showorder' => '2','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '3','validation' => 'email','fieldtype' => 'text'),
		array('fieldid' => 'jobtitle','fieldname' => 'Job Title','defaulttext' => 'Job Title','showorder' => '4','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'company','fieldname' => 'Company','defaulttext' => 'Company','showorder' => '5','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'address1','fieldname' => 'Address1','defaulttext' => 'Address1','showorder' => '6','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'address2','fieldname' => 'Address2','defaulttext' => 'Address2','showorder' => '7','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'city','fieldname' => 'City','defaulttext' => 'City','showorder' => '8','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'zipcode','fieldname' => 'ZipCode','defaulttext' => 'ZipCode','showorder' => '10','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'country','fieldname' => 'Country','defaulttext' => 'Country','showorder' => '11','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'telephonenumber','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','showorder' => '12','validation' => 'number','fieldtype' => 'text'),
		array('fieldid' => 'comments','fieldname' => 'Comments','defaulttext' => 'Comments','showorder' => '13','validation' => 'textarea','fieldtype' => 'textarea'),
		array('fieldid' => 'coverletter','fieldname' => 'Cover Letter','defaulttext' => 'Cover Letter','showorder' => '14','validation' => 'textarea','fieldtype' => 'textarea'),
		array('fieldid' => 'Skills','fieldname' => 'Skills','defaulttext' => 'Skills','showorder' => '15','validation' => 'upload','fieldtype' => 'textarea'),
		array('fieldid' => 'upload','fieldname' => 'Upload File','defaulttext' => 'Upload File','showorder' => '16','validation' => 'textarea','fieldtype' => 'upload'),
		array('fieldid' => 'industry','fieldname' => 'Industry','defaulttext' => 'Industry','showorder' => '17','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '18','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '19','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '20','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '21','validation' => '','fieldtype' => 'radio'),
		array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '22','validation' => '','fieldtype' => 'checkbox')
		);

		//For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom_jobapplicant');
		if(!empty($addtional_custom)):
		$fields = array_merge($fields,$addtional_custom);
		endif;

		return $fields;
	}

	function get_master_fieldsfor_searchjobs()
	{
		/* $fields = array(
		array('fieldid' => 'keywords','fieldname' => 'Keywords','defaulttext' => 'Keywords','showorder' => '1','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'customfield1','fieldname' => 'Industry','defaulttext' => 'Industry','showorder' => '2','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield2','fieldname' => 'JobType','defaulttext' => 'Job Type','showorder' => '3','validation' => '','fieldtype' => 'select'),
		); */
		$fields = array(
			array('fieldid' => 'keywords','fieldname' => 'Keywords','defaulttext' => 'Keywords','showorder' => '1','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'industry','fieldname' => 'Industry','defaulttext' => 'Industry','showorder' => '2','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'jobtype','fieldname' => 'JobType','defaulttext' => 'Job Type','showorder' => '3','validation' => '','fieldtype' => 'select'),
			);
		return $fields;
	}

	/**
	 * Retrieve list of validations supported by Apptivo hrjobs Form
	 *
	 */
	function get_master_validations(){
		$validations = array(
		array('validationLabel' => 'None','validation' => 'none'),
		array('validationLabel' => 'Email ID','validation' => 'email'),
		array('validationLabel' => 'Number','validation' => 'number')
		);
		return $validations;
	}
	/**
	 *
	 * * Retrieve list of Field Types supported by Apptivo hrjobs Form
	 */
	function get_master_fieldtypes(){
		$fieldtypes = array(
		array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
		array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
		array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
		array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
		array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
		);
		return $fieldtypes;
	}

	function get_master_fieldtypes_jobsearch(){
		$fieldtypes = array(
		array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
		array('fieldtypeLabel' => 'Select','fieldtype' => 'select')
		);
		return $fieldtypes;
	}


	/**
	 * return array of plugin templates available with Template name and template file name
	 */
	function get_plugin_templates($dir_hrjobs)
	{

		$default_headers = array(
		'Template Name' => 'Template Name'		
		);
		$templates = array();

		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_hrjobs)) {
			if ($dh = opendir($dir_hrjobs)) {
				while (($file = readdir($dh)) !== false) {
					if ( substr( $file, -4 ) == '.php' )
					{
						$plugin_data = get_file_data( $dir_hrjobs."/".$file, $default_headers, '' );
						if(strlen(trim($plugin_data['Template Name'])) != 0 )
						{
							$templates[$plugin_data['Template Name']] = $file;
						}
					}
				}

				closedir($dh);
			}
		}
		return $templates;

	}

	function get_apptivo_template_data( $apptivo_template_file,$template_filename)
	{

		$test = array();
		$default_headers = array(
		'Apptivo Template Name' => 'Apptivo Template Name',		
		'Version' => 'Version',
		'Description' => 'Description',
		'Author' => 'Author',
		);

		$plugin_data = get_file_data( $apptivo_template_file, $default_headers, '' );
		if(strlen(trim($plugin_data['Apptivo Template Name'])) != 0 )
		{

			$test[$plugin_data['Apptivo Template Name']] = $template_filename;

		}

	}
	/**
	 * Create field array
	 */
	function createformfield_array($fieldid,$showtext,$required,$type,$validation,$options,$displayorder){
		
		
	   $displayorder = (trim($displayorder)=="")?0:trim($displayorder);
		
	   $options = (is_array($options))?$options:stripslashes(str_replace( array('"'), '', strip_tags($options)));

		if( trim($type) != 'text' && trim($type) != 'textarea')
		{
			$pos = strpos(trim($fieldid), 'customfield');
			if( $pos !== false )
			{
				if( !is_array($options) && trim($options) == '')
				{	
					return '';
				}
			}
		}
		
		
		$hrjobsformfield= array(
	            'fieldid'=>$fieldid,
                'showtext' => stripslashes(str_replace( array('"'), '', strip_tags($showtext))),
	            'required' => $required,
				'type' => $type,
				'validation' => $validation,
				'options' => $options,
	   			'order' => $displayorder
		);
		return $hrjobsformfield;
	}

	function createJobsoptions()
	{
		$_POST['awp_jobs_add'] = (array_key_exists('awp_jobs_add',$_POST)) ? sanitize_text_field($_POST['awp_jobs_add']) : "";
		$_POST['nogdog'] = (array_key_exists('nogdog',$_POST)) ? sanitize_text_field($_POST['nogdog']) : "";
		if($_POST['awp_jobs_add'] && ($_POST['nogdog'] == $_SESSION['apptvo_single_jobs']))  //Create Jobs
		{
			$jobtitle = sanitize_text_field($_POST['jobs_title']);
			$content = stripslashes(sanitize_text_field($_POST['content']));
			$content = apply_filters('the_content', $content);
			$jobindustry = sanitize_text_field($_POST['jobs_industry']);
			//$jobtype = sanitize_text_field($_POST['jobs_type']);
			//$jobstatus = sanitize_text_field($_POST['jobs_status']);
			//$isFeatured = sanitize_text_field($_POST['jobs_featured']);
			$jobtype = isset($_POST['jobs_type']) ? sanitize_text_field($_POST['jobs_type']) : '';
			$jobstatus = isset($_POST['jobs_status']) ? sanitize_text_field($_POST['jobs_status']) : '';
			$isFeatured = isset($_POST['jobs_featured']) ? sanitize_text_field($_POST['jobs_featured']) : '';
			if( strlen(trim($jobtitle)) == 0 || strlen(trim($content)) == 0)
			{
				$errorMsg = "Job Title and Job Description can not be empty.";

			} else {

				if(!empty($jobindustry)){
					$jobindustry = explode('_', $jobindustry);
					$industryName =$jobindustry[1];
					$industryCode = isset($jobindustry[0]) ? $jobindustry[0] : NULL;
				}else{
					$industryName = '';
					$industryCode = null;
				}

				if(!empty($jobtype)){
					$jobtype = explode('_', $jobtype);
					$categoryName =$jobtype[1];
					$categoryCode = isset($jobtype[0]) ? $jobtype[0] : NULL;
				}else{
					$categoryName = '';
					$categoryCode = null;
				}

				if(!empty($jobstatus)){		
					$jobstatus = explode('_', $jobstatus);
					$statusName =$jobstatus[1];
					$statusId = isset($jobstatus[0]) ? $jobstatus[0] : NULL;
				}else{
					$statusName = '';
					$statusId = null;
				}
				
				$positionDetails = new stdClass();
				$positionDetails->positionNumber = "Auto generated number";
				$positionDetails->statusName = $statusName;
				$positionDetails->statusId = $statusId;
				$positionDetails->statusCode = strtoupper($statusName);
				if(isset($isFeatured) && $isFeatured == "on"){
					$positionDetails->isFeatured = "Y";
				}else{
					$positionDetails->isFeatured = "N";
				} 
				$positionDetails->title = $jobtitle;
				$positionDetails->description = $content;
				$positionDetails->industryName = $industryName;
				$positionDetails->industryId = $industryCode;
				$positionDetails->categoryName = $categoryName;
				$positionDetails->categoryId = $categoryCode;

				$response = createJob(json_encode($positionDetails));
				
				if(isset($response->status) && $response->status == 'SUCCESS'){
					$sucMsg = "Job Created successfully.";
				}else{
					$sucMsg = '<span style="color:#f00;">Please Try again later.</span>';	
				}
				
				/*$response = createJobs($jobtitle,$content,$jobindustry,$jobtype,$isFeatured);
				if($response == 'E_100')
				{
					$sucMsg = '<span style="color:#f00;">Invalid Keys</span>';
				}else if(isset($response->status) && $response->status != '1000')
				{
					$sucMsg = '<span style="color:#f00;">'.$response->statusMessage.'</span>';
				}else { $sucMsg = "Job Created successfully."; }*/
			}
				
		} //End of Create Jobs.
		$_POST['awp_updatejobs'] = (!empty($_POST['awp_updatejobs'])) ? sanitize_text_field($_POST['awp_updatejobs']) : "";
		if($_POST['awp_updatejobs'])  // Update Jobs
		{
			$jobtitle = sanitize_text_field($_POST['jobs_title']);
			$content = stripslashes(sanitize_text_field($_POST['editcontent']));
			$content = apply_filters('the_content', $content);
			$jobId = sanitize_text_field($_POST['jobs_id']);
			$industryId =  sanitize_text_field($_POST['jobs_industry']);
			$jobindustry = sanitize_text_field($_POST['jobs_industry']);
			//$jobtype = sanitize_text_field($_POST['jobs_type']);
			//$jobstatus = sanitize_text_field($_POST['jobs_status']);
			//$isFeatured = sanitize_text_field($_POST['jobs_featured']);
			$jobtype = isset($_POST['jobs_type']) ? sanitize_text_field($_POST['jobs_type']) : '';
			$jobstatus = isset($_POST['jobs_status']) ? sanitize_text_field($_POST['jobs_status']) : '';
			$isFeatured = isset($_POST['jobs_featured']) ? sanitize_text_field($_POST['jobs_featured']) : '';

			if( strlen(trim($jobtitle)) == 0 || strlen(trim($content)) == 0)
			{
				$errorMsg = "Job Title and Job Description can not be empty.";

			}else {
					
				if(isset($jobId) && is_numeric($jobId))
				{
					
				$jobindustry = explode('_', $jobindustry);
				$industryName =$jobindustry[1] ?? '';
				$industryCode =$jobindustry[0] ?? '';
				
				$jobtype = explode('_', $jobtype);
				$categoryName =$jobtype[1] ?? '';
				$categoryCode =$jobtype[0] ?? '';
				
				$jobstatus = explode('_', $jobstatus);
				$statusName =$jobstatus[1] ?? '';
				$statusId =$jobstatus[0] ?? '';
				
				$positionDetails = new stdClass();
				$positionDetails->positionNumber = "Auto generated number";
				$positionDetails->statusName = $statusName;
				$positionDetails->statusId = $statusId;
				$positionDetails->statusCode = strtoupper($statusName);
				if(isset($isFeatured) && $isFeatured == "on"){
				$positionDetails->isFeatured = "Y";
				}else{
				$positionDetails->isFeatured = "N";
				} 
				$positionDetails->title = $jobtitle;
				$positionDetails->description = $content;
				$positionDetails->industryName = $industryName;
				$positionDetails->industryId = $industryCode;
				$positionDetails->categoryName = $categoryName;
				$positionDetails->categoryId = $categoryCode;
				

				$response = updateJob(json_encode($positionDetails),$jobId);
				
				if(isset($response->data) && $response->data != ''){
					$sucMsg = "Job Updated successfully.";
				}else{
					$sucMsg = '<span style="color:#f00;">Please Try again later.</span>';	
				}
			       /*$response = updatejobs($jobId,$jobtitle,$content,$industryId,$jobtype,$isFeatured,$jobstatus);
					if(isset($response->statusCode) && $response->statusCode != '1000')
					{
						$sucMsg = '<span style="color:#f00;">'.$response->statusMessage.'</span>';
					}else {
						$sucMsg  = "Job Updated successfully.";}*/
				}
			}
		} //End of Update Jobs.
		
		//$jobTypeLists = array('Full Time' => 'Full Time','Part Time' => 'Part Time','Contract' => 'Contract');
		//$jobTypeStatus = array('New' => 'New','Approved' => 'Approved','Closed' => 'Closed','Canceled' => 'Canceled');
		
		$jobTypeLists = getAllStatusesTypesV6()->categories;
		$jobStatuses = getAllStatusesTypesV6()->statuses;
		?>
		<div class="wrap"> <h2 style="font-size: 23px;font-weight: normal;">Jobs Management </h2> </div>
		<?php checkSoapextension("jobs"); 
			checkCaptchaOption();
		?>
		<div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('jobs_icon'); ?>') " ><br>
		</div>
		<h2 class="nav-tab-wrapper"><a class="nav-tab nav-tab-active"
			href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_jobs"><?php _e('Jobs','apptvo-businesssite'); ?></a>
		<a class="nav-tab"
			href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_jobs&keys=configuration"><?php _e('Configuration','apptvo-businesssite'); ?></a>
		<a class="nav-tab"
			href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_jobs&keys=jobsearch"><?php _e('Job Search','apptvo-businesssite'); ?></a>
		</h2>

		<?php if(!$this->_plugin_activated){
			echo "<div class='wrap'>Jobs plugin currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a></div>";
		}
		?>
		<p><img id="elementToResize"
			src="<?php echo awp_flow_diagram('jobs');?>" alt="Jobs" title="Jobs" />
		</p>

		<span style="margin: 10px;">For Complete instructions, see the - <a
			href="<?php echo awp_developerguide('jobs');?>" target="_blank">Developer's
		Guide.</a></span>

		<?php
		 
		//Displaying  Job Lists

		if($this->_plugin_activated){
			$this->AllJobs();
		}
		 
		 
		//Displaying  Job Lists
		$sucMsg = (!empty($sucMsg)) ? $sucMsg : '';
		if(strlen($sucMsg) != 0 ) { ?>
			<div id="message" style="width: 80%;" class="updated below-h2">
			<p><?php echo html_entity_decode(esc_attr($sucMsg)); ?></p>
			</div>
		<?php }
		//$_GET['action'] = (array_key_exists('action',$_GET)) ? sanitize_text_field($_GET['action']) : '';
		$action = sanitize_text_field($_GET['action'] ?? '');
		if($action === 'edit' && isset($_GET['id']) && is_numeric($_GET['id'])){
			$industryId = array();
			$jobresultsbyid = getJobById(sanitize_text_field($_GET['id']));
			$selectedJobs = $jobresultsbyid->data;

			?>
			<div class="wrap">
			<h2><?php _e('Update Jobs','apptivo-businesssite'); ?></h2>
			<?php if(strlen($errorMsg ?? '') != 0 ) { ?>
				<div id="message" style="width: 80%;" class="updated below-h2">
				<p><?php echo esc_attr($errorMsg); ?></p>
				</div>
			<?php }
			if(strlen($sucMsg) != 0 ){ ?>
				<div id="message" style="width: 80%;" class="updated below-h2">
				<p><?php echo html_entity_decode(esc_attr($sucMsg)); ?></p>
				</div>
			<?php } ?></div>
			<form name="awp_updatejobs"
				action="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs&keys=jobcreation"
				method="post" onsubmit="return validateupdatejobs(this)"><input
				type="hidden" id="job_ID" value="job_ID"
				value="<?php echo sanitize_text_field($_GET['id']); ?>" />
				<table width="700" cellspacing="0" cellpadding="0" class="form-table">
					<tbody>
						<tr>
							<td><?php _e('Title','apptivo-businesssite'); ?>&nbsp;<span
								style="color: #f00;">*</span></td>
							<td><input type="text" size="43" id="jobs_title" name="jobs_title"
								value="<?php echo !empty($_POST['jobs_title']) ? esc_attr(sanitize_text_field($_POST['jobs_title'])) : esc_attr(sanitize_text_field($selectedJobs->title)); ?>">
							<input type="hidden" id="jobs_id" name="jobs_id"
								value="<?php echo esc_attr(sanitize_text_field($selectedJobs->positionId)); ?>" /></td>
						</tr>
						<tr>
							<td valign="top"><?php _e('Description','apptivo-businesssite'); ?>&nbsp;<span
								style="color: #f00;">*</span></td>
							<td>
							<div style="width: 630px;"><?php 
							//if(!empty(sanitize_text_field($_POST['editcontent']))) { $updated_value =  sanitize_text_field($_POST['editcontent']); } else {  $updated_value =  $selectedJobs->description; }
							$updated_value = !empty($_POST['editcontent']) ? sanitize_text_field($_POST['editcontent']) : sanitize_text_field($selectedJobs->description);
							//the_editor($updated_value,'editcontent','',FALSE);
							wp_editor($updated_value, 'editcontent', array());
							?></div>
							</td>
						</tr>

						<tr>
							<td><?php _e('Industry','apptivo-businesssite'); ?></td>
							<td><select id="jobs_industry" name="jobs_industry">
							<?php 
							$allIndustries = getAllIndustriesV6()->industries;
							foreach($allIndustries as $industries)
							{
								?>
								<option
								<?php selected($industries->industryId, $selectedJobs->industryId); ?>
									value="<?php echo html_entity_decode(esc_attr($industries->industryId));?>"><?php echo html_entity_decode(esc_attr($industries->industryName));?></option>
									<?php
							}?>
							</select></td>
						</tr>
						<tr>
							<td><?php _e('Job Type','apptivo-businesssite'); ?></td>
							<td><select id="jobs_type" name="jobs_type">
							<?php
							foreach($jobTypeLists  as $key => $value)
							{
								?>
								<option <?php selected($value->jobTypeId, $selectedJobs->categoryId); ?>
								value="<?php echo html_entity_decode(esc_attr($value->jobTypeId)).'_'.html_entity_decode(esc_attr($value->name)); ?>">
								<?php echo html_entity_decode(esc_attr($value->name));?></option>
								<?php
							} 
							
							?>
							</select></td>
						</tr>

						<tr>
							<td><?php _e('Job Status','apptivo-businesssite'); ?></td>
							<td><select id="jobs_status" name="jobs_status">
							<?php
							foreach($jobStatuses  as $key => $status)
							{
								?>
								<option <?php selected($status->statusName, $selectedJobs->statusName); ?> value="<?php echo html_entity_decode(esc_attr($status->statusId)).'_'.html_entity_decode(esc_attr($status->statusName)); ?>">
								<?php echo html_entity_decode(esc_attr($status->statusName)); ?>
								</option>
								
									<?php
							}
							?>
							</select></td>
						</tr>


						<tr>
							<td><?php _e('Is featured','apptivo-businesssite'); ?></td>
							<td><input <?php checked('Y',$selectedJobs->isFeatured); ?>
								type="checkbox" id="jobs_featured" name="jobs_featured" /></td>
						</tr>


						<tr>
							<td></td>
							<td><input type="submit" class="button-primary" name="awp_updatejobs"
								value="<?php _e('Update Jobs','apptivo-businesssite');?>"></td>
						</tr>

					</tbody>
				</table>

			</form>

		<?php }else{
			// Jobs generals
			?>
			<div class="wrap"><h2><?php _e('Create Jobs','apptivo-businesssite');?></h2>
				<?php $nogdog = uniqid();$_SESSION['apptvo_single_jobs'] = $nogdog; ?>
				<?php $errorMsg = (!empty($errorMsg)) ? $errorMsg : '';
				if(strlen($errorMsg) != 0 ) { ?>
					<div id="message" style="width: 80%;" class="updated below-h2">
					<p><?php echo esc_attr($errorMsg); ?></p>
					</div>
				<?php } ?>
				<form name="awp_events_form"
					action="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_jobs&keys=jobcreation"
					method="post" onsubmit="return validatecreatejobs(this)"><input
					type="hidden" name="nogdog" value="<?php echo esc_attr($nogdog);?>">
				<table width="700" cellspacing="0" cellpadding="0" class="form-table">
					<tbody>
						<tr>
							<td><?php _e('Title','apptivo-businesssite');?>&nbsp;<span
								style="color: #f00;">*</span></td>
							<td><input type="text" size="43" value="" id="jobs_title"
								name="jobs_title"></td>
						</tr>
						<tr>
							<td valign="top"><?php _e('Description','apptivo-businesssite');?>&nbsp;<span
								style="color: #f00;">*</span></td>
							<td>
							<div style="width: 630px;"><?php
							$updated_value = (!empty($updated_value)) ? $updated_value : '';
							//the_editor($updated_value,'content','',FALSE);
							wp_editor($updated_value, 'content', array());
							?></div>
							</td>

						</tr>

						<tr>
							<td><?php _e('Industry','apptivo-businesssite');?></td>
							<td><select id="jobs_industry" name="jobs_industry">
								<option value="">--Select Industry--</option>
								<?php 
								$allIndustries = getAllIndustriesV6()->industries;
								foreach($allIndustries as $industries)
								{
									?>
								<option value="<?php echo html_entity_decode(esc_attr($industries->industryId)).'_'.html_entity_decode(esc_attr($industries->industryName));?>"><?php echo html_entity_decode(esc_attr($industries->industryName));?></option>
								<?php
								}?>
							</select></td>
						</tr>
						<tr>
							<td><?php _e('Job Type','apptivo-businesssite');?></td>
							<td><select id="jobs_type" name="jobs_type">
							<?php
							foreach($jobTypeLists  as $key => $value)
							{
								?>
								<option value="<?php echo html_entity_decode(esc_attr($value->jobTypeId)).'_'.html_entity_decode(esc_attr($value->name)); ?>"><?php echo html_entity_decode(esc_attr($value->name)); ?></option>
								<?php
							} ?>
							</select></td>
						</tr>
						
						<tr>
							<td><?php _e('Job Status','apptivo-businesssite');?></td>
							<td><select id="jobs_status" name="jobs_status">
							<?php
							foreach($jobStatuses  as $key => $status)
							{
								?>
								<option value="<?php echo esc_attr($status->statusId).'_'.esc_attr($status->statusName); ?>"><?php echo esc_attr($status->statusName); ?></option>
								<?php
							} ?>
							</select></td>
						</tr>

						<tr>
							<td><?php _e('Is featured','apptivo-businesssite');?></td>
							<td><input type="checkbox" id="jobs_featured" name="jobs_featured" />
							</td>
						</tr>

						<tr>
							<td></td>
							<td><input
							<?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }?>
								type="submit" class="button-primary" name="awp_jobs_add"
								value="<?php _e('Create Jobs','apptivo-businesssite');?>"></td>
						</tr>

					</tbody>
				</table>

				</form>
			</div>

		<?php } ?>

			<?php
	}


	function AllJobs()
	{
		//$Job_results  = get_apptivojobs();//To get Jobs From Apptivo.
		//$JobSearchResults = awp_convertObjToArray($Job_results->jobDetails);
		$Job_results  = getAlljobs();//To get Jobs From Apptivo.
		$JobSearchResults = awp_convertObjToArray($Job_results->data);
		
		//if( $Job_results->numResults != 0)
		if( $Job_results->countOfRecords != 0)
		{
			$numberofjobs = count($JobSearchResults);
			$jobsperpage =5;
			$tpages = ceil($numberofjobs/$jobsperpage);
			$_GET['pageno'] = (!empty($_GET['pageno'])) ? sanitize_text_field($_GET['pageno']) : 0;
			$currentpage   = intval($_GET['pageno']);
			if($currentpage<=0)  $currentpage  = 1;
			if($currentpage>=$tpages)  $currentpage  = $tpages;
			$reload = $_SERVER['PHP_SELF'].'?page=awp_jobs&keys=jobcreation';
			$start = ( $currentpage - 1 ) * $jobsperpage;
			$JobSearchResults = array_slice( $JobSearchResults, $start, $jobsperpage);
			?>
<div class="wrap"><?php if(!$this->_plugin_activated){
	echo "Jobs plugin currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
}else {
	$jobapplicant_settings = get_option('awp_jobsforms');
	if($jobapplicant_settings != false){
	$job_appl_page = $jobapplicant_settings[0]['properties']['jobapplicant_page'];
	$awp_jobs_settings = get_option('awp_jobs_settings');
	$list_template = $awp_jobs_settings['list_template_name'];
	if(strlen(trim($job_appl_page)) == 0 && strlen(trim($list_template)) == 0)
	{
		echo 'To show job list in Website. Update <a href="'.SITE_URL.'/wp-admin/admin.php?page=awp_jobs&keys=configuration">Job settings</a> and <a href="'.SITE_URL.'/wp-admin/admin.php?page=awp_jobs&keys=configuration&step=2">Job Applicant form</a> Configuration before adding Jobs list shortcode in Page or Post.';
	}else if(strlen(trim($job_appl_page)) != 0 && strlen(trim($list_template)) == 0)
	{
		echo 'To show job list in Website. Update <a href="'.SITE_URL.'/wp-admin/admin.php?page=awp_jobs&keys=configuration">Job settings</a> Configuration before adding Jobs list shortcode in Page or Post.';
	}else if(strlen(trim($job_appl_page)) == 0 && strlen(trim($list_template)) != 0)
	{
		echo 'To show job list in Website. Update <a href="'.SITE_URL.'/wp-admin/admin.php?page=awp_jobs&keys=configuration&step=2">Job Applicant form</a> Configuration before adding Jobs list shortcode in Page or Post.';
	}
}

}

?></div>
<br />
<?php
if( $numberofjobs > $jobsperpage)
{
	echo awp_paginate($reload,$currentpage,$tpages,$numberofjobs);
}
?>
<form name="awp_jos_deleteform" method="post"
	action="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_jobs"><input type="hidden"
	name="job_delete_form" id="job_delete_form" />
<table class="widefat plugins" width="700" cellspacing="0"
	cellpadding="0">
	<thead>
		<tr>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Title','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Description','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Industry','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Status','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Type','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Featured','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF; text-align: center;"><?php _e('Action','apptivo-businesssite');?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Title','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Description','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Industry','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Status','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Type','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF;"><?php _e('Job Featured','apptivo-businesssite');?></th>
			<th style="border-top: 1px solid #DFDFDF; text-align: center;"><?php _e('Action','apptivo-businesssite');?>
			</th>
		</tr>
	</tfoot>
	<tbody id="the-list">
	<?php
	if(!empty($JobSearchResults)){
		foreach ($JobSearchResults as $jobs) {
			if(array_key_exists('id',$_GET)){
			if(sanitize_text_field($_GET['id']) == $jobs->id)
			{
				$style = 'style="background-color: #E7E7E7;"';
			}
			} else {
				$style = '';
			}
			?>
		<tr <?php echo esc_attr($style); ?>>
			<td><?php echo esc_attr($jobs->title); ?></td>
			<td>
			<div>
			<p><?php if (strlen(strip_tags(html_entity_decode($jobs->description))) < 30)
			{
				echo esc_attr(stripslashes(strip_tags($jobs->description)));
			}
			else
			{
				$sub = strip_tags($jobs->description);
				echo $sub = stripslashes(substr($sub, 0, 30)).'...';
			}
			?></p>
			</div>
			</td>
			<td><?php 
				$jobs->industryName = (property_exists($jobs,'industryName')) ? $jobs->industryName : '';
			    echo esc_attr($jobs->industryName); ?></td>
			<td><?php 
				$jobs->statusName = (property_exists($jobs,'statusName')) ? $jobs->statusName : '';
				echo esc_attr($jobs->statusName); ?></td>
			<td><?php 
			$jobs->categoryName = (property_exists($jobs,'categoryName')) ? $jobs->categoryName : '';
			echo esc_attr($jobs->categoryName); ?></td>
			<td><?php if($jobs->isFeatured == 'Y') { echo awp_image('success',true); }else { echo awp_image('success-off',true); } ?></td>
			<td style="text-align: center;"><?php 
			if($this->_plugin_activated)
			{ ?> <a
				href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs&keys=jobcreation&action=edit&id=<?php echo esc_attr($jobs->positionId); ?>"><img
				src="<?php echo awp_image('edit_icon'); ?>"></a> <?php } else { echo 'No Action'; } ?>

			</td>
		</tr>
		<?php  }
	}
	else{

		?>
		<tr class="no-items">
			<td class="colspanchange" colspan="5"><?php _e('No jobs found','apptivo-businesssite');?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
</form>

<br />
	<?php if($this->_plugin_activated) {
		echo '<p>Copy and Paste this short code in your page to display this list of jobs  <input type="text" name="jobs_shortcode" id="jobs_shortcode" value="[apptivo_jobs]" readonly="true" /></p>';
	} ?>


	<?php
		}

	}
	/**
	 * It renders UI in Admin page
	 */
	function jobApplicant(){
		
		$allIndustries = getAllIndustriesV6()->industries;
		$_SESSION['industries'] = $allIndustries;
		
		$Results  = getAlljobs();
			
		$updatemessage="";
		/*if( $Results->numResults != 0) { */
		 
		$hrjobs_forms=array();
		$hrjobsformdetails=array();
		$hrjobs_forms=get_option('awp_jobsforms');
		if(empty($hrjobs_forms))
		{
			$jobapplicant_array =array("name"=>'jaform');
			$jobapplicantform=array($jobapplicant_array);
			 
			update_option('awp_jobsforms',$jobapplicantform);
			$hrjobs_forms=get_option('awp_jobsforms');

		}

		/*
		 * Saving selected form settings
		 */

		if(!empty($_POST['awp_jobsform_settings'])){
			$templatelayout="";
			$newformname=sanitize_text_field($_POST['awp_jobsform_name']);
            if(sanitize_text_field($_POST['awp_jobsform_templatetype'])=="awp_plugin_template")
			$templatelayout=sanitize_text_field($_POST['awp_jobsform_plugintemplatelayout']);
			else
			$templatelayout=sanitize_text_field($_POST['awp_jobsform_themetemplatelayout']);
			$_POST['subscribe_option'] = (array_key_exists('subscribe_option',$_POST)) ? sanitize_text_field($_POST['subscribe_option']) : null;
			$hrjobsformproperties=array(
							'tmpltype' =>sanitize_text_field($_POST['awp_jobsform_templatetype']),
	                        'layout' =>$templatelayout,
	                        'confmsg' => stripslashes(sanitize_text_field($_POST['awp_jobsform_confirmationmsg'])),				
	                        'css' => stripslashes(sanitize_text_field($_POST['awp_jobsform_customcss'])),
                            'confirm_msg_page' =>  stripslashes(sanitize_text_field($_POST['awp_jobsform_confirm_msg_page'])),
                            'confirm_msg_pageid' => sanitize_text_field($_POST['awp_jobsform_confirmmsg_pageid']),
                            'subscribe_option' => sanitize_text_field($_POST['subscribe_option']),
                            'submit_button_type' => sanitize_text_field($_POST['awp_jobsform_submit_type']),
                            'submit_button_val' => sanitize_text_field($_POST['awp_jobsform_submit_val']),
			                'jobapplicant_page'  => sanitize_text_field($_POST['awp_jobapplicant_page']) );
				
			//New custom fields
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 23;
			for($i=6;$i<200;$i++)
			{

				if(!empty($_POST['customfield'.$i.'_newest']) )
				{
					$addtional_custom = array('fieldid' => 'customfield'.$i.'','fieldname' => 'Custom Field '.$i.'',
					                          'defaulttext' => 'Custom Field'.$i.'','showorder' => $addtional_order,'validation' => '',
					                          'fieldtype' => 'select');
					$addtional_order++;
					array_push($stack, $addtional_custom);
						
				}else {
					break;
				}
			}
				
			if(!empty($stack)) :
			update_option('awp_addtional_custom_jobapplicant',$stack);
			endif;
				
			//General fields
			$hrjobsformfields=array();
			foreach( $this->get_master_fields() as $fieldsmasterproperties )
			{
				$enabled=0;
				$hrjobsformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				if(!empty ($_POST[$fieldid.'_order'])){
					$displayorder = sanitize_text_field($_POST[$fieldid.'_order']);
				}
				else{
					$displayorder = $fieldsmasterproperties['showorder'];
				}
				if(!empty ($_POST[$fieldid.'_text'])){
					$displaytext = sanitize_text_field($_POST[$fieldid.'_text']);
				}
				else{
					$displaytext = $fieldsmasterproperties['defaulttext'];
				}
				if($fieldid=='lastname' || $fieldid=='email' || $fieldid=='firstname' || $fieldid=='country' )
				{
					$enabled = 1;
					$required = 1;
				}
				else
				{
					$enabled = sanitize_text_field($_POST[$fieldid.'_show']) ?? '';
					$_POST[$fieldid.'_require'] = (array_key_exists($fieldid.'_require',$_POST)) ? $_POST[$fieldid.'_require'] : 0;
					$required = sanitize_text_field($_POST[$fieldid.'_require']);
				}
				if($enabled){
					$_POST[$fieldid.'_options'] = (array_key_exists($fieldid.'_options',$_POST)) ? sanitize_textarea_field($_POST[$fieldid.'_options']) : '';
					$_POST[$fieldid.'_validation'] = (array_key_exists($fieldid.'_validation',$_POST)) ? sanitize_text_field($_POST[$fieldid.'_validation']) : '';
					$hrjobsformfield=$this->createformfield_array($fieldid,$displaytext,$required,sanitize_text_field($_POST[$fieldid.'_type']),$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
				array_push($hrjobsformfields, $hrjobsformfield);
				}
			}
			//usort($hrjobsformfields, "awp_sort_by_order");
			if(!empty($hrjobsformfields)){
				$newhrjobsformdetails=array('name'=>$newformname,'properties'=>$hrjobsformproperties,'fields'=>$hrjobsformfields);

				$formExists="";
				if(!empty($hrjobs_forms))
				$formExists = awp_recursive_array_search($hrjobs_forms,$newformname,'name' );
				if(trim($formExists)!=="" ){
						
					unset($hrjobs_forms[$formExists]);
					array_push($hrjobs_forms, $newhrjobsformdetails);
					sort($hrjobs_forms);
					update_option('awp_jobsforms',$hrjobs_forms);
					$hrjobs_forms=get_option('awp_jobsforms');
					$updatemessage= "Jobs Form '".$newformname."' settings updated. Use Short code '[apptivo_job_applicantform name=\"".$newformname."\"]' in your page to use this form.";
				}

			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for jobs Form.</span>";
			}
			$selectedhrjobsform=$newformname;
		}

		// Now display the settings editing screen

		echo '<div class="wrap">';

		//if updatemessage is not empty display the div
		if(trim($updatemessage)!=""){
			?>
<div id="message" style="width: 80%;" class="updated">
<p><?php echo esc_attr($updatemessage);?></p>
</div>
			<?php }
			 
			if(!empty($hrjobs_forms)){
				//Template Files
				$themetemplates = get_awpTemplates(TEMPLATEPATH.'/jobs/jobapplicant','Plugin'); //Job applicant theme template
				$plugintemplates=$this->get_plugin_templates(AWP_JOBSFORM_TEMPLATEPATH); //Job applicant form plugin templates
				?>
				<br>
				<?php
				$selectedhrjobsform = (!empty($selectedhrjobsform)) ? $selectedhrjobsform : ' ';
				if(trim($selectedhrjobsform)==""){
					$selectedhrjobsform=$hrjobs_forms[0]['name'];
				}
				$hrjobsformdetails=$this->get_settings($selectedhrjobsform,'');
				if(count($hrjobsformdetails)>0){
					$selectedhrjobsform=$hrjobsformdetails['name'];
					$fields=$hrjobsformdetails['fields'];
					$formproperties=$hrjobsformdetails['properties'];
				}
				?>
				<?php if(!empty($formproperties)){ ?>

					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th valign="top"><label for="awp_jobsform_select_form"><?php _e("Jobs Form", 'apptivo-businesssite' ); ?>:</label>
								</th>
								<td valign="top"><input style="width: 350px;"
									name="awp_jobsform_select_form" id="awp_jobsform_select_form"
									type="text" readonly="readonly"
									value=<?php echo esc_attr($selectedhrjobsform); ?> /></td>
							</tr>
							<tr valign="top">
								<th valign="top"><label for="awp_customform_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
								<br>
								<span class="description"><?php _e("Copy and Paste this short code in your page to display the job applicant form.", 'apptivo-businesssite' ); ?></span>
								</th>
								<td valign="top"><span id="awp_customform_shortcode"
									name="awp_customform_shortcode"> <input style="width: 350px;"
									type="text" readonly="readonly" id="job_applicantform_shortcode"
									name="job_applicantform_shortcode"
									value='[apptivo_job_applicantform name="<?php echo esc_attr($selectedhrjobsform)?>"]' />
								</span> <span style="margin: 10px;">*Developers Guide - <a
									href="<?php echo awp_developerguide('job-applicant-shortcode');?>"
									target="_blank">Job Applicant Form Shortcodes.</a></span></td>
							</tr>
						</tbody>
					</table>
				<?php }else{
					echo '<span style="color: rgb(255, 0, 0);line-height:24px;"> Save the below settings to get the Shortcode for job applicant form. </span>';
				} ?>

					<form name="awp_jobs_settings_form" method="post" action="">
						<table class="form-table">
							<tbody>

								<tr valign="top">
									<th><label id="awp_jobapplicant_page" for="awp_jobapplicant_page"><?php _e("Job Applicant Page", 'apptivo-businesssite' ); ?></label>
									<br>
									<span class="description" valign="top"></span></th>
									<td valign="top"><select id="awp_jobapplicant_page"
										name="awp_jobapplicant_page">

										<?php
										$pages = get_pages();
										foreach ($pages as $pagg) {
											?>
										<option value="<?php echo esc_attr($pagg->ID); ?>"
										<?php selected($pagg->ID, $formproperties['jobapplicant_page']); ?>>
											<?php echo esc_attr($pagg->post_title); ?></option>
											<?php
										}
										?>
									</select></td>
								</tr>

								<tr valign="top">
									<th valign="top"><label for="awp_jobsform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
									</th>
									<td valign="top"><input type="hidden" id="awp_jobsform_name"
										name="awp_jobsform_name" value="<?php echo esc_attr($selectedhrjobsform);?>">
									<select name="awp_jobsform_templatetype"
										id="awp_jobsform_templatetype"
										onchange="japplicant_change_template();">
										<option value="awp_plugin_template"
										<?php selected($formproperties['tmpltype'],'awp_plugin_template'); ?>>
											<?php _e("Plugin Templates", 'apptivo-businesssite' ); ?></option>
											<?php if(!empty($themetemplates)) : ?>
										<option value="theme_template"
										<?php selected($formproperties['tmpltype'],'theme_template'); ?>><?php _e("Templates from Current Theme", 'apptivo-businesssite' ); ?></option>
										<?php endif; ?>
									</select> <span style="margin: 10px;">*Developers Guide - <a
										href="<?php echo awp_developerguide('job-applicant-template');?>"
										target="_blank">Job Applicant Form Templates.</a></span></td>
								</tr>

								<tr valign="top">
									<th valign="top"><label for="awp_jobsform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
									<br>
									<span class="description"><?php _e("Selecting Theme template which doesnt support jobs form structure will wont show the jobs form in webpage.", 'apptivo-businesssite' ); ?></span>
									</th>
									<td valign="top"><select name="awp_jobsform_plugintemplatelayout"
										id="awp_jobsform_plugintemplatelayout"
										<?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?>>
										<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
										<option value="<?php echo esc_attr($plugintemplates[$template])?>"
										<?php selected($formproperties['layout'],$plugintemplates[$template]); ?>>
											<?php echo esc_attr($template); ?></option>
											<?php }?>
									</select> <select name="awp_jobsform_themetemplatelayout"
										id="awp_jobsform_themetemplatelayout"
										<?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?>>
										<?php  foreach (array_keys( $themetemplates ) as $template ) { ?>
										<option value="<?php echo esc_attr($themetemplates[$template])?>"
										<?php selected($formproperties['layout'],$themetemplates[$template]); ?>>
											<?php echo esc_attr($template); ?></option>
											<?php }?>
									</select></td>
								</tr>

								<tr valign="top">
									<th><label for="awp_jobs_samepage"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label>
									</th>
									<td valign="top">
										<input type="radio" value="same" id="awp_jobs_samepage" name="awp_jobsform_confirm_msg_page" <?php checked('same',$formproperties['confirm_msg_page']); ?> checked="checked" />
										<label for="awp_jobs_samepage">Same Page</label>
										<input type="radio" value="other" id="awp_jobs_otherpage" name="awp_jobsform_confirm_msg_page" <?php checked('other',$formproperties['confirm_msg_page']); ?>/>
										<?php // print_r($formproperties); ?>
										<label for="awp_jobs_otherpage">Other page</label>
										<br />
										<br />
										<select id="awp_jobsform_confirmmsg_pageid" name="awp_jobsform_confirmmsg_pageid" <?php if($formproperties['confirm_msg_page'] != 'other') echo 'style="display:none;"';?> >
											<?php
											$pages = get_pages();
											foreach ($pages as $pagg) {
												?>
												<option value="<?php echo esc_attr($pagg->ID); ?>"  <?php selected($pagg->ID, $formproperties['confirm_msg_pageid']); ?> >
																		<?php echo esc_attr($pagg->post_title); ?>
												</option>
												<?php
											}
											?>
											</select>

									</td>
														
									</td>
								</tr>

								<tr valign="top" id="awp_jobsform_confirmationmsg_tr" <?php if($formproperties['confirm_msg_page'] == 'other') echo 'style="display:none;"';?> >
									<th valign="top"> <label for="awp_jobsform_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label> <br> <span class="description">This message will shown in your website page, once contact form submitted.</span>
									</th>
									<td valign="top">
										<div style="width:620px;">
										<?php 
											//the_editor($formproperties['confmsg'],'awp_jobsform_confirmationmsg','',FALSE);
											wp_editor($formproperties['confmsg'], 'awp_jobsform_confirmationmsg', array());
										?>
										</div>
									</td>
								</tr>

								<tr valign="top">
									<th><label for="awp_jobsform_customcss"> <?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
									<br> <span valign="top" class="description"><?php _e("Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.", 'apptivo-businesssite' ); ?></span>
									</th>
									<td valign="top"><textarea name="awp_jobsform_customcss"
										style="width: 350px;" id="awp_jobsform_customcss" size="100"
										cols="40" rows="10"><?php echo esc_attr($formproperties['css']);?></textarea> <span
										style="margin: 10px;">*Developers Guide - <a
										href="<?php echo awp_developerguide('job-applicant-customcss');?>"
										target="_blank">Job Applicant Form CSS.</a></span>
									</td>
								</tr>

								<tr valign="top">
									<th><label id="awp_jobsform_submit_type"
										for="awp_jobsform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
									<br>
									<span valign="top" class="description"></span></th>

									<td valign="top"><input type="radio" value="submit"
										id="awp_jobs_cant_btn" name="awp_jobsform_submit_type"
										<?php checked('submit',$formproperties['submit_button_type']); ?>
										checked="checked" /> <label for="awp_jobs_cant_btn">Button</label> <input
										type="radio" value="image" id="awp_jobs_cant_img"
										name="awp_jobsform_submit_type"
										<?php checked('image',$formproperties['submit_button_type']); ?> /> <label
										for="awp_jobs_cant_img">Image</label>
									</td>
								</tr>

								<tr valign="top">
									<th><label for="awp_jobsform_submit_val"
										id="awp_jobsform_submit_value"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
									<br>
									<span valign="top" class="description"></span></th>
									<td valign="top">
										<input type="text" name="awp_jobsform_submit_val" id="awp_jobsform_submit_val" value="<?php echo esc_attr($formproperties['submit_button_val']);?>" size="52" />
										<span id="japp_upload_img_button" style="display: none;">
											<input id="japplicant_upload_image" type="button" value="Upload Image"  class="button-primary"/> <br />
											<?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
										</span>
									</td>
								</tr>

							</tbody>
						</table>

						<br>
					<?php
						echo "<h3>" . __( 'Job Applicant Form Fields', 'apptivo-businesssite' ) . "</h3>";?>
						<div style="amrgin: 10px;"><span class="description"><?php _e("Select and configure list of fields from below table to show in your jobs form.", 'apptivo-businesssite' ); ?></span> <span style="margin: 10px;">*Developers Guide - <a href="<?php echo awp_developerguide('job-applicant-basicconfig');?>" target="_blank">Basic Job Applicant Form Config.</a></span></div>

						<br>

						<table width="900" cellspacing="0" cellpadding="0" id="hrjobs_form_fields" name="hrjobs_form_fields" style="border-collapse: collapse;">
							<tbody>
								<tr>
									<th></th>
								</tr>
								<tr align="center" style="background-color: rgb(223, 223, 223); font-weight: bold;" class="widefat">
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?></td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?></td>
								</tr>
								<tr>
									<th></th>
								</tr>
						<?php
							$pos = 0;
							$index_key = 0;
							foreach( $this->get_master_fields() as $fieldsmasterproperties ){
								$enabled=0;
								$fieldExists=array();
								$fieldid=$fieldsmasterproperties['fieldid'];
								$fieldExistFlag="";

								if(!empty($fields)){
									$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');
								}

								if(trim($fieldExistFlag)!==""){
									$enabled=1;
									$fieldData=array("fieldid"=>$fieldid,
													"fieldname"=>$fieldsmasterproperties['fieldname'],
													"show"=>$enabled,
													"required"=>$fields[$fieldExistFlag]['required'],
													"showtext"=>$fields[$fieldExistFlag]['showtext'],
													"type"=>$fields[$fieldExistFlag]['type'],
													"validation"=>$fields[$fieldExistFlag]['validation'],
													"options"=>$fields[$fieldExistFlag]['options'],
													"order"=>$fields[$fieldExistFlag]['order']
												);
								}else{
									if($fieldid=='lastname' || $fieldid=='email' || $fieldid=='firstname' || $fieldid=='country'){
										$enabled =1;
										$required =1;

									}
									$required = (!empty($required)) ? $required : 0;
									$fieldData=array("fieldid"=>$fieldid,
													"fieldname"=>$fieldsmasterproperties['fieldname'],
													"show"=>$enabled,
													"required"=>$required,
													"showtext"=>$fieldsmasterproperties['defaulttext'],
													"type"=>"",
													"validation"=>"",
													"options"=>"",
													"order"=>""
												);
								}

								$pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
									?>
								<tr>
									<td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;"><?php echo esc_attr($fieldData['fieldname'])?>
									</td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);">
										<input <?php  if($enabled) { ?> checked="checked" <?php } if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email' || $fieldData['fieldid']=='firstname' || $fieldData['fieldid']=='country'){ ?> disabled="disabled" <?php } ?> type="checkbox" id="<?php echo esc_attr($fieldData['fieldid'])?>_show" name="<?php echo esc_attr($fieldData['fieldid'])?>_show" size="30" onclick="hrjobsform_enablefield('<?php echo esc_attr($fieldData['fieldid'])?>')">
										<?php if( $index_key > 20 ): ?>
										<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_newest" name="<?php echo esc_attr($fieldData['fieldid'])?>_newest" value="" />
										<?php
											endif;
											$index_key++;
										?>
									</td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);">
										<input <?php if(!$enabled){ ?> disabled="disabled" <?php } elseif( $fieldData['required'] ){ ?> checked="checked" <?php }?> type="checkbox" <?php if( $fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'|| $fieldData['fieldid']=='firstname' || $fieldData['fieldid']=='country' ){ ?> disabled="disabled" <?php } ?> id="<?php echo esc_attr($fieldData['fieldid'])?>_require" name="<?php echo esc_attr($fieldData['fieldid'])?>_require" size="30">
									</td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);">
										<input type="text" onkeypress="return isNumberKey(event)" id="<?php echo esc_attr($fieldData['fieldid'])?>_order" name="<?php echo esc_attr($fieldData['fieldid'])?>_order" value="<?php echo esc_attr($fieldData['order']); ?>" size="3" maxlength="2" <?php if(!$enabled){ ?> disabled="disabled" <?php } ?>>
									</td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);">
										<input <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> type="text" id="<?php echo esc_attr($fieldData['fieldid'])?>_text" name="<?php echo esc_attr($fieldData['fieldid'])?>_text" value="<?php echo esc_attr($fieldData['showtext']); ?>">
									</td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);">
										<?php 
											$name_postfix="type";
											if($pos===false){ ?>
											
										<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_type" name="<?php echo esc_attr($fieldData['fieldid'])?>_type" <?php if($fieldid=="upload"){ echo 'value="file"' ; } elseif($fieldid=="country" || $fieldid == 'industry'){ ?> value="select"<?php }elseif($fieldid=="comments" || $fieldid=="coverletter" || $fieldid=="Skills"){ ?> value="textarea" <?php }else{ ?> value="text" <?php }?>>
										<input <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly" type="text" id="<?php echo esc_attr($fieldData['fieldid'])?>_typehiddentext" name="<?php echo esc_attr($fieldData['fieldid'])?>_typehiddentext" <?php if($fieldid=="upload"){ echo 'value="File"'; }elseif($fieldid=="country" || $fieldid == 'industry'){ ?> value="Select" <?php }else if($fieldid=="comments"){ ?> value="Textarea" <?php }else{ ?> value="Textbox" <?php }?>>
										<?php $name_postfix="type_select"; }else{ ?>
										<select name="<?php echo esc_attr($fieldData['fieldid'])?>_type" id="<?php echo esc_attr($fieldData['fieldid'])?>_type" <?php if($pos===false){ ?> readonly="readonly" <?php }if(!$enabled || ($pos===false)){ ?> disabled="disabled"<?php } ?> onChange="hrjobsform_showoptionstextarea('<?php echo esc_attr($fieldData['fieldid'])?>');"> <?php foreach( $this->get_master_fieldtypes() as $masterfieldtypes ){ ?>
											<option value="<?php echo esc_attr($masterfieldtypes['fieldtype']);?>" <?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?> selected="selected" <?php }?>> <?php echo esc_attr($masterfieldtypes['fieldtypeLabel']);?> </option> <?php }?>

										</select>
										<?php }	?>
									</td>
									<td align="center" style="border: 1px solid rgb(204, 204, 204);">
										<?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield"); 
										if($pos===false){ ?>
											<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_validation" name="<?php echo esc_attr($fieldData['fieldid'])?>_validation" <?php if($fieldid=="email"){ ?> value="email"
												<?php }else if($fieldid=="telephonenumber"){ ?> value="number"
												<?php }else{ ?> value="none" <?php }?>>
											<input <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly" type="text" id="<?php echo esc_attr($fieldData['fieldid'])?>_validationhidden" name="<?php echo esc_attr($fieldData['fieldid'])?>_validationhidden" <?php if($fieldid=="email"){
												?> value="Email Id"
												<?php }elseif($fieldid=="telephonenumber"){ ?> value="Number"
												<?php }else{ ?> value="None" <?php }?>> 
										<?php }else{ ?>				
											<select name="<?php echo esc_attr($fieldData['fieldid'])?>_validation" id="<?php echo esc_attr($fieldData['fieldid'])?>_validation" <?php if(!$enabled){ ?> disabled="disabled" <?php }if($pos===false){ ?> readonly="readonly" <?php } ?>>
												<?php foreach( $this->get_master_validations() as $masterfieldtypes ){ ?>
												<option value="<?php echo esc_attr($masterfieldtypes['validation']);?>" <?php if($masterfieldtypes['validation']==$fieldData['validation']){ ?> selected="selected" <?php }?>>
												<?php echo esc_attr($masterfieldtypes['validationLabel']);?></option> <?php }?>
											</select>
										<?php } ?>
									</td>

									<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
									if($pos===false){
										if($fieldData['fieldid']!='industry')
										echo "N/A";
										//Not a custom field. Dont show any thing
									}

									if($fieldData['fieldid'] == 'industry'){
										$fieldData['options'] = ($fieldData['options']) ? $fieldData['options'] : array();
										?>
										<select id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_options[]" size="4"
										multiple="multiple" style="height: 75px;">
										<?php //$allIndustries = getAllIndustriesV6()->industries;
											foreach($allIndustries as $industries){ ?>
											<option <?php if( in_array($industries->industryId.'::'.$industries->industryName, $fieldData['options'])){ echo 'selected="selected"';}  ?> value="<?php echo html_entity_decode(esc_attr($industries->industryId)).'::'.html_entity_decode(esc_attr($industries->industryName));?>"><?php echo html_entity_decode(esc_attr($industries->industryName));?></option>
											<?php } ?>
										</select>
									<?php }else if($fieldData['fieldid'] != 'country'){
										if(($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")){
											$fieldData['options'] = trim($fieldData['options']); ?>
											<textarea style="width: 190px;" <?php if(!$enabled){ ?> disabled="disabled" <?php } ?> id="<?php echo esc_attr($fieldData['fieldid'])?>_options" name="<?php echo esc_attr($fieldData['fieldid']) ?>_options"> <?php echo esc_attr($fieldData['options']); ?> </textarea>
										<?php }else{ ?>
											<textarea disabled="disabled"style="display: none; width: 190px;" id="<?php echo esc_attr($fieldData['fieldid'])?>_options" name="<?php echo esc_attr($fieldData['fieldid'])?>_options"></textarea>
										<?php } ?>

									</td>
								</tr>
									<?php }
							} ?>

							</tbody>
						</table>
		<?php
		$addtional_custom = get_option('awp_addtional_custom_jobapplicant');
		if(empty($addtional_custom))
		{
			$cnt_custom_filed = 6;
		}else {
			$cnt_custom_filed = 6 + count($addtional_custom);
		}
		?>
<p><a rel="<?php echo esc_attr($cnt_custom_filed); ?>" href="javascript:void(0);"
	id="job_addcustom_field" name="job_addcustom_field">+Add Another Custom
Field</a></p>
<p class="submit"><input
<?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; } ?>
	type="submit" name="awp_jobsform_settings" id="awp_jobsform_settings"
	class="button-primary"
	value="<?php esc_attr_e('Save Configuration') ?>" /></p>
</form>

</div>
<?php
			}

	}

	/**
	 * Job Configuration.
	 */
	function jobconfiguration()
	{
		?>
<div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('jobs_icon'); ?>') " ><br>
</div>
<h2 class="nav-tab-wrapper"><a class="nav-tab"
	href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs"><?php _e('Jobs','apptivo-businesssite'); ?></a>
<a class="nav-tab nav-tab-active"
	href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs&keys=configuration"><?php _e('Configuration','apptivo-businesssite'); ?></a>
<a class="nav-tab"
	href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs&keys=jobsearch"><?php _e('Job search','apptivo-businesssite'); ?></a>
</h2>
		<?php
		if(!$this->_plugin_activated) :
		echo "Jobs Plugin is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
		endif;
		?>
<div style="margin-top: 30px;"><?php 
$_GET['step'] = (array_key_exists('step',$_GET)) ? sanitize_text_field($_GET['step']) : null;
if($_GET['step'] == 1 || !isset($_GET['step']) || $_GET['step'] != 2 )
{
	echo '<a class="nav-tab-active" >Jobs Settings</a><span style="margin:0px 25px 0px 25px;"></span>';
	echo '<a class="" href="'.SITE_URL.'/wp-admin/admin.php?page=awp_jobs&keys=configuration&step=2">Jobs Applicant form</a>';
	$this->descriptionSettings();
}
if($_GET['step'] == 2)
{
	echo '<a class="" href="'.SITE_URL.'/wp-admin/admin.php?page=awp_jobs&keys=configuration">Jobs Settings</a><span style="margin:0px 25px 0px 25px;"></span>';
	echo '<a class="nav-tab-active">Jobs Applicant form</a>';
	$this->jobApplicant();
}
?></div>

<?php
	}
	/**
	 * Job description page settings.
	 *
	 */
	function descriptionSettings()
	{
		//To find applicant page
		$applicantformName = 'jaform';
		$_POST['awp_joblists_settings'] = (array_key_exists('awp_joblists_settings',$_POST)) ? sanitize_text_field($_POST['awp_joblists_settings']) : '';
		if($_POST['awp_joblists_settings'])
		{
			$_POST['awp_joblist_itemsperpage'] = (array_key_exists('awp_joblist_itemsperpage',$_POST)) ? sanitize_text_field($_POST['awp_joblist_itemsperpage']) : null;
			if($_POST['awp_joblist_itemsperpage'] == '' || $_POST['awp_joblist_itemsperpage'] == 0)
			{
				$itemsperpage = 999;
			}else {
				$itemsperpage = sanitize_text_field($_POST['awp_joblist_itemsperpage']);
			}
			$_POST['awp_joblist_readmoretext'] = (array_key_exists('awp_joblist_readmoretext',$_POST)) ? sanitize_text_field($_POST['awp_joblist_readmoretext']) : null;
			if($_POST['awp_joblist_readmoretext'] == '' )
			{
				$readmoreText = 'Read More ..';
			}
			else {
				$readmoreText = $_POST['awp_joblist_readmoretext'];
			}

			//Job list template name
			if(sanitize_text_field($_POST['awp_joblists_templatetype'])=="theme_template") :
			$joblist_template = sanitize_text_field($_POST['awp_joblists_theme_template']);
			else :
			$joblist_template = sanitize_text_field($_POST['awp_joblist_template']);
			endif;
				
			//Job Description template Name.
			if(sanitize_text_field($_POST['awp_jobdesc_templatetype'])=="theme_template") :
			$jobdesc_template = sanitize_text_field($_POST['awp_jobdesc_theme_template']);
			else :
			$jobdesc_template = sanitize_text_field($_POST['awp_jobdesc_template']);
			endif;
				

			$jobs_settings_post = array('description_page' => sanitize_text_field($_POST['awp_joblist_descriptionpage']),
			                       'submit_type' => sanitize_text_field($_POST['awp_joblist_submit_type']),
                                   'submit_val' =>sanitize_text_field($_POST['awp_joblist_submit_value']),
			                       'applicant_form' =>  $applicantformName,
								   'desc_template_name' => $jobdesc_template,
								   'jobdescription_template_type' => sanitize_text_field($_POST['awp_jobdesc_templatetype']),
								   'list_template_name' => $joblist_template,
								   'joblist_template_type' => sanitize_text_field($_POST['awp_joblists_templatetype'])
			);

			update_option('awp_jobs_settings',$jobs_settings_post);
		}
		$jobs_settings = get_option('awp_jobs_settings');
		?>
		<?php
		if(isset($jobs_settings['description_page']) && strlen(trim($jobs_settings['description_page'])) != 0 ){ ?>
<p>Copy and Paste this short code in Job Description Page : <input
	type="text" readonly="readonly" id="job_description_shortcode"
	name="job_description_shortcode" value="[apptivo_job_description]" /></p>
		<?php } else { echo '<p style="color:#f00;">Save the below settings to get the Shortcode for job description.</p>'; } ?>
<form action="" method="post" name="awp_joblists_settings_form">
<table class="form-table">
	<tbody>

		<tr valign="top">
			<th><label id="awp_jobsearchform_submit_type"
				for="awp_joblist_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"></span></th>

			<td valign="top"><input type="radio" id="awp_jlists_btn"
				value="submit" name="awp_joblist_submit_type"
				<?php checked('submit',$jobs_settings['submit_type']); ?>
				checked="checked" /> <label for="awp_jlists_btn">Button</label> <input
				type="radio" value="image" id="awp_jlists_img"
				name="awp_joblist_submit_type"
				<?php checked('image',$jobs_settings['submit_type']); ?> /> <label
				for="awp_jlists_img">Image</label></td>
		</tr>
		<tr valign="top">
			<th><label for="awp_joblist_submit_val" id="awp_joblist_submit_val"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"></span></th>
			<td valign="top"><input style="width: 360px;" type="text"
				name="awp_joblist_submit_value" id="awp_joblist_submit_value"
				value="<?php echo esc_attr($jobs_settings['submit_val']);?>" size="52" /> <span
				id="jlist_upload_img_button" style="display: none;"> <input
				id="jlist_upload_image" type="button" value="Upload Image"  class="button-primary"/> <br />
				<?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
			</span></td>
		</tr>

		<tr valign="top">
			<th valign="top"><label for="awp_joblist_targetpage"><?php _e('Job Description Page:','apptivo-businesssite'); ?></label>
			</th>
			<td valign="top"><select id="awp_joblist_descriptionpage"
				name="awp_joblist_descriptionpage">
				<?php
				$pages = get_pages();
				foreach ($pages as $pagg) {
					?>
				<option value="<?php echo esc_attr($pagg->ID); ?>"
				<?php selected($pagg->ID, $jobs_settings['description_page']); ?>><?php echo esc_attr($pagg->post_title); ?>
				</option>
				<?php
				}
				?>
			</select></td>
		</tr>
		<!-- =============================== Job Description Templates types ====================================== -->
		<tr valign="top">
			<th valign="top"><label for="awp_jobdescription_templatetype"><?php _e('Job Description Template Type:','apptivo-businesssite'); ?></label>
			</th>
			<td valign="top"><?php $awp_jobdesc_themetemplates = get_awpTemplates(TEMPLATEPATH.'/jobs/jobdescription','Plugin');     ?>

			<select name="awp_jobdesc_templatetype" id="awp_jobdesc_templatetype">
				<option value="awp_plugin_template"
				<?php selected($jobs_settings['jobdescription_template_type'],'awp_plugin_template'); ?>><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
				<?php if(!empty($awp_jobdesc_themetemplates)) : ?>
				<option value="theme_template"
				<?php selected($jobs_settings['jobdescription_template_type'],'theme_template'); ?>>Templates
				from Current Theme</option>
				<?php endif; ?>
			</select></td>
		</tr>
		<!-- =============================== Job Description Templates ====================================== -->
		<tr valign="top">
			<th valign="top"><label for="awp_joblist_targetpage"><?php _e('Job Description Template:','apptivo-businesssite'); ?></label>
			</th>
			<td valign="top"><?php
			//Job Description Templates
			$plugintemplates =get_awpTemplates(AWP_JOBDESCRIPTION_TEMPLATEPATH,'plugin');

			?> <select name="awp_jobdesc_template" id="awp_jobdesc_template"
			<?php if($jobs_settings['jobdescription_template_type'] == 'theme_template' ) echo 'style="display:none;"'; ?>>
				<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
				<option value="<?php echo esc_attr($plugintemplates[$template])?>"
				<?php selected($plugintemplates[$template], $jobs_settings['desc_template_name']); ?>>
					<?php echo esc_attr($template)?></option>
					<?php }?>
			</select> <select name="awp_jobdesc_theme_template"
				id="awp_jobdesc_theme_template"
				<?php if($jobs_settings['jobdescription_template_type'] != 'theme_template' ) echo 'style="display:none;"'; ?>>
				<?php foreach (array_keys($awp_jobdesc_themetemplates) as $template) : ?>
				<option value="<?php echo esc_attr($awp_jobdesc_themetemplates[$template]) ?>"
				<?php selected($awp_jobdesc_themetemplates[$template],$jobs_settings['desc_template_name']); ?>>
					<?php echo esc_attr($template) ?></option>
					<?php endforeach; ?>
			</select></td>
		</tr>

		<!-- =============================== Job Listing Templates types ====================================== -->
		<tr valign="top">
			<th valign="top"><label for="awp_listing_templatetype"><?php _e('Job Listing Template Type:','apptivo-businesssite'); ?></label>
			</th>
			<td valign="top"><?php $awp_joblists_themetemplates = get_awpTemplates(TEMPLATEPATH.'/jobs/joblists','Plugin');  ?>

			<select name="awp_joblists_templatetype"
				id="awp_joblists_templatetype">
				<option value="awp_plugin_template"
				<?php selected($jobs_settings['joblist_template_type'],'awp_plugin_template'); ?>><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
				<?php if(!empty($awp_joblists_themetemplates)) : ?>
				<option value="theme_template"
				<?php selected($jobs_settings['joblist_template_type'],'theme_template'); ?>>Templates
				from Current Theme</option>
				<?php endif; ?>
			</select></td>
		</tr>

		<!-- =============================== Job Listing Templates ====================================== -->

		<tr valign="top">
			<th valign="top"><label for="awp_joblist_targetpage"><?php _e('Job Listing Template:','apptivo-businesssite'); ?></label>
			</th>
			<td valign="top"><?php $plugintemplates =get_awpTemplates(AWP_JOBLISTS_TEMPLATEPATH,'plugin');  ?>
			<select name="awp_joblist_template" id="awp_joblist_template"
			<?php if($jobs_settings['joblist_template_type'] == 'theme_template'  ) echo 'style="display:none;"'; ?>>
				<?php foreach (array_keys( $plugintemplates ) as $template ) : ?>
				<option value="<?php echo esc_attr($plugintemplates[$template])?>"
				<?php selected($plugintemplates[$template], $jobs_settings['list_template_name']); ?>>
					<?php echo esc_attr($template)?></option>
					<?php endforeach; ?>
			</select> <select name="awp_joblists_theme_template"
				id="awp_joblists_theme_template"
				<?php if($jobs_settings['joblist_template_type'] != 'theme_template' ) echo 'style="display:none;"'; ?>>
				<?php foreach (array_keys($awp_joblists_themetemplates) as $template) : ?>
				<option
					value="<?php echo esc_attr($awp_joblists_themetemplates[$template]) ?>"
					<?php selected($awp_joblists_themetemplates[$template], $jobs_settings['list_template_name']); ?>>
					<?php echo esc_attr($template) ?></option>
					<?php endforeach; ?>
			</select></td>
		</tr>



	</tbody>
</table>


<p class="submit"><input
<?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; } ?>
	type="submit"
	value="<?php _e('Save Configuration','apptivo-businesssite'); ?>"
	class="button-primary" id="awp_joblists_settings"
	name="awp_joblists_settings"></p>
</form>


<?php

	}

	/**
	 * Job search form
	 *
	 */
	function jobsearch(){ ?>

		<div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('jobs_icon'); ?>') " ><br>
		</div>
		<h2 class="nav-tab-wrapper"> <a class="nav-tab" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs"><?php _e('Jobs','apptivo-businesssite'); ?></a>
		<a class="nav-tab" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs&keys=configuration"><?php _e('Configuration','apptivo-businesssite'); ?></a>
		<a class="nav-tab nav-tab-active" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_jobs&keys=jobsearch"><?php _e('Job Search','apptivo-businesssite'); ?></a>
		</h2>
		<?php
		if(!$this->_plugin_activated){
			_e("Jobs Plugin is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.",'apptivo-businesssite');
		}
		?>
		<?php
		$updatemessage="";

		$hrjobs_forms=array();
		$hrjobsformdetails=array();
		$hrjobs_forms=get_option('awp_jobsearchforms');

		if(empty($hrjobs_forms)){
			$newhrjobsformname_array =array("name"=>'jsform');
			$newhrjobsform=array($newhrjobsformname_array);

			update_option('awp_jobsearchforms',$newhrjobsform);
			$hrjobs_forms=get_option('awp_jobsearchforms');
		}

		/*
		 * Saving selected form settings
		 */
		if(!empty($_POST['awp_jobsearchform_settings'])){
			$templatelayout="";
			$newformname=sanitize_text_field($_POST['awp_jobsearchform_name']);

			if(sanitize_text_field($_POST['awp_jobsearchform_templatetype'])=="awp_plugin_template")
			$templatelayout=sanitize_text_field($_POST['awp_jobsearchform_plugintemplatelayout']);
			else
			$templatelayout=sanitize_text_field($_POST['awp_jobsearchform_themetemplatelayout']);

			$_POST['awp_jobsearchform_confirmationmsg'] = (array_key_exists('awp_jobsearchform_confirmationmsg',$_POST)) ? sanitize_text_field($_POST['awp_jobsearchform_confirmationmsg']) : '';
			$_POST['subscribe_option'] = (array_key_exists('subscribe_option',$_POST)) ? sanitize_text_field($_POST['subscribe_option']) : '';
			$_POST['awp_target_pageurl'] = (array_key_exists('awp_target_pageurl',$_POST)) ? sanitize_text_field($_POST['awp_target_pageurl']) : '';
			$_POST['awp_jobapplicant_pageurl'] = (array_key_exists('awp_jobapplicant_pageurl',$_POST)) ? sanitize_text_field($_POST['awp_jobapplicant_pageurl']) : '';
			$_POST['keywords_options'] = (array_key_exists('keywords_options',$_POST)) ? sanitize_text_field($_POST['keywords_options']) : '';

			$hrjobsformproperties=array(
				'tmpltype' =>sanitize_text_field($_POST['awp_jobsearchform_templatetype']),
				'layout' =>$templatelayout,
				'confmsg' => stripslashes(sanitize_text_field($_POST['awp_jobsearchform_confirmationmsg'])),
				'css' => stripslashes(sanitize_text_field($_POST['awp_jobsearchform_customcss'])),
				'subscribe_option' => sanitize_text_field($_POST['subscribe_option']),
				'submit_button_type' => sanitize_text_field($_POST['awp_jobsearchform_submit_type']),
				'submit_button_val' => sanitize_text_field($_POST['awp_jobsearchform_submit_value']),
				'target_pageurl' => sanitize_text_field($_POST['awp_target_pageurl']),
				'jobapplicant_pageurl' => sanitize_text_field($_POST['awp_jobapplicant_pageurl'])
			);
				
			$hrjobsformfields=array();
			foreach( $this->get_master_fieldsfor_searchjobs() as $fieldsmasterproperties ){
				$enabled=0;
				$hrjobsformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];

				if(!empty ($_POST[$fieldid.'_order'])){
					$displayorder = sanitize_text_field($_POST[$fieldid.'_order']);
				}else{
					$displayorder = $fieldsmasterproperties['showorder'];
				}

				if(!empty ($_POST[$fieldid.'_text'])){
					$displaytext = sanitize_text_field($_POST[$fieldid.'_text']);
				}else{
					$displaytext = $fieldsmasterproperties['defaulttext'];
				}

				$enabled = (isset($_POST[$fieldid.'_show'])) ? sanitize_text_field($_POST[$fieldid.'_show']) : '';
				if($enabled){
					$hrjobsformfield=$this->createformfield_array($fieldid,$displaytext,'',sanitize_text_field($_POST[$fieldid.'_type']),'',sanitize_textarea_field($_POST[$fieldid.'_options']),$displayorder);
					array_push($hrjobsformfields, $hrjobsformfield);
						
				}
			}
				
				
			if(!empty($hrjobsformfields)){
				$newhrjobsformdetails=array('name'=>$newformname,'properties'=>$hrjobsformproperties,'fields'=>$hrjobsformfields);
				$formExists="";
				if(!empty($hrjobs_forms))
					$formExists = awp_recursive_array_search($hrjobs_forms,$newformname,'name' );
					if(trim($formExists)!=="" ){
						unset($hrjobs_forms[$formExists]);
						array_push($hrjobs_forms, $newhrjobsformdetails);
						sort($hrjobs_forms);
						
						update_option('awp_jobsearchforms',$hrjobs_forms);
						$hrjobs_forms=get_option('awp_jobsearchforms');
						$updatemessage= "Job Search Form '".$newformname."' settings updated. Use Short code '[apptivo_job_searchform name=\"".$newformname."\"]' in your page to use this form.";
					}
			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for job search Form.</span>";
			}
			$selectedhrjobsform=$newformname;
		}

		// Now display the settings editing screen
		echo '<div class="wrap">';
		// header
		//if updatemessage is not empty display the div
		if(trim($updatemessage)!=""){ ?>
			<div id="message" style="width: 80%;" class="updated">
			<p><?php echo esc_attr($updatemessage);?></p>
			</div>
		<?php }

		//get the count of total hrjobs forms created
		$hrjobsformscount=0;

		if(!empty($hrjobs_forms)){

			//Template Files
			$themetemplates  = get_awpTemplates(TEMPLATEPATH.'/jobs/jobsearch','Plugin'); //Job applicant theme template

			$plugintemplates = get_awpTemplates(AWP_JOBSEARCHFORM_TEMPLATEPATH,'plugin'); //Job applicant form plugin templates


			?>
			<br>

			<?php
			$selectedhrjobsform = (!empty($selectedhrjobsform)) ? $selectedhrjobsform : '';
			if(trim($selectedhrjobsform)==""){
				$selectedhrjobsform=$hrjobs_forms[0]['name'];
			}
			$hrjobsformdetails=$this->get_settings($selectedhrjobsform,'jobsearch');
				
			if(count($hrjobsformdetails)>0){
				$selectedhrjobsform=$hrjobsformdetails['name'];
				$fields=$hrjobsformdetails['fields'];
				$formproperties=$hrjobsformdetails['properties'];
			}
			?>
				<?php if(!empty($formproperties)){ ?>
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th valign="top"><label for="awp_jobsearchform_select_form"><?php _e("Job Search Form", 'apptivo-businesssite' ); ?>:</label>
								</th>
								<td valign="top"><input style="width: 360px;" type="text"
									readonly="readonly" id="awp_jobsearchform_select_form"
									name="awp_jobsearchform_select_form"
									value="<?php echo esc_attr($selectedhrjobsform); ?>" /></td>

							</tr>

							<tr valign="top">
								<th valign="top"><label for="awp_customform_shortcode"><?php _e("Form Shortcode", 'awp_jobsearchform' ); ?>:</label>
								<br>
								<span class="description"><?php _e('Copy and Paste this short code in your page to display the job search form.', 'apptivo-businesssite' ); ?></span>
								</th>
								<td valign="top"><span id="awp_customform_shortcode"
									name="awp_customform_shortcode"> <input style="width: 360px;"
									type="text" readonly="readonly" id="job_searchform_shortcode"
									name="job_searchform_shortcode"
									value='[apptivo_job_searchform name="<?php echo esc_attr($selectedhrjobsform)?>"]' />
								</span> <span style="margin: 10px;">*Developers Guide - <a
									href="<?php echo awp_developerguide('job-searchform-shortcode');?>"
									target="_blank">Job Search Form Shortcodes.</a></span></td>
							</tr>

						</tbody>
					</table>
				<?php }else{
					echo '<span style="color: rgb(255, 0, 0);line-height:24px;"> Save the below settings to get the Shortcode for job search form. </span>';
				} ?>

				<form name="awp_jobsearch_settings_form" method="post" action="">
					<table class="form-table">
						<tbody>

							<tr valign="top">
								<th valign="top"><label for="awp_jobsearchform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
								</th>
								<td valign="top"><input type="hidden" id="awp_jobsearchform_name"
									name="awp_jobsearchform_name"
									value="<?php echo esc_attr($selectedhrjobsform);?>"> <select
									name="awp_jobsearchform_templatetype"
									id="awp_jobsearchform_templatetype"
									onchange="change_searchform_Template();">
									<option value="awp_plugin_template"
									<?php selected($formproperties['tmpltype'],'awp_plugin_template'); ?>>Plugin
									Templates</option>
									<?php if(!empty($themetemplates)) :?>
									<option value="theme_template"
									<?php selected($formproperties['tmpltype'],'theme_template'); ?>>Templates
									from Current Theme</option>
									<?php endif; ?>
								</select> <span style="margin: 10px;">*Developers Guide - <a
									href="<?php echo awp_developerguide('job-searchform-template');?>"
									target="_blank">Job Search Form Templates.</a></span></td>
							</tr>
							<tr valign="top">
								<th valign="top"><label for="awp_jobsearchform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
								<br>
								<span class="description"><?php _e("Selecting Theme template which doesnt support jobs search form structure will wont show the jobs form in webpage.", 'apptivo-businesssite' ); ?></span>
								</th>
								<td valign="top"><select
									name="awp_jobsearchform_plugintemplatelayout"
									id="awp_jobsearchform_plugintemplatelayout"
									<?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?>>
									<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
									<option value="<?php echo esc_attr($plugintemplates[$template])?>"
									<?php selected($formproperties['layout'],$plugintemplates[$template]); ?>>
										<?php echo esc_attr($template); ?></option>
										<?php }?>
								</select> <select name="awp_jobsearchform_themetemplatelayout"
									id="awp_jobsearchform_themetemplatelayout"
									<?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?>>
									<?php foreach (array_keys( $themetemplates ) as $template ){ ?>
									<option value="<?php echo esc_attr($themetemplates[$template])?>"
									<?php selected($formproperties['layout'],$themetemplates[$template]);?>>
										<?php echo esc_attr($template); ?></option>
										<?php }?>
								</select></td>
							</tr>

							<tr valign="top">
								<th><label for="awp_jobsearchform_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
								<br>
								<span valign="top" class="description"><?php _e('Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.', 'apptivo-businesssite' ); ?></span>
								</th>
								<td valign="top"><textarea style="width: 360px;"
									name="awp_jobsearchform_customcss" id="awp_jobsearchform_customcss"
									size="100" cols="40" rows="10"><?php echo esc_attr($formproperties['css']);?></textarea>
								<span style="margin: 10px;">*Developers Guide - <a
									href="<?php echo awp_developerguide('job-searchform-customcss');?>"
									target="_blank">Job Search Form CSS.</a></span></td>
							</tr>
							<tr valign="top">
								<th><label id="awp_jobsearchform_submit_type"
									for="awp_jobsearchform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
								<br>
								<span valign="top" class="description"></span></th>

								<td valign="top"><input type="radio" id="awp_jsrch_btn"
									value="submit" name="awp_jobsearchform_submit_type"
									<?php checked('submit',$formproperties['submit_button_type']); ?>
									checked="checked" /> <label for="awp_jsrch_btn">Button</label> <input
									type="radio" id="awp_jsrch_img" value="image"
									name="awp_jobsearchform_submit_type"
									<?php checked('image',$formproperties['submit_button_type']); ?> /> <label
									for="awp_jsrch_img">Image</label></td>
							</tr>
							<tr valign="top">
								<th><label for="awp_jobsearchform_submit_val"
									id="awp_jobsearchform_submit_val"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
								<br>
								<span valign="top" class="description"></span></th>
								<td valign="top"><input style="width: 360px;" type="text"
									name="awp_jobsearchform_submit_value"
									id="awp_jobsearchform_submit_value"
									value="<?php echo esc_attr($formproperties['submit_button_val']);?>" size="52" />
								<span id="jsearch_upload_img_button" style="display: none;"> <input
									id="jsearch_upload_image" type="button" value="Upload Image"  class="button-primary"/> <br />
									<?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
								</span></td>
							</tr>

						</tbody>
					</table>
					<br>
				<?php echo "<h3>" . __( 'Job Search Form Fields', 'apptivo-businesssite' ) . "</h3>"; ?>
				<div style="margin: 10px;"> <span class="description"><?php _e('Select and configure list of fields from below table to show in your hrjobs form.','apptivo-businesssite');?></span> <span style="margin: 10px;">*Developers Guide - <a href="<?php echo awp_developerguide('job-searchform-basicconfig');?>" target="_blank">Basic Job Search Form Config.</a> </span> </div>

				<table width="900" cellspacing="0" cellpadding="0" id="hrjobs_form_fields" name="hrjobs_form_fields" style="border-collapse: collapse;">
					<tbody>
						<tr>
							<th></th>
						</tr>
						<tr align="center"
							style="background-color: rgb(223, 223, 223); font-weight: bold;"
							class="widefat">
							<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite');?></td>
							<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite');?></td>
							<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite');?></td>
							<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite');?></td>
							<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite');?></td>
							<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite');?></td>
						</tr>

						<tr>
							<th></th>
						</tr>
						<?php
						$pos = 0;
						foreach( $this->get_master_fieldsfor_searchjobs() as $fieldsmasterproperties ){
							$enabled=0;
							$fieldExists=array();
							$fieldid=$fieldsmasterproperties['fieldid'];
							$fieldExistFlag="";
							if(!empty($fields)){
								$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');
							}

							//echo "<pre> fields :- ";print_r($fields);echo "</pre>";
							//echo "<pre> fieldid :- ";print_r($fieldid);echo "</pre>";
							//echo "<pre> fieldExistFlag :- ";print_r($fieldExistFlag);echo "</pre>";
							
							if( trim($fieldExistFlag) !== "" ){
								$enabled=1;
								$fieldData=array("fieldid"=>$fieldid,
													"fieldname"=>$fieldsmasterproperties['fieldname'],
													"show"=>$enabled,											
													"showtext"=>$fields[$fieldExistFlag]['showtext'],
													"type"=>$fields[$fieldExistFlag]['type'],
													"options"=>$fields[$fieldExistFlag]['options'],
													"order"=>$fields[$fieldExistFlag]['order']
										);
							}else{
								if($fieldid=='lastname' || $fieldid=='email'){
									$enabled =1;
									$required =1;
								}
								if($fieldid=='Industry' || $fieldid=='JobType'){
									$enabled =1;
									$required =1;
								}
								$fieldData=array("fieldid"=>$fieldid,
													"fieldname"=>$fieldsmasterproperties['fieldname'],
													"show"=>$enabled,											
													"showtext"=>$fieldsmasterproperties['defaulttext'],
													"type"=>"",										
													"options"=>"",
													"order"=>""
											);
							}
							$pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
							?>
							<tr>
								<td style="border: 1px solid rgb(204, 204, 204); padding-left: 10px;"><?php echo esc_attr($fieldData['fieldname'])?>
								</td>

								<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
								<?php  if($enabled) { ?> checked="checked"
								<?php } if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'){?>
									disabled="disabled" <?php } ?> type="checkbox"
									id="<?php echo esc_attr($fieldData['fieldid'])?>_show"
									name="<?php echo esc_attr($fieldData['fieldid'])?>_show" size="30"
									onclick="jsearch_form_enablefield('<?php echo esc_attr($fieldData['fieldid'])?>')">
								</td>

								<td align="center" style="border: 1px solid rgb(204, 204, 204);">
									<input type="text" onkeypress="return isNumberKey(event)" id="<?php echo esc_attr($fieldData['fieldid'])?>_order" name="<?php echo esc_attr($fieldData['fieldid'])?>_order" value="<?php echo esc_attr($fieldData['order']); ?>" size="3" maxlength="2" <?php if(!$enabled){ ?> disabled="disabled" <?php } ?>>
								</td>

								<td align="center" style="border: 1px solid rgb(204, 204, 204);">
									<input <?php if(!$enabled) { ?> disabled="disabled" <?php } ?> type="text" id="<?php echo esc_attr($fieldData['fieldid'])?>_text" name="<?php echo esc_attr($fieldData['fieldid'])?>_text" value="<?php echo esc_attr($fieldData['showtext']); ?>">
								</td>

								<td align="center" style="border: 1px solid rgb(204, 204, 204);">
									<?php 
									$name_postfix="type";
									if($pos===false){
										?> <input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_type"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_type"
										<?php if($fieldid=="country"){
											?> value="select"
											<?php }else if($fieldid=="comments" || $fieldid=="coverletter" || $fieldid=="Skills"){ ?>
										value="textarea" <?php }else{?> value="text" <?php }?>> <input
										<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6"
										readonly="readonly" type="text"
										id="<?php echo esc_attr($fieldData['fieldid'])?>_typehiddentext"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_typehiddentext"
										<?php if($fieldid=="country"){
											?> value="Select"
											<?php }else if($fieldid=="comments"){ ?> value="Textarea"
											<?php }else if($fieldid=="industry"){ ?> value="Select"
											<?php }else if($fieldid=="jobtype"){ ?> value="Select"
											<?php }else{ ?> value="Text box" <?php }?>><?php
											$name_postfix="type_select";
									}else{ ?>
										<select name="<?php echo esc_attr($fieldData['fieldid'])?>_type"
											id="<?php echo esc_attr($fieldData['fieldid'])?>_type"
											<?php

											if($pos===false) {?> readonly="readonly"
											<?php }
											if(!$enabled || ($pos===false)) { ?> disabled="disabled"
											<?php } ?>
											onChange="jsearch_form_showoptionstextarea('<?php echo esc_attr($fieldData['fieldid'])?>');">
											<?php  if( $fieldData['fieldname']  == 'Industry'){ ?>
											<option value="select">Select</option>
											<?php }else{
											foreach( $this->get_master_fieldtypes_jobsearch() as $masterfieldtypes ){ ?>
												<option value="<?php echo esc_attr($masterfieldtypes['fieldtype']);?>"
												<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){ ?>
												selected="selected" <?php } ?>> <?php echo esc_attr($masterfieldtypes['fieldtypeLabel']);?> </option>
											<?php } } ?>
										</select>
									<?php } ?>
								</td>

								<td align="center" style="border: 1px solid rgb(204, 204, 204);">
								<?php
									if($pos===false){
										//echo "n/a123";
										//Not a custom field. Dont show any thing
									}

									if($fieldData['fieldid'] == 'industry'){
										$fieldData['options'] = ($fieldData['options']) ? $fieldData['options'] : array(); ?>
										<select id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_options[]" size="4"
										multiple="multiple" style="height: 75px;">
											<?php $allIndustries = getAllIndustriesV6()->industries;
											foreach($allIndustries as $industries){ ?>
												<option <?php if( in_array($industries->industryId.'::'.$industries->industryName, $fieldData['options'])){ echo 'selected="selected"'; } ?> value="<?php echo html_entity_decode(esc_attr($industries->industryId)).'::'.html_entity_decode(esc_attr($industries->industryName)); ?>"> <?php echo html_entity_decode(esc_attr($industries->industryName)); ?> </option>
											<?php } ?>
										</select>
									<?php }else if($fieldData['fieldid'] == 'jobtype'){
										$fieldData['options'] = ($fieldData['options']) ? $fieldData['options'] : array();
										$jobTypeLists = array('Full time' => 'Full Time','Part Time' => 'Part Time','Contract' => 'Contract');
										$allJobTypes = getAllStatusesTypesV6()->categories;
										?>
										<select id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_options[]" size="4"
										multiple="multiple" style="height: 60px; width: 180px;">
										<?php /*foreach($jobTypeLists  as $key => $value){ ?>
										<option <?php if( in_array($key, $fieldData['options'])){ echo 'selected="selected"'; }  ?> value="<?php echo esc_attr($key); ?>"> <?php echo esc_attr($value); ?></option>
										<?php }*/ ?>
										<?php foreach($allJobTypes as $jobType){ ?>
												<option value="<?php echo html_entity_decode(esc_attr($jobType->jobTypeId)); ?>"> <?php echo html_entity_decode(esc_attr($jobType->name)); ?> </option>
										<?php } ?>
									</select>
									<?php }else if(($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")){ ?>
									<textarea <?php if(!$enabled){ ?> disabled="disabled" <?php } ?>
										id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_options"><?php echo esc_attr($fieldData['options']); ?></textarea>
										<?php }else{ ?> <textarea disabled="disabled" style="display: none"
										id="<?php echo esc_attr($fieldData['fieldid'])?>_options"
										name="<?php echo esc_attr($fieldData['fieldid'])?>_options"></textarea>
										<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<p class="submit"><input <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; } ?> type="submit" name="awp_jobsearchform_settings" id="awp_jobsearchform_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration') ?>" /></p>
			</form>

			</div>

	<?php
		}
	}

	/**
	 * Add hrjobs form scripts and styles, only when short code is present in page/posts
	 */
	function check_for_shortcode($posts) {
		$applicantformfound=awp_check_for_shortcode($posts,'[apptivo_job_applicantform');
		$searchform_found=awp_check_for_shortcode($posts,'[apptivo_job_searchform');
		$joblists_found=awp_check_for_shortcode($posts,'[apptivo_jobs');
		$jobdesc_found=awp_check_for_shortcode($posts,'[apptivo_job_description');
		if ($applicantformfound){
			// load styles and scripts
			$this->loadscripts();
		}
		if($searchform_found || $joblists_found || $jobdesc_found || $applicantformfound)
		{
			$this->loadstyles();
		}
	  
		return $posts;
	}

	function loadstyles()
	{
		wp_enqueue_style('style_awp_job', AWP_PLUGIN_BASEURL.'/inc/jobs/css/style.css' , false, '1.0.0', 'screen');
	}

	/**
	 * Load the JS files
	 */
	function loadscripts() {

		wp_enqueue_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));

	}

	/**
	 * Country lists from Apptivo
	 */
	function getAllCountryList()
	{
		$awp_services_obj=new AWPAPIServices();
		$countrylist = $awp_services_obj->getAllCountries();
		return $countrylist;
	}


} //End Class

/**
 * Create Jobs in Apptivo.
 */
/*function createJobs($jobTitle,$jobDescription,$industryId,$jobtype,$isFeatured)
{
	if(isset($isFeatured) && $isFeatured == 'on'){
		$isFeatured = 'Y';
	}else{
		$isFeatured = 'N';
	}

	$jobStatusName = 'New';
	$jobDetails = new jobDetails($fillByDate, $firmId, $industryId, $industryName, $isFeatured, $jobDescription, $jobId, $jobNumber, $jobStatusId, $jobStatusName, $jobTitle, $jobTypeId, $jobtype);
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $jobDetails
	);
	$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createJob',$params);
	if($response == 'E_100')
	{
		return $response;
	}
	return $response->return;

}*/

/**
 * Document details for upload document.
 */
/*function getDetailsForDocumentUpload(){
	$params = array ( "arg0" => APPTIVO_BUSINESS_API_KEY,"arg1"=> APPTIVO_BUSINESS_ACCESS_KEY,"arg2" => '0');
	$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getUploadDocumentDetails',$params);
	return $response;
}*/

/**
 * Update Apptivo Jobs
 */
/*function updatejobs($jobId,$jobTitle,$jobDescription,$industryId,$jobtype,$isFeatured,$jobStatusName='New'){

	if(isset($isFeatured) && $isFeatured == 'on'){
		$isFeatured = 'Y';
	}else{
		$isFeatured = 'N';
	}
	$jobDetails = new jobDetails($fillByDate, $firmId, $industryId, $industryName, $isFeatured, $jobDescription, $jobId, $jobNumber, $jobStatusId, $jobStatusName, $jobTitle, $jobTypeId, $jobtype);
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" =>$jobDetails
	);
	$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'updateJob',$params);
	return $response->return;

}*/

/**
 * Job Details based on Jobs ID.
 */
/*function getJobByJobId($jobId){
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $jobId
	);
	$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getJobByJobId',$params);
	return $response;
}*/

/*
 * Job Applicant Details are stored in Apptivo Jobs apps
 */
/*function createJobApplicant($addressId, $addressLine1, $addressLine2, $applicantId, $applicantNumber, $city, $comments, $country, $countyAndDistrict, $emailId, $expectedDesignation, $expectedSalary, $firstName, $industryId, $jobApplicantId, $jobId, $jobNumber, $lastName, $middleName, $noteDetails, $phoneNumber, $postalCode, $provinceAndState, $resumeCoverLetter, $resumeDetails, $resumeFileName, $resumeId, $skills,$upload_docid){
	$verification = check_blockip();
	if($verification){
		return $verification;
	}
	$jobapplicantdetals = new JobApplicantDetails($addressId, $addressLine1, $addressLine2, $applicantId, $applicantNumber, $city, $comments, $country,$countyAndDistrict,$emailId, $expectedDesignation, $expectedSalary, $firstName, $industryId, $jobApplicantId,$jobId, $jobNumber, $lastName, $middleName, $noteDetails, $phoneNumber, $postalCode, $provinceAndState, $resumeCoverLetter, $resumeDetails, $resumeFileName, $resumeId, $skills,$upload_docid);
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $jobapplicantdetals
	);
	$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createNewJobApplicant',$params);

	$noteDetails=$response->return->noteDetails->noteText;
	$applicantId=$response->return->applicantId;  
			if($applicantId!='' && $upload_docid!=''){ 
                    $file_ext = strtolower( end(explode('.',$upload_docid)));
                    $file_size= $_FILES['file_upload']['size'];
                    $file_tmp= $_FILES['file_upload']['tmp_name'];
                    $data = file_get_contents($file_tmp);
                    $base64 = base64_encode($data);
                    $add_document=uploadDocument($applicantId,$upload_docid,$file_ext,$file_size,$base64);
                
			}    
	     if($noteDetails!="" && $applicantId != "")
					{
						
						$noteText=$response->return->noteDetails->noteText;
						$caseNotes='{"noteText":"'.$noteText.'"}';
						$param = array (
                				"a"         => "save",
                				"objectId"  => APPTIVO_JOBS_OBJECT_ID,
                				"objRefId"  => "$applicantId",
                				"noteData"  =>  "$caseNotes",
                				"apiKey"=> APPTIVO_BUSINESS_API_KEY,
                				"accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
						);
						
						$notesResponse= getRestAPICall("POST", APPTIVO_NOTES_API,$param);
						$noteid=$notesResponse->noteId;
						
					}
	if((isset($response->return->statusCode) && $response->return->statusCode != '1000') || $response =='E_100')
	{
		echo awp_messagelist('jobapplicant-display-page');
	}
	return $response->return;
}*/

/**
 * Create Jobs in Apptivo V6.
 */
function createJob($positionDetails){

	$param = array(
		"a" => "save",
		"positionData" => $positionDetails,
		"apiKey" => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
	);
	$response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API,$param);
	return $response;
}

/**
 * Update Jobs in Apptivo V6.
 */
function updateJob($positionDetails,$jobId){

	  $param = array(
            "a" => "update",
            "positionData" => $positionDetails,
	  		"positionId"=>$jobId,
	  		"attributeName"=>'["statusName","title","industryName","categoryName","isFeatured","description"]',
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API,$param);
        return $response;
}

/**
 * 
 * Save Candidate...
 */
function saveCandidate($docId,$docName,$firstName,$lastName,$emailId,$phone,$resumeName,$coverLetter,$jobTitle,$comments,$industry,$industryId,$address1,$address2,$city,$state,$zipcode,$country,$countryIdCode,$countryName,$skills) {
        
	$countryIdCode = explode('_', $countryIdCode);
	$countryId = $countryIdCode[0] ?? '';
	$countryCode = $countryIdCode[1] ?? '';
	/*if($countryIdCode == ""){
		$countryId = 176;
		$countryCode = "US";
		$countryName = "United States";
	}*/
	if($state == ""){ $state = null;}
	
	if($docId != ''){
		$doc =  ',"candidateResumeId":'.$docId.',"candidateResume":"'.$docName.'"';
	}
	
	//$candidateDetails = '{"applicantNumber":"Auto+generated+number","statusId":"-1","firstName":"'.htmlspecialchars($firstName).'","emailId":"'.htmlspecialchars($emailId).'","lastName":"'.htmlspecialchars($lastName).'","phoneNumber":"'.$phone.'","industryId":"'.$industryId.'","mobileNumber":"","resumeId":"'.$resumeName.'","skills":"'.htmlspecialchars($skills).'","resumeCoverLetter":"'.htmlspecialchars($coverLetter).'","address_country":"176","jobTitle":"'.$jobTitle.'","comments":"'.htmlspecialchars($comments).'"}';
     $candidateDetails = '{"candidateNumber":"Auto generated number", "customAttributes":[],"educationalHistories":[{"customAttributes":[]}],"labels":[],"tags":[],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"3","addressType":"Communication","addressLine1":"'.$address1.'","addressLine2":"'.$address2.'","city":"'.$city.'","county":"","stateCode":"","state":null,"zipCode":"'.$zipcode.'","countryId":'.$countryId.',"countryName":"'.$countryName.'","countryCode":"'.$countryCode.'","deliveryInstructions":null,"addressGroupName":"Address1"}],"firstName":"'.$firstName.'","lastName":"'.$lastName.'","industryName":"Electronic Equip./Components","industryId":12924110,"skills":"'.$skills.'","coverLetter":"'.$coverLetter.'","phoneNumbers":[{"id":"candidate_phone_input","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","phoneNumber":"'.$phone.'"}],"phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","emailAddresses":[{"id":"cont_email_input","emailType":"Business","emailTypeCode":"BUSINESS","emailAddress":"'.$emailId.'"}],"emailType":"Business","emailTypeCode":"BUSINESS","removePhoneNumbers":[],"removeEmailAddresses":[],"statusCode":"APPLIED"'.$doc.'}';
     
     //$candidateDetails = '{"experience":0,"candidateNumber":"Auto generated number","statusName":"Applied","statusId":67169,"customAttributes":[],"labels":[],"tags":[],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"1","addressType":"Billing Address","addressLine1":"","addressLine2":"","city":"","county":"","stateCode":"","state":null,"zipCode":"","countryId":70,"countryName":"India","countryCode":"IN","deliveryInstructions":null,"addressGroupName":"Address1"}],"currentSalaryCurrencyCode":"INR","currencyCode":"INR","expectedSalaryCurrencyCode":"INR","isDirtypage":null,"firstName":"Test","title":"Dr.","lastName":"test","phoneNumbers":[{"id":"candidate_phone_input","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","phoneNumber":"4444444444"}],"phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","emailAddresses":[{"id":"cont_email_input","emailType":"Business","emailTypeCode":"BUSINESS","emailAddress":"dsd@berijam.com"}],"emailType":"Business","emailTypeCode":"BUSINESS","removePhoneNumbers":[],"removeEmailAddresses":[],"statusCode":"APPLIED"}';  
      // $addressData = '["-1","'.$country.'","'.$address1.'","'.$address2.'","","'.$city.'","'.$state.'","'.$zipcode.'","'.$country.'"]';
       /*  $param = array(
            "a" => "saveCandidate",
            "candidateDetails" => $candidateDetails,
            "addressData" => $addressData,
            "documentId" => $documentId,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );*/
        
	$param = array(
		"a" => "save",
		"candidateData" => $candidateDetails,
		"apiKey" => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
	);
	$response = getRestAPICall("POST", APPTIVO_CANDIDATE_V6_API,$param);
	return $response;
}


function associateCandidatesToPosition($jobApplicantId,$positionId){
	 $param = array(
            "a" => "associateCandidatesToPosition",
            "candidateIds" => '['.$jobApplicantId.']',
            "positionId"=>$positionId,
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API,$param);
        return $response;
}

/* Save Notes to Objects */
function getUploadDetailsByBucketName() {
        $param = array(
            "a" => "getUploadDetailsByBucketName",
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_COMMON_API, $param);
        return $response;
}

/*
* upload document
*/
function uploadDocument($file_name,$file_ext,$file_size,$base64) {
    $params = array(
        "a" => "uploadDoc",
        "objectId" => 177,
    	"docName"=> $file_name,
        "docTitle"=> $file_name,
    	"docType"=> $file_ext,
    	"docSize"=> $file_size,
    	"encodedDocStr"=> $base64,
        "apiKey" => APPTIVO_BUSINESS_API_KEY,
        "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
    );
    $response = getRestAPICall("POST", APPTIVO_DOCUMENT_API, $params);
    return $response;
}


/* 
 * get All  industries
 */
function getAllIndustriesV6(){
	$param = array(
		"a" => "getConfigData",
		"apiKey" => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
	);
	$response = getRestAPICall("POST", APPTIVO_CANDIDATE_V6_API, $param);
	return $response;
}


/* 
 * get All  industries
 */
function getAllStatusesTypesV6(){
 		
	$param = array(
		"a" => "getConfigData",
		"apiKey" => APPTIVO_BUSINESS_API_KEY,
		"accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
	);
	$response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API, $param);
	return $response;	
}


/* 
 * get All jobs from recruitment
 */
function getAlljobs(){
 		
		$param = array(
            "a" => "getAll",
			"iDisplayLength" => 50,
			"iDisplayStart"=>0,
			"numRecords"=>50,
			"sSortDir_0"=>"desc",
			"selectedTab"=>"show-all",
			"sortColumn"=>"lastUpdateDate",
			"sortDir"=>"desc",
            "apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API, $param);
        return $response;	
}

function getJobById($id){
			$param = array(
            "a" => "getById",
			"positionId" => $id,
			"apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API, $param);
        return $response;	
	
}

/* search job */
function searchJob($keyword,$industry,$job_types){
		
	if(sizeof($job_types) == 0 ){
		$jobtype = array();
	    $jobType = json_encode($jobtype);
	}else{
		$jobType = json_encode($job_types);
	}
	
		$param = array(
            "a" => "getAllByAdvancedSearch",
			"startIndex"=>0,
			"numRecords"=>50,
			"iDisplayLength"=>50,
			"iDisplayStart"=>0,
			"filterAdvData"=>'{"statusIds":[],"industryIds":['.$industry.'],"categoryIds":'.$jobType.',"departmentIds":[]}',
			"searchData"=>'{"customAttributes":[],"title":"'.$keyword.'","isFeatured":null,"labels":[]}',
			"objectId"=>APPTIVO_RECRUITMENT_OBJECT_ID,
			"apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
        $response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API, $param);
        return $response;	
 
}

/**
 * Get ALL Jobs from Index
 */
/*function getAllHrjobs($maxCount=999,$pageIndex=1,$getFeaturedJobsOnly='false',$status = null)
{
	$sortBy = 0;
	$params_plugincall = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
    			"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $getFeaturedJobsOnly,
                "arg3" => $pageIndex,
                "arg4" => $maxCount,
                "arg5" => $sortBy,
    			"arg6" => $status
	);
	 
	$response = getsoapCall(APPTIVO_BUSINESS_INDEX,'getAllJobsWithStatus',$params_plugincall);
	if($response == 'E_100') :
	echo awp_messagelist('validate-getAllJobsWithStatus');
	endif;
	return $response->return;
}*/

/*
 * Get ALL Jobs  from Apptivo
 */
/*function get_apptivojobs()
{
	$params_plugincall = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
    			"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => 'false',
                "arg3" => 999,
                "arg4" => 0,
                "arg5" => 0,
    			"arg6" => null
	);
	 
	$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getAllJobsWithStatus',$params_plugincall);
	if($response == 'E_100')
	{
		echo awp_messagelist('validate-getAllJobsWithStatus');
	}
	return $response->return;
}*/

/**
 * Search By Jobs
 */
/*function serchByJobs($keyword,$industry,$job_types,$maxcount=999,$pageIndex=1)
{
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $keyword,
                "arg3" => $industry,
                "arg4" => $job_types,
                "arg5" => 'false',
                "arg6" => $pageIndex,
                "arg7" => $maxcount,
                "arg8" => 0
	);
	$response = getsoapCall(APPTIVO_BUSINESS_INDEX,'searchJobsBySearchText',$params); //searchJobs
	return $response;
}*/

/**
 * Job Details based on Job number.
 */
/*function jobdescriptionByNumber($jobNo){
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $jobNo
	);
	$response = getsoapCall(APPTIVO_BUSINESS_INDEX,'getJobByJobNo',$params); //getJobByJobNumber
	if( $response == 'E_100')
	{
		return $response;
	}
	return $response->return;
	 
}*/

function jobdescriptionByNumberV6($jobNo){
	$param = array(
            "a" => "getAllByAdvancedSearch",
			"startIndex"=>0,
			"numRecords"=>50,
			"iDisplayLength"=>50,
			"iDisplayStart"=>0,
			"filterAdvData"=>'{"statusIds":[],"industryIds":[],"categoryIds":[],"departmentIds":[]}',
			"searchData"=>'{"customAttributes":[],"positionNumber":"'.$jobNo.'","isFeatured":null,"labels":[]}',
			"objectId"=>APPTIVO_RECRUITMENT_OBJECT_ID,
			"apiKey" => APPTIVO_BUSINESS_API_KEY,
            "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
        );
	$response = getRestAPICall("POST", APPTIVO_RECRUITMENT_V6_API, $param);
	return $response;
}

/**
 * Apptivo Job Industries.
 */
/*function getAllIndustries(){
	$params = array ( "arg0" => APPTIVO_BUSINESS_API_KEY, "arg1" => APPTIVO_BUSINESS_ACCESS_KEY );
	$data_key = APPTIVO_BUSINESS_API_KEY.'-industries';
	if(class_exists('Memcache'))
	{
		$mcache_obj = new AWP_Cache_Util(); //Create Object in AWP_DataCache clss
		$mcacheconnect = $mcache_obj->connectmcache();
	}
	else {
		$mcacheconnect = FALSE;
	}
	//To check if the MemCache is connected or not.
	if( $mcacheconnect ){
		$response = $mcache_obj->getdata($data_key);
		if( empty($response)) //Check the published date key value is set in memcahe or not.
		{
			$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getAllIndustries',$params);
			$mcache_obj->storedata($data_key,$response);
		}
	}else{
		
		$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getAllIndustries',$params);
	}
	
	return $response->return;
}*/


/* SelectAll functionality for Job Industry,Job Type in Job Search From Fields */

add_action("admin_footer", "apptivo_business_jobs_search_formfields");

function apptivo_business_jobs_search_formfields(){ ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
		
		/*Handled for Vulnerability Script Code Injection */
		$("#jobs_title").blur(function(event){
		var text = $(this).val().replace(/[^\da-zA-Z0-9 ]/g,'');
		$(this).val(text);
		return true;
		});

		$("#awp_jobsform_submit_val,#awp_jobsearchform_submit_value,#awp_joblist_submit_value").blur(function(event) {
			var text = $(this).val().replace(/[^\da-zA-Z0-9/:. ]/g,'');
			$(this).val(text);
			return true;
		});

		jQuery('#industry_show').change(function(){
			if(jQuery(this).is(":checked")) {
			      //checked event code
			      jQuery('#industry_options option').attr('selected', 'selected');
			      return;
			   }
			jQuery('#industry_options option').removeAttr('selected');
		});
		jQuery('#jobtype_show').change(function(){
			if(jQuery(this).is(":checked")) {
			      //checked event code
			      jQuery('#jobtype_options option').attr('selected', 'selected');
			      return;
			   }
			jQuery('#jobtype_options option').removeAttr('selected');
		});
        
    });
    </script>
<?php } ?>
