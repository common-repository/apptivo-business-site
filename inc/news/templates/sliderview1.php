<?php
/*
 Template Name:Slider View1
 Template Type: Inline
 */

$awp_all_news=$news_content['allnews'];
$numberofitems= $news_content['itemstoshow'];
if( $news_content['custom_css']!= '' )
 {
 $css='<style type="text/css">'.$news_content['custom_css'].'</style>';
 }

echo "<script type='text/javascript'>
jQuery(document).ready(function(){
 jQuery('#awp_news')
	.cycle({
        fx: 'fade'
     });
});
</script>";
                        
echo '<style type="text/css">
#awp_news {
width:100%;
border:1px solid #D8D9D6;
word-wrap: break-word;
}
#awp_news blockquote{
padding:10px;
width:96%; !important;
font-family:Georgia, "Times New Roman", Times, serif;
font-style:italic;
color:#808080;
display:block;
margin:0px;
padding:10px;
}
 
#awp_news blockquote p{
margin: 0 !important;padding: 5px!important;text-align:justify; 
}
#awp_news blockquote p a { text-decoration:none;}
#awp_news blockquote p img {
float:left;padding-right:10px;padding-top:0px;margin:0px;box-shadow:none;
}
#awp_news blockquote cite a{ text-decoration:none; }
#awp_news blockquote cite {
font-style: normal;
display: block;
text-transform: uppercase;
font-weight: bold;
font-style:italic;
color: #555;
padding-left:5px;
margin-top:10px;
}
#awp_news cite{

}</style>';


 echo '<div id="awp_news">';
  $awp_all_news = array_slice($awp_all_news, 0, $numberofitems);
 
 foreach($awp_all_news as $news) {
 	 $absp_news_publisher = '';
 	if( $news->publishedBy != '')
 	{
 		$absp_news_publisher = '&ndash;'.$news->publishedBy; 
 	}
 	echo ' <blockquote><p>';
 	echo '<cite style="margin-bottom:10px;"><a class="absp_news_posttitle" href="'.$news_content[pagelink].'" >'.$news->newsHeadLine.'</a></cite>';
 	if(strlen($news->newsImages) != 0 ) { echo '<img src="'.$news->newsImages.'" alt="image" width="120" height="90" class="absp_news_image" />'; }
	      	   if(strlen(strip_tags($news->description))>500)
      echo '<span class="absp_news_descrption">'.substr(strip_tags($news->description),0,500).'</span>&nbsp;&nbsp;<span class="absp_news_readmore"><a href="'.$news_content[pagelink].'" >'.$news_content[more_text].'</a></span>';
   else
       echo '<span class="absp_news_descrption">'.strip_tags($news->description).'</span>';
 	
    echo '<cite><span class="absp_news_postauthor"'.$absp_news_publisher.'<span></cite></p></blockquote>';
 }
 
echo '</div>';          
 echo $css;
?>