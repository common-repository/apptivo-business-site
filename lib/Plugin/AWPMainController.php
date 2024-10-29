<?php
/**
 * Apptivo Main Controller
 */
require_once AWP_LIB_DIR . '/Plugin.php';
/**
 * Class AWP_MainController
 */
class AWP_MainController extends AWP_Base
{
    /**
     * Current page
     * @var string
     */
    var $_page = 'awp_general';
    //aip_
    /**
     * Notes
     * @var array
     */
    var $_notes = array();
    
    /**
     * Errors
     * @var array
     */
    var $_errors = array();
    
    /**
     * Runs plugin
     */
    function run()
    {
        add_action('admin_menu', array(&$this,'admin_menu'));
               
        add_action('init', array(&$this,'init'));
        
        /**
         * Run Contact Forms Plugin
         */
        require_once AWP_PLUGINS_DIR . '/ContactForms.php';
        $awp_contactforms = new AWP_ContactForms();
        $awp_contactforms->instance();
        /* $awp_contactforms = & AWP_ContactForms::instance(); */
        $awp_contactforms->run();
        
        /**
         * Run Cases Forms Plugin
         */
        require_once AWP_PLUGINS_DIR . '/cases.php';
        $awp_cases = new AWP_Cases();
        $awp_cases->instance();
        /* $awp_cases = & AWP_Cases::instance(); */
        $awp_cases->run();
        
        
         /**
         * Run Forms Plugin
         */
        require_once AWP_PLUGINS_DIR . '/jobs.php';
        $awp_jobsforms = new AWP_Jobs();
        $awp_jobsforms->instance();
        /* $awp_jobsforms = & AWP_Jobs::instance(); */
        $awp_jobsforms->run();
        
         /**
         * Run Newsletter Plugin
         */
        require_once AWP_PLUGINS_DIR . '/Newsletter.php';
        $awp_newsletter = new AWP_Newsletter();
        $awp_newsletter->instance();
        /* $awp_testimonials = & AWP_Newsletter::instance(); */
        $awp_newsletter->run();
        
         /**
         * Run Testimonials Plugin
         */
        require_once AWP_PLUGINS_DIR . '/Testimonials.php';
        $awp_testimonials = new AWP_Testimonials();
        $awp_testimonials->instance();
        /* $awp_testimonials = & AWP_Testimonials::instance(); */
        $awp_testimonials->run();

    }
    
    /**
     * Returns plugin instance
     *
     * @return AWP_Controller
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
     * Init action
     */
    function init()
    {
        
    }
    
    /**
     * Load action
     */
    function load()
    {
        require_once AWP_LIB_DIR . '/Request.php';
        
        $AWP_Request = new AWP_Request();
        $this->_page = $AWP_Request->get_string('page');
        
        switch (true) {
        	case ($this->_page == 'awp_general'):
        	case ($this->_page == 'awp_ip_deny'):
            case ($this->_page == 'awp_contactforms'):
            case ($this->_page == 'awp_newsletter'):
            case ($this->_page == 'awp_testimonials'):
            case ($this->_page == 'awp_jobs'):   
            case ($this->_page == 'awp_cases'):                    
            break;
                        
            default:
                $this->_page = 'awp_general';
        }
        
        /**
         * Save config
         */
        $awp_general_common = (isset($_REQUEST['awp_generalform_commonplugin_settings']) && !empty($_REQUEST['awp_generalform_commonplugin_settings'])) ? sanitize_text_field($_REQUEST['awp_generalform_commonplugin_settings']) : '';
        if (isset($awp_general_common) && !empty($awp_general_common)) {
        	$this->save_generalsettings();
        }
    }

    /**
     * Save General Settings form
     * 
     */
    function save_generalsettings(){
    	$settings=array();
    	$AWP_Request = new AWP_Request();
    	$settings["contactforms"]=$AWP_Request->get_boolean("contactforms_enable");
    	$settings["newsletters"]=$AWP_Request->get_boolean("newsletters_enable");
    	$settings["testimonials"]=$AWP_Request->get_boolean("testimonials_enable");
    	$settings["jobs"]=$AWP_Request->get_boolean("jobs_enable");
    	$settings["cases"]=$AWP_Request->get_boolean("cases_enable"); 
    	if(get_option("awp_plugins")!=="false"){
    		update_option("awp_plugins", $settings);
    	}else{
    		add_option("awp_plugins", $settings);
    	}
    }
    
