<?php 
/**
 * Apptivo Newsletter Form
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';

/**
 * Class AWP_ContactForms
 */
class AWP_Newsletter extends AWP_Base
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
    		if($settings["newsletters"])
    			$this->_plugin_activated=true;
    	}
    }

    /**
     * Returns plugin instance
     *
     * @return AWP_ContactForms
     */
    function &instance()
    {
         $instances = array();

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
                     add_action( 'widgets_init',array(&$this,'register_widget')); //initialize widget
                     add_action('the_posts', array( &$this,'check_for_shortcode' )); // check shortcode
			         add_shortcode('apptivonewsletterform', array(&$this,'shownewsletterform'));
	         }
    }

function shownewsletterform($atts){
	ob_start();
    $this->loadscripts();
	//add_action('wp_footer', 'abwpExternalScripts');

	extract(shortcode_atts(array('name'=>  ''), $atts));
	$formname=trim($name);
	$content="";
	$successmsg="";
	$newsletterform=$this->get_newsletterform_fields($formname);
	$_POST['awp_newsletterformname'] = (!empty($_POST['awp_newsletterformname'])) ? $_POST['awp_newsletterformname'] : '';
	$submitformname= sanitize_text_field($_POST['awp_newsletterformname']);
	if(isset($_POST['awp_newsletterform_submit']) && $submitformname==$formname){
		$successmsg= $this->save_newsletter($submitformname); 
	}
	if(!empty($newsletterform) && !empty($newsletterform['fields']) ){
		include $newsletterform['templatefile'];
	}else{
		echo awp_messagelist('newsletter-display-page');
	}

	$content = ob_get_clean();
	return $content;
}

function save_newsletter($formname){
	$newsletterform=$this->get_newsletterform_fields($formname);

	if(!empty($newsletterform)){
		$newsletterformfields=$newsletterform['fields'];
		//Process the $_POST here..
		$submittedformvalues=array();
		$submittedformvalues['category'] = sanitize_text_field($_POST['newsletter_category']);
		foreach($newsletterformfields as $field){
			$notesLabel=$field['showtext'];
			$fieldid=$field['fieldid'];
			if($fieldid=='newsletter_phone'){
				if(isset($_POST[$formname.'_newsletter_phone1'])){
					$submittedformvalues[$fieldid]= sanitize_text_field($_POST[$formname.'_newsletter_phone1']).sanitize_text_field($_POST[$formname.'_newsletter_phone2']).sanitize_text_field($_POST[$formname.'_newsletter_phone3']);
				}else{
					$submittedformvalues[$fieldid]= sanitize_text_field($_POST[$fieldid]);
				}
			}else{
				$submittedformvalues[$fieldid]= stripslashes(sanitize_text_field($_POST[$fieldid]));
			}
		}
		//Submit the $submittedformvalues to Apptivo Lead Webservice
		//Dont forgot to save the contact form name as Lead Source value
		$category = $submittedformvalues['category'];

		$firstname = $submittedformvalues['newsletter_firstname'];
		$lastname = $submittedformvalues['newsletter_lastname'];
		$email = $submittedformvalues['newsletter_email'];
		$phoneNumber = $submittedformvalues['newsletter_phone'];
		$comments = $submittedformvalues['newsletter_comments'];

		if(!empty($email)){
			$response = createTargetList($category, $firstname, $lastname,$email,$phoneNumber,$comments,$notesLabel);
			if(isset($response->responseObject->id ) && $response->responseObject->id  != ''){
				$confmsg = "Newsletter subscribed successfully";
			}
		}

		if($response == 'E_100'){
			echo awp_messagelist('newslettertarget-display-page'); 
		}else  if($response == 'E_N001' || $response == 'E_N002' ){
			echo awp_messagelist('newsletter-target-error'); 
		}else if($response == 'E_IP'){
			echo awp_messagelist('IP_banned');
		}else if(!empty($confmsg) && $confmsg != "Email already registered"){
			if(!empty($newsletterform['confmsg'])){
				$confmsg = $newsletterform['confmsg'];
			}
		}
	}
	return $confmsg;
}

