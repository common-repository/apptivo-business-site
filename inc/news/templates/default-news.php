<?php
/*
 Template Name:Defaut News
 Template Type: Shortcode
 */
$allnews = $awp_news['allnews'];
if( $awp_news[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_news[custom_css].'</style>';
}
echo '<style type="text/css">
s
{
  text-decoration:none;	
}
.newss {display: inline-block;margin-top: 15px;margin-left: 8px;}
.middle {  display: inline-block;padding:8px; }
.news_image {float: left;height: 80px;margin: 3px 10px 4px 12px;width: 78px;border: 1px solid #000;}    
p {color: #211B15;font-family: Trebuchet MS,Arial,Helvetica,sans-serif;font-size: 13px;font-style: italic;font-weight: normal;margin-right: 12px;text-align: justify;}  
#news0 {border:1px solid #ccc;margin: 10px 0px 10px 0px; }
.absp_news_postmeta .absp_news_post { font-weight:bold;}
.absp_news_posttitle { font-weight : bold; }
.top { margin:0px; } </style>';
echo $css;

foreach($allnews as $News) {
	$creationDate = explode('T',$News->creationDate);
	$ListDate=strtotime($creationDate[0]);
	if($News->publishedAt != '') :
	$dispDate=$News->publishedAt;
	else:
	$dispDate=date('M d, y',$ListDate);
	endif;
	
	$imgSrc = $News->newsImages;
	?>
 <div id="news0">                        
                        <div class="newss">
                        <div class="top"></div>
                          <p class="absp_news_posttitle"><?php echo $News->newsHeadLine; ?></p>
                         <div class="absp_news_postmeta">
                         <span class="absp_news_post">Posted: </span><span class="absp_news_postdate"><?php echo $dispDate; ?></span>
                         <?php  if(strlen($News->publishedBy) != 0) { ?><span class="absp_news_postby"> by </span><span class="absp_news_postauthor"><?php echo '<a href="'.$News->link.'" >'.$News->publishedBy.'</a></span>';?><?php } ?></div>
                        <div class="middle">
                        <?php if( strlen(trim($imgSrc)) != 0 ) { ?>
                        <img class="absp_news_image" src="<?php echo $imgSrc; ?>"> <?php } ?>              
<p class="absp_news_description"><?php echo $News->description; ?></p> 
                    <div style="float: right;">
                        <?php if(strlen(trim($Name)) != 0) { ?><p><?php echo '<span class="absp_news_name">'.$Name.'</span>'; echo '&nbsp;'.'<span class="absp_news_jobtitle">'.$JobTitle.$seperator.'</span>'.'<a class="absp_news_company" href="'.$website.'" target="_blank" >'.$companyName.'</a>'; ?></p> <?php } ?>
                    </div>
                    </div> 
          <div class="bottom"></div>      
                
                </div>
               
             </div> 
<?php } ?>