    /**
     * Admin menu
     */
    function admin_menu()
    {
       
    	 $pages = array('awp_general' => array('General Settings',  'General Settings' ));
          	
    	 $awp_pluginsettings = get_option('awp_plugins');
         if (_is_curl_installed()) {
          	$ip_deny = array('awp_ip_deny' => array('IP Deny','IP Deny'));
          	$pages = array_merge($pages, $ip_deny); 
         
          if(!defined('AWP_CASES_DISABLE') || !AWP_CASES_DISABLE)
          {
           $cases = array('awp_cases' => array('Cases','Cases'));
           $pages = array_merge($pages, $cases); 
          }
   		 if(!defined('AWP_CONTACTFORM_DISABLE') || !AWP_CONTACTFORM_DISABLE)
          {
          	$contact_pages = array('awp_contactforms' => array('Contact Forms','Contact Forms'));
          	$pages = array_merge($pages, $contact_pages); 
          }
   		 if(!defined('AWP_NEWSLETTER_DISABLE') || !AWP_NEWSLETTER_DISABLE)
          {
          	$newsletter_pages = array('awp_newsletter' => array('Newsletter', 'Newsletter' ));
          	$pages = array_merge($pages, $newsletter_pages); 
          }
   		 if(!defined('AWP_TESTIMONIALS_DISABLE') || !AWP_TESTIMONIALS_DISABLE)
          {
          	$testimonials_pages = array('awp_testimonials' => array('Testimonials', 'Testimonials'));
          	$pages = array_merge($pages, $testimonials_pages); 
          }
    	if(!defined('AWP_JOBS_DISABLE') || !AWP_JOBS_DISABLE)
          { 
          
          	$jobs_pages = array('awp_jobs' => array('Jobs',  'Jobs' ));
          	$pages = array_merge($pages, $jobs_pages); 
          }
		 }
        $apptivo_iconurl = awp_image('apptivo_icon'); 
        add_menu_page('Apptivo', 'Apptivo', 'manage_options', 'awp_general', '',$apptivo_iconurl);
        $submenu_pages = array();
        
		foreach ($pages as $slug => $titles) {
				$submenu_pages[] = add_submenu_page('awp_general', $titles[0] . ' | Apptivo', $titles[1], 'manage_options', $slug, array(
                &$this, 
                'options'
            ));
           }
         
        if (current_user_can('manage_options')) {
            /**
             * Only admin can modify AIP settings
             */
            foreach ($submenu_pages as $submenu_page) {
                add_action('load-' . $submenu_page, array(&$this,'load'));
            }
        }
    }

    /**
     * Options page
     */
    function options()
    {
 
        /**
         * Show tab
         */
        switch ($this->_page) {
            case 'awp_general':
                $this->options_general();
                break;
            
            case 'awp_ip_deny':
            	$this->options_ipdeny();
            	break;  
           	  
            case 'awp_contactforms':
                $this->options_contactforms();
                break;
            
            case 'awp_newsletter':
                $this->options_newsletter();
                break;
            
            case 'awp_testimonials':
                $this->options_testimonials();
                break;
                
           case 'awp_jobs':
                $this->options_jobs();
                break;
           case 'awp_cases':
                $this->options_cases();
                break;     
         

        }
    }
    

    /**
     * General tab
     */
    function options_general(){
    	$this->show_general_settings();
    }
    /**
     * Denyed IP tab
     */
    function options_ipdeny(){
        $IP_Deny = new AWP_IPDeny();
    	require_once AWP_PLUGINS_DIR . '/ipdeny.php';
        $awp_ipdeny = & $IP_Deny->instance();
        $awp_ipdeny->settings();
    }
    
    /**
     * Contact Forms tab
     */
    function options_contactforms(){
    	require_once AWP_PLUGINS_DIR . '/ContactForms.php';
    	$awp_contactforms = new AWP_ContactForms();
    	$awp_contactforms->instance();
        /* $awp_contactforms = & AWP_ContactForms::instance(); */
        $awp_contactforms->options();
    }
    /**
     * Cases tab
     */
    function options_cases(){
    	require_once AWP_PLUGINS_DIR . '/cases.php';
    	$awp_cases = new AWP_Cases();
    	$awp_cases->instance();
        /* $awp_cases= & AWP_Cases::instance(); */
        $awp_cases->settings();
    }
   /**
     * Jobs Form tab
     */
    function options_jobs(){
        $JobForms = new AWP_Jobs();
    	require_once AWP_PLUGINS_DIR . '/jobs.php';
        $awp_jobsforms = & $JobForms->instance();
        $_GET['keys'] = (isset($_GET['keys']) && !empty($_GET['keys'])) ? sanitize_text_field($_GET['keys']) : '';
        $job_keys = $_GET['keys'];
        switch ($job_keys){
        	case 'jobcreation':
        		$awp_jobsforms->createJobsoptions();
        		break;
        	case 'configuration':
        		$awp_jobsforms->jobconfiguration();
        		break;
        	case 'jobsearch':
        		$awp_jobsforms->jobsearch();
        		break;		
        	default :
        		$awp_jobsforms->createJobsoptions();
        }   
    }
    

    
    /**
     * Testimonials tab
     */
    function options_testimonials(){
    	require_once AWP_PLUGINS_DIR . '/Testimonials.php';
		$awp_testimonials = new AWP_Testimonials();
    	$awp_testimonials->instance();		
        //$awp_testimonials = & AWP_Testimonials::instance();
        $awp_testimonials->options();
    }
    
	
	
    /**
     * Newsletter tab
     */
    function options_newsletter(){
    	require_once AWP_PLUGINS_DIR . '/Newsletter.php';
		$awp_newsletter = new AWP_Newsletter();
    	$awp_newsletter->instance();
		//  $awp_testimonials = & AWP_Newsletter::instance();
        $awp_newsletter->options();
    }
    
