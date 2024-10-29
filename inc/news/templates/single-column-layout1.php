<?php
/*
 Template Name:Single Column
 Template Type: Shortcode
 */
$allnews = $awp_news['allnews'];
if( $awp_news[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_news[custom_css].'</style>';
}
echo '<style type="text/css">
.absp_news_description p{font-family: Arial,Helvetica,sans-serif;font-size: 12px; line-height: 21px;text-align: justify;}
.whl_news .absp_news_posttitle{font-family:Arial, Helvetica, sans-serif;font-size:14px;padding-bottom:10px;font-weight:bold;}
.whl_news{padding:10px;border-bottom:1px dashed #666;}
.absp_news_image{display:inline-block;}
</style>';

foreach($allnews as $news)
{
echo '<div class="whl_news">
<div class="absp_news_posttitle">'.$news->newsHeadLine.'</div>';
if(strlen(trim($news->newsImages)) != 0)
{
echo '<div class="absp_news_image pic_1"><img src="'.$news->newsImages.'" /></div>';
}
echo '<div class="absp_news_description">
<p>'.$news->description.'</p>
</div>
</div>
';
}
echo $css;
?>