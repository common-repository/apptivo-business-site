<?php
/*
 Template Name: Default Template
 Template Type: Widget
 */
echo $before_widget;
                    $awp_all_news = array();
                    $awp_all_news = $awp_news;                    
                    $currentdate = gmdate(DATE_ATOM,mktime());
                    $count = 1;
                    $page_details = get_page($instance['page_id']);
                     if($instance['order'] == '1')
                        {
                            usort($awp_all_news,'awp_creation_date_compare');
                        }
                    else if($instance['order'] == '2'){
                        usort($awp_all_news,'awp_creation_date_compare');
                    $awp_all_news = array_reverse($awp_all_news,true);
                        }
                    else if($instance['order'] == '3'){
                        shuffle($awp_all_news);
                        }
                    else{
                         usort($awp_all_news,'awp_sort_by_sequence');
                         }
                         if( $instance['custom_css'] != '' )
                        {
                          $css='<style type="text/css">'.$instance['custom_css'].'</style>';

                        }
                        if($instance['itemstoshow']!=0){
                        $numberofitems = $instance['itemstoshow'];
                        }
                        else{
                        $numberofitems = count($awp_all_news);
                        }
                        echo '<style type="text/css">
                        .awp_absp_news_widget .absp_news_posttitle{
                        font-weight: bold;
                        font-size: 13px;
                        }
                        </style>';
                        if(!empty($awp_all_news)){
                        if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
                        foreach($awp_all_news as $news){
                        if($count <= $numberofitems){
                        echo '<div class="awp_absp_news_widget">
                            <div class="absp_news_widget_content">';
                         if($news->link!="")
                            echo '<div class="absp_news_posttitle absp_news_heading"><a href="'.$news->link.'" target="_blank">'.$news->newsHeadLine.'</a></div>';
                         else
                            echo '<div class="absp_news_posttitle absp_news_heading">'.$news->newsHeadLine.'</div>';
                        if(strlen(strip_tags($news->description)) < 250)
                        echo '<div class="absp_news_description"><p>'.strip_tags($news->description).'</p></div>';
                        else
                        echo '<div class="absp_news_description"><p>'.substr(strip_tags($news->description),0,250).'...</p></div>';
                        if($news->newsImages!=""){
                                if(is_array($news->newsImages)) $imageurl = $news->newsImages[0];
                                else $imageurl = $news->newsImages;
                        echo '<div class="image"><img class="absp_news_image image" src="'.$imageurl.'" alt="image" width="48" height="48" /></div>';
                        }

                        echo '<div class="absp_news_postdate puplish_date">'.$news->publishedAt.'</div>
                        <div class="absp_news_postauthor publish_by">'.$news->publishedBy.'</div>';
                        if(trim($instance['more_text']!=""))
                        echo '<div class="readmore"><span><a class="absp_news_readmore" href="'.$page_details->guid.'">'.$instance['more_text'].'</a><span></div>';
                        echo '</div></div>';
                        }
                        $count++;
                        }
                        echo $css;
                       }

echo $after_widget;
                    wp_reset_query();
?>