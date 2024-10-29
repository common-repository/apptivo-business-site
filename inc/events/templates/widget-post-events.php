<?php
/*
 Template Name:  Postdate and Events
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
			if( $events->publishedAt == '' )
			{
				$creationDate = explode('T',$events->creationDate);
				$ListDate=strtotime($creationDate[0]);
				$post_date=date('M d, y',$ListDate);
			}else {
				$post_date = $events->publishedAt;
			}
			echo '<div class="awp_absp_events_widget"><span class="absp_news_postdate">'.$post_date.'</span>';
			if(strlen(strip_tags($events->description)) < 250) :
			echo '<div class="absp_events_description">'.strip_tags($events->description).'</div>';
			else :
			echo '<div class="absp_events_description">'.substr(strip_tags($events->description),0,250).'...</div><span class="absp_events_readmore"><a href="'.$page_details->guid.'" >'.$instance['more_text'].'</a></span>';
			endif;
			echo '</div>';
			
		}
		$count++;
	}
	echo $css;
}
echo $after_widget;
?>