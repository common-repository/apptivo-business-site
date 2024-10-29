<?php
/*
Template Name:Slider View1
Template Type: Inline
 */

$awp_all_testimonials = $awp_testimonials['alltestimonials'];
$numberofitems= $awp_testimonials['itemstoshow'];
if( $awp_testimonials['custom_css'] != '' ){
	$css='<style type="text/css">'.esc_attr($awp_testimonials['custom_css']).'</style>';
}
if($awp_testimonials['itemstoshow']!=0){
	$numberofitems = $awp_testimonials['itemstoshow'];
}else{
	$numberofitems = count($awp_all_testimonilas);
}
                        
echo "<script type='text/javascript'>
jQuery(document).ready(function(){
 jQuery('#testimonials')
	.cycle({
        fx: 'fade'
     });
});
</script>";
                        
echo '<style type="text/css">
#testimonials {
width:100%;
border:1px solid #D8D9D6;
word-wrap: break-word;
}
#testimonials blockquote{
padding:10px;
width:96%; !important;
font-family:Georgia, "Times New Roman", Times, serif;
font-style:italic;
color:#808080;
display:block;
margin:0px;
padding:10px;
}
 
#testimonials blockquote p{
margin: 0 !important;padding: 5px!important;text-align:justify; 
}
#testimonials blockquote p a { text-decoration:none;}
#testimonials blockquote p img {
float:left;padding-right:10px;padding-top:0px;margin:0px;box-shadow:none;
}
#testimonials blockquote cite a{ text-decoration:none; }
#testimonials blockquote cite {
font-style: normal;
display: block;
text-transform: uppercase;
font-weight: bold;
font-style:italic;
color: #555;
padding-left:5px;
margin-top:10px;
}</style>';


echo '<div id="testimonials">';
//$awp_all_testimonials = array_slice($awp_all_testimonials, 0, $numberofitems+1);
$count="1";
foreach($awp_all_testimonials as $testimonial){
	$accountName = '';
	$jobTitle = '';
	$companyName = ''; 	
	$testimonialStatus=$testimonial->statusName;
	if($testimonialStatus=="Approved"){
		if( $testimonial->customerName != '' ){
			$accountName = '<cite>&ndash;'.$testimonial->customerName.'</cite>';
		}
		if (property_exists($testimonial,'contact')){
			if( $testimonial->contact->jobTitle != ''){
				$jobTitle = '&ndash;'.$testimonial->contact->jobTitle;
			}
		}
		if (property_exists($testimonial,'account') && property_exists($testimonial,'contact')){
			if( $testimonial->account->website != '' &&  $testimonial->contact->companyName != ''){
				$companyName = '<a href="'.$testimonial->account->website.'" target="_blank">'.$testimonial->contact->companyName.'</a>';
			}elseif ( $testimonial->contact->companyName != ''){
				$companyName = $testimonial->contact->companyName;
			}
		}

		echo ' <blockquote><p>';
		if(strlen(strip_tags($testimonial->testimonial))>400)
			echo '<span class="absp_testimonials_descrption">'.esc_attr(substr(strip_tags($testimonial->testimonial),0,400)).'</span>&nbsp;&nbsp;<span class="read"><a class="absp_testimonials_readmore" href="'.esc_attr($awp_testimonials['pagelink']).'" >'.esc_attr($awp_testimonials['more_text']).'</a></span>';
		else
			echo '<span class="absp_testimonials_descrption">'.esc_attr(strip_tags($testimonial->testimonial)).'</span>';

		echo html_entity_decode(esc_attr($accountName));
		echo '<cite>'.'<span class="absp_testimonials_jobtitle">'.esc_attr($jobTitle).'</span>'.'&nbsp;&nbsp;'.'<span class="absp_testimonials_company">'.esc_attr($companyName).'</span>'.'</cite></p></blockquote>';
		if($count==$numberofitems){ echo '</div>';
			echo html_entity_decode(esc_attr($css)); break;
		}
		$count++;
	}
}
?>
