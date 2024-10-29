<?php
/**
 * Apptivo Events plugin
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/MarketingEvent.php';
/**
 * Class AWP_Events
 */
class AWP_Events extends AWP_Base
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
    		if($settings["events"])
    			$this->_plugin_activated=true;
    	}
    }

    /**
     * Returns plugin instance
     *
     * @return AIP_Plugin_BrowserCache
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
    		add_action( 'widgets_init',array(&$this,'register_widget'));
    		add_shortcode('apptivo_events_fullview',array(&$this,'show_events_fullview'));
			add_shortcode('apptivo_events_inline',array(&$this,'show_events_inline'));
			add_action('the_posts',array(&$this,'check_for_shortcode'));
	   }
    }
   function check_for_shortcode($posts) {
		$events_fullView   = awp_check_for_shortcode($posts,'[apptivo_events_fullview');	
		$events_inlineView = awp_check_for_shortcode($posts,'[apptivo_events_inline');		
		if ($events_inlineView){
           // load styles and scripts	      
	       $this->loadscripts();
	    }	   
	    return $posts;
	}

  function loadscripts()
	{   
		wp_enqueue_script('jquery_cycleslider.js',AWP_PLUGIN_BASEURL. '/assets/js/jquery.cycle.all.latest.js',array('jquery'));
   	}
    function options() {
        //echo gmdate(DATE_ATOM,mktime(0,0,0,gmdate('m'),gmdate('d')+2,gmdate('Y')+20)); exit;
        ?>
            <div class="wrap">
            <h2><?php _e('Events Management','apptivo-businesssite');?></h2>
            <?php checkSoapextension("Events"); 
            checkCaptchaOption();
            ?>
            </div>
            <?php
            if( $_REQUEST['keys'] == 'fullviewsetting')
            {
             $eventsClass  = 'nav-tab';
             $fullviewsettingClass = 'nav-tab nav-tab-active';
             $inlineviewsettingClass = 'nav-tab';
            }else if( $_REQUEST['keys'] == 'inlineviewsetting'){
             $eventsClass = 'nav-tab';
             $fullviewsettingClass  = 'nav-tab';
             $inlineviewsettingClass = 'nav-tab nav-tab-active';
            }else {
             $eventsClass = 'nav-tab nav-tab-active';
             $fullviewsettingClass  = 'nav-tab';
             $inlineviewsettingClass = 'nav-tab';
            }
             ?>
            <div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('events_icon'); ?>') " ><br></div> 
            <h2 class="nav-tab-wrapper">
            <a class="<?php echo $eventsClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_events"><?php _e('Events','apptivo-businesssite');?></a>
            <a class="<?php echo $fullviewsettingClass; ?>" href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_events&keys=fullviewsetting"><?php _e('Full View Settings','apptivo-businesssite');?></a>
            <a class="<?php echo $inlineviewsettingClass; ?>" href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_events&keys=inlineviewsetting"><?php _e('Inline View Settings','apptivo-businesssite');?></a>
            </h2>
            
       
	   <p>
	   <img id="elementToResize" src="<?php echo awp_flow_diagram('events');?>" alt="Events" title="Events"  />
	   </p>
	  	       
        <p style="margin:10px;">
		For Complete instructions,see the <a href="<?php echo awp_developerguide('events');?>" target="_blank">Developer's Guide.</a>
		</p>          
         <?php
             if(!$this->_plugin_activated){
                    echo "Events Plugin is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
                }
            if (isset($_POST['awp_events_add']) && ($_POST['nogdog'] == $_SESSION['apptivo_single_events']) ) {          //Events Add.
            	    $addevents_response = $this->add_events(); 
            	   
                    if ( strlen(trim($_POST['awp_events_title'])) == 0 )
                    {
                            $_SESSION['awp_events_messge'] = 'Please Enter a Events title';
                            
                    }else if($addevents_response == 'E_100')
                    {
                    	$_SESSION['awp_events_messge'] = '<span style=color:#f00;">Invalid Keys</span>';
                    }else if($addevents_response->return->statusCode != '1000')
                    {
                    	$_SESSION['awp_events_messge'] = '<span style=color:#f00;">'.$addevents_response->return->statusMessage.'</span>';
                    }else {
                          $_SESSION['awp_events_messge'] = 'Events Added Successfully';
                    }

            }else if ($_POST['awp_events_update'] == 'Update') {  //Events Update.
                 $updateevents_response = $this->update_events();
                
            if($updateevents_response->return->statusCode != '1000')
            {
            	$_SESSION['awp_events_messge'] = '<span style=color:#f00;">'.$updateevents_response->return->statusMessage.'</span>';
            }else {
                 $_SESSION['awp_events_messge'] = 'Events Updated Successfully';
            }
            }else if ($_REQUEST['tstmode'] == 'delete') {   //Events Delete.
                $deleteevents_response = $this->delete_events();
             if($deleteevents_response->return->statusCode != '1000')
            {
            	$_SESSION['awp_events_messge'] = '<span style=color:#f00;">'.$deleteevents_response->return->statusMessage.'</span>';
            }else {
                $_SESSION['awp_events_messge'] = 'Events Deleted Successfully';
            }
            }else {
                    $_SESSION['awp_events_messge'] = '';
            }
        
            switch($_REQUEST['keys'])
            {
                    case fullviewsetting:
                            $this->fullViewSettings();
                            break;
                    case inlineviewsetting:
                            $this->inlineViewSettings();
                            break;
                    default :
                            $this->get_all_events();            //Display Events
                            if ($_REQUEST['tstmode'] == 'edit')
                            {        $eventsId = $_REQUEST['tstid'];
       								 $response = getMarketingEventById($eventsId);
       								 $events = $response->return;
					                 if($events->statusCode != '1000')
							         {  
							         	echo '<div class="message" id="newsmessage" style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);">
					      	                <p style="margin: 0.5em; padding: 2px;"><span style="color: rgb(255, 0, 0);">'.$events->methodResponse->statusMessage.'</span></p></div>';
							         }
                                     $this->edit_events($events);   //Events Edit form.
                            } else {
                                    $this->events_form();           //Events Create Form    
                            }
                            break;
            }
            ?>
<style type="text/css">
	.awp_eventsform td { width:90px;}	          
</style>             

            <?php
       
    }
    /* Add events */
    function add_events() {

            $awp_events_options = array(
                    'Title' => stripslashes($_POST['awp_events_title']),
                    'Description' => stripslashes($_POST['awp_events_desc']),
                    'startdate' => gmdate(DATE_ATOM,mktime()),
                    'enddate' =>gmdate(DATE_ATOM,mktime(0,0,0,gmdate('m'),gmdate('d'),gmdate('Y')+20)),
                    'Link' => stripslashes($_POST['awp_events_link']),
                    'publishedat' => stripslashes($_POST['awp_events_published_at']),
                    'publishedby' => stripslashes($_POST['awp_events_published_by']),
                    'imageurl' => stripslashes($_POST['awp_events_imageurl']),
                    'showflag' => stripslashes($_POST['awp_events_show']),
                    'order' => stripslashes($_POST['awp_events_order']),
                  );
             $awp_events_options= wp_parse_args($awp_events_options,array(
                    'Title' => '',
                    'Description' => '',
                    'startdate' =>'',
                    'enddate' =>'',
                    'Link' => '',
                    'publishedat' =>'',
                    'publishedby' => '',
                    'imageurl' =>'',
                    'showflag' => '',
                    'order' => ''
                ));
            extract($awp_events_options);
            $Description = apply_filters('the_content', $Description);
            $response = addEvents($Title, $Description, $startdate, $enddate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $Link, $publishedat, $publishedby, $order, $marketingEventId,null,$imageurl);
            return $response;
    }

    //Update events
    function update_events() {
            $marketingEventId = $_POST['awp_tstid'];
            $awp_events_options = array(
                    'Title' => stripslashes($_POST['awp_events_title']),
                    'Description' => stripslashes($_POST['awp_events_desc_update']),
                    'startdate' =>$_POST['startdate'],
                    'enddate' =>$_POST['enddate'],
                    'Link' => $_POST['awp_events_link'],
                    'publishedat' => stripslashes($_POST['awp_events_published_at']),
                    'publishedby' => stripslashes($_POST['awp_events_published_by']),
                    'imageurl' => stripslashes($_POST['awp_events_imageurl']),
                    'showflag' => stripslashes($_POST['awp_events_show']),
                    'order' => stripslashes($_POST['awp_events_order'])
                  );
             $awp_events_options= wp_parse_args($awp_events_options,array(
                    'Title' => '',
                    'Description' => '',
                    'startdate' => '',
                    'enddate' => '',
                    'Link' => '',
                    'publishedat' =>'',
                    'publishedby' => '',
                    'imageurl' =>'',
                    'showflag' => '',
                    'order' => ''
                ));
            extract($awp_events_options);
            $Description = apply_filters('the_content', $Description);
            $response = updateEvents($Title, $Description, $startdate, $enddate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $Link, $publishedat, $publishedby, $order, $marketingEventId,null,$imageurl);
            return $response;
    }
    //Delete events
    function delete_events(){
            $enddate=gmdate(DATE_ATOM,mktime(0,0,0,gmdate('m'),gmdate('d')-1,gmdate('Y')));
            $marketingEventId = $_REQUEST['tstid'];
            $response = getMarketingEventById($marketingEventId);
            $eventdetails = $response->return;
            $response = updateEvents($eventdetails->eventName, $eventdetails->description, $eventdetails->startDate, $enddate, $eventdetails->displayFirstName, $eventdetails->displayLastName, $eventdetails->displayAddress, $eventdetails->displayEmailId, $eventdetails->displayPhoneNumber, $eventdetails->sendRegistrationEmail, $eventdetails->registrantFirstName, $eventdetails->registrantLastName, $eventdetails->registrantEmailId, $eventdetails->registrantPhoneNumber, $eventdetails->registrantAddressLine1, $eventdetails->registrantAddressLine2, $eventdetails->registrantCity, $eventdetails->registrantStateCode, $eventdetails->registrantStateName, $eventdetails->registrantPinCode, $eventdetails->registrantCountryCode, $eventdetails->registrantCountryName, $eventdetails->pageSectionImages, $eventdetails->link, $eventdetails->publishedAt, $eventdetails->publishedBy, $eventdetails->sequenceNumber, $eventdetails->marketingEventId);
            return $response;

    }
    //Save Events Settings
    function save_events_settings() {
        if ($_POST['awp_events_templatetype'] == "awp_plugin_template")
            $events_layout = $_POST['awp_events_plugintemplatelayout'];
        else
            $events_layout = $_POST['awp_events_themetemplatelayout'];
        $awp_events_settings = array(
            'template_type' => $_POST['awp_events_templatetype'],
            'template_layout' => $events_layout,
            'custom_css' => stripslashes($_POST['custom_css']),
            'order' => $_POST['order'],
            'page_ID' => $_POST['page_ID'],
            'itemsperpage' => (!empty($_POST['itemsperpage'])) ? $_POST['itemsperpage'] : 5
        );
       
        update_option('awp_events_settings', $awp_events_settings);
    }
    //Full View Settings Form
    function fullview_settings() {
        $awp_events_settings = get_option('awp_events_settings');
        //update page content with shortcode
        //theme templates
        $awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/events','Plugin');
        //Plugin templates
        $awp_tst_plugintemplates = get_awpTemplates(AWP_EVENTS_TEMPLATEPATH,'Plugin');
        ksort($awp_tst_plugintemplates);
        if( empty($awp_events_settings) )
        {
        	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for full view. </span>';
        }    
        ?>
        <form action="" class="awp_eventsform" name="awp_events_full" method="post">
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                    <?php if(isset($awp_events_settings) && !empty($awp_events_settings)) {?>
                    <tr valign="top">
					<td valign="top"><label for="events_fullview_shortcode">Shortcode:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the events.','apptivo-businesssite');?></span>
					</td>
					<td valign="top"><span id="awp_customform_shortcode" name="awp_customform_shortcode">
					<input type="text" style="width: 300px;" id="events_fullview_shortcode" name="events_fullview_shortcode" readonly="true" value="[apptivo_events_fullview]">
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('events-fullview-shortcode');?>" target="_blank">Events Fullview Shortcodes.</a></span>
					</span>
					</td>
				    </tr> <?php } ?>
                                    <tr valign="top"> <td><?php _e('Template Type','apptivo-businesssite');?></td>
                                        <td valign="top">
                                           <select name="awp_events_templatetype" id="awp_events_templatetype" onchange="events_change_template();">
                                                <option value="awp_plugin_template" <?php selected($awp_events_settings['template_type'],'awp_plugin_template'); ?> ><?php _e('Plugin Templates','apptivo-businesssite');?></option>
                                                <?php if(!empty($awp_tst_themetemplates)) : ?>
                                                <option value="theme_template" <?php selected($awp_events_settings['template_type'],'theme_template'); ?> >Templates from Current Theme</option>
                                                <?php endif; ?>
                                           </select>
                                            <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('events-fullview-template');?>" target="_blank">Events Fullview Templates.</a></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php _e('Select Layout','apptivo-businesssite');?></td>
                                        <td valign="top">
                                        <select name="awp_events_plugintemplatelayout" id="awp_events_plugintemplatelayout" <?php if($awp_events_settings['template_type'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
                                                 <?php foreach (array_keys($awp_tst_plugintemplates) as $template) : ?>
				                                <option value="<?php echo $awp_tst_plugintemplates[$template] ?>" <?php selected($awp_tst_plugintemplates[$template],$awp_events_settings['template_layout']); ?> >
				                                <?php echo $template ?>
				                                 </option>
                                               <?php endforeach; ?>
                                             </select> 
                                             
                                             <select name="awp_events_themetemplatelayout" id="awp_events_themetemplatelayout" <?php if($awp_events_settings['template_type'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
                                              <?php foreach (array_keys($awp_tst_themetemplates) as $template) : ?>
                                   				<option value="<?php echo $awp_tst_themetemplates[$template] ?>" <?php selected($awp_tst_themetemplates[$template],$awp_events_settings['template_layout']); ?> ><?php echo $template ?> </option>
                            				  <?php endforeach; ?>
                                             </select>
                                             
                                         </td>
                                     </tr>
                                     <tr><td><?php _e('Order','apptivo-businesssite');?></td><td>
                                             <select  name="order">
                                                 <option value="1" <?php selected('1', $awp_events_settings['order']); ?> >Newest First</option>
                                                 <option value="2" <?php selected('2', $awp_events_settings['order']); ?> >Oldest First</option>
                                                 <option value="3" <?php selected('3', $awp_events_settings['order']); ?> >Random Order</option>
                                                 <option value="4" <?php selected('4', $awp_events_settings['order']); ?> >Custom Order</option>
                                             </select>
                                         </td></tr>

                                     <tr><td valign="top"><?php _e('Events','apptivo-businesssite');?>Custom CSS</td>
                                     <td><textarea name="custom_css" cols="30" rows="5"><?php echo $awp_events_settings['custom_css']; ?></textarea>
                                     <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('events-fullview-customcss');?>" target="_blank">Events Fullview CSS.</a></span>
                                     </td></tr>
                                         <tr><td></td><td>
                                         <input type="submit" <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?>  value="Save Settings" name="full_view_settings" class="button-primary" /></td></tr>
                                     </table>

        </form>
      
        
        
        <?php
    }
    //Save Inline View settings
    function save_inline_settings()
    {
         if ($_POST['awp_events_templatetype'] == "awp_plugin_template"):
            $events_layout = $_POST['awp_events_plugintemplatelayout'];
        else:
            $events_layout = $_POST['awp_events_themetemplatelayout'];
        endif;    
        //Inline Events items to show.
         $inline_events_itemtoshow = $_POST['itemstoshow'];
         if(!is_numeric($_POST['itemstoshow']) || $_POST['itemstoshow'] <= 0 ):
         	$inline_events_itemtoshow =   AWP_DEFAULT_ITEM_SHOW;
         endif; 
             
        $awp_events_inline_settings = array(
                    'template_type' => $_POST['awp_events_templatetype'],
                    'template_layout' => $events_layout,
                     'style' => $_POST['style'],
                     'custom_css' => stripslashes($_POST['custom_css']),
                     'order' => $_POST['order'],
                     'itemstoshow' => $inline_events_itemtoshow,
                     'more_text' => (trim($_POST['more_text'])!="")?$_POST['more_text']:AWP_DEFAULT_MORE_TEXT,
                     'page_ID' => $_POST['page_ID'],
                     );
      
        update_option('awp_events_inline_settings', $awp_events_inline_settings);
    }
    //Inline View Settings form
    function inlineview_settings(){
        $awp_events_inline_settings = get_option('awp_events_inline_settings');
        //theme templates
        $awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/events','Inline');
        //plugin templates
        $awp_tst_plugintemplates = get_awpTemplates(AWP_EVENTS_TEMPLATEPATH,'Inline');
        ksort($awp_tst_plugintemplates);
        if( empty($awp_events_inline_settings) )
        {
        	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for inline view. </span> ';
        }  
         ?>
        <form action="" class="awp_eventsform" name="awp_events_inline" method="post">
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
            <?php if(isset($awp_events_inline_settings) && !empty($awp_events_inline_settings))
            { ?>
            <tr valign="top">
					<td valign="top"><label for="events_inlineview_shortcode">Shortcode:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the events.','apptivo-businesssite'); ?></span>
					</td>
					<td valign="top"><span id="awp_customform_shortcode" name="awp_customform_shortcode">
					<input type="text" style="width: 300px;" id="events_inlineview_shortcode" name="events_inlineview_shortcode" readonly="true" value="[apptivo_events_inline]">
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('events-inline-shortcode');?>" target="_blank">Events Inline Shortcodes.</a></span>
					</span>
					</td>
				    </tr>
		  <?php } ?>
<tr valign="top"> <td><?php _e('Template Type','apptivo-businesssite'); ?></td>
                                        <td valign="top"><select
                                                name="awp_events_templatetype"
                                                id="awp_events_templatetype" onchange="events_change_template();">
                                                <option value="awp_plugin_template" <?php selected($awp_events_inline_settings['template_type'],'awp_plugin_template'); ?> ><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
                                                <?php if(!empty($awp_tst_themetemplates)): ?>
                                                <option value="theme_template" <?php selected($awp_events_inline_settings['template_type'],'theme_template'); ?> >Templates from Current Theme</option>
                                                <?php endif;?>
                                            </select>
                                            <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('events-inline-template');?>" target="_blank">Events Inline Templates.</a></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php _e('Select Layout','apptivo-businesssite'); ?></td>
                                        <td valign="top">
                                        <select name="awp_events_plugintemplatelayout" id="awp_events_plugintemplatelayout" <?php if($awp_events_inline_settings['template_type'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
                                            <?php foreach (array_keys($awp_tst_plugintemplates) as $template) : ?>
                                			<option value="<?php echo $awp_tst_plugintemplates[$template] ?>" <?php selected($awp_tst_plugintemplates[$template],$awp_events_inline_settings['template_layout']); ?> > 
                                        		<?php echo $template; ?>
                                         	</option>
                                            <?php endforeach; ?>
                                        </select> 
                                        
                                        <select name="awp_events_themetemplatelayout" id="awp_events_themetemplatelayout" <?php if($awp_events_inline_settings['template_type'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
                                              <?php foreach (array_keys($awp_tst_themetemplates) as $template) : ?>
                                              <option value="<?php echo $awp_tst_themetemplates[$template] ?>" <?php selected($awp_tst_themetemplates[$template],$awp_events_inline_settings['template_layout']); ?>  >
                                			  	<?php echo $template; ?>
                                              </option>
                                              <?php endforeach; ?>
                                         </select>
                                         
                                         </td>
                                     </tr>
                                        <tr><td><?php _e('Order','apptivo-businesssite'); ?></td>
                                        <td>
                                            <select  name="order">
                                                <option value="1" <?php selected('1', $awp_events_inline_settings['order']); ?> >Newest First</option>
                                                <option value="2" <?php selected('2', $awp_events_inline_settings['order']); ?> >Oldest First</option>
                                                <option value="3" <?php selected('3', $awp_events_inline_settings['order']); ?> >Random Order</option>
                                                <option value="4" <?php selected('4', $awp_events_inline_settings['order']); ?> >Custom Order</option>
                                            </select>
                                        </td></tr>
                                    <tr>
                                        <td><?php _e('Number of items to show','apptivo-businesssite'); ?></td>
                                        <td><input type="text" class="num" name="itemstoshow" value="<?php echo ($awp_events_inline_settings['itemstoshow'] == '')?AWP_DEFAULT_ITEM_SHOW:$awp_events_inline_settings['itemstoshow'];?>" size="3"/>&nbsp;&nbsp; <small>( Default :  <?php echo AWP_DEFAULT_ITEM_SHOW; ?> ) </small></td>
                                    </tr>
                                    <tr><td><?php _e('More items Link title','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="more_text" value="<?php echo($awp_events_inline_settings['more_text'] == '')?AWP_DEFAULT_MORE_TEXT:$awp_events_inline_settings['more_text'];?>"/>&nbsp;&nbsp; <small>( Default :  <?php echo AWP_DEFAULT_MORE_TEXT;?> ) </small></td></tr>
                                    <tr><td><?php _e('Full View  page name','apptivo-businesssite'); ?></td><td>
                        <?php wp_dropdown_pages(array('name' => 'page_ID', 'selected' => $awp_events_inline_settings['page_ID'])); ?>
                        </td></tr>
                         <tr><td valign="top">Custom CSS</td>
                         <td><textarea name="custom_css" cols="30" rows="5"><?php echo $awp_events_inline_settings['custom_css']; ?></textarea>
                         <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('events-inline-customcss');?>" target="_blank">Events Inline CSS.</a></span>
                         </td></tr>
                    <tr><td></td><td>
                    <input type="submit" <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?>  value="Save Settings" name="inline_view_settings" class="button-primary" /></td></tr>
                </table>

        </form>
       
        
        <?php
    }
    //events Form
    function events_form(){
        ?>
        <div class="wrap addevents">
             <h2>Add Events</h2>
        <?php $nogdog = uniqid();$_SESSION['apptivo_single_events'] = $nogdog; ?>
        <form method="post" action="" name="awp_events_form" onsubmit="return validateevents('add')" >
         <input type="hidden" name="nogdog" value="<?php echo $nogdog;?>" >
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                                     <tr>
                                        <td><?php _e('Title','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
                                        <td><input type="text" name="awp_events_title" id="awp_events_title" value="" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><?php _e('Description','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
                                        <td>
                                        <div style="width:630px;">
                                         <?php the_editor($updated_value,'awp_events_desc','',FALSE);  ?>
                                        </td>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Link','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_link" id="awp_events_link" value="" size="63"/>&nbsp;<small>(For ex: http://www.example.com/)</small></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published at','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_published_at" id="awp_events_published_at" value="" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published by','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_published_by" id="awp_events_published_by" value="" size="63"/></td>
                                    </tr>
                                     <tr valign="top">
									<th scope="row"><?php _e('Image URL','apptivo-businesssite'); ?></th>
									<td><label for="upload_image">
									<input id="awp_events_imageurl" type="text" size="50" name="awp_events_imageurl" value="" />
									<input id="events_upload_image" type="button" value="Upload Image"  class="button-primary" />
									<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
									</label></td>
									</tr>
									
                                    <tr>
                                        <td><?php _e('Order to show','apptivo-businesssite'); ?></td>
                                        <td><input type="text" class="num" name="awp_events_order" id="awp_events_order" value="" size="3" /></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input type="submit" <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?>  value="<?php _e('Add events','apptivo-businesssite'); ?>" name="awp_events_add" class="button-primary"/></td>
                                    </tr>

                                </table>

        </form>
        </div>
            <?php
    }
    //Edit events Form
    function edit_events($events){
         ?>
        <div class="wrap addevents">
        <h2>Edit Events</h2>
        <form method="post" action="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_events" name="awp_events_form" onsubmit="return validateevents('edit')" >
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td><?php _e('Title','apptivo-businesssite'); ?><span style="color:#f00;">*</span></td>
                                        <td><input type="text" name="awp_events_title" id="awp_events_title" value="<?php echo $events->eventName; ?>" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><?php _e('Title','apptivo-businesssite'); ?>Description<span style="color:#f00;">*</span></td>
                                        <td>
                                        <div style="width:630px;">
                                        <?php  
                                        $updated_value = $events->description;
                                        the_editor($updated_value,'awp_events_desc_update','',FALSE); ?>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Link','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_link" id="awp_events_link" value="<?php echo $events->link; ?>" size="63"/>&nbsp;<small>(For ex: http://www.example.com/)</small></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published at','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_published_at" id="awp_events_published_at" value="<?php echo $events->publishedAt; ?>" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published by','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_published_by" id="awp_events_published_by" value="<?php echo $events->publishedBy; ?>" size="63"/></td>
                                    </tr>
                                    
                                    <tr valign="top">
									<th scope="row"><?php _e('Image URL','apptivo-businesssite'); ?></th>
									<td><label for="upload_image">
									<input id="awp_events_imageurl" type="text" size="50" name="awp_events_imageurl" value="<?php if(!is_array($events->eventImages)){ echo $events->eventImages; } else{ echo $events->eventImages[0]; } ?>" />
									<input id="events_upload_image" type="button" value="Upload Image" class="button-primary" />
									<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
									</label></td>
									</tr>
									
                                    <tr>
                                        <td><?php _e('Order to show','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_events_order" class="num" id="awp_events_order" value="<?php echo $events->sequenceNumber; ?>" size="3" /></td>
                                    </tr>
                                    <tr>
                                        <td><input type="hidden" name="startdate" value="<?php echo $events->startDate; ?>"/>
                                        <input type="hidden" name="enddate" value="<?php echo $events->endDate; ?>"/>
                                        </td>
                                        <td><input type="hidden" name="awp_tstid" value="<?php echo $events->marketingEventId; ?>"/>
                                        <input type="submit" <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?>  value="<?php _e('Update','apptivo-businesssite'); ?>" name="awp_events_update" class="button-primary"/></td>
                                    </tr>

            </table>
        </form>
        </div>
        <?php
    }
    //Display All events
    function get_all_events(){
   	$all_awp_events = $this->getAllEvents();
    $numberofevents = count($all_awp_events);
   	$eventsperpage =5;
   	$tpages = ceil($numberofevents/$eventsperpage); 
   	$currentpage   = intval($_GET['pageno']);
   	if($currentpage<=0)  $currentpage  = 1;
   	if($currentpage>=$tpages)  $currentpage  = $tpages;
   	$start = ( $currentpage - 1 ) * $eventsperpage;
   	$all_awp_events = array_slice( $all_awp_events, $start, $eventsperpage );
   	$reload = $_SERVER['PHP_SELF'].'?page=awp_events';   	

    if (!empty($_SESSION['awp_events_messge']) && strlen(trim($_SESSION['awp_events_messge'])) != 0 ) :
            	echo '<div style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);width:80%;" id="newsmessage" class="message">
      	<p style="margin: 0.5em; padding: 2px;">'.$_SESSION['awp_events_messge'].'</p></div>';
    endif;
      
    if(!empty($all_awp_events[0])){
         ?>
        <div class="wrap">
       
        <?php

      
        
        if( $numberofevents > $eventsperpage)
        {
        echo awp_paginate($reload,$currentpage,$tpages,$numberofevents);
        }  ?>
       
        <table class="widefat plugins" width="700" cellspacing="0" cellpadding="0">
        <thead>
                                    <tr>
                                        <th><?php _e('Title','apptivo-business-site'); ?></th>
                                        <th><?php _e('Description','apptivo-business-site'); ?></th>
                                        <th><?php _e('Link','apptivo-business-site'); ?></th>
                                        <th><?php _e('Published at','apptivo-business-site'); ?></th>
                                        <th><?php _e('Published by','apptivo-business-site'); ?></th>
                                        <th><?php _e('Order to show','apptivo-business-site'); ?></th>
                                        <th><?php _e('Edit','apptivo-business-site'); ?></th>
                                        <th><?php _e('Delete','apptivo-business-site'); ?></th>
                                    </tr>
       </thead>
        <tfoot>
                                    <tr>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Tile','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Description','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Link','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Published at','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Published by','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Order to show','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Edit','apptivo-business-site'); ?></th>
                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Delete','apptivo-business-site'); ?></th>
                                    </tr>
       </tfoot>
       <tbody id="the-list">
                                    <?php
                                    foreach ($all_awp_events as $events) {
                                    	if( $_GET['tstid'] == $events->marketingEventId )
                                    	{
                                    		$class = "active";
                                    	}else { $class = "inactive";}
                                    ?><tr id="<?php echo $events->marketingEventId;?>" class="<?php echo $class; ?>">
                                            <td><?php echo $events->eventName; ?></td>
                                            <td><div><p>
                                             <?php if (strlen(strip_tags(html_entity_decode($events->description))) < 30)
		                                            {
		                                                echo strip_tags(html_entity_decode($events->description));
		                                            }                                                  
		                                            else
                                                  	{  
                                                  		 $sub = strip_tags(html_entity_decode($events->description));                                                  	 
													     echo $sub = substr($sub, 0, 30).'...';
                                                  	}										       
                                            ?>
                                            </p></div></td>
                                            <td><?php if(strlen($events->link)>20)echo substr($events->link,0,20); else echo $events->link; ?></td>
                                            <td><?php echo $events->publishedAt; ?></td>
                                            <td><?php echo $events->publishedBy; ?></td>
                                            <td><?php echo $events->sequenceNumber; ?></td>
                                            <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_events&amp;tstmode=edit&amp;tstid=<?php echo $events->marketingEventId;?>&amp;pageno=<?php echo $currentpage;?>"><img src="<?php echo awp_image('edit_icon'); ?>"/></a></td>
                                            <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_events&amp;tstmode=delete&amp;tstid=<?php echo $events->marketingEventId;?>" onclick="return delete_events('<?php echo $this->_plugin_activated; ?>')" ><img src="<?php echo awp_image('delete_icon'); ?>"/></a></td>
                                    </tr>
                                    <?php
                                     }
            ?></tbody>
                   
       </table>
        </div>
        
        <?php
            }
    }
    //ShortCode For events Full View
    function show_events_fullview(){
    	 $awp_events_settings = get_option('awp_events_settings');
         $awp_events = $this->getAllEventsForfullView();
         ob_start();
	      if(empty($awp_events_settings))
        	{
        		echo awp_messagelist('eventsconfigure-display-page'); // Events are not configured 
        	}else if(empty($awp_events[allevents]))
	        {  
	        	echo awp_messagelist('events-display-page');         // Events are not found. 
	        }else { include $awp_events['templatefile']; }
	            
		$show_events = ob_get_clean();
        return $show_events;
    }

	 function display_events()
	    {
	    	$awp_events = $this->getAllEventsForInline();
	    	$awp_events['allevents'] = array_slice($awp_events['allevents'],0,$awp_events['itemstoshow']);
	        unset($awp_events['templatefile']);
	        unset($awp_events['custom_css']);
	        return $awp_events;           
	    }
     
    //Short code for inline view
    function show_events_inline(){
        $awp_events_inline_settings = get_option('awp_events_inline_settings');
        $events_content = $this->getAllEventsForInline();
        $events_content[apptivo_methodresponse]->responseCode;
        ob_start();
        
        	if(empty($awp_events_inline_settings))
        	{
        		echo awp_messagelist('eventsconfigure-display-page'); // Events are not configured 
        	} else if(empty($events_content[allevents]))
	        {
	        	echo awp_messagelist('events-display-page');         // Events are not found.    
	        }else { include $events_content['templatefile']; }
      
        $show_events = ob_get_clean();
        return $show_events;
            
    }
    function getAllEventsForInline(){

            $awp_all_events=array();
            $awp_events_inline_settings = get_option('awp_events_inline_settings');
            $page_details = get_page($awp_events_inline_settings['page_ID']);
            $response = getAllEvents();
	        $all_awp_events = awp_convertObjToArray($response->return->eventsList);
	        $allevents=array();
	        $currentdate = gmdate(DATE_ATOM,mktime());
	        if( count($all_awp_events)>0){
	        foreach($all_awp_events as $events){
	        if(strtotime($events->startDate)<=strtotime($currentdate) && strtotime($events->endDate)>=strtotime($currentdate)){
	           array_push($allevents,$events);
	        }
	        }
	        }
	        $awp_all_events = $allevents;
            
	         $order=$awp_events_inline_settings['order'];
            $awp_all_events = $this->sortNewsByOrder($awp_all_events, $order);
            if($awp_events_inline_settings['itemstoshow']!=0){
            $numberofitems = $awp_events_inline_settings['itemstoshow'];
            }
            else{
             $numberofitems = count($awp_all_events);
            }
            //include template files
            if($awp_events_inline_settings['template_type']=="awp_plugin_template") :
                    $templatefile=AWP_EVENTS_TEMPLATEPATH."/".$awp_events_inline_settings['template_layout']; //plugin template
            else :
                    $templatefile=TEMPLATEPATH."/events/".$awp_events_inline_settings['template_layout']; //theme template
            endif;        
                    
           if (!file_exists($templatefile))
            {   
            	$templatefile = AWP_EVENTS_TEMPLATEPATH."/sliderview1.php";
            }       
            $events = array();
            $events['allevents'] = $awp_all_events;
            $events['custom_css'] = $awp_events_inline_settings[custom_css];
            $events['itemstoshow'] = $numberofitems;
            $events['templatefile'] = $templatefile;
            $events['pagelink'] = $page_details->guid;
            $events['more_text'] = $awp_events_inline_settings['more_text'];
            return $events;


    }
    function getAllEventsForfullView(){
            $awp_events_settings = get_option('awp_events_settings');
            //include template files.
            if($awp_events_settings['template_type']=="awp_plugin_template") :
                    $templatefile=AWP_EVENTS_TEMPLATEPATH."/".$awp_events_settings['template_layout']; //plugin template
            else :
                    $templatefile=TEMPLATEPATH."/events/".$awp_events_settings['template_layout']; //theme template
            endif;        
    		if (!file_exists($templatefile))
            {   
            	$templatefile = AWP_EVENTS_TEMPLATEPATH."/".AWP_EVENTS_DEFAULT_TEMPLATE;
            }
            $awp_events=array();
	        
            $response = getAllEvents();
	    	$all_awp_events = awp_convertObjToArray($response->return->eventsList);
	        $allevents=array();
	        $currentdate = gmdate(DATE_ATOM,mktime());
	        if( count($all_awp_events)>0){
	        foreach($all_awp_events as $events){
	        if(strtotime($events->startDate)<=strtotime($currentdate) && strtotime($events->endDate)>=strtotime($currentdate)){
	           array_push($allevents,$events);
	        }
	        }
	        }
        
	       /* For Default Config*/
            $events_pageid = get_option('awp_events_pageid');
            if($events_pageid != '') {
               if( count($allevents) == 0 || empty($allevents))
	           {
	           $allevents = dummy_events();
            
	           }
            }
            /* For Default Config*/
            
            $awp_events = $allevents;
           
            $order=$awp_events_settings['order'];
            $awp_events = $this->sortNewsByOrder($awp_events,$order);
            $events = array();
            $events['allevents'] = $awp_events;
            $events['custom_css'] = $awp_events_settings[custom_css];
            $events['templatefile'] = $templatefile;
            //$events['apptivo_methodresponse'] = $response->return->methodResponse;
            return $events;

    }
    function sortNewsByOrder($awp_all_events,$order){
        switch($order){
                case '1':
                    usort($awp_all_events,'awp_creation_date_compare');
                    break;
                case '2':
                    usort($awp_all_events,'awp_creation_date_compare');
                    $awp_all_events = array_reverse($awp_all_events);
                    break;
                case '3':
                    shuffle($awp_all_events);
                    break;
                default:
                    usort($awp_all_events,'awp_sort_by_sequence');

             }
             return $awp_all_events;
    }
    /*
     * Register Widget
     */
    function register_widget(){
	    //register new widget in Available widgets
	        register_widget( 'AWP_Events_Widget' );
    }
    /**
     * To Call Full View Settings.
     */
    function fullViewSettings()
    {
    	 ?>   <div class="wrap">
          
        <?php
        if (isset($_POST['full_view_settings'])){
            $this->save_events_settings();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Full View Settings Saved Successfully.
             </p></div>';
        }
        $this->fullview_settings();
        ?>
        </div>
        <?php
    }
    /**
     * To Call Inline View Settings.
     *
     */
    function  inlineViewSettings()
    {
    	?>
        <div class="wrap">
           
        <?php
        if (isset($_POST['inline_view_settings'])) {
            $this->save_inline_settings();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Inline View Settings Saved Successfully.
                </p></div>';
        }
       $this->inlineview_settings();
        ?>
        </div>
        <?php 
    }
    //function to get all Events from apptivo
    function getAllEvents(){
      	$response = getAllEvents();
      	$all_awp_events = awp_convertObjToArray($response->return->eventsList);
        $allevents=array();
        $currentdate = gmdate(DATE_ATOM,mktime());
        if( count($all_awp_events)>0){
        foreach($all_awp_events as $events){
        if(strtotime($events->startDate)<=strtotime($currentdate) && strtotime($events->endDate)>=strtotime($currentdate)){
           array_push($allevents,$events);
        }
        }
        }
        return $allevents;
    }
    
    function getalleventsView()
    {
    	$response = getAllEvents();
    	$all_awp_events = awp_convertObjToArray($response->return->eventsList);
        $allevents=array();
        $currentdate = gmdate(DATE_ATOM,mktime());
        if( count($all_awp_events)>0){
        foreach($all_awp_events as $events){
        if(strtotime($events->startDate)<=strtotime($currentdate) && strtotime($events->endDate)>=strtotime($currentdate)){
           array_push($allevents,$events);
        }
        }
        }
        return $allevents;
    	
    }

       
}
/**
 * To get All Events.
 *
 * @return unknown
 */
function getAllEvents()
{   
   $pubdate_params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                 "arg1" =>APPTIVO_BUSINESS_ACCESS_KEY
	            );
	            
   $plugin_params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
	            "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
                );
 // $response = get_data(APPTIVO_BUSINESS_SERVICES,'-events-publisheddate','-events-data','getLastPublishDate','getAllEvents',$pubdate_params,$plugin_params);
//  return $response;
                
}
/**
 * To Add Marketting Events..
 *
 * @param unknown_type $eventName
 * @param unknown_type $description
 * @param unknown_type $startDate
 * @param unknown_type $endDate
 * @param unknown_type $displayFirstName
 * @param unknown_type $displayLastName
 * @param unknown_type $displayAddress
 * @param unknown_type $displayEmailId
 * @param unknown_type $displayPhoneNumber
 * @param unknown_type $sendRegistrationEmail
 * @param unknown_type $registrantFirstName
 * @param unknown_type $registrantLastName
 * @param unknown_type $registrantEmailId
 * @param unknown_type $registrantPhoneNumber
 * @param unknown_type $registrantAddressLine1
 * @param unknown_type $registrantAddressLine2
 * @param unknown_type $registrantCity
 * @param unknown_type $registrantStateCode
 * @param unknown_type $registrantStateName
 * @param unknown_type $registrantPinCode
 * @param unknown_type $registrantCountryCode
 * @param unknown_type $registrantCountryName
 * @param unknown_type $pageSectionImages
 * @param unknown_type $link
 * @param unknown_type $publishedAt
 * @param unknown_type $publishedBy
 * @param unknown_type $sequenceNumber
 * @param unknown_type $marketingEventId
 * @return unknown
 */
function addEvents($eventName, $description, $startDate, $endDate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $marketingEventId,$creationDate,$newsImages)
{   
	$mktg_events = new AWP_MarketingEvent($eventName, $description, $startDate, $endDate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $marketingEventId,null,$newsImages);
    $params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
    			"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $mktg_events
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'addMarketingEvent',$params);
    return $response;
}
/**
 * Get Marketting events for the particular events Id.
 *
 * @param unknown_type $eventsId
 * @return unknown
 */
function getMarketingEventById($eventsId)
{
	$params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $eventsId
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getMarketingEventById',$params);      
    return $response;
}
/**
 * Update Events.
 *
 * @param unknown_type $eventName
 * @param unknown_type $description
 * @param unknown_type $startDate
 * @param unknown_type $endDate
 * @param unknown_type $displayFirstName
 * @param unknown_type $displayLastName
 * @param unknown_type $displayAddress
 * @param unknown_type $displayEmailId
 * @param unknown_type $displayPhoneNumber
 * @param unknown_type $sendRegistrationEmail
 * @param unknown_type $registrantFirstName
 * @param unknown_type $registrantLastName
 * @param unknown_type $registrantEmailId
 * @param unknown_type $registrantPhoneNumber
 * @param unknown_type $registrantAddressLine1
 * @param unknown_type $registrantAddressLine2
 * @param unknown_type $registrantCity
 * @param unknown_type $registrantStateCode
 * @param unknown_type $registrantStateName
 * @param unknown_type $registrantPinCode
 * @param unknown_type $registrantCountryCode
 * @param unknown_type $registrantCountryName
 * @param unknown_type $pageSectionImages
 * @param unknown_type $link
 * @param unknown_type $publishedAt
 * @param unknown_type $publishedBy
 * @param unknown_type $sequenceNumber
 * @param unknown_type $marketingEventId
 * @return unknown
 */
function updateEvents($eventName, $description, $startDate, $endDate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber = '', $marketingEventId = '',$creationDate = '',$newsImages = '')
{   
    $mktg_events = new AWP_MarketingEvent($eventName, $description, $startDate, $endDate, $displayFirstName, $displayLastName, $displayAddress, $displayEmailId, $displayPhoneNumber, $sendRegistrationEmail, $registrantFirstName, $registrantLastName, $registrantEmailId, $registrantPhoneNumber, $registrantAddressLine1, $registrantAddressLine2, $registrantCity, $registrantStateCode, $registrantStateName, $registrantPinCode, $registrantCountryCode, $registrantCountryName, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $marketingEventId,null,$newsImages);
    $params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
    			"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $mktg_events
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'updateEvent',$params);    
    return $response;
}
?>