    /**
     * Mem cache Settings.
     *
     * @param unknown_type $memcachesettings
     * @param unknown_type $test_m_cacheconnect
     */
 
    
    function memCacheSettings($memcachesettings,$test_m_cacheconnect)
    {
    	 echo "<h3>" . __( 'Memcache Settings', 'apptivo-businesssite' ) . "</h3>";
    	 ?>
    	 <form name="awp_memcache_settings" method="post" action="">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
					<th valign="top"><label for="memcache_enable"><?php _e("Enable Memcache", 'apptivo-businesssite' ); ?>:</label>
						<!-- <br><span class="description">Enable the memcache to store the data which is coming for apptivo web services</span> -->
					</th>
					<td valign="top">
                    <?php $_POST['awp_memcache_test'] = (isset($_POST['awp_memcache_test']) && !empty($_POST['awp_memcache_test'])) ? sanitize_text_field($_POST['awp_memcache_test']) : 0;?>
						<input type="checkbox" name="memcache_enable" id="memcache_enable" onclick="genralform_enablefield('memcache_enable')" class="enabled" 
                        <?php 
                        if(isset($memcachesettings['memcache_enable']) && !empty($memcachesettings['memcache_enable'])){       
                        checked(1, $memcachesettings['memcache_enable']);checked('Test',$_POST['awp_memcache_test']); } ?>/>
					    
					</td>
				</tr>
                
                
				<tr>
        		<th><label for="memcached_servers">Memcached hostname:port / <acronym title="Internet Protocol">IP</acronym>:port:</label></th>
        		<td>
        			<input <?php  
                     if(isset($memcachesettings['memcache_enable']) && !empty($memcachesettings['memcache_enable'])){ 
                    if(!$memcachesettings['memcache_enable'] && $_POST['awp_memcache_test'] != 'Test' ){ echo 'disabled="disabled"'; } ?>  type="text" size="30" value="<?php if($_POST['awp_memcache_test']) { echo sanitize_text_field($_POST['hostname_portno']); } else { echo esc_attr($memcachesettings['hostname_portno']); }}  ?>" name="hostname_portno" id="hostname_portno">
        			<input <?php  
                    if(isset($memcachesettings['memcache_enable']) && !empty($memcachesettings['memcache_enable'])){
                    if(!$memcachesettings['memcache_enable'] && $_POST['awp_memcache_test'] != 'Test' ){ echo 'disabled="disabled"'; }} ?>  type="submit" name="awp_memcache_test" id="awp_memcache_test" class="button-primary" value="Test">
        			<?php 
                         if($_POST['awp_memcache_test'] && $test_m_cacheconnect )
                         {
                         echo '<span class="message" style="display:inline-block;padding: 5px;background-color: #BBFFBB ;" id="memcached_test_status">Test passed.</span>';
                         }else if($_POST['awp_memcache_test'])
                         {
                          echo '<span class="message" style="display:inline-block;padding: 5px;background-color: #FF9999 ;" id="memcached_test_status">Test Failed.</span>';	
                         }                     	
                        ?>
        			<br><span class="description">e.g. domain.com:22122 (or) 192.168.1.100:11211 </span>
        		</td>
        	</tr>
        	
                  <tr>
					<td>
					&nbsp;						
					</td>
					
                    <td>
    				<input type="submit" name="awp_memcache_settings" id="awp_memcache_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration') ?>" />
					</td>
					
				</tr>
                        </tbody>
                    </table>
                </form>
                <?php 
    }
 /**
     * Site Information.
     *
     * @param unknown_type $apptivo_api_key
     */
    function siteInformation($apptivo_api_key,$apptivo_access_key)
    {
    	echo "<h3>" . __( 'Site Information', 'apptivo-businesssite' ) . "</h3>";
    	if(isset($_POST['awp_siteinfo_form']) && !empty($_POST['awp_siteinfo_form'])){
    		if(strlen(trim(sanitize_text_field($_POST['api_key']))) != 0 ){
    		    echo '<div class="updated below-h2" id="message"> <p> Updated Site Information Settings.</p></div>';
    	    }else{
    		    echo '<div class="updated below-h2" id="message"><p>Site key cannot be empty.</p></div>'; 
    	    }
        }
    	?>
    	<?php    	
    	if (!defined("APPTIVO_ECOMMERCE_VERSION")){	$keys_readonly = false; }else { $keys_readonly = true; }
    	?>
    	
    	            <form name="awp_siteinfo_form" method="post" action="">
                    <table class="form-table">
                        <tbody>
                <tr valign="top">
					<th valign="top" style="width:425px;"><label for="api_key"><?php _e("API key", 'apptivo-businesssite' ); ?> ( <a href="<?php echo awp_developerguide('api-key'); ?>" target="_blank" >Get an Apptivo API Key</a> ) :</label>
						<br><span class="description">API key generated in Apptivo</span>
					</th>
					<td valign="top">
                     <input style="width:100%;" type="text" <?php if($keys_readonly) { echo 'readonly="true"';} ?>  name="api_key" id="api_key" class="enabled" value="<?php echo esc_attr($apptivo_api_key); ?>"/>
                     <input type="hidden" name="prev_api_key" id="prev_api_key" value="<?php echo esc_attr($apptivo_api_key); ?>"/>
                     <input type="hidden" name="update_site_inf" id="update_site_inf" value="yes"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th valign="top"><label for="access_key"><?php _e("Access key", 'apptivo-businesssite' ); ?> </label>
						<br><span class="description">Access key generated in Apptivo</span>
					</th>
					<td valign="top">
                     <input style="width:100%;" type="text" <?php if($keys_readonly) { echo 'readonly="true"';} ?>  name="access_key" id="access_key" class="enabled" value="<?php echo esc_attr($apptivo_access_key); ?>"/>
                    </td>
				</tr>
				
				
				<tr valign="top">
					<th valign="top"><label for="poweredby_apptivo"><?php _e("Show Powered By Apptivo", 'apptivo-businesssite' ); ?> </label>
					<br />
					<br />					
					<a target="_blank" href="http://www.apptivo.com/e-commerce">
                    <img style="border: medium none;" alt="Apptivo.com is the best free way to run your business. Apptivo.com powers ecommerce websites, provides free CMS, free CRM, free ERP, free Project Management and free Invoicing to small businesses." title="Apptivo.com is the best free way to run your business. Apptivo.com powers ecommerce websites, provides free CMS, free CRM, free ERP, free Project Management and free Invoicing to small businesses." src="http://cdn18.apptivo.com/templates/app/footer/apptivo.png"> 
                    </a>
						
					</th>
					<td valign="top">
                   
                   <input  <?php checked('dont_show',get_option('apptivo_poweredby_status')); ?> <?php checked('',get_option('apptivo_poweredby_status')); ?> type="radio" name="powered_status" id="donot_show"  value="dont_show"/><label for="donot_show">Don't Show &nbsp;&nbsp;&nbsp;</label>
                   <input  <?php checked('show_homepage',get_option('apptivo_poweredby_status')); ?> type="radio" name="powered_status" id="show_home"  value="show_homepage"/><label for="show_home"> Show in Home Page &nbsp;&nbsp;&nbsp;</label>
                   <input  <?php checked('show_allpages',get_option('apptivo_poweredby_status')); ?> type="radio" name="powered_status" id="show_all"  value="show_allpages"/> <label for="show_all">Show in All Pages &nbsp;&nbsp;&nbsp;</label> 
                   
                    </td>
				</tr>
				
				
				<tr>
					<td colspan="2">
						<p class="submit">
							<input type="submit" name="awp_siteinfo_form"
								id="awp_siteinfo_form" class="button-primary"
								value="<?php esc_attr_e('Save Configuration') ?>" onclick="return cmp_sitekey();" />
						</p>
					</td>
				</tr>
                        </tbody>
                    </table>
                </form>           
    	<?php 
    	
    	
    }
    
	/**
     * Plugins Settings Options.
     *
     * @param unknown_type $disable_pluginsSettings
     * @param unknown_type $generalsettings
     */
    function pluginsSettings($generalsettings)
    {
    	echo "<h3>" . __( 'Plugins Settings', 'apptivo-businesssite' ) . "</h3>";
	if(isset($_POST['awp_generalform_commonplugin_settings']) && !empty($_POST['awp_generalform_commonplugin_settings'])){
		echo '<div class="updated below-h2" id="message"> <p>Updated Plugins Settings.</p></div>';	    
	}
    	$apptivo_api_key = get_option('apptivo_apikey');
    	if (defined("APPTIVO_BUSINESS_API_KEY") ){
			$disable_plugin=false;
    	}
    	else if ((isset($apptivo_api_key) && !empty($apptivo_api_key)) || strlen(trim($apptivo_api_key)) > 0 )
    	{	
    		$disable_plugin=false;
    	}
		else 
		{	
			$disable_plugin=true;
		}
	   
     	 ?>
	  <form name="awp_generalsettings_form" method="post" action="">
		<table class="form-table">
			<tbody>
			<!-- Contact Forms  -->
			 <?php if(!defined('AWP_CONTACTFORM_DISABLE') || !AWP_CONTACTFORM_DISABLE) {  ?>
			<tr valign="top">
					<th class="titledesc" scope="row"><label for="contactforms_enable"><?php _e("Contact Forms", 'apptivo-businesssite' ); ?></label></th>
                    <td class="forminp">
                    <input type="checkbox" name="contactforms_enable" id="contactforms_enable" class="enabled" 
						<?php if($disable_plugin) {?>
						disabled="disabled"
						<?php } if($generalsettings["contactforms"]){?>
						checked="checked"
						<?php }?>
						>
                    <span class="description">Contact Forms to collect lead from your website.</span></td>
                </tr>
               <?php } ?> 
               
                         <?php if(!defined('AWP_CASES_DISABLE') || !AWP_CASES_DISABLE) {  ?>
			<tr valign="top">
					<th class="titledesc" scope="row"><label for="cases_enable"><?php _e("Cases", 'apptivo-businesssite' ); ?></label></th>
                    <td class="forminp">
                  <input type="checkbox" name="cases_enable" id="cases_enable" class="enabled" 
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php } if($generalsettings["cases"]){?>
						checked="checked"
						<?php }?>
						>
                   <span class="description">Customers to log a case from your website and you can manage it using Apptivo Cases App</span></td>
                </tr>
               <?php } ?> 
               
               <!-- Newsletter Form -->
                <?php if(!defined('AWP_NEWSLETTER_DISABLE') || !AWP_NEWSLETTER_DISABLE) {  ?>
			<tr valign="top">
					<th class="titledesc" scope="row"><label for="newsletters_enable"><?php _e("Newsletters", 'apptivo-businesssite' ); ?></label></th>
                    <td class="forminp">
                   <input type="checkbox" name="newsletters_enable" id="newsletters_enable" class="enabled"
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php  } if($generalsettings["newsletters"]){?>
						checked="checked"
						<?php }?>
						>
                    <span class="description">Subscribe your users to newsletters using Apptivo Campaigns.</span></td>
                </tr>
               <?php } ?> 
               
