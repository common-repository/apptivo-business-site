<?php
/*
Template Name:Slider View1
Template Type: Inline
 */
$awp_all_events = $events_content['allevents'];
           $numberofitems =  $events_content['itemstoshow'];
             if( $events_content['custom_css']!= '' )
            {
               $css='<style type="text/css">'.$events_content['custom_css'].'</style>';
            }

  echo "<script type='text/javascript'>
jQuery(document).ready(function(){
 jQuery('#awp_events')
	.cycle({
        fx: 'fade'
     });
});
</script>";
                        
echo '<style type="text/css">
#awp_events {
width:100%;
border:1px solid #D8D9D6;
word-wrap: break-word;
}
#awp_events blockquote{
padding:10px;
width:96%; !important;
font-family:Georgia, "Times New Roman", Times, serif;
font-style:italic;
color:#808080;
display:block;
margin:0px;
padding:10px;
}
 
#awp_events blockquote p{
margin: 0 !important;padding: 5px!important;text-align:justify; 
}
#awp_events blockquote p a { text-decoration:none;}
#awp_events blockquote p img {
float:left;padding-right:10px;padding-top:0px;margin:0px;box-shadow:none;
}
#awp_events blockquote cite a{ text-decoration:none; }
#awp_events blockquote cite {
font-style: normal;
display: block;
text-transform: uppercase;
font-weight: bold;
font-style:italic;
color: #555;
padding-left:5px;
margin-top:10px;
}
#awp_events cite{

}</style>';

 echo '<div id="awp_events">';
 $awp_all_events = array_slice($awp_all_events, 0, $numberofitems);
 foreach($awp_all_events as $events) { 	
    $eventsPublisher = '';
 	if( $events->publishedBy != '')
 	{
 		$eventsPublisher = '&ndash;'.$events->publishedBy; 
 	}
 	echo ' <blockquote><p>';
 	echo '<cite style="margin-bottom:10px;"><a class="absp_events_posttitle" href="'.$events_content[pagelink].'" >'.$events->eventName.'</a></cite>';
 	if(strlen($events->eventImages) != 0 ) { echo '<img src="'.$events->eventImages.'" alt="image" width="120" height="90" class="evetns_image" />'; }
	      	   if(strlen(strip_tags($events->description))>500)
      echo '<span class="absp_events_descrption">'.substr(strip_tags($events->description),0,500).'</span>&nbsp;&nbsp;<span class="readmore"><a class="abs_events_readmore"  href="'.$events_content[pagelink].'" >'.$events_content[more_text].'</a></span>';
   else
       echo '<span class="absp_events_descrption">'.strip_tags($events->description).'</span>';
 	
    echo '<cite><span class="absp_events_postauthor">'.$eventsPublisher.'</span></cite></p></blockquote>';
 }
 
echo '</div>';            
 echo $css;
 ?>