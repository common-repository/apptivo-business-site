<?php
/*
 Plugin Name: Apptivo Business site Plugin
 Plugin URI: https://www.apptivo.com/integrations/wordpress-crm-plugin/
 Description: Apptivo Business Site plugin provides Testimonials, Jobs, Contact Forms, Cases and Newsletter sub plugins with <a href="https://www.apptivo.com" target="_blank">Apptivo ERP</a>.
 Version: 5.3
 Author: Apptivo
 Author URI: https://www.apptivo.com/
 */
if (!session_id()) session_start();
if (!defined('AWP_PLUGIN_BASEPATH')){
 	
 	if( is_admin() )
 	{
 		register_activation_hook( __FILE__, 'activate_apptivo_business' );
        register_activation_hook( __FILE__, 'awp_ip_jal_install' );
 	}
 	define('AWP_PLUGIN_BASEPATH',plugin_dir_path(__FILE__));
 	define('AWP_PLUGIN_BASEURL',plugins_url(basename( dirname(__FILE__))));
 	/**
     * Require plugin configuration
     */
    require_once dirname(__FILE__) . '/inc/define.php';
    require_once dirname(__FILE__) . '/inc/dummy-config.php';
    
    /**
     * Load plugins
     */
    awp_load_plugins();
    
    /**
     * Load Widgets
     */
    awp_load_widgets();

    /**
     * Run plugin
     */
	add_action( 'admin_enqueue_scripts', 'apptivo_business_enqueue' );

	function apptivo_business_enqueue($hook) {
		switch ($hook) {
				case 'apptivo_page_awp_news':
				case 'apptivo_page_awp_events':
				case 'apptivo_page_awp_testimonials':
				case 'apptivo_page_awp_jobs':
				case 'apptivo_page_awp_contactforms':
				case 'apptivo_page_awp_newsletter':
				case 'apptivo_page_awp_cases':	
					add_filter("mce_buttons", "remove_mce_buttons");
					//add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
					add_filter( 'wp_default_editor', function() {return "tinymce";} );
					if ( is_admin() ) {
						add_action('admin_print_scripts', 'apptivo_business_scripts');
						add_action('admin_print_styles', 'apptivo_business_styles');					
					}
					break;  
			}
	}

	function isValueSet($val){
		if(isset($val)){
			return $val;
		}else{
			return "";
		}
	}

	/**
	 * Shortcode button in post editor
	 **/
	add_action( 'init', 'apptivo_businesssite_add_shortcode_button' );

	add_filter('widget_text', 'do_shortcode_apptivo');
	function do_shortcode_apptivo($content){
		global $shortcode_tags;
		if (empty($shortcode_tags) || !is_array($shortcode_tags)){
			return $content;
		}

		$pattern = get_shortcode_regex();    
		return preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $content );
	}

	function apptivo_businesssite_add_shortcode_button() {
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
		if ( get_user_option('rich_editing') == 'true') :
			add_filter('mce_external_plugins', 'apptivo_businesssite_add_shortcode_tinymce_plugin');
			add_filter('mce_buttons', 'apptivo_businesssite_register_shortcode_button');
		endif;
	}

	function apptivo_businesssite_register_shortcode_button($buttons) {
		array_push($buttons, "|", "apptivo-businesssite_shortcodes_button");
		return $buttons;
	}

	function apptivo_businesssite_add_shortcode_tinymce_plugin($plugin_array) {
		$plugin_array['ApptivoBusinesssiteShortcodes'] = AWP_PLUGIN_BASEURL . '/assets/js/editor_plugin.js';
		return $plugin_array;
	}


	function remove_mce_buttons($buttons) {
	echo '<script type="text/javascript" language="javascript" >jQuery(document).ready(function(){	 
	jQuery("#editor-toolbar").remove(); 
	jQuery("#quicktags #ed_toolbar").remove(); 
	});</script>';
		unset($buttons[17]);
		array_push($buttons,'code');
		return $buttons;

	}
	require_once AWP_PLUGINS_DIR . '/messages.php'; 
    require_once AWP_PLUGINS_DIR . '/AWPMainController.php';
    
    $awp_maincontroller = new AWP_MainController(); 
    $awp_maincontroller->instance();
    /* $awp_maincontroller = & AWP_MainController::instance();*/
    $awp_maincontroller->run(); 
}

function activate_apptivo_business()
{   
     //Update plugin version.
	update_option( "apptivo_business_plugin_version", '1.2.4' );
    update_option( "apptivo_business_plugin_installed", 1 );
    awp_bsp_createcaptcha();
}

global $awp_ip_jal_db_version;