function get_newsletterform_fields($formname){

	$formExists="";
	$newsletter_forms=array();
	$newsletterform=array();
	$newsletterformdetails=array();
	$formname=trim($formname);
	
	$newsletter_forms=get_option('awp_newsletterforms');
	
	if($formname=="")
		$formExists="";
	else if(!empty($newsletter_forms))
		$formExists = awp_recursive_array_search($newsletter_forms,$formname,'name' );
		
	if(trim($formExists)!=="" ){
		$newsletterform=$newsletter_forms[$formExists];
		//build contactformdetails array
		$newsletterformdetails['name']=$newsletterform['name'];		
		//add properties
		$newsletterformproperties=$newsletterform['properties'];
		$newsletterformdetails['tmpltype']=$newsletterformproperties['tmpltype'];
		$newsletterformdetails['layout']=$newsletterformproperties['layout'];
		$newsletterformdetails['confmsg']=$newsletterformproperties['confmsg'];
		$newsletterformdetails['css']=$newsletterformproperties['css'];
        $newsletterformdetails['category']=$newsletterformproperties['category'];
        $newsletterformdetails['submit_button_type']=$newsletterformproperties['submit_button_type'];
        $newsletterformdetails['submit_button_val']=$newsletterformproperties['submit_button_val'];
        echo '<style> label.error{color:#FF0400;} </style>';
        //include newsletter template files.
        if($newsletterformproperties['tmpltype']=="awp_plugin_template") :
			$templatefile=AWP_NEWSLETTER_TEMPLATEPATH."/".$newsletterformproperties['layout']; //plugin template
		else :
			$templatefile=TEMPLATEPATH."/newsletter/".$newsletterformproperties['layout']; //theme template
		endif;				
		$newsletterformdetails['templatefile']=$templatefile;
		//add fields
		$newsletterformfields=$newsletterform['fields'];
		if(!empty($newsletterformfields)){
			//usort($newsletterformfields, "awp_sort_by_order");
			$newnewsletterformfields=array();
				
		foreach( $newsletterformfields as $newsletterformfield )
			{ 
				$fieldinfo= $this->get_master_newsformfield($newsletterformfield['fieldid']);
			
				if(trim($newsletterformfield['showtext'])=="")
					$newsletterformfield['showtext']=$fieldinfo['showtext'];
				if(trim($newsletterformfield['required'])=="")
					$newsletterformfield['required']=0;
				$newsletterformfield['validation']=$fieldinfo['validation'];
				$newsletterformfield['fieldtype']=$fieldinfo['fieldtype'];
				array_push($newnewsletterformfields,$newsletterformfield);
			}
			usort($newnewsletterformfields, "awp_sort_by_order");
			$newsletterformdetails['fields']=$newnewsletterformfields;
		}
	}
	return $newsletterformdetails;
}


function get_master_newsformfield($fieldid){
	$masterfields=$this->get_master_newsletterform_fields();
	$fieldinfo=array();
	$fieldid=trim($fieldid);
	if($fieldid!=""){
		$formExists="";
		$formExists = awp_recursive_array_search($masterfields,$fieldid,'fieldid' );
		if(trim($formExists)!=="" ){
			$fieldinfo['validation']=$masterfields[$formExists]['validation'];
			$fieldinfo['fieldtype']=$masterfields[$formExists]['fieldtype'];
			$fieldinfo['showtext']=$masterfields[$formExists]['defaulttext'];
		}
	}
	return $fieldinfo;
}

function awp_getnewsletterletter_settings($title){
	$formExists="";
	$newsletter_forms=array();
	$newsletterform=array();
	$formname=trim($title);
	
	$newsletter_forms=get_option('awp_newsletterforms');
	
	if($formname=="")
		$formExists="";
	else if(!empty($newsletter_forms))
		$formExists = awp_recursive_array_search($newsletter_forms,$formname,'name' );
		
	
		
	if(trim($formExists)!=="" ){
		$newsletterform=$newsletter_forms[$formExists];
	}
	return $newsletterform;
}


