<?php
/*
Template Name: Name, Testimonials
Template Type: Shortcode
*/
$awp_all_testimonials = $awp_testimonials['alltestimonials'];
$css = '';

if( $awp_testimonials['custom_css'] != '' ){
	$css = '<style type="text/css">' . wp_kses_post($awp_testimonials['custom_css']) . '</style>';
}
?>
<style>.testimonial_title_text{float: left;}</style>
<div id="sfstest-page">
<?php //echo "<pre> awp_all_testimonials:- ";print_r($awp_all_testimonials);echo "</pre>"; ?>
<?php foreach($awp_all_testimonials as $testimonial){
	if($testimonial->statusName == "Approved"){
		$name = $testimonial->customerName;
		$testimonialContent = $testimonial->testimonial;
		$testimonialStatus=$testimonial->statusName;
		?>
		<div class="testimonial_title_text"><?php echo esc_html($name).', '; ?></div>
		<div class="testimonial_description_text">
		<?php echo esc_html($testimonialContent); ?>
		</div>
		<div align="left" class="bdr"></div>
	<?php }
} ?>
</div>
<?php
$css = (!empty($css)) ? $css : '';
echo html_entity_decode($css);
?>