function awp_ip_jal_install() {
	global $wpdb;
	$awp_ip_jal_db_version = '1.0';

	$table_name = $wpdb->prefix . 'absp_ipdeny';
	
	$charset_collate = '';

	if ( ! empty( $wpdb->charset ) ) {
	  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
	}

	if ( ! empty( $wpdb->collate ) ) {
	  $charset_collate .= " COLLATE {$wpdb->collate}";
	}

	$sql = "CREATE TABLE $table_name (
		ID INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		ip_address VARCHAR(200),
		ip_type VARCHAR(200),
		cur_timestamp TIMESTAMP
            ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $awp_ip_jal_db_version );
}


function awp_bsp_createcaptcha(){
   
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/awp_captcha';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0700 );
    }
}

add_action('admin_init', 'install_apptivo_business_redirect');
function install_apptivo_business_redirect() {
	global $pagenow;
	//if ( is_admin() && !empty(sanitize_text_field($_GET['activate'])) && (sanitize_text_field($_GET['activate']) == true) && $pagenow == 'plugins.php' & get_option('apptivo_business_plugin_installed') == 1) :
	if ( is_admin() && isset($_GET['activate']) && !empty($_GET['activate']) && (sanitize_text_field($_GET['activate']) == true) && $pagenow == 'plugins.php' && get_option('apptivo_business_plugin_installed') == 1){
		update_option( "apptivo_business_plugin_installed", 0 );
		// Redirect to general settings page.
		wp_redirect(admin_url('admin.php?page=awp_general'));
		exit;
	}
}
/*function apptivo_business_admin_scripts()
{
	$screen = get_current_screen();
	wp_register_script( 'apptivo_business_plugin', AWP_PLUGIN_BASEURL . '/assets/js/apptivo-business-plugin.js', array('jquery'), '1.0' );
	if (in_array( $screen->id, array( 'toplevel_page_awp_general','apptivo_page_awp_cases','apptivo_page_awp_jobs','apptivo_page_awp_events','apptivo_page_awp_news','apptivo_page_awp_ip_deny','apptivo_page_awp_testimonials','apptivo_page_awp_newsletter', 'apptivo_page_awp_contactforms'))) :
	  wp_enqueue_script( 'apptivo_business_plugin' );
	endif;
}*/
function apptivo_business_admin_scripts(){
	$screen = get_current_screen();
	wp_register_script('apptivo_business_plugin', AWP_PLUGIN_BASEURL . '/assets/js/apptivo-business-plugin.js', array('jquery'), '1.0');
	if (in_array($screen->id, array('toplevel_page_awp_general', 'apptivo_page_awp_cases', 'apptivo_page_awp_jobs', 'apptivo_page_awp_events', 'apptivo_page_awp_news', 'apptivo_page_awp_ip_deny', 'apptivo_page_awp_testimonials', 'apptivo_page_awp_newsletter', 'apptivo_page_awp_contactforms'))){
		wp_enqueue_script('apptivo_business_plugin');
	}

	// Add help tab for this screen if needed
	$screen->add_help_tab(array(
		'id'      => 'apptivo-business-help',
		'title'   => __('Apptivo Business Plugin Help', 'apptivo-business'),
		'content' => '<p>' . __('Help content goes here.', 'apptivo-business') . '</p>',
	));
}
add_action('admin_enqueue_scripts', 'apptivo_business_admin_scripts');

function apptivo_business_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');	
}

function apptivo_business_styles() {
	echo '<style type="text/css">
	#wp-awp_contactform_confirmationmsg-editor-container .wp-editor-area{height:175px; width:100%;}
	#wp-awp_cases_confirmationmsg-editor-container .wp-editor-area{height:175px; width:100%;}
	#wp-awp_jobsform_confirmationmsg-editor-container .wp-editor-area{height:175px; width:100%;}
	#wp-awp_newsletterform_confirmation_msg-editor-container .wp-editor-area{height:175px; width:100%;}
	</style>';
	wp_enqueue_style('thickbox');
}


/**
 * Create auto Pages ( News, Events, Testimonials and Cobtactus ) Nedd to place theme's function.php [ do_action( 'absp_autopages'); ]
 *
 */