function get_master_newsletterform_fields()
{
	$fields = array(
		array('fieldid' => 'newsletter_firstname','fieldname' => 'First Name','defaulttext' => 'First Name','showorder' => '1','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'newsletter_lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','showorder' => '2','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'newsletter_email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '3','validation' => 'email','fieldtype' => 'text'),
		array('fieldid' => 'newsletter_phone','fieldname' => 'Phone','defaulttext' => 'Phone','showorder' => '4','validation' => 'number','fieldtype' => 'text'),
		array('fieldid' => 'newsletter_comments','fieldname' => 'Comments','defaulttext' => 'Comments','showorder' => '5','validation' => 'text','fieldtype' => 'textarea')
		
		
	);
	return $fields;
}

function options(){
// Delete form Name:
	$_POST['delformname'] = (!empty($_POST['delformname'])) ? sanitize_text_field($_POST['delformname']) : '';	
if($_POST['delformname'])
		{
			if(strlen(trim($_POST['delformname'])) != 0)
			{ 
				$formname = $_POST['delformname'];
				$newsletter_forms=get_option('awp_newsletterforms');
							
				//get Key value.				
				$formExists = awp_recursive_array_search($newsletter_forms,$formname,'name' );
							
				if(isset($formExists))
				{    
					unset($newsletter_forms[$formExists]);
				}
				$newsletter_sort_form = array();
				foreach($newsletter_forms as $news_forms_tosort )
				{
					array_push($newsletter_sort_form,$news_forms_tosort);
				}
				update_option('awp_newsletterforms', $newsletter_sort_form);
				$updatemessage= 'Newsletter Form "'.$formname.'" Deleted Successfully.';
			}
		}
/*
	 * Saving New form
	 */
	$newsletter_forms=get_option('awp_newsletterforms');
if(isset($_POST['newnewsletterformname']))
	{   
		$newsletter_forms=get_option('awp_newsletterforms');
		
		$newnewsletterformname =   sanitize_text_field($_POST['newnewsletterformname']);
        $newnewsletterformname = preg_replace('/[^\w]/', '', $newnewsletterformname);
		if($newnewsletterformname!='')
		{
			$newsletterform=array();
			$newsletterform=$this->awp_getnewsletterletter_settings($newnewsletterformname);
			if( count($newsletterform)==0 )
			{
				$newnewsletterformname_array =array("name"=>$newnewsletterformname);
				$newnewsletterform=array($newnewsletterformname_array);
				if( empty($newsletter_forms) ){
  				update_option('awp_newsletterforms',$newnewsletterform);
				}else{
    			array_push($newsletter_forms, $newnewsletterformname_array);
					update_option('awp_newsletterforms',$newsletter_forms);
				}
				$newsletter_forms=get_option('awp_newsletterforms');
				$newsletterform=$this->awp_getnewsletterletter_settings($newnewsletterformname);
				$selectednewsletterform=$newnewsletterformname;
				$updatemessage= "Newsletter Form created. Please configure settings using the below Configuration section.";
			}else{
					$updatemessage= "<span style='color:#f00;'>Form already exists. To change configuration, please select the form from below configuration section.</span>";
				}
		}else{
					$updatemessage= "Form Name cannot be empty.";
			}		
	}

	/*
	 * Loading the settings of selected form
	 */
	if(isset($_POST['awp_newsletter_selection_form']))
	{
		$selectednewsletterform =  trim(sanitize_text_field($_POST['awp_newsletter_selection_form']));
		
		if($selectednewsletterform != '')
		{
			$newsletterforms_name=array();
			$newsletterforms_name=$this->awp_getnewsletterletter_settings($selectednewsletterform);
			
			
			if( empty($newsletterforms_name))
			{
				//echo "Selected form configuration doestn exist.";
			}else{
				$newsletterforms_name=$newsletterforms_name;
			}
		}
	}
	
	$_POST['awp_newsletterform_settings'] = (!empty($_POST['awp_newsletterform_settings'])) ? sanitize_text_field($_POST['awp_newsletterform_settings']) : '';
if($_POST['awp_newsletterform_settings']){
		$templatelayoutnewsletter="";
		if(sanitize_text_field($_POST['awp_newsletterform_templatetype'])=="awp_plugin_template")
		$templatelayoutnewsletter=sanitize_text_field($_POST['awp_newsletterform_plugintemplatelayout']);
		else
		$templatelayoutnewsletter=sanitize_text_field($_POST['awp_newsletterform_themetemplatelayout']);
		$newformname= sanitize_text_field($_POST['awp_newsletterform_name']);
		$newsletterformproperties=array(
		                'tmpltype' =>sanitize_text_field($_POST['awp_newsletterform_templatetype']),
                        'layout' =>$templatelayoutnewsletter,
						'subscribetype' =>sanitize_text_field($_POST['awp_newsletterform_subscribetype']),
                        'confmsg' =>stripslashes(sanitize_text_field($_POST['awp_newsletterform_confirmation_msg'])),
                        'css' => sanitize_text_field($_POST['awp_newsletterform_customcss']),
                        'category' => sanitize_text_field($_POST['awp_newsletterform_category']),
                         'submit_button_type' => sanitize_text_field($_POST['awp_newsletterform_submit_type']),
                         'submit_button_val' => sanitize_text_field($_POST['awp_newsletterform_submit_value']));
						 
		$newsletterformfields=array();
		foreach( $this->get_master_newsletterform_fields() as $fieldsmasterproperties )
		{
			$enabled=0;
			$newsletterformfield=array();
			$fieldid=$fieldsmasterproperties['fieldid'];
                        $fieldtype= $fieldsmasterproperties['fieldtype'];
                        if($fieldsmasterproperties['fieldid']=='newsletter_email'){
                            $enabled = 1;
                            $required = 1;
                            $validate = 'email';
                        }
                        else{
							$_POST[$fieldid.'_require'] = (!empty($_POST[$fieldid.'_require'])) ? $_POST[$fieldid.'_require'] : '';
							$_POST[$fieldid.'_show'] = (!empty($_POST[$fieldid.'_show'])) ? sanitize_text_field($_POST[$fieldid.'_show']) : '';
			                $enabled = $_POST[$fieldid.'_show'];
                            $required = sanitize_text_field($_POST[$fieldid.'_require']);
                            $validate = sanitize_text_field($_POST[$fieldid.'_validation']);                            
                        }
                        if(!empty($_POST[$fieldid.'_text'])){
                            $displaytext = sanitize_text_field($_POST[$fieldid.'_text']);
                        }
                        else{
                            $displaytext = $fieldsmasterproperties['defaulttext'];
                        }
                        if(!empty($_POST[$fieldid.'_order'])){
                            $displayorder = sanitize_text_field($_POST[$fieldid.'_order']);
                        }
                        else{
                            $displayorder = $fieldsmasterproperties['showorder'];
                        }
                       if($enabled){
			
			$_POST[$fieldid.'_options'] = (!empty($_POST[$fieldid.'_options'])) ? sanitize_textarea_field($_POST[$fieldid.'_options']) : [];
				$newsletterformfield=$this->createformfield_array($fieldid,$displaytext,$required,$fieldtype,$validate,$_POST[$fieldid.'_options'],$displayorder);
				$newsletterformfield =(!empty($newsletterformfield)) ? $newsletterformfield : [];
				$newsletterformfields =(!empty($newsletterformfields)) ? $newsletterformfields : [];
				array_push($newsletterformfields, $newsletterformfield);
			}
		}
		
	
		$newnewsletterformdetails=array('name'=>$newformname,'properties'=>$newsletterformproperties,'fields'=>$newsletterformfields);
		
		$formExists="";
		if(!empty($newsletter_forms))
			$formExists = awp_recursive_array_search($newsletter_forms,$newformname,'name' );
		if(trim($formExists)!=="" ){
			
			unset($newsletter_forms[$formExists]);
			
			array_push($newsletter_forms, $newnewsletterformdetails);
			
			sort($newsletter_forms);
      
			update_option('awp_newsletterforms',$newsletter_forms);
			$newsletter_forms=get_option('awp_newsletterforms');
			$updatemessage= "Newsletter Form '".$newformname."' settings updated. Use Shortcode '[apptivonewsletterform name=\"".$newformname."\"]' in your page to use this form.";
		}
		$selectednewsletterform=$newformname;
               
		
	}
	
	
	echo "<div class='wrap'><h2>" . __( 'Apptivo Newsletter Forms', 'awp_newsletterform' ) . "</h2></div>";
        checkSoapextension("Newsletter");
	echo '<div class="newsletterform_err"></div>';
	$updatemessage = (!empty($updatemessage)) ? $updatemessage : '';
if(trim($updatemessage)!=""){
	?>
	<div id="message" class="updated">
        <p>
        <?php echo esc_attr($updatemessage);?>
        </p>
    </div>
    
    <?php }
        if(!$this->_plugin_activated){
        	$disabledForm = 'disabled="disabled"';
	    	echo "Newsletter Forms is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
	    }
    
	?>
	
	<form name="awp_newsletterform_new" id="awp_newsletterform_new" method="post" action="" onsubmit="return validatenewsletterforms(this)">
	   <p>
	   <img id="elementToResize" src="<?php echo awp_flow_diagram('newsletter');?>" alt="Newsletter" title="Newsletter"  />
	   </p>	   
	 <p style="margin:10px;">
		For Complete instructions,see the <a href="<?php echo awp_developerguide('newsletter');?>" target="_blank">Developer's Guide.</a>
	</p>
		
	<p>
	<?php _e("Newsletter Form Name", 'apptivo-businesssite' ); ?>
		<span style="color:#f00;">*</span>&nbsp;&nbsp;<input type="text" name="newnewsletterformname" id="newnewsletterformname" size="20" >
		
		</p> 
		<p>
		<?php $disabledForm = (!empty($disabledForm)) ? $disabledForm : '';?>
		<input <?php echo esc_attr($disabledForm); ?> type="submit" name="Submit" class="button-primary newletter-add" value="<?php esc_attr_e('Add New') ?>" />
	</p>
</form>
<br />


<?php 
$newsletter_categories = $this->getNewsletterCategory();
$newsletter_categories->countOfRecords = (!empty($newsletter_categories->countOfRecords)) ? $newsletter_categories->countOfRecords : '';
if(empty($newsletter_categories) && $newsletter_categories->countOfRecords == 0){
    echo "<span style='color:red; font-size:14px;'>Please add target list in apptivo before configuring newsletter</span><input type='hidden' class='awp_targetlist' value='0'/>";
    exit;
}else{
   	$newsletter_categories = $newsletter_categories->data;
   	echo "<input type='hidden' class='awp_targetlist' value='1'/>";
}

if( !empty($newsletter_forms)){ ?>
<br/>
		<?php
		$selectednewsletterform = (!empty($selectednewsletterform)) ? $selectednewsletterform : '';
		if(trim($selectednewsletterform)==""){
			$selectednewsletterform=$newsletter_forms[0]['name'];
		}
		$newsletterformdetails = $this->awp_getnewsletterletter_settings($selectednewsletterform);
		if(count($newsletterformdetails)>0){
			$newsletterformdetails['name'] = (!empty($newsletterformdetails['name'])) ? $newsletterformdetails['name'] : [];
			$newsletterformdetails['fields'] = (!empty($newsletterformdetails['fields'])) ? $newsletterformdetails['fields'] : [];
			$newsletterformdetails['properties'] = (!empty($newsletterformdetails['properties'])) ? $newsletterformdetails['properties'] : [];
			$selectednewsletterform=$newsletterformdetails['name'];
			$newsletter_fields=$newsletterformdetails['fields'];
			$newsletter_formproperties=$newsletterformdetails['properties'];
		}
		?>
		
<?php  echo "<h2>" . __( 'Apptivo Newsletter Form Configuration', 'awp_newsletterform' ) . "</h2>"; ?>


	<table class="form-table">
		<tbody>
			<?php if(empty($newsletter_formproperties['tmpltype'])){ //To chech Newsletter configurations or save or not. 
		        echo '<span style="color:#f00;">Save the below settings to get the Shortcode for Newsletter form.</span>';
			} ?>
			<tr valign="top">
				<th valign="top"><label for="awp_newsletterform_select_form"><?php _e("Newsletter Form Name", 'apptivo-businesssite' ); ?>:</label>
				</th>
				<td valign="top">
					<form name="awp_newsletter_selection_form" method="post" action="" style="float:left;">
					<select name="awp_newsletter_selection_form" id="awp_newsletter_selection_form" onchange="this.form.submit();">
						<?php
						for($i=0; $i<count($newsletter_forms); $i++){ ?>
							<option value="<?php echo esc_attr($newsletter_forms[$i]['name'])?>"
							<?php if(trim($selectednewsletterform)==$newsletter_forms[$i]['name'])
							echo "selected='true'";?>>
							<?php echo esc_attr($newsletter_forms[$i]['name'])?>
							</option>
						<?php } ?>
					</select>
					</form>
					<?php if($this->_plugin_activated){ ?>
						<form name="awp_newsletter_deletion_form" id="awp_newsletter_deletion_form" method="post" action="" style="float:left;padding-left:30px;">
						<a href="javascript:newsletter_confirmation('<?php echo 
						esc_attr($selectednewsletterform);  ?>');" >Delete</a>
						<input type="hidden" name="delformname" id="delformname"  />
						</form>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>


<br>

<form name="awp_newsletterform_settings" method="post" action="">
<?php 
$themetemplates_newsletter  = $awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/newsletter','Plugin');
$plugintemplates_newsletter = get_awpTemplates(AWP_NEWSLETTER_TEMPLATEPATH,'Plugin');
arsort($plugintemplates_newsletter); 
		?>
<table class="form-table">
		<tbody>
			<?php if(!empty($newsletter_formproperties['tmpltype'])): //To chech Newsletter form configurations or save or not. ?>
			<tr valign="top">
				<th valign="top"><label for="newsletterform_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
				<br><span class="description">Copy and Paste this shortcode in your page to display this contact form.</span>
				</th>
				<td valign="top"><span id="awp_customform_shortcode" name="awp_customform_shortcode">
				<input style="width:300px;" type="text" id="newsletterform_shortcode" name="newsletterform_shortcode" readonly="true" value='[apptivonewsletterform name="<?php echo esc_attr($selectednewsletterform)?>"]' />
				</span>
				<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('newsletter-shortcode');?>" target="_blank">Newsletter Form Shortcodes.</a></span>
				</td>
			</tr>
			<?php endif; ?>
			
			<tr valign="top">
				<th valign="top"><label for="awp_newsletterform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
				</th>
				<td valign="top">
				<select name="awp_newsletterform_templatetype" id="awp_newsletterform_templatetype" onchange="changeTemplateNewsletter();">
				<?php $newsletter_formproperties['tmpltype'] = (!empty($newsletter_formproperties['tmpltype'])) ? $newsletter_formproperties['tmpltype'] : []; ?>
						<option value="awp_plugin_template" <?php selected($newsletter_formproperties['tmpltype'],'awp_plugin_template'); ?> >Plugin Templates</option>
						<?php if (!empty($themetemplates_newsletter)) :?>
						<option value="theme_template" <?php selected($newsletter_formproperties['tmpltype'],'theme_template'); ?> >Templates from Current Theme</option>
						<?php endif; ?>
				</select>
				<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('newsletter-template');?>" target="_blank">Newsletter Form Templates.</a></span>
				</td>
			</tr>
			<tr valign="top">
				<th valign="top"><label for="awp_newsletterform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
				<!-- <br><span class="description">Selecting Theme template which doesnt support Contact form structure will wont show the contact form in webpage.</span> -->
				</th>
				<td valign="top">
				<select name="awp_newsletterform_plugintemplatelayout" id="awp_newsletterform_plugintemplatelayout" <?php if($newsletter_formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
					<?php
					foreach (array_keys( $plugintemplates_newsletter ) as $template )
					{
						?>
						<option value="<?php echo esc_attr($plugintemplates_newsletter[$template])?>"
						<?php if(($newsletter_formproperties['tmpltype']=="awp_plugin_template")&& ($newsletter_formproperties['layout']==$plugintemplates_newsletter[$template]))  echo "selected='true'"?>
						>
						<?php echo esc_attr($template)?>
						</option>
						<?php }?>
				</select>
				 
				<select name="awp_newsletterform_themetemplatelayout" id="awp_newsletterform_themetemplatelayout" <?php if($newsletter_formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
					<?php foreach (array_keys( $themetemplates_newsletter ) as $template ) : ?>
						<option value="<?php echo esc_attr($themetemplates_newsletter[$template])?>" <?php selected($themetemplates_newsletter[$template],$newsletter_formproperties['layout']); ?> >
						<?php echo esc_attr($template)?>
						</option>
						<?php endforeach;?>
				</select>
				</td>
				<input type="hidden" id="awp_newsletterform_name" name="awp_newsletterform_name" value="<?php echo esc_attr($selectednewsletterform);?>" >
				<input type="hidden" id="awp_newsletterform_subscribetype" name="awp_newsletterform_subscribetype" value="subscribe">
			</tr>
			
				
	
			<tr valign="top">
				<th valign="top"><label for="awp_newsletterform_confirmation_msg1"><?php _e('Confirmation Message:','apptivo-businesssite'); ?></label>
				<br><span class="description"><?php _e('This message will shown in your website page, once newsletter letter form submitted.','apptivo-businesssite'); ?></span>
				</th>
				<td valign="top">
				<div style=width:620px;">
				<?php $newsletter_formproperties['confmsg'] = (!empty($newsletter_formproperties['confmsg'])) ? $newsletter_formproperties['confmsg'] : ''; ?>
				<?php 
					//the_editor($newsletter_formproperties['confmsg'],'awp_newsletterform_confirmation_msg','',FALSE);
					wp_editor($newsletter_formproperties['confmsg'], 'awp_newsletterform_confirmation_msg', array());
				?>
				</div>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_newsletterform_customcss"><?php _e('Custom CSS:','apptivo-businesssite'); ?></label>
				<br><span class="description" valign="top"><?php _e('Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.','apptivo-businesssite'); ?></span>
				</th>
				<td valign="top">
				<?php $newsletter_formproperties['css'] = (!empty($newsletter_formproperties['css'])) ? $newsletter_formproperties['css'] : ''; ?>
				<textarea size="100" rows="10" cols="40" id="awp_newsletterform_customcss" name="awp_newsletterform_customcss"><?php echo esc_attr($newsletter_formproperties['css']); ?></textarea>
				<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('newsletter-customcss');?>" target="_blank">Newsletter Form CSS.</a></span>
				</td>
			</tr>
                        <tr valign="top">
					<th><label id="awp_newsletterform_submit_type" for="awp_newsletterform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>

                   <td valign="top">
				   <?php $newsletter_formproperties['submit_button_type'] = (!empty($newsletter_formproperties['submit_button_type'])) ? $newsletter_formproperties['submit_button_type'] : []; ?>
                     <input type="radio" id="awp_newsletter_btn"  value="submit" name="awp_newsletterform_submit_type" <?php checked('submit',$newsletter_formproperties['submit_button_type']); ?> checked="checked"/>
                     <label for="awp_newsletter_btn" >Button</label>
                     <input type="radio" id="awp_newsletter_img" value="image" name="awp_newsletterform_submit_type"  <?php checked('image',$newsletter_formproperties['submit_button_type']); ?> />
                     <label for="awp_newsletter_img" >Image</label>
					</td>
				</tr>
                                 <tr valign="top">
					<th><label for="awp_newsletterform_submit_val"  id="awp_newsletterform_submit_val" ><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>
                      <td valign="top">
					  <?php $newsletter_formproperties['submit_button_val'] = (!empty($newsletter_formproperties['submit_button_val'])) ? $newsletter_formproperties['submit_button_val'] : ''; ?>
                      <input type="text" name="awp_newsletterform_submit_value" id="awp_newsletterform_submit_value" value="<?php echo esc_attr($newsletter_formproperties['submit_button_val']);?>" size="52"/>
                      <span id="newsletter_upload_img_button" style="display:none;"  >
                    <input id="newsletter_image_button" type="button" value="Upload Image" class="button-primary"/>
					<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
					</span>
					</td>
				</tr>
                        <tr valign="top">
				<th><label for="awp_newsletterform_customcss"><?php _e("Apptivo Target List", 'apptivo-businesssite' ); ?>:</label>
				<br><span class="description" valign="top">Select which category you want to add your newsletter.</span>
				</th>
				<td valign="top">
                          <select id="awp_newsletterform_category" name="awp_newsletterform_category">
                           <?php foreach($newsletter_categories as $category){  
							?>
						   <?php $newsletter_formproperties['category'] = (!empty($newsletter_formproperties['category'])) ? $newsletter_formproperties['category'] : null;
						   $category->id =  (!empty($category->id)) ? $category->id : '';
						   $category->name = (!empty($category->name)) ? $category->name : '';
						 ?>
                               <option value="<?php echo  esc_attr($category->id); ?>" <?php selected($category->id, $newsletter_formproperties['category']) ?> ><?php echo  esc_attr($category->name); ?></option>
                           <?php } ?>
                            </select>
				</td>
			</tr>
		</tbody>
	</table>
	


<br>
	<?php
	echo "<h3>" . __( 'Newsletter Form Fields', 'awp_newsletterform' ) . "</h3>";?>
	<div style="margin:10px;">
	<span class="description"><?php _e('Select and configure list of fields from below table to show in your Newsletter form.','apptivo-businesssite'); ?></span>
	<span style="margin-left:30px;">*Developers Guide - <a href="<?php echo awp_developerguide('newsletter-basicconfig');?>" target="_blank">Basic Newsletter Form Config.</a></span>
	</div>
	<br>
	<table width="700" cellspacing="0" cellpadding="0"
		id="newsletter_form_fields" name="newsletter_form_fields"
		style="border-collapse: collapse;">
		<tbody>
			<tr>
				<th></th>
			</tr>
			<tr align="center"
				style="background-color: rgb(223, 223, 223); font-weight: bold;"
				class="widefat">

				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?></td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?></td>				
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?></td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Order','apptivo-businesssite'); ?></td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite'); ?></td>
			</tr>
			<tr>
				<th></th>
			</tr>
			<?php
			
			$pos = 0;
			foreach( $this->get_master_newsletterform_fields() as $fieldsmasterproperties )
			{       
                                $enabled = 0;
                                $required = 0;
				$fieldExists=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				$validation=$fieldsmasterproperties['validation'];
				$fieldExistFlag="";
				if(!empty($newsletter_fields))
				{
					$fieldExistFlag= awp_recursive_array_search($newsletter_fields, $fieldid, 'fieldid');
				}
				
				if(trim($fieldExistFlag)!=="")
				{
					$enabled=1;
					$fieldData=array("fieldid"=>$fieldid,
									 "validation"=>$validation,
									 "fieldname"=>$fieldsmasterproperties['fieldname'],
									 "show"=>$enabled,
									 "required"=>$newsletter_fields[$fieldExistFlag]['required'],
									 "showtext"=>$newsletter_fields[$fieldExistFlag]['showtext'],
									 "order"=>$newsletter_fields[$fieldExistFlag]['order']);
				}else{
                                        if($fieldid=="newsletter_email")
                                        {
                                            $enabled = 1;
                                            $required = 1;
                                        }
					$fieldData=array("fieldid"=>$fieldid,
					 				 "validation"=>$validation,
									 "fieldname"=>$fieldsmasterproperties['fieldname'],
									 "show"=>$enabled,
									 "required"=> $required,
									 "showtext"=>"",
									 "order"=>"");
				}
			?>
			<tr>
				<td
					style="border: 1px solid rgb(204, 204, 204); padding-left: 10px;"><?php echo esc_attr($fieldData['fieldname'])?>
					<input type="hidden" id="<?php echo esc_attr($fieldData['fieldid'])?>_validation" name="<?php echo esc_attr($fieldData['fieldid'])?>_validation" value="<?php echo esc_attr($fieldData['validation'])?>" />
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);">
<input <?php  if($enabled) { ?> checked="checked" <?php } ?> <?php if($fieldData['fieldid']=="newsletter_email") { ?> disabled="disabled" <?php } ?> type="checkbox"  id="<?php echo esc_attr($fieldData['fieldid'])?>_show" name="<?php echo esc_attr($fieldData['fieldid'])?>_show" size="30" onclick="enablefield('<?php echo esc_attr($fieldData['fieldid'])?>')" >
              </td> 
				<td align="center" style="border: 1px solid rgb(204, 204, 204);">
				<input
				<?php
					if(!$enabled) { ?> disabled="disabled" <?php }  
				 else if($fieldData['required'] ) { ?>
					checked="checked" <?php }?>type="checkbox"
                                        <?php if($fieldData['fieldid']=="newsletter_email") { ?> disabled="disabled" <?php } ?>
					id="<?php echo esc_attr($fieldData['fieldid'])?>_require"
					name="<?php echo esc_attr($fieldData['fieldid'])?>_require" size="30"></td>
	<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					type="text"
					onkeypress="return isNumberKey(event)"
					id="<?php echo esc_attr($fieldData['fieldid'])?>_order"
					name="<?php echo esc_attr($fieldData['fieldid'])?>_order"
					value="<?php echo esc_attr($fieldData['order']); ?>" size="3"
					maxlength="2" <?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
					
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
				<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>
					type="text" id="<?php echo esc_attr($fieldData['fieldid'])?>_text"
					name="<?php echo esc_attr($fieldData['fieldid'])?>_text"
					value="<?php echo esc_attr($fieldData['showtext']); ?>"></td>
			</tr>
			<?php  } ?>

		</tbody>
	</table>
	<p class="submit">
		<input <?php echo esc_attr($disabledForm); ?> type="submit" name="awp_newsletterform_settings" id="awp_newsletterform_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration') ?>" />
	</p>
	
    </form>

    <?php
    }
	
    }

    function get_subscribeLists()
    {
	$templates = array("Subscribe"=>'subscribe');
	return $templates;
    }
    /**
     * getNewsletterCategory
     *
     * @return unknown
     */
    function getNewsletterCategory(){
     /*$category = getAllTargetListcategory();
     return $category;*/
		$category = getAllTargetList();
		$category->status = ( $category !== null && !empty($category->status)) ? $category->status : "";
		if($category->status == "SUCCESS" && $category->countOfRecords != ''){
			return $category;	
		}

    }

    function createformfield_array($fieldid,$showtext,$required,$type,$validation,$options,$displayorder){
		if(trim($displayorder)=="")
		$displayorder=0;
		$contactformfield= array(
	                        'fieldid'=>$fieldid,
	   					    'showtext' => $showtext,
	                        'required' => $required,
							'type' => $type,
							'validation' => $validation,
							'options' => $options,
	   					    'order' => $displayorder
		);
		return $contactformfield;
	}

    function register_widget(){
	    //register new widget in Available widgets
	        register_widget( 'AWP_Newsletter_Widget' );
    }

	function check_for_shortcode($posts) {
		$found=awp_check_for_shortcode($posts,'[apptivonewsletterform');
		if ($found){
             // load styles and scripts
	        $this->loadscripts();

	        
	    }
	    return $posts;
	}
	
    function loadscripts() {
      wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));
	  wp_print_scripts('jquery_validation');
	}

}
/**
 * Get All Targetlists from Apptivo.
 *
 * @return unknown
 */