             <!-- Testimonials Sections -->
                  <?php if(!defined('AWP_TESTIMONIALS_DISABLE') || !AWP_TESTIMONIALS_DISABLE) {  ?>
			<tr valign="top">
					<th class="titledesc" scope="row"><label for="testimonials_enable"><?php _e("Testimonials", 'apptivo-businesssite' ); ?></label></th>
                    <td class="forminp">
                   <input type="checkbox" name="testimonials_enable" id="testimonials_enable" class="enabled"
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php } if($generalsettings["testimonials"]){?>
						checked="checked"
						<?php }?>
						>
                   <span class="description">Collect and show in your website, what your clients tell about you.</span></td>
                </tr>
               <?php } ?>             
               
               <!-- Jobs Sections -->
                <?php if(!defined('AWP_JOBS_DISABLE') || !AWP_JOBS_DISABLE) {  ?>
			<tr valign="top">
					<th class="titledesc" scope="row"><label for="jobs_enable"><?php _e("Jobs", 'apptivo-businesssite' ); ?></label></th>
                    <td class="forminp">
                  <input type="checkbox" name="jobs_enable" id="jobs_enable" class="enabled" 
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php } if($generalsettings["jobs"]){?>
						checked="checked"
						<?php }?>
						>
                    <span class="description">Customers to upload a resume from your website and you can manage it using Apptivo Jobs App</span></td>
                </tr>
               <?php } ?> 
               	<tr>
					<td colspan="2">
						<p class="submit">
							<input <?php if($disable_plugin) { echo 'disabled="disabled"'; } ?>type="submit" name="awp_generalform_commonplugin_settings"
								id="awp_generalform_commonplugin_settings" class="button-primary"
								value="<?php esc_attr_e('Save Configuration') ?>" />
						</p>
						
					</td>
				</tr>
	   		</tbody>
		</table>
		</form>
	<?php 
    }
