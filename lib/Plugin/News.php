<?php  
/**
 * Apptivo News plugin
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/News.php';
/**
 * Class AWP_News
 */
class AWP_News extends AWP_Base
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
    		if($settings["news"])
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
			add_shortcode('apptivo_news_fullview',array(&$this,'show_news_fullview'));
			add_shortcode('apptivo_news_inline',array(&$this,'show_news_inline'));
			add_action('the_posts',array(&$this,'check_for_shortcode'));
	   }
    }
    function check_for_shortcode($posts) {
		$news_fullView=awp_check_for_shortcode($posts,'[apptivo_news_fullview');	
		$news_inlineView=awp_check_for_shortcode($posts,'[apptivo_news_inline');		
		if ($news_inlineView){
           // load styles and scripts	      
	       $this->loadscripts();
	    }	   
	    return $posts;
	}

  function loadscripts()
	{   
		wp_enqueue_script('jquery_cycleslider.js',AWP_PLUGIN_BASEURL. '/assets/js/jquery.cycle.all.latest.js',array('jquery'));
   	}
	

    /* Add News */
    function add_news()
    {
        $awp_news_options = array(
                'Title' => stripslashes($_POST['awp_news_title']),
                'Description' => stripslashes($_POST['awp_news_desc']),
                'startdate' => gmdate(DATE_ATOM,mktime()),
                'enddate' =>gmdate(DATE_ATOM,mktime(0,0,0,gmdate('m'),gmdate('d'),gmdate('Y')+20)),
                'Link' => $_POST['awp_news_link'],
                'publishedat' => stripslashes($_POST['awp_news_published_at']),
                'publishedby' => stripslashes($_POST['awp_news_published_by']),
                'imageurl' => stripslashes($_POST['awp_news_imageurl']),
                'showflag' => stripslashes($_POST['awp_news_show']),
                'order' => stripslashes($_POST['awp_news_order']),
              );
         $awp_news_options= wp_parse_args($awp_news_options,array(
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
        extract($awp_news_options);
        $Description = apply_filters('the_content', $Description);
        $response = addNews(null, $Title, $Description, $isFeatured, $startdate, $imageurl, $Link, $publishedat, $publishedby, $order, $enddate,null,$imageurl);
        if($response == 'E_100')
        {
        	return 'E_100';
        }
        return $response->return;
       }
    //Update news
    function update_news()
    {
        $newsId = $_POST['awp_tstid'];
        $awp_news_options = array(
                'Title' => stripslashes($_POST['awp_news_title']),
                'Description' => stripslashes($_POST['awp_news_desc_update']),
                'startdate' =>$_POST['startdate'],
                'enddate' =>$_POST['enddate'],
                'Link' => $_POST['awp_news_link'],
                'publishedat' => stripslashes($_POST['awp_news_published_at']),
                'publishedby' => stripslashes($_POST['awp_news_published_by']),
                'imageurl' => stripslashes($_POST['awp_news_imageurl']),
                'showflag' => stripslashes($_POST['awp_news_show']),
                'order' => stripslashes($_POST['awp_news_order']),
              );
        //print_obj($awp_news_options);
         $awp_news_options= wp_parse_args($awp_news_options,array(
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
        extract($awp_news_options);
        $Description = apply_filters('the_content', $Description);
        $response = updateNews($newsId, $Title, $Description, $isFeatured, $startdate, $imageurl, $Link, $publishedat, $publishedby, $order, $enddate,null, $imageurl);
        return $response;
      }
    //Delete news
    function delete_news()
    {
        $newsId = $_REQUEST['tstid'];
        $enddate=gmdate(DATE_ATOM,mktime(0,0,0,gmdate('m'),gmdate('d')-1,gmdate('Y')));
        $newsId= $_REQUEST['tstid'];
        $response = getNewsById($newsId);
        $newsdetails = $response->return;
        $response = updateNews($newsdetails->newsId,$newsdetails->newsHeadLine,$newsdetails->description, $newsdetails->isFeatured,$newsdetails->startDate,$newsdetails->pageSectionImages, $newsdetails->link, $newsdetails->publishedAt, $newsdetails->publishedBy, $newsdetails->sequenceNumber,$enddate);
        return $response;

    }
    function options()
    {
        ?>
            <div class="wrap">
            <h2><?php _e('News Management','apptivo-businesssite'); ?></h2>
            </div>
<?php 
if( $_REQUEST['keys'] == 'fullviewsetting')
{
 $generalClass  = 'nav-tab';
 $fullviewsettingClass = 'nav-tab nav-tab-active';
 $inlineviewsettingClass = 'nav-tab';
}else if( $_REQUEST['keys'] == 'inlineviewsetting'){
 $generalClass = 'nav-tab';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab nav-tab-active';
}else {
 $generalClass = 'nav-tab nav-tab-active';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab';
}
checkSoapextension("News");
checkCaptchaOption();
?>
<div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('news_icon'); ?>') " ><br></div>             
<h2 class="nav-tab-wrapper">
<a class="<?php echo $generalClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_news"><?php _e('News','apptivo-businesssite'); ?></a>
<a class="<?php echo $fullviewsettingClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_news&keys=fullviewsetting"><?php _e('Full View Settings','apptivo-businesssite'); ?></a>
<a class="<?php echo $inlineviewsettingClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_news&keys=inlineviewsetting"><?php _e('Inline View Settings','apptivo-businesssite'); ?></a>
</h2>

	   <p>
	   <img id="elementToResize" src="<?php echo awp_flow_diagram('news');?>" alt="News" title="News"  />
	   </p>
	   
    <p style="margin:10px;">
		For Complete instructions,see the <a href="<?php echo awp_developerguide('news');?>" target="_blank">Developer's Guide.</a>
	</p>
	
        <?php
         if(!$this->_plugin_activated){
	    	echo "News Plugin is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
	    }
        if (isset($_POST['awp_news_add']) && ($_POST['nogdog'] == $_SESSION['apptivo_single_news'])) {   //ADD News.
        	$addnews_response = $this->add_news();
        	if(strlen(trim($_POST['awp_news_title'])) == 0 )
	    	{
             $_SESSION['awp_news_messge']  = 'Please enter a news title.';
            }else if($addnews_response == 'E_100') {
	    		$_SESSION['awp_news_messge']  = '<span style=color:#f00;">Invalid Keys</span>';
	    	}else if($addnews_response->statusCode != '1000') {
	    		$_SESSION['awp_news_messge']  = '<span style=color:#f00;">'.$addnews_response->statusMessage.'</span>';
	    	}else { 
	    		 $_SESSION['awp_news_messge']  = 'News Added Successfully';
	    	} 
        } else if ($_POST['awp_news_update'] == 'Update') {  //Update News.
            $updatenews_response = $this->update_news();
            if($updatenews_response->return->statusCode != '1000')
            {
            	$_SESSION['awp_news_messge'] = '<span style=color:#f00;">'.$updatenews_response->return->statusMessage.'</span>';
            }else {
            $_SESSION['awp_news_messge'] = 'News Updated Successfully';
            }
        } else if ($_REQUEST['tstmode'] == 'delete') {      //Delete News.
            $deletenews_response = $this->delete_news();
            if($deletenews_response->return->statusCode != '1000')
            {
            	$_SESSION['awp_news_messge'] = '<span style=color:#f00;">'.$deletenews_response->return->statusMessage.'</span>';
            }else {
            $_SESSION['awp_news_messge'] = 'News Deleted Successfully';
            }
        }else {
        	$_SESSION['awp_news_messge'] = '';
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
        		$this->get_all_news();                    //Display All News.
        		if ($_REQUEST['tstmode'] == 'edit')
        		{ 
        	     $newsId = $_REQUEST['tstid'];
		         $response = getNewsById($newsId);
		         $news = $response->return;
		       if($news->statusCode != '1000')
		         {  
		         	echo '<div class="message" id="newsmessage" style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);">
      	                <p style="margin: 0.5em; padding: 2px;"><span style="color: rgb(255, 0, 0);">'.$news->methodResponse->statusMessage.'</span></p></div>';
		         }
		         $this->edit_news($news);                // News Edit Forms
         
        		} else {
        			$this->news_form();                 // News Create Forms.
        		} 
        		break;
        } 
        ?>
<style type="text/css">
	        .awp_newsform td { width:80px;}	          
</style>       

        <?php 
       
    }

    
/**
     * To Call Full View Settings.
     */
    function fullViewSettings()
    {
    	 ?><div class="wrap">
           
        <?php
        if (isset($_POST['full_view_settings'])){
            $this->save_news_settings();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Full View Settings Saved Successfully
             </p></div>';
        }
        $this->fullview_settings();
        ?>
        </div><?php
    }
    /**
     * To Call Inline View Settings.
     *
     */
    function  inlineViewSettings()
    {
    	?> <div class="wrap">
           
        <?php
        if (isset($_POST['inline_view_settings'])) {
            $this->save_inline_settings();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Inline View Settings Saved Successfully
                 </p></div>';
        }
        $this->inlineview_settings();
        ?>
        </div><?php 
    }
    
    /*
     * Register Widget
     */
    function register_widget(){
	    //register new widget in Available widgets
	        register_widget( 'AWP_News_Widget' );
    }
    //Display All news
    function get_all_news()
    {
        
	$all_awp_news = $this->getAllNews();
	$numberofitems = count($all_awp_news);
   	$itemsperpage =5;
   	$tpages = ceil($numberofitems/$itemsperpage); 
   	$currentpage   = intval($_GET['pageno']);
   	if($currentpage<=0)  $currentpage  = 1;
   	if($currentpage>=$tpages)  $currentpage  = $tpages;
   	$start = ( $currentpage - 1 ) * $itemsperpage;
   	$all_awp_news = array_slice( $all_awp_news, $start, $itemsperpage );
   	$reload = $_SERVER['PHP_SELF'].'?page=awp_news';   	
        
   	    if (!empty($_SESSION['awp_news_messge']) && strlen(trim($_SESSION['awp_news_messge'])) != 0) : 
        echo '<div style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);width:80%;" id="newsmessage"  class="message">
      	<p style="margin: 0.5em; padding: 2px;">'.$_SESSION['awp_news_messge'].'</p></div>';
        endif;
        
   	
        if(!empty($all_awp_news[0])){
        ?>
        <div class="wrap">
       
        <?php 
       
        if( $numberofitems > $itemsperpage)
        {
        echo awp_paginate($reload,$currentpage,$tpages,$numberofitems);
        }
       
                                    	
        ?>
        <table class="widefat plugins" width="700" cellspacing="0" cellpadding="0">
        <thead>
         <tr>
             <th><?php _e('Title','apptivo-businesssite'); ?></th>
             <th><?php _e('Description','apptivo-businesssite'); ?></th>
             <th><?php _e('Link','apptivo-businesssite'); ?></th>
             <th><?php _e('Published at','apptivo-businesssite'); ?></th>
             <th><?php _e('Published by','apptivo-businesssite'); ?></th>
             <th><?php _e('Order to show','apptivo-businesssite'); ?></th>
             <th><?php _e('Edit','apptivo-businesssite'); ?></th>
             <th><?php _e('Delete','apptivo-businesssite'); ?></th>
          </tr>
        </thead>
         <tfoot>
         <tr>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Title','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Description','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Link','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Published at','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Published by','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Order to show','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Edit','apptivo-businesssite'); ?></th>
             <th style="border-top: 1px solid #DFDFDF;"><?php _e('Delete','apptivo-businesssite'); ?></th>
          </tr>
        </tfoot>
        <tbody id="the-list">
                                    <?php
                                    foreach ($all_awp_news as $news) {
                                     if( $_GET['tstid'] == $news->newsId )
                                    	{
                                    		$class = "active";
                                    	}else { $class = "inactive"; }
                                       ?><tr id="<?php echo $news->newsId;?>" class="<?php echo $class; ?>" >
                                            <td><?php echo $news->newsHeadLine; ?></td>
                                            <td>
                                             <?php if (strlen(strip_tags(html_entity_decode($news->description))) < 30)
		                                            {
		                                                echo strip_tags(html_entity_decode($news->description));
		                                            }                                                  
		                                            else
                                                  	{  
                                                  		 $sub = strip_tags(html_entity_decode($news->description));                                                  	 
													     echo $sub = substr($sub, 0, 30).'...';
                                                  	}										       
                                            ?>
                                            
                                            </td>
                                            <td><?php echo $news->link; ?></td>
                                            <td><?php echo $news->publishedAt; ?></td>
                                            <td><?php echo $news->publishedBy; ?></td>
                                            <td><?php echo $news->sequenceNumber; ?></td>
                                            <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_news&amp;tstmode=edit&amp;tstid=<?php echo $news->newsId;?>&amp;pageno=<?php echo intval($_GET['pageno']);?>"><img src="<?php echo awp_image('edit_icon'); ?>"/></a></td>
                                            <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_news&amp;tstmode=delete&amp;tstid=<?php echo $news->newsId;?>" onclick="return delete_news('<?php echo $this->_plugin_activated; ?>')" ><img src="<?php echo awp_image('delete_icon'); ?>"/></a></td>
                                    </tr>
                                    <?php
                                     }
            ?>
           </tbody>
           </table>
        </div>
      
            <?php
                                    }
    }
    

    //Inline View Settings form
    function inlineview_settings()
    {
        $awp_news_inline_settings = get_option('awp_news_inline_settings');
        $awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/news','Inline');
        //plugin template
        $awp_tst_plugintemplates = get_awpTemplates(AWP_NEWS_TEMPLATEPATH,'inline');
        ksort($awp_tst_plugintemplates);
        if( empty($awp_news_inline_settings) )
        {
        	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for inline view. </span>';
        }
        
         ?>
        <form action="" class="awp_newsform" name="awp_news_inline" method="post">
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                    <?php if(isset($awp_news_inline_settings) && !empty($awp_news_inline_settings)) { ?>
                    <tr valign="top">
					<td valign="top"><label for="news_inlineview_shortcode">Shortcode:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the news.','apptivo-businesssite'); ?></span>
					</td>
					<td valign="top"><span name="awp_customform_shortcode" id="awp_customform_shortcode">
					<input type="text" value="[apptivo_news_inline]" id="news_inlineview_shortcode" name="news_inlineview_shortcode" readonly="true" style="width: 300px;">
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('news-inline-shortcode');?>" target="_blank">News Inline Shortcodes.</a></span>
					</span>
					</td>
				    </tr> <?php } ?>
                                   <tr valign="top"> <td><?php _e('Template Type','apptivo-businesssite'); ?> </td>
                                        <td valign="top">
                                        <select name="awp_news_templatetype" id="awp_news_templatetype" onchange="news_change_template();">
                                                <option value="awp_plugin_template" <?php selected($awp_news_inline_settings['template_type'],'awp_plugin_template'); ?> ><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
                                                <?php if(!empty($awp_tst_themetemplates)) : ?>
                                                <option value="theme_template" <?php selected($awp_news_inline_settings['template_type'],'theme_template'); ?> >Templates from Current Theme</option>
                                                <?php endif; ?>
                                         </select>
                                            <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('news-inline-template');?>" target="_blank">News Inline Templates.</a></span>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php _e('Select Layout','apptivo-businesssite'); ?></td>
                                        <td valign="top">
                                        
                                        <select name="awp_news_plugintemplatelayout" id="awp_news_plugintemplatelayout" <?php if($awp_news_inline_settings['template_type'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
                                                 <?php foreach (array_keys($awp_tst_plugintemplates) as $template) : ?>
                               					 <option value="<?php echo $awp_tst_plugintemplates[$template] ?>" <?php selected($awp_tst_plugintemplates[$template],$awp_news_inline_settings['template_layout']); ?> >
                                        		<?php echo $template ?>  </option>
                                                 <?php endforeach;  ?>
                                        </select>
                                        
                                        <select name="awp_news_themetemplatelayout" id="awp_news_themetemplatelayout" <?php if($awp_news_inline_settings['template_type'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
                                              <?php foreach (array_keys($awp_tst_themetemplates) as $template) : ?>
                                   			  <option value="<?php echo $awp_tst_themetemplates[$template] ?>" <?php selected($awp_tst_themetemplates[$template],$awp_news_inline_settings['template_layout']); ?> > <?php echo $template ?> </option>                 
                            				  <?php endforeach; ?>
                                        </select>
                                        
                                         </td>
                                     </tr>
                                       <tr><td><?php _e('Order','apptivo-businesssite'); ?></td>
                                        <td>
                                            <select  name="order">
                                                <option value="1" <?php selected('1', $awp_news_inline_settings['order']); ?> >Newest First</option>
                                                <option value="2" <?php selected('2', $awp_news_inline_settings['order']); ?> >Oldest First</option>
                                                <option value="3" <?php selected('3', $awp_news_inline_settings['order']); ?> >Random Order</option>
                                                <option value="4" <?php selected('4', $awp_news_inline_settings['order']); ?> >Custom Order</option>
                                            </select>
                                        </td></tr>
                                    <tr>
                                        <td><?php _e('Number of items to show','apptivo-businesssite'); ?></td>
                                        <td><input class="num" type="text" name="itemstoshow" value="<?php echo ($awp_news_inline_settings['itemstoshow'] == '')?AWP_DEFAULT_ITEM_SHOW:$awp_news_inline_settings['itemstoshow']; ?>" size="3"/> &nbsp;&nbsp; <small>( Default : <?php echo AWP_DEFAULT_ITEM_SHOW; ?> ) </small></td>
                                    </tr>
                                    <tr><td><?php _e('More items Link title','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="more_text" value="<?php echo ($awp_news_inline_settings['more_text'] == '')? AWP_DEFAULT_MORE_TEXT:$awp_news_inline_settings['more_text']; ?>"/> &nbsp;&nbsp; <small>( Default : <?php echo AWP_DEFAULT_MORE_TEXT; ?> ) </small></td></tr>
                                    <tr><td><?php _e('Full View  page name','apptivo-businesssite'); ?></td><td>
                        <?php wp_dropdown_pages(array('name' => 'page_ID', 'selected' => $awp_news_inline_settings['page_ID'])); ?>
                        </td></tr>
                                    <tr><td valign="top"><?php _e('Custom CSS','apptivo-businesssite'); ?></td>
                                        <td><textarea name="custom_css" cols="30" rows="5"><?php echo $awp_news_inline_settings['custom_css']; ?></textarea>
                                        <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('news-inline-customcss');?>" target="_blank">News Inline CSS.</a></span>
                                        </td></tr>
                    <tr><td></td><td>
                    <input type="submit" <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?>  value="<?php _e('Save Settings','apptivo-businesssite'); ?>" name="inline_view_settings" class="button-primary" /></td></tr>
                </table>

        </form>
              
        <?php
    }

    //ShortCode For news Full View
    function show_news_fullview()
    {
           $awp_news_settings = get_option('awp_news_settings'); 
           $awp_news = $this->getAllNewsForfullView();
            ob_start();
           if(empty($awp_news_settings))
        	{  
        		echo awp_messagelist('newsconfigure-display-page');   //News are not configured.
        	}else if(empty($awp_news[allnews]))
	        {  
	        	echo awp_messagelist('news-display-page');             //News are not found.
	        }else { include $awp_news['templatefile']; }
	        
	        
            $show_news = ob_get_clean();
            return $show_news;
    }
    
    function display_news()
    {
    	$awp_news = $this->getAllNewsForInline();
    	$awp_news['allnews'] = array_slice($awp_news['allnews'],0,$awp_news['itemstoshow']);
        unset($awp_news['templatefile']);
        unset($awp_news['custom_css']);
        return $awp_news;           
    }

    //Short code for inline view
    function show_news_inline()
    {       
    	    $awp_news_inline_settings = get_option('awp_news_inline_settings');
    	 
            $news_content = $this->getAllNewsForInline();
            //$absp_news_content = $news_content;
            ob_start();
            
           if(empty($awp_news_inline_settings))
        	{  
        		echo awp_messagelist('newsconfigure-display-page');   //News are not configured.
        	} else if(empty($news_content[allnews]))
	        {  
	        	echo awp_messagelist('news-display-page');            //News are not found.
	        }else { include $news_content['templatefile']; }

	        
            $show_news = ob_get_clean();
            return $show_news;
            
    }
    function getAllNewsForInline(){

            $awp_all_news=array();
            $awp_news_inline_settings = get_option('awp_news_inline_settings');
            $page_details = get_page($awp_news_inline_settings['page_ID']);
             
            $response=getAllNews();
            $all_awp_news = awp_convertObjToArray($response->return->newsList);
            $allnews=array();
            $currentdate = gmdate(DATE_ATOM,mktime());
            if( count($all_awp_news)>0){
            foreach($all_awp_news as $news){
            if(strtotime($news->startDate)<=strtotime($currentdate) && strtotime($news->endDate)>=strtotime($currentdate)){
               array_push($allnews,$news);
            }
            }
            }
            $awp_all_news = $allnews;
            
            
            $order=$awp_news_inline_settings['order'];
            $awp_all_news = $this->sortNewsByOrder($awp_all_news, $order);
            if($awp_news_inline_settings['itemstoshow']!=0){
            $numberofitems = $awp_news_inline_settings['itemstoshow'];
            }
            else{
             $numberofitems = count($awp_all_news);
            }
            
             if($awp_news_inline_settings['template_type']=="awp_plugin_template") :
                    $templatefile=AWP_NEWS_TEMPLATEPATH."/".$awp_news_inline_settings['template_layout'];
            else :
                    $templatefile=TEMPLATEPATH."/news/".$awp_news_inline_settings['template_layout'];
            endif;
            
             if (!file_exists($templatefile))
            {   
            	$templatefile = AWP_NEWS_TEMPLATEPATH."/sliderview1.php";
            } 
            $news = array();
            $news['allnews'] = $awp_all_news;
            $news['custom_css'] = $awp_news_inline_settings['custom_css'];
            $news['itemstoshow'] = $numberofitems;
            $news['templatefile'] = $templatefile;
            $news['pagelink'] = $page_details->guid;
            $news['more_text'] = $awp_news_inline_settings['more_text'];
           
            return $news;


    }
    function getAllNewsForfullView(){
            $awp_news_settings = get_option('awp_news_settings');          
            if($awp_news_settings['template_type']=="awp_plugin_template") :
                    $templatefile=AWP_NEWS_TEMPLATEPATH."/".$awp_news_settings['template_layout'];
            else :
                    $templatefile=TEMPLATEPATH."/news/".$awp_news_settings['template_layout'];
            endif;
                    
            if (!file_exists($templatefile)) 
            {   
            	$templatefile = AWP_NEWS_TEMPLATEPATH."/".AWP_NEWS_DEFAULT_TEMPLATE;
            }            
            $awp_news=array();
            $response=getAllNews();
            $all_awp_news = awp_convertObjToArray($response->return->newsList);
            $allnews=array();
            $currentdate = gmdate(DATE_ATOM,mktime());
            if( count($all_awp_news)>0){
            foreach($all_awp_news as $news){
            if(strtotime($news->startDate)<=strtotime($currentdate) && strtotime($news->endDate)>=strtotime($currentdate)){
               array_push($allnews,$news);
            }
            }
            }
            /* For Default Config*/
            $news_pageid = get_option('awp_news_pageid');
            if($news_pageid != '') {
               if( count($allnews) == 0 || empty($allnews))
	           {
	           $allnews = dummy_news();
            
	           }
            }
            /* For Default Config*/
            
            $awp_news = $allnews;          
            $order=$awp_news_settings['order'];
            $awp_news = $this->sortNewsByOrder($awp_news,$order);
            $news = array();
            $news['allnews'] = $awp_news;
            $news['custom_css'] = $awp_news_settings[custom_css];
            $news['templatefile'] = $templatefile;
            //$news['apptivo_methodresponse'] = $response->return->methodResponse;
            return $news;
        
    }
    function sortNewsByOrder($awp_all_news,$order){
        switch($order){
                case '1':
                    usort($awp_all_news,'awp_creation_date_compare');
                    break;
                case '2':
                    usort($awp_all_news,'awp_creation_date_compare');
                    $awp_all_news = array_reverse($awp_all_news);
                    break;
                case '3':
                    shuffle($awp_all_news);
                    break;
                default:
                    usort($awp_all_news,'awp_sort_by_sequence');

             }
             return $awp_all_news;
    }
    //Full View Settings Form
    function fullview_settings() {
        $awp_news_settings = get_option('awp_news_settings');
        //update page content with shortcode
        //themes templates
        $awp_tst_themetemplates = $awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/news','Plugin');
        //plugin templates
        $awp_tst_plugintemplates = get_awpTemplates(AWP_NEWS_TEMPLATEPATH,'Plugin'); 
        ksort($awp_tst_plugintemplates); 
       if( empty($awp_news_settings) )
        {
        	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for full view. </span>';
        }      
        ?>
        <form action="" class="awp_newsform" name="awp_news_full" method="post">
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                <?php if(isset($awp_news_settings) && !empty($awp_news_settings)) {?>
                <tr valign="top">
					<td valign="top"><label for="news_fullview_shortcode">Shortcode:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the news.','apptivo-businesssite'); ?></span>
					</td>
					<td valign="top"><span name="awp_customform_shortcode" id="awp_customform_shortcode">
					<input type="text" value="[apptivo_news_fullview]" id="news_fullview_shortcode" name="news_fullview_shortcode" readonly="true" style="width: 300px;">
					
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('news-fullview-shortcode');?>" target="_blank">News Fullview Shortcodes.</a></span>
					
					</span>
					</td>
				    </tr> <?php } ?>
				    
                                    <tr valign="top"> <td><?php _e('Template Type','apptivo-businesssite'); ?> </td>
                                        <td valign="top"><select
                                                name="awp_news_templatetype"
                                                id="awp_news_templatetype" onchange="news_change_template();">
                                                <option value="awp_plugin_template" <?php selected($awp_news_settings['template_type'],'awp_plugin_template'); ?> ><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
                                                <?php if(!empty($awp_tst_themetemplates)) : ?>
                                                	<option value="theme_template" <?php selected($awp_news_settings['template_type'],'theme_template'); ?> ><?php _e('Templates from Current Theme','apptivo-businesssite'); ?></option>
                                                <?php endif; ?>
                                            </select>
                                            <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('news-fullview-template');?>" target="_blank">News Fullview Templates.</a></span>
                                            
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td><?php _e('Select Layout','apptivo-businesssite'); ?></td>
                                        <td valign="top">
                                        
                                        <select name="awp_news_plugintemplatelayout" id="awp_news_plugintemplatelayout" <?php if($awp_news_settings['template_type'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
                                                 <?php foreach (array_keys($awp_tst_plugintemplates) as $template) :  ?>
                                                	<option value="<?php echo $awp_tst_plugintemplates[$template] ?>" <?php selected($awp_tst_plugintemplates[$template],$awp_news_settings['template_layout']); ?> >
                                                      <?php echo $template ?>
                                         			</option>
                                                 <?php endforeach; ?>
                                        </select> 
                                        
                                        <select name="awp_news_themetemplatelayout" id="awp_news_themetemplatelayout" <?php if($awp_news_settings['template_type'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
                                              <?php foreach (array_keys($awp_tst_themetemplates) as $template) : ?>
                                   				<option value="<?php echo $awp_tst_themetemplates[$template] ?>"  <?php selected($awp_tst_themetemplates[$template],$awp_news_settings['template_layout']); ?> > <?php echo $template ?> </option>
                                              <?php endforeach; ?>
                                         </select>
                                         
                                         </td>
                                     </tr>
                                     <tr><td><?php _e('Order','apptivo-businesssite'); ?></td><td>
                                             <select  name="order">
                                                 <option value="1" <?php selected('1', $awp_news_settings['order']); ?> >Newest First</option>
                                                 <option value="2" <?php selected('2', $awp_news_settings['order']); ?> >Oldest First</option>
                                                 <option value="3" <?php selected('3', $awp_news_settings['order']); ?> >Random Order</option>
                                                 <option value="4" <?php selected('4', $awp_news_settings['order']); ?> >Custom Order</option>
                                             </select>
                                         </td></tr>
                                     <tr><td valign="top"><?php _e('Custom CSS','apptivo-businesssite'); ?></td><td>
                                     <textarea name="custom_css" cols="30" rows="5"><?php echo $awp_news_settings['custom_css']; ?></textarea>
                                     <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('news-fullview-customcss');?>" target="_blank">News Fullview CSS.</a></span>
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
         if ($_POST['awp_news_templatetype'] == "awp_plugin_template"):
            $news_layout = $_POST['awp_news_plugintemplatelayout'];
        else:
            $news_layout = $_POST['awp_news_themetemplatelayout'];
         endif;
         //Inline news items to show.
         $inline_news_itemtoshow = $_POST['itemstoshow'];
         if(!is_numeric($_POST['itemstoshow']) || $_POST['itemstoshow'] <= 0 ):
         	$inline_news_itemtoshow =   AWP_DEFAULT_ITEM_SHOW;
         endif;  
        $awp_news_inline_settings = array(
                     'template_type' => $_POST['awp_news_templatetype'],
                     'template_layout' => $news_layout,
                     'style' => $_POST['style'],
                     'custom_css' => stripslashes($_POST['custom_css']),
                     'order' => $_POST['order'],
                     'itemstoshow' => $inline_news_itemtoshow,
                     'more_text' => (trim($_POST['more_text'])!="")?$_POST['more_text']:AWP_DEFAULT_MORE_TEXT,
                     'page_ID' => $_POST['page_ID'],
                     );
        
        update_option('awp_news_inline_settings', $awp_news_inline_settings);
    }

    //Save Testomonials Settings
    function save_news_settings() {
        if ($_POST['awp_news_templatetype'] == "awp_plugin_template")
            $news_layout = $_POST['awp_news_plugintemplatelayout'];
        else
            $news_layout = $_POST['awp_news_themetemplatelayout'];
        $awp_news_settings = array(
            'template_type' => $_POST['awp_news_templatetype'],
            'template_layout' => $news_layout,
            'custom_css' => stripslashes($_POST['custom_css']),
            'order' => $_POST['order'],
            'page_ID' => $_POST['page_ID'],
            'itemsperpage' => (!empty($_POST['itemsperpage'])) ? $_POST['itemsperpage'] : 5
        );
       
        update_option('awp_news_settings', $awp_news_settings);
    }
    //News Form
    function news_form(){
        ?>
         <div class="wrap addnews">
         <h2>Add News</h2>
         <?php $nogdog = uniqid();$_SESSION['apptivo_single_news'] = $nogdog; ?>
         <form method="post" action="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_news" name="awp_news_form" onsubmit="return validatenews('add')" >
         <input type="hidden" name="nogdog" value="<?php echo $nogdog;?>" >
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td><?php _e('Title','apptivo-businesssite'); ?> &nbsp;<span style="color:#f00;">*</span></td>
                                        <td><input type="text" name="awp_news_title" id="awp_news_title" value="" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><?php _e('Description','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
                                        <td>                                       
                                        <div style="width:630px;">
                                        <?php 
                                        the_editor($updated_value,'awp_news_desc','',FALSE); 
                                        ?>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Link','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_link" id="awp_news_link" value="" size="63"/>&nbsp;<small>(For ex: http://www.example.com/)</small></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published at','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_published_at" id="awp_news_published_at" value="" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published by','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_published_by" id="awp_news_published_by" value="" size="63"/></td>
                                    </tr>
                                        
                                    <tr valign="top">
									<th scope="row"><?php _e('Image URL','apptivo-businesssite'); ?></th>
									<td><label for="upload_image">
									<input id="awp_news_imageurl" type="text" size="50" name="awp_news_imageurl" value="" />
									<input id="news_upload_image" type="button" value="Upload Image"  class="button-primary"/>
									<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
									</label></td>
									</tr>
                                    
                                    
                                    <tr>
                                        <td><?php _e('Order to show','apptivo-businesssite'); ?></td>
                                        <td><input type="text" class="num" name="awp_news_order" id="awp_news_order" value="" size="3" /></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?> type="submit" value="<?php _e('Add news','apptivo-businesssite'); ?>" name="awp_news_add" class="button-primary"/></td>
                                    </tr>

                                </table>

        </form>
        </div>
            <?php
    }
    //Edit News Form
    function edit_news($news){
         
        ?>
        <div class="wrap addnews">
        <h2><?php _e('Edit News','apptivo-businesssite'); ?></h2>
        <form method="post" action="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_news" name="awp_news_form" onsubmit="return validatenews('edit')" >
            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td><?php _e('Title','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
                                        <td><input type="text" name="awp_news_title" id="awp_news_title" value="<?php echo $news->newsHeadLine; ?>" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><?php _e('Description','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
                                        <td>
                                        <div style="width:630px;">
                                        <?php 
                                         $updated_value = $news->description;
                                         the_editor($updated_value,'awp_news_desc_update','',FALSE); 
                                         ?>
                                         </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Link','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_link" id="awp_news_link" value="<?php echo $news->link; ?>" size="63"/>&nbsp;<small><?php _e('(For ex: http://www.example.com/)','apptivo-businesssite'); ?></small></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published at','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_published_at" id="awp_news_published_at" value="<?php echo $news->publishedAt; ?>" size="63"/></td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Published by','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_published_by" id="awp_news_published_by" value="<?php echo $news->publishedBy; ?>" size="63"/></td>
                                    </tr>
                                    
                                    <!-- <tr>
                                        <td><?php //_e('Image URL','apptivo-businesssite'); ?></td>
                                        <td><input type="text" name="awp_news_imageurl" id="awp_news_imageurl" value="<?php //if(!is_array($news->newsImages)){ echo $news->newsImages; } else{echo $news->newsImages[0]; } ?>" size="63"/></td>
                                    </tr>-->
                                    
                                     <tr valign="top">
									<th scope="row"><?php _e('Image URL','apptivo-businesssite'); ?></th>
									<td><label for="upload_image">
									<input id="awp_news_imageurl" type="text" size="50" name="awp_news_imageurl" value="<?php if(!is_array($news->newsImages)){ echo $news->newsImages; } else{echo $news->newsImages[0]; } ?>" />
									<input id="news_upload_image" type="button" value="Upload Image"  class="button-primary" />
									<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
									</label></td>
									</tr>
									
                                    <tr>
                                        <td><?php _e('Order to show','apptivo-businesssite'); ?></td>
                                        <td><input type="text" class="num" name="awp_news_order" id="awp_news_order" value="<?php echo $news->sequenceNumber; ?>" size="3" /></td>
                                    </tr>
                                    <tr>
                                        <td><input type="hidden" name="startdate" value="<?php echo $news->startDate; ?>"/>
                                        <input type="hidden" name="enddate" value="<?php echo $news->endDate; ?>"/></td>
                                        <td><input type="hidden" name="awp_tstid" value="<?php echo $news->newsId; ?>"/>
                                        <input type="submit" <?php if(!$this->_plugin_activated) { echo 'disabled="disabled"'; }  ?>  value="Update" name="awp_news_update" class="button-primary"/></td>
                                    </tr>

            </table>
        </form>
        </div>
        <?php
    }
    //function to get all news from apptivo
    function getAllNews(){
            $response=getAllNews();
            $all_awp_news = awp_convertObjToArray($response->return->newsList);
            $allnews=array();
            $currentdate = gmdate(DATE_ATOM,mktime());
            if( count($all_awp_news)>0){
	            foreach($all_awp_news as $news)
	            {
		            if(strtotime($news->startDate)<=strtotime($currentdate) && strtotime($news->endDate)>=strtotime($currentdate)){
		               array_push($allnews,$news);
		            }
	            }
            }
            return $allnews;
    }
}
/** End Class
/**
 * get ALL News.
 *
 * @return unknown
 */
function getAllNews()
{         
	     $pubdate_params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                 "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
	            );
	      $plugin_params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
	            "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
                );
          
          // $response = get_data(APPTIVO_BUSINESS_SERVICES,'-news-publisheddate','-news-data','getLastPublishDate','getAllNews',$pubdate_params,$plugin_params);
          // return $response;
}

/**
 * To Add News.
 *
 * @param unknown_type $newsId
 * @param unknown_type $newsHeadLine
 * @param unknown_type $description
 * @param unknown_type $isFeatured
 * @param unknown_type $startDate
 * @param unknown_type $pageSectionImages
 * @param unknown_type $link
 * @param unknown_type $publishedAt
 * @param unknown_type $publishedBy
 * @param unknown_type $sequenceNumber
 * @param unknown_type $endDate
 * @return unknown
 */
function addNews($newsId, $newsHeadLine, $description, $isFeatured, $startDate, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $endDate, $creationDate, $newsImages)
{

	$mktg_news = new AWP_MktNews($newsId, $newsHeadLine, $description, $isFeatured, $startDate, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $endDate,null,$newsImages);
    $params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $mktg_news
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'addNews',$params);
    return $response;
   
}
/**
 * To get news for the particular newsId.
 *
 * @param unknown_type $newsId
 * @return unknown
 */
function getNewsById($newsId)
{
	$params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $newsId
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getNewsByNewsId',$params);
    return $response;
}
/**
 * Update News.
 *
 * @param unknown_type $newsId
 * @param unknown_type $newsHeadLine
 * @param unknown_type $description
 * @param unknown_type $isFeatured
 * @param unknown_type $startDate
 * @param unknown_type $pageSectionImages
 * @param unknown_type $link
 * @param unknown_type $publishedAt
 * @param unknown_type $publishedBy
 * @param unknown_type $sequenceNumber
 * @param unknown_type $endDate
 * @return unknown
 */
function updateNews($newsId, $newsHeadLine, $description, $isFeatured, $startDate, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $endDate = '',$creationDate = '',$newsImages = '')
{
	$mktg_news = new AWP_MktNews($newsId, $newsHeadLine, $description, $isFeatured, $startDate, $pageSectionImages, $link, $publishedAt, $publishedBy, $sequenceNumber, $endDate, null,$newsImages);
    $params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
     			"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $mktg_news
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'updateNews',$params);     
    return $response;
}

?>