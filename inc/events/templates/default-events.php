<?php
/*
Template Name:Default Events
Template Type: Shortcode
 */

$allevents = $awp_events['allevents'];
if( $awp_events[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_events[custom_css].'</style>';
}
echo '<style type="text/css">
s{ text-decoration:none;	}
.eventss {display: inline-block;margin-top: 15px;margin-left:8px;}
.middle {  display: inline-block;padding:8px; }
.absp_events_image {float: left;height: 80px;margin: 3px 10px 4px 12px;width: 78px; border: 1px solid #f00;}    
p {color: #211B15;font-family: Trebuchet MS,Arial,Helvetica,sans-serif;font-size: 13px;font-style: italic;font-weight: normal;margin-right: 12px;text-align: justify;}
#events0 { border:1px solid #ccc;margin: 10px 0px 10px 0px;}
.absp_events_posttitle { font-weight : bold; }
.top { margin:0px; }
.absp_events_postmeta .post{color:#f00; }
</style>';

foreach($allevents as $events) {
	$creationDate = explode('T',$events->creationDate);
	$ListDate=strtotime($creationDate[0]);
	if($events->publishedAt != '')
	{
	$dispDate = $events->publishedAt;
	}else {
	$dispDate=date('M d, y',$ListDate); }
	$imgSrc = $events->eventImages;
	
	?>
 <div id="events0">                        
                        <div class="eventss">
                        <div class="top"></div>
                          <p class="absp_events_posttitle"><?php echo $events->eventName; ?></p>
                          <div class="absp_events_postmeta">
                          <span class="absp_events_post">Posted: </span><span class="absp_events_postdate"><?php echo $dispDate; ?></span>
                          <?php if($events->publishedBy != '' ) { ?>
                          <span class="absp_events_postby"> by </span>
                          <?php echo '<span class="event_postauthor"><a class="absp_events_postauthor" href="'.$events->link.'" >'.$events->publishedBy.'</a></span>';?>
                          <?php } ?>
                          </div>
                        <div class="middle">
                        <?php if( strlen(trim($imgSrc)) != 0 ) { ?>
                        <img class="absp_events_image" src="<?php echo $imgSrc; ?>"> <?php } ?>              
<p class="absp_events_description"><?php echo $events->description; ?></p> 
               
                    </div> 
          <div class="bottom"></div>      
                
                </div>
             </div> 
 <?php } 
 ?>
 <?php echo $css; ?>     