/**
	 * Memcache Configuration and test Memcache...
	 *
	 */    
	function configure_memcache()
	{       
		    if(class_exists('Memcache'))
		    { 
		    $awp_datacache = new AWP_Cache_Util(); //Create Object in AWP_DataCache clss]
		    }
		    else {
		    	echo '<span style="color:#f00;">PHP Memcache is not detected in this system. Install PHP Memcache and configure for better performance.</span>';
		    //trigger_error("PHP Class 'Memcache' does not exist!", E_USER_ERROR);	
		    }
		    $memcachesettings = array();
		    $AWP_Request = new AWP_Request();
            $memcachesettings['memcache_enable']= $AWP_Request->get_boolean("memcache_enable");
		    $memcachesettings['hostname_portno']= $AWP_Request->get_string("hostname_portno");
            update_option('awp_memcache_settings',$memcachesettings);
	        if(isset($_POST['awp_memcache_settings']) && !empty($_POST['awp_memcache_settings'])){
	            if(get_option("awp_memcache_settings")!=="false"){
	            }else{
	               add_option('awp_memcache_settings', $memcachesettings);
	            }
	            if(!$memcachesettings['memcache_enable']){
	            	update_option('awp_memcache_settings','');
	            }
	            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Memcache configuration successfully saved.
	                </p></div>';
	        }else{
                error_log("awp_memcache_settings Not Set");
            }
	}
	
	function  test_mcacheconnect(){
		if(isset($_POST['awp_memcache_test']) && !empty($_POST['awp_memcache_test'])){
            $AWP_Request = new AWP_Request();
		  if(class_exists('Memcache')){
			$awp_datacache = new AWP_Cache_Util(); //Create Object in AWP_DataCache clss]
			$memcachesettings = array();
			$memcachesettings['memcache_enable']= $AWP_Request->get_boolean("memcache_enable");
			$memcachesettings['hostname_portno']= $AWP_Request->get_string("hostname_portno");  
			$test_connect = $awp_datacache->connectmcache( $memcachesettings['hostname_portno']);
			return $test_connect;
		    }else{
			return false;
		    }
		}
	}
	
	function general_tabssettings()
	{
		 /* Stored Site Information */	
       if(isset($_POST['awp_siteinfo_form']) && !empty($_POST['awp_siteinfo_form'])){
            $awprequest = new AWP_Request();
       	    $apptivo_poweredby_status = $awprequest->get_string('powered_status');
       	    update_option('apptivo_poweredby_status',$apptivo_poweredby_status);
       	    $apptivo_api_key= $awprequest->get_string("api_key");
            $apptivo_access_key= $awprequest->get_string("access_key");
            $apptivo_update_site_inf= $awprequest->get_string("update_site_inf"); // To check options or delete or not.
            if (isset($apptivo_api_key) && (!empty($apptivo_api_key) || strlen(trim($apptivo_api_key)) > 0) && (strtolower($apptivo_update_site_inf) != 'no'))
            {
            if(get_option("apptivo_apikey")!=="false"){ 
            update_option('apptivo_apikey',$apptivo_api_key);
            update_option('apptivo_ecommerce_apikey',$apptivo_api_key);
            }else {
            	add_option('apptivo_apikey', $apptivo_api_key);
            	add_option('apptivo_ecommerce_apikey', $apptivo_api_key);
            }
            }
            
            //apptivo access Key
             if(get_option('apptivo_accesskey')!=="false") :
             	update_option('apptivo_accesskey',$apptivo_access_key);
             	update_option('apptivo_ecommerce_accesskey',$apptivo_access_key);
             else:
               add_option('apptivo_accesskey', $apptivo_access_key);
               add_option('apptivo_ecommerce_accesskey', $apptivo_access_key);
             endif;
               
       }
       
       $apptivo_api_key = get_option('apptivo_apikey');
       $apptivo_access_key = get_option('apptivo_accesskey');
   if(isset($updatemessage))
   {	   
	if(trim($updatemessage)!=""){
		?>
		<div id="message" class="updated">
	        <p>
	        <?php echo esc_attr($updatemessage);?>
	        </p>
	    </div>
	    <?php }
   }
	        $this->siteInformation($apptivo_api_key,$apptivo_access_key);
	}
	
	function plugin_tabsettings()
	{
		$general_plugins_settings=get_option("awp_plugins");
            if($general_plugins_settings == '' || empty($general_plugins_settings)) :
	            if( basename(dirname(AWP_LIB_DIR))  == 'apptivo-businesssite-crm') :
	            $general_plugins_settings = array(
	            'contactforms' => 1,
	            'newsletters' => 1,
	            'testimonials' => 1,
	            );
                    if(get_option("apptivo_sitekey") != '') :
	            	update_option("awp_plugins", $general_plugins_settings);
	            	endif;
	            endif;
	            if( basename(dirname(AWP_LIB_DIR))  == 'apptivo-businesssite-hrms') :
	            $general_plugins_settings = array(            
	            'newsletters' => 1, 
	            'jobs'=> 1
	            );
                   if(get_option("apptivo_apikey") != '') :
	            	update_option("awp_plugins", $general_plugins_settings);
	            	endif;
	            endif;
            endif;
            
            $this->pluginsSettings($general_plugins_settings);
	}
	function captcha_tabsettings()
    {
    $captcha_Option = (isset($_REQUEST['captcha_option']) && !empty($_REQUEST['captcha_option'])) ? sanitize_text_field($_REQUEST['captcha_option']) : '';
        if(isset($captcha_Option) && !empty($captcha_Option))
	{
    	$apptivo_recapthca['recaptcha_mode']   =   sanitize_text_field($_POST['apptivo_business_recaptcha_mode']);
    	$apptivo_recapthca['recaptcha_publickey']= sanitize_text_field($_POST['apptivo_business_recaptcha_publickey']);
    	$apptivo_recapthca['recaptcha_publickey'] = preg_replace('/\s+/', '', $apptivo_recapthca['recaptcha_publickey']);
    	$apptivo_recapthca['recaptcha_privatekey']= sanitize_text_field($_POST['apptivo_business_recaptcha_privatekey']);
    	$apptivo_recapthca['recaptcha_privatekey'] = preg_replace('/\s+/', '', $apptivo_recapthca['recaptcha_privatekey']);
    	$apptivo_recapthca['recaptcha_theme']  =  sanitize_text_field($_POST['apptivo_business_recaptcha_theme']);
    	$apptivo_recapthca['recaptcha_language']= sanitize_text_field($_POST['apptivo_business_recaptcha_language']);
    	$apptivo_recapthca['awp_captcha_type']= sanitize_text_field($_POST['awp_captcha_type']);
    	$apptivo_recapthca['awp_fg_color']= sanitize_text_field($_POST['awp_fg_color']);
    	$apptivo_recapthca['awp_bg_color']= sanitize_text_field($_POST['awp_bg_color']);
    	if ($apptivo_recapthca['awp_captcha_type'] == 'simplecaptcha') { $captcha_success_msg = 'Simple Captcha'; }
    	else if($apptivo_recapthca['awp_captcha_type'] == 'recaptcha') { $captcha_success_msg = 'reCaptcha'; }
    	echo '<div style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;" class="message">
        <p style="margin: 0.5em;padding: 2px;">'.esc_attr($captcha_success_msg).' configuration successfully saved.</p></div>';
    	$apptivo_recapthca=json_encode($apptivo_recapthca);
    	if(get_option ('apptivo_business_recaptcha_settings')=="")
    	{
   			add_option( 'apptivo_business_recaptcha_settings', "$apptivo_recapthca");
    	}
    	else
    	{
        	update_option( 'apptivo_business_recaptcha_settings', "$apptivo_recapthca");
    	}
    	if(get_option('apptivo_business_recaptcha_mode')=="")
    	{
    		add_option('apptivo_business_recaptcha_mode',"$_POST[apptivo_business_recaptcha_mode]");
    	}
    	else
    	{
        	update_option('apptivo_business_recaptcha_mode',"$_POST[apptivo_business_recaptcha_mode]");
    	}
	}
$option=get_option('apptivo_business_recaptcha_settings') ;
$option=json_decode($option);
if(isset($option->awp_captcha_type) && $option->awp_captcha_type=="recaptcha"){
	$display="block";
	$display_color="none";
}elseif (isset($option->awp_captcha_type) && $option->awp_captcha_type=="simplecaptcha"){
	$display="none";$display_color="block";
}else{
	$display="block";
    $display_color="none";
}

    ?>
<form name="capthca" method="post" action="">
<h3>Captcha in Forms </h3>
 <table class="form-table">

        <tbody><tr valign="top">
        <th class="titledesc" scope="row">Select reCaptcha/Simple Captcha</th>
                    <td class="forminp"><select style="width:185px;" id="awp_captcha_type" name="awp_captcha_type" onchange="awp_captcha_change()">
                                                    <option <?php if(isset($option->awp_captcha_type) && $option->awp_captcha_type=="recaptcha"){echo 'selected="selected"'; }?> value="recaptcha">reCaptcha</option>
                                                    <option <?php if(isset($option->awp_captcha_type) && $option->awp_captcha_type=="simplecaptcha"){echo 'selected="selected"'; }?> value="simplecaptcha">Simple Captcha</option>
                                        </select>
                    </td></tr>
         </tbody></table>
         <table class="form-table" id="color_table" style="display:<?php echo esc_attr($display_color);?>"><tbody>
         	<tr valign="top">
        	<th class="titledesc" scope="row">Captcha Foreground Color</th>
        	<td ><input class="color {hash:true}" name="awp_fg_color" type="text" value=<?php if(isset($option->awp_fg_color) && $option->awp_fg_color !="") { echo esc_attr($option->awp_fg_color); } else { echo '#FFFFFF'; } ?>></td>
        	</tr>
        	<tr valign="top">
        	<th class="titledesc" scope="row">Captcha Background Color</th>
        	<td ><input class="color {hash:true}" name="awp_bg_color" type="text" value=<?php if(isset($option->awp_bg_color) && $option->awp_bg_color !="") { echo esc_attr($option->awp_bg_color); } else { echo '#000000'; }?>></td>
        	</tr>
		</tbody></table>
		
            <table class="form-table" id="recaptcha_table" style="display:<?php echo esc_attr($display);?>">

<tbody><tr valign="top">
					<th class="titledesc" scope="row">Enable reCaptcha</th>
                    <td class="forminp"><select style="width:185px;" id="apptivo_business_recaptcha_mode" name="apptivo_business_recaptcha_mode">
                                                    <option <?php if(isset($option->recaptcha_mode) && $option->recaptcha_mode=="yes"){echo 'selected="selected"'; }?> value="yes">Enabled</option>
                                                    <option <?php if(isset($option->recaptcha_mode) && $option->recaptcha_mode=="no"){echo 'selected="selected"'; }?> value="no">Disabled</option>
                                        </select>
    <span class="description"><a href="https://www.google.com/recaptcha/admin/create" title="Create a reCAPTCHA key" target="_blank">Create a reCAPTCHA key</a></span>
                    </td>
                </tr><tr valign="top">
                <?php
					$privateKey = trim(get_option( 'apptivo_ecommerce_recaptcha_privatekey' ));
					$publicKey = trim(get_option( 'apptivo_ecommerce_recaptcha_publickey' ));
					if ( is_plugin_active( 'apptivo-ecommerce/apptivo_ecommerce.php' ) ){
						if($privateKey!="" && $publicKey !=""){
							$absp_privatekey= $privateKey;
							$absp_pubickey= $publicKey;
							$disable	=	'readonly="true"';
						}
					}else{
						$absp_pubickey	=	$option->recaptcha_publickey;
						$absp_privatekey=	$option->recaptcha_privatekey;
						$disable	=	"";
					}
				?>
    <th class="titledesc" scope="row">reCaptcha - Public Key</th>
    <td class="forminp">
    <input type="text" value="<?php echo esc_attr($absp_pubickey); ?>" <?php echo esc_attr($disable); ?> style="width:500px;" id="apptivo_business_recaptcha_publickey"  name="apptivo_business_recaptcha_publickey">

                    <span class="description"></span></td>
                </tr><tr valign="top">
					<th class="titledesc" scope="row">reCaptcha - Private Key</th>
                    <td class="forminp">
                    <input type="text" value="<?php echo esc_attr($absp_privatekey); ?>" <?php echo esc_attr($disable); ?> style="width:500px;" id="apptivo_business_recaptcha_privatekey" name="apptivo_business_recaptcha_privatekey">

                    <span class="description"></span></td>
                </tr><tr valign="top">
					<th class="titledesc" scope="row">reCaptcha - Theme</th>
                    <td class="forminp"><select style="width:185px;" id="apptivo_business_recaptcha_theme" name="apptivo_business_recaptcha_theme">
                                                    <option <?php if(isset($option->recaptcha_theme) && $option->recaptcha_theme=="red"){echo 'selected="selected"'; }?> value="red">Red</option>
                                                    <option <?php if(isset($option->recaptcha_theme) && $option->recaptcha_theme=="white"){echo 'selected="selected"'; }?> value="white">White</option>
                                                    <option <?php if(isset($option->recaptcha_theme) && $option->recaptcha_theme=="blackglass"){echo 'selected="selected"'; }?> value="blackglass">Black Glass</option>
                                               </select> <span class="description"></span>
                    </td>
                </tr><tr valign="top">
					<th class="titledesc" scope="row">reCaptcha - Language</th>
                    <td class="forminp"><select style="width:185px;" id="apptivo_business_recaptcha_language" name="apptivo_business_recaptcha_language">
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="en"){echo 'selected="selected"'; }?> value="en">English</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="nl"){echo 'selected="selected"'; }?> value="nl">Dutch</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="fr"){echo 'selected="selected"'; }?> value="fr">French</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="de"){echo 'selected="selected"'; }?> value="de">German</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="pt"){echo 'selected="selected"'; }?> value="pt">Portuguese</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="ru"){echo 'selected="selected"'; }?> value="ru">Russian</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="es"){echo 'selected="selected"'; }?> value="es">Spanish</option>
                                                    <option <?php if(isset($option->recaptcha_language) && $option->recaptcha_language=="tr"){echo 'selected="selected"'; }?> value="tr">Turkish</option>
                                               </select> <span class="description"></span>
                    </td>
                </tr></tbody></table>
