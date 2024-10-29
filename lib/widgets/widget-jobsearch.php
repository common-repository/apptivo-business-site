<?php
/**
 * Apptivo Job Search form Widget
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
class JobSearch_Widget extends WP_Widget {
        /** constructor */
        var $widget_name;
		var $widget_description;
		 
   function __construct()
   {    
        $this->widget_description = __( 'A form to search your job positions', 'apptivo-businesssite' );
		$this->widget_name = __('[Apptivo] Job Search', 'apptivo-businesssite' );
        $widget_ops = array('description' => $this->widget_description );
        parent::__construct('jobsearch_widget', $this->widget_name, $widget_ops,'');
        
        }

        function widget($args, $instance) {
           extract($args);
           $instance = wp_parse_args((array) $instance, array(
                            'title' => '',
                            'style' => '',
                            'page_id' => '',
                            'custom_css' => '',                           
                            'itemstoshow' => AWP_DEFAULT_ITEM_SHOW,
                            'more_text' => AWP_DEFAULT_MORE_TEXT,
           					'readmore_link' => '',
                            'content_limit' => '',
                            'jobsearch_form' => '',
                            'awp_widget_templatelayout' => ''
                    ) );
             $_template_file = AWP_JOBSEARCHFORM_TEMPLATEPATH."/".$instance['awp_widget_templatelayout'];
             $action = $instance['page_id'];
             $readmore_Link = $instance['readmore_link'];
             $formname = $instance['jobsearch_form'];
             $maxcnt = $instance['itemstoshow'];
             if($maxcnt == 0 )
             {
             	$maxcnt = 1;
             }
             $jobsearch_forms=get_option('awp_jobsearchforms');
             $jobsearchform=AWP_Jobs::get_jobsearch_field($formname);
             if(!empty($jobsearch_forms[0]['fields']))
              {	 
            	include $_template_file;           
              }else {
              	if ($instance['title']) echo html_entity_decode(esc_attr($before_title)) . apply_filters('widget_title', $instance['title']) . $after_title;
              	echo awp_messagelist('jobsearch-form-display-page');
              }
            
            }

            function update($new_instance, $old_instance) {
            	$new_instance['more_text']=(trim($new_instance['more_text'])!="")?$new_instance['more_text']:AWP_DEFAULT_MORE_TEXT;
                $new_instance['itemstoshow'] = is_numeric($new_instance['itemstoshow'])?$new_instance['itemstoshow']:AWP_DEFAULT_ITEM_SHOW;
                return $new_instance;
                
            }
            function form($instance) {
                    $instance = wp_parse_args( (array)$instance, array(
                            'title' => '',
                            'style' => '',
                            'page_id' => '',
                            'custom_css' => '',                           
                            'itemstoshow' => AWP_DEFAULT_ITEM_SHOW,
                            'more_text' => AWP_DEFAULT_MORE_TEXT,
                            'readmore_link' => '',
                            'content_limit' => '',
                            'jobsearch_form' => '',
                            'awp_widget_templatelayout' => ''                   
                            ) );

                        ?>
<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'apptivo-businesssite'); ?>:</label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>


            <p>
              <label for="<?php echo esc_attr($this->get_field_id('custom_css')); ?>"><?php _e('Custom CSS:','apptivo-businesssite'); ?></label>
              <textarea id="<?php echo esc_attr($this->get_field_id('custom_css')); ?>" name="<?php echo esc_attr($this->get_field_name('custom_css')); ?>" class="widefat" rows="6" cols="4"><?php echo esc_attr($instance['custom_css']); ?></textarea>
            </p>
            
          

            <p>
            <label for="<?php echo esc_attr($this->get_field_id('jobsearch_form')); ?>"><?php _e('Job Search Form Name', 'apptivo-businesssite'); ?>:</label>
            <?php 
            $jobSearch_form = get_option('awp_jobsearchforms');
           ?>
            
                  <select id="<?php echo esc_attr($this->get_field_id('jobsearch_form')); ?>" name="<?php echo esc_attr($this->get_field_name('jobsearch_form')); ?>" >
						<?php
						foreach ($jobSearch_form as $job_Search )
						{
							?>
							<option value="<?php echo esc_attr($job_Search['name']); ?>"  <?php selected($job_Search['name'], $instance['jobsearch_form']); ?> >
							<?php echo esc_attr($job_Search['name']); ?>
							</option>
							<?php }?>
				  </select>
		    </p>
		    
		                <p>
            <label for="<?php echo esc_attr($this->get_field_id('awp_widget_templatelayout')); ?>"><?php _e('Select Template', 'apptivo-businesssite'); ?>:</label>
            <?php 
            $plugintemplates = get_awpTemplates(AWP_JOBSEARCHFORM_TEMPLATEPATH,'widget');
           ?>
            
                  <select id="<?php echo esc_attr($this->get_field_id('awp_widget_templatelayout')); ?>" name="<?php echo esc_attr($this->get_field_name('awp_widget_templatelayout')); ?>" >
						<?php
						foreach (array_keys( $plugintemplates ) as $template )
						{
							?>
							<option value="<?php echo esc_attr($plugintemplates[$template])?>"  <?php selected($plugintemplates[$template], $instance['awp_widget_templatelayout']); ?> >
							<?php echo esc_attr($template)?>
							</option>
							<?php }?>
				  </select>
		    </p>
            <p><label for="<?php echo esc_attr($this->get_field_id('page_id')); ?>"><?php _e('Job Result Page', 'apptivo-businesssite'); ?>:</label>
                 
 <select id="<?php echo esc_attr($this->get_field_id('page_id')); ?>" name="<?php echo esc_attr($this->get_field_name('page_id')); ?>">
 <?php
  $pages = get_pages(); 
  foreach ($pages as $pagg) {
  	?>
  	<option value="<?php echo esc_attr($pagg->ID); ?>"  <?php selected($pagg->ID, $instance['page_id']); ?> >
							<?php echo esc_attr($pagg->post_title); ?>
	</option>
  	<?php 
  }
 ?>	</select>

 </p>
            
            <?php  if(strlen(trim($instance['page_id'])) != 0 )
            { ?>
            <p>
            Copy and Paste this shortcode in Target page <br  /> <b>[apptivo_job_searchform name="<?php echo esc_attr($instance['jobsearch_form']); ?>" resulttype="widget"]</b> 
            </p>
            <?php } ?>        

            <?php
            }

}