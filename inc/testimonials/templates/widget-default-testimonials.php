<?php
/* 
 Template Name: Default Template
 Template Type: Widget
 */
$awp_all_testimonials = $awp_testimonials;
$count=1;
$page_details = get_page($instance['page_id']);
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
                        if ($instance['title']) html_entity_decode(esc_attr($before_title)) . apply_filters('widget_title', $instance['title']) . $after_title;
                    foreach($awp_all_testimonials as $testimonial){
if($testimonial->testimonialStatus=="APPROVED"){
                        if($count <= $numberofitems){
                       echo  '<div class="awp_news_inline">
                            <div class="testimonials_description awp_testimonisls_content">';
                       if(strlen(strip_tags($testimonial->testimonial))>250)
                          echo '<div class="absp_testimonials_description testimonial">'.esc_attr(substr(strip_tags($testimonial->testimonial),0,250)).'</div>';
                       else
                           echo '<div class="absp_testimonials_description testimonial">'.esc_attr(strip_tags($testimonial->testimonial)).'</div>';
                            if($testimonial->testimonialImageUrl!="" && strrpos($testimonial->testimonialImageUrl,'http')!==false)
                                echo '<div class="image"><img src="'.esc_attr($testimonial->testimonialImageUrl).'" alt="image" width="48" height="48" class="testimonials_image" /></div>';
                        echo '<div class="absp_testimonials_author name">'.esc_attr($testimonial->account->accountName).'</div>';
                        echo '<div class="absp_testimonials_jobtitle company">'.esc_attr($testimonial->contact->jobTitle).'</div>';
                        echo '<div class="absp_testimonials_comapny company">'.esc_attr($testimonial->contact->companyName).'</div>';
                        echo '<div class="absp_testimonials_website website">'.esc_attr($testimonial->account->website).'</div>';
                        if(trim($instance['more_text']!=""))
                        echo '<div class="readmore"><span><a class="absp_testimonials_readmore" href="'.esc_attr($page_details->guid).'">'.esc_attr($instance['more_text']).'</a><span></div>';
                        echo '</div></div>';
                        }
                   }
                   $count++;
	        }
            echo html_entity_decode(esc_attr($css));
              }

?>