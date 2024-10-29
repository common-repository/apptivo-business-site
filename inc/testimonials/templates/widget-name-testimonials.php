<?php
/* 
 Template Name: Name, Testimonials
 Template Type: Widget
 */
$awp_all_testimonials = $awp_testimonials;
$count=1;
$page_details = get_post($instance['page_id']);


if($instance['order'] == '1')
                        {
                            usort($awp_all_testimonials,'awp_creation_date_compare');
                        }
                    else if($instance['order'] == '2'){
                        usort($awp_all_testimonials,'awp_creation_date_compare');
                    $awp_all_testimonilas = array_reverse($awp_all_testimonials,true);
                        }
                    else if($instance['order'] == '3'){
                        shuffle($awp_all_testimonials);
                        }
                    else{
                         usort($awp_all_testimonials,'awp_sort_by_sequence');
                         }
                         if( $instance['custom_css'] != '' )
                        {
                        	
                          $css='<style type="text/css">'.esc_attr($instance['custom_css']).'</style>';

                        }
                        
                       if($instance['itemstoshow']!=0){
                        $numberofitems = $instance['itemstoshow'];
                        }
                        else{
                        $numberofitems = count($awp_all_testimonials);
                        }
                       if(!empty($awp_all_testimonials)){
                        if ($instance['title']) echo html_entity_decode(esc_attr($before_title)) . apply_filters('widget_title', $instance['title']) . $after_title;
                    foreach($awp_all_testimonials as $testimonial){
                   if($testimonial->statusName=="Approved"){
                        if($count <= $numberofitems){

                        if(strlen(strip_tags($testimonial->testimonial))>250)
                        {
                        	$testimonial_content = substr(strip_tags($testimonial->testimonial),0,250);                        	
                        }else {
                        	$testimonial_content = strip_tags($testimonial->testimonial);
                        }                        
                        echo '<div id="sfstest-sidebar">
                        <p class="testimonial_title_text">'.esc_attr($testimonial->customerName).'</p>
                        <p class="testimonial_description_text">
                        '.esc_attr($testimonial_content).'
                        </p>
                        <div align="left" class="bdr"></div>    
                        </div>';
                        
                   }                   
                   
                   $count++;
	        }
                    echo html_entity_decode(esc_attr($css));
                    }
              }
              if(!empty($awp_all_testimonials)){
              echo '<div class="normal_text">
                        <strong>
                        <a href="'.esc_attr($page_details->guid).'">'.esc_attr($instance['more_text']).'</a>
                        </strong>
                        </div>';
              }              

?>