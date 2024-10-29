<?php
/*
 Template Name: Default Template
 Template Type: Widget
 */
 echo $before_widget;
                    $awp_all_events = array();
                    $awp_all_events = $awp_events;
                    $currentdate = gmdate(DATE_ATOM,mktime());
                    $count = 1;
                    $page_details = get_page($instance['page_id']);
                    if($instance['order'] == '1')
                        {
                            usort($awp_all_events,'awp_creation_date_compare');
                        }
                    else if($instance['order'] == '2'){
                        usort($awp_all_events,'awp_creation_date_compare');
                    $awp_all_events = array_reverse($awp_all_events,true);
                        }
                    else if($instance['order'] == '3'){
                        shuffle($awp_all_events);
                        }
                    else{
                         usort($awp_all_events,'awp_sort_by_sequence');
                         }
                         if( $instance['custom_css'] != '' )
                        {
                          $css='<style type="text/css">'.$instance['custom_css'].'</style>';

                        }
                        if($instance['itemstoshow']!=0){
                        $numberofitems = $instance['itemstoshow'];
                        }
                        else{
                        $numberofitems = count($awp_all_events);
                        }
                        if(!empty($awp_all_events)){
                         if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
                        foreach($awp_all_events as $events){
                        if($count <= $numberofitems){
                        echo '<div class="awp_events_widget">
                            <div class="events_widget_content">';
                         if($events->link!="")
                            echo '<div class="absp_events_posttitle event_name"><a href="'.$events->link.'" target="_blank">'.$events->eventName.'</a></div>';
                         else
                           echo '<div class="absp_events_posttitle event_name">'.$events->eventName.'</div>';
                       if($events->eventImages!=""){
                         if(is_array($events->eventImages)) $imageurl = $events->eventImages[0];
                        else $imageurl = $events->eventImages;
                        echo '<div class="absp_events_image image"><img src="'.$imageurl.'" alt="image" width="48" height="48" /></div>';
                        }
                         if(strlen(strip_tags($events->description)) < 250)
                        echo '<div class="absp_events_description">'.strip_tags($events->description).'</div>';
                        else
                        echo '<div class="absp_events_description">'.substr(strip_tags($events->description),0,250).'...</div>';
                        echo '<div class="absp_events_postdate publish_date">'.$events->publishedAt.'</div>
                        <div class="absp_events_postauthor publish_by">'.$events->publishedBy.'</div>';
                        if(trim($instance['more_text']!=""))
                        echo '<div class="readmore"><span><a class="absp_events_readmore" href="'.$page_details->guid.'">'.$instance['more_text'].'</a><span></div>';
                        echo '</div></div>';
                        }
                          $count++;
                        }
                        echo $css;
                        }

echo $after_widget;
wp_reset_query();
?>