function absp_autopages()
{
	/*apptivo Plugin settings*/
    $general_plugins_settings=get_option("awp_plugins");

    if($general_plugins_settings == '' || empty($general_plugins_settings)) {
    	    $plugins = array('contactforms'=>true,'testimonials'=>true,'events'=> true,'news'=>true);
	    	update_option("awp_plugins", $plugins);
	}
	    
	/*Contact Forms*/
     $contact_form_options = get_option('awp_contactforms');
     if($contact_form_options == '' || empty($contact_form_options)) {
	 $config_contactforms = array('0'=>array('name'=>'contact_form',
                                 'properties'=>array('tmpltype'=>'awp_plugin_template','layout'=>'single-column-layout2.php','confirm_msg_page'=>'same','submit_button_type'=>'submit'),
                                 'fields'=>array('0'=>array('fieldid'=>'lastname','showtext'=>'Last Name','required'=>1,'type'=>'text','validation'=>'none','order'=>2),
                                                 '1'=>array('fieldid'=>'email','showtext'=>'Email','required'=>1,'type'=>'text','validation'=>'text','order'=>3))
                                 ));                                 
	 update_option("awp_contactforms", $config_contactforms);

    $contactus_pageid = get_option('awp_contactus_pageid');
	if($contactus_pageid == '' || empty($contactus_pageid)) 
	{
	$page_content = '[apptivocontactform name="contact_form"]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'contact',
        'post_title' => 'Contact',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_contactus_pageid", $page_id);   
	}
	
	 }
	
	
	/*Testimonials*/
	 $testimonials_options = get_option('awp_testimonials_settings');
	  if($testimonials_options == '' || empty($testimonials_options)) {
	   $config_testimonials = array('template_type'=>'awp_plugin_template','template_layout'=>'default-testimonials.php','order'=>1,'itemsperpage'=>5);
	   update_option("awp_testimonials_settings", $config_testimonials);
	   
	  $testimonials_pageid = get_option('awp_testimonials_pageid');
	if($testimonials_pageid == '' || empty($testimonials_pageid)) 
	{ 
	 $page_content = '[apptivo_testimonials_fullview]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'testimonials',
        'post_title' => 'Testimonials',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_testimonials_pageid", $page_id);      
	}
	
	
	  }
	
	/*News*/
   $news_options = get_option('awp_news_settings');
	  if($news_options == '' || empty($news_options)) {
	   $config_news =array('template_type'=>'awp_plugin_template','template_layout'=>'default-news.php','order'=>1,'itemsperpage'=>5);
	   update_option("awp_news_settings", $config_news);
	   
	  $news_pageid = get_option('awp_news_pageid');
	if($news_pageid == '' || empty($news_pageid)) 
	{ 
	 $page_content = '[apptivo_news_fullview]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'news',
        'post_title' => 'News',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_news_pageid", $page_id);      
	}
	
	  }
	
 
	
	/*Events*/	
	$events_options = get_option('awp_events_settings');
	  if($events_options == '' || empty($events_options)) {
	   $config_events =array('template_type'=>'awp_plugin_template','template_layout'=>'default-events.php','order'=>1,'itemsperpage'=>5);
	   update_option("awp_events_settings", $config_events);
	   
	   $events_pageid = get_option('awp_events_pageid');
	if($events_pageid == '' || empty($events_pageid)) 
	{ 
	 $page_content = '[apptivo_events_fullview]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'events',
        'post_title' => 'Events',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_events_pageid", $page_id);      
	}	
	
	  }
	
             
} 
add_action( 'absp_autopages', 'absp_autopages', 10, 2 );
//Powered By Apptivo.
add_action('wp_footer','powered_apptivo_status');
function powered_apptivo_status()
{
	$status = get_option('apptivo_poweredby_status');
	if( $status != '' && $status != 'dont_show') :	
	if($status == 'show_homepage') :
        if(is_front_page()) :	
  		  $apptivo_logo = poweredby_apptivo();
  		endif;
  	else:
  		  $apptivo_logo = poweredby_apptivo();
  	endif;	
  	echo '<div class="poweredbyapptivo" style="text-align:center;" >'.esc_attr($apptivo_logo).'</div>';
  	endif;
}
//Contact Form Submit.
add_action('wp_ajax_apptivo_business_contactus', 'apptivo_business_contactus_lead');
add_action('wp_ajax_nopriv_apptivo_business_contactus', 'apptivo_business_contactus_lead');
function apptivo_business_contactus_lead(){
    $formname = sanitize_text_field($_POST['awp_contactformname']);
    $contact_form = new AWP_ContactForms();
    $contact_formlead = $contact_form->save_contact($formname,true);
    echo esc_attr($contact_formlead);exit;		
}


//Newsletter Form Submit.
add_action('wp_ajax_apptivo_business_newsletter', 'apptivo_business_newsletter_target');
add_action('wp_ajax_nopriv_apptivo_business_newsletter', 'apptivo_business_newsletter_target');
function apptivo_business_newsletter_target(){
    $formname = sanitize_text_field($_POST['awp_newsletterformname']);
    $newsletter_form = new AWP_Newsletter();
    $newsletter_subscribe = $newsletter_form->save_newsletter($formname);
	echo esc_attr($newsletter_subscribe);exit;		
}

function abwpExternalScripts(){
	wp_enqueue_script("jquery");
	wp_enqueue_script( 'jquery_validation', AWP_PLUGIN_BASEURL . '/assets/js/validator-min.js', array("jquery"), '1.0.0', true );
}
?>