function getAllTargetListcategory()
{
    if(_isCurl()){
        
        $awp_services_obj=new AWPAPIServices();
        $response = $awp_services_obj->getTargetListcategory();
        return $response;
    }
   
}

function target_lists_category($category)
{
   $target = new AWP_Newsletter();	
   $targetcategory = $target->getNewsletterCategory();   

   foreach($targetcategory->data as $targetLists):
    if($targetLists->id == $category)
	   {
	   	return $category;
	   }
   endforeach;
  
   
   if( $targetcategory->status == "SUCCESS" && $targetcategory->countOfRecords == 0) :
     return 'E_N001'; //target Lists not available
   else:
     return 'E_N002'; // Need to configuration newsletter once.
   endif;
}

add_action("admin_footer", "apptivo_business_newsletter_validation");

function apptivo_business_newsletter_validation() {
	?>
<script type="text/javascript">
    jQuery(document).ready(function($){

		/*Handled for Vulnerability Script Code Injection */
		$("#newnewsletterformname").blur(function(event) { 
		var text = $(this).val().replace(/[^\da-zA-Z0-9 ]/g,'');
		$(this).val(text);
		return true;
		});

		$("#awp_newsletterform_submit_value").blur(function(event) { 
			var text = $(this).val().replace(/[^\da-zA-Z0-9/:. ]/g,'');
			$(this).val(text);
			return true;
		});

	});
</script>
<?php } ?>
