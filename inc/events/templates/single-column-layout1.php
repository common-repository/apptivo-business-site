<?php
/*
Template Name:Single-Column-Layout1
Template Type: Shortcode
 */
$allevents = $awp_events['allevents'];

if( $awp_events[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_events[custom_css].'</style>';
}

echo '<style type="text/css">
.absp_events_description p{font-family: Arial,Helvetica,sans-serif;font-size: 12px; line-height: 21px;text-align: justify;}
.whl_events .heading{font-family:Arial, Helvetica, sans-serif;font-size:14px;padding-bottom:10px;font-weight:bold;}
.whl_events{padding:10px;border-bottom:1px dashed #666;}
pic_1{display:inline-block;}
</style>';

foreach($allevents as $events)
{
echo '<div class="whl_events">
<div class="absp_events_posttitle heading">'.$events->eventName.'</div>';
if(strlen(trim($events->eventImages)) != 0 )
{
echo '<div class="pic_1"><img class="absp_events_image" src="'.$events->eventImages.'" /></div>';
}
echo '<div>
<p class="absp_events_description">'.$events->description.'</p>
</div>
</div>
';
}
echo $css;
?>