<input type="submit" name="captcha_option" value="Save Changes" class="button-primary"/>
</form>
<br/><br/>

<?php
        }
	function memcache_tabsettings()
	{
	 
            if(!defined("AWP_MEMCACHED_HOST") && !defined("AWP_MEMCACHED_PORT") )
            {
            	$this->configure_memcache();
                $memcachesettings = get_option('awp_memcache_settings');
                $test_m_cacheconnect = $this->test_mcacheconnect();
                $this->memCacheSettings($memcachesettings,$test_m_cacheconnect);
	        }
	}

        /*
         * Configuration for Proxy settings
         */
        function proxy_tabsettings(){
		$proxysettings = array();
	        if(isset($_POST['awp_proxy_settings']) && !empty($_POST['awp_proxy_settings'])){
                $AWP_Request = new AWP_Request();
                    $proxysettings['proxy_enable']=  $AWP_Request->get_boolean("proxy_enable");
                    $proxysettings['proxy_hostname_portno']=  $AWP_Request->get_string("proxy_hostname_portno");
                    $proxysettings['proxy_loginuser_pwd']=  $AWP_Request->get_string("proxy_loginuser_pwd");
	            if(get_option("awp_proxy_settings")!=="false"){
	               update_option('awp_proxy_settings',$proxysettings);
	            }
	            else{
	               add_option('awp_proxy_settings', $proxysettings);
	            }
	            if(!$proxysettings['proxy_enable'])
	            {
	            	update_option('awp_proxy_settings','');
	            }
	            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Proxy configuration successfully saved.
	                </p></div>';
	        }

            $proxysettings = get_option('awp_proxy_settings');
            echo "<h3>" . __( 'Proxy Settings', 'apptivo-businesssite' ) . "</h3>"; ?>

            <form name="awp_proxy_settings" method="post" action="">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
					<th valign="top"><label for="memcache_enable"><?php _e("Enable Proxy", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Enable if you need proxy settings to reach Apptivo from your server</span>
					</th>
					<td valign="top">
						<input <?php  if(isset($proxysettings['proxy_enable'])){ echo 'checked="checked"'; } ?>  type="checkbox" name="proxy_enable" id="proxy_enable" onclick="proxy_enablefield('proxy_enable')" class="enabled" />
					</td>
				</tr>
                    <tr>
        		<th><label for="proxy_hostname_portno">Proxy hostname:port / <acronym title="Internet Protocol">IP</acronym>:port:</label></th>
        		<td>
        			<input <?php  if(!isset($proxysettings['proxy_enable'])){ echo 'disabled="disabled"'; } ?>  type="text" size="30" value="<?php if(isset($proxysettings['proxy_hostname_portno'])){ echo esc_attr($proxysettings['proxy_hostname_portno']); } ?>" name="proxy_hostname_portno" id="proxy_hostname_portno">
        			<br><span class="description">e.g. domain.com:22122 (or) 192.168.1.100:11211 </span>
        		</td>
                    </tr>
                    <tr>
        		<th><label for="proxy_loginuser_pwd">Proxy login username:password </label></th>
        		<td>
        			<input <?php  if(!isset($proxysettings['proxy_enable'])){ echo 'disabled="disabled"'; } ?>  type="text" size="30" value="<?php if(isset($proxysettings['proxy_loginuser_pwd'])) { echo esc_attr($proxysettings['proxy_loginuser_pwd']);  } ?>" name="proxy_loginuser_pwd" id="proxy_loginuser_pwd">
        			
        			<br><span class="description">e.g. username:password </span>
        		</td>
                    </tr>

                  <tr>
					<td>
					&nbsp;
					</td>

                    <td>
    				<input type="submit" name="awp_proxy_settings" id="awp_proxy_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration') ?>" />
					</td>

				</tr>
                        </tbody>
                    </table>
                </form>
                <?php
	}

	/**
     * Render Apptivo General Settings page
     */
    function show_general_settings(){
        echo '<div class="wrap">';
	    echo "<h2>" . __( 'Apptivo General Settings', 'apptivo-businesssite' ) . "</h2></ br>";
		?>
    	<?php 
       /* General Settings */
		$this->general_tabssettings();
		/* Plugin Settings */
		$this->plugin_tabsettings();
        /* Capthcha Settings */
        $this->captcha_tabsettings();
		if(_isCurl())
		{
		/* Proxy Settings */
        $this->proxy_tabsettings();
		}
		/* Memcache Settings. */
		$this->memcache_tabsettings();
                
		 ?>

<?php 
	     echo "</div>";
    }
	
}
add_action('admin_enqueue_scripts', 'awp_load_scripts');

function awp_load_scripts($hook_suffix) {
    if($hook_suffix == 'toplevel_page_awp_general'){
        wp_register_script('colorpicker-js',plugins_url(basename(dirname(__FILE__ ,3))).'/assets/js/jscolor/jscolor.js');
	    wp_enqueue_script('colorpicker-js');
    }
}



add_action("admin_footer", "apptivo_business_businesskey_validation");

function apptivo_business_businesskey_validation() {
	?>
<script type="text/javascript">
    jQuery(document).ready(function($){

		/*Handled for Vulnerability Script Code Injection */
        $("#api_key,#access_key").blur(function(event) { 
        var text = $(this).val().replace(/[^\da-zA-Z0-9-]/g,'');
		$(this).val(text);
		return true;
		});

        $("#proxy_hostname_portno,#hostname_portno").blur(function(event) { 
        var text = $(this).val().replace(/[^.:\da-zA-Z0-9]/g,'');
		$(this).val(text);
		return true;
		});
        
	});
</script>
<?php } ?>
