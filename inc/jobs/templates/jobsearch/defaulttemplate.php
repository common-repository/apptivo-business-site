<?php
/*
 Template Name:Default Template
 Template Type: Shortcode
 */ 
$form = $jobsearchform;
$form_Fields = $jobsearchform['fields'];
$JobSearchResults = (!empty($JobSearchResults)) ? $JobSearchResults : '';
$allJobs = $JobSearchResults;
$jobTypeLists = getAllStatusesTypesV6()->categories;
if( $jobsearchform['css'] != '' ){
	echo $css='<style type="text/css">'.esc_attr($jobsearchform['css']).'</style>';
}
if( (!$jobsearchForm_Submit) && ( $result_type == 'widget' || $result_type == '') ){  // $display_jobsearchForm is boolean ?>
<div class="search_main">
<div class="jobsearch_main">

<form id="<?php echo esc_attr($jobsearchform['name']).'_jobsearchforms '; ?>" name="<?php echo esc_attr($jobsearchform['name']).'_jobsearchforms '; ?>" action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post">
<input type="hidden" value="<?php echo esc_attr($jobsearchform['name']); ?>" name="awp_job_seachformname" id="awp_job_seachformname">
<?php
	//echo "<pre> form_Fields deftemp123 :- "; print_r($form_Fields); echo "</pre>";
	foreach( $form_Fields as $formFields){
		if(is_array($formFields) && $formFields['type'] == 'text' && $formFields['fieldid'] != 'industry' && $formFields['fieldid'] != 'jobtype'){
			?>
			<div class="text_bg job_keywords">
				<div class="label"> <span class="absp_jobsearch_label"><?php echo esc_attr($formFields['showtext']); ?></span> </div>
				<div class="field" >
					<input type="text" border="0" value="Keyword" class="absp_jobsearch_input_text" onclick="if(this.value=='Keyword') this.value='';" onblur="if(this.value=='') this.value='Keyword';" name="<?php echo esc_attr($formFields['fieldid']);?>" id="<?php echo esc_attr($formFields['fieldid']); ?>">
				</div>
			</div>
		<?php }
		//$optionvalues = $formFields['options'];
		if( isset($formFields['options']) ){
			$optionvalues = $formFields['options'];
		}else{
			$optionvalues = '';
		}
		
		//if($optionvalues != ''){
		if( is_array($formFields) && isset($formFields['fieldid']) && $formFields['fieldid'] == 'industry' ){ ?>
			<div  class="text_bg job_industry">
				<div class="label"> <span class="absp_jobsearch_label"><?php echo esc_attr($formFields['showtext']); ?> </span> </div>
				<div class="field">
					<select class="absp_jobsearch_select" value="" name="industry_select" id="industry_select"  border="0" style="border: 0px none; width:270px; height:55px; margin-top:2px;overflow:auto;">
						<option  value="0" style="">Select  <?php echo esc_attr($formFields['showtext']);?></option>
						<?php /*foreach($optionvalues as $opt_val){
									$option_arr = explode('::',$opt_val); ?>                       
								<option value="<?php echo esc_attr($option_arr[0]); ?>" style=""><?php echo esc_attr($option_arr[1]); ?></option>
						<?php }*/ ?>
							<?php
								$allIndustries = getAllIndustriesV6()->industries;
								foreach($allIndustries as $industries){ ?>
								<option value="<?php echo html_entity_decode(esc_attr($industries->industryId)); ?>"><?php echo html_entity_decode(esc_attr($industries->industryName));?></option>
								<?php
								} ?>
					</select>
				</div>
			</div>
				
		<?php }
		
		if( is_array($formFields) && isset($formFields['fieldid']) && $formFields['fieldid'] == 'jobtype' ){
			$allJobTypes = getAllStatusesTypesV6()->categories; ?>
			<div  class="text_bg job_industry">
				<div class="label"> <span class="absp_jobsearch_label"><?php echo esc_attr($formFields['showtext']); ?> </span> </div>
				<div class="field">
					<select class="absp_jobsearch_select" value="" name="<?php echo esc_attr($formFields['fieldid']); ?>" id="<?php echo esc_attr($formFields['fieldid']); ?>"  border="0" style="border: 0px none; width: 250px; height: 55px; margin-top: 2px;">
						<option value="0" style="">Select  <?php echo esc_attr($formFields['showtext']);?></option>
						<?php foreach($allJobTypes  as $jobType){ ?>
								<option value="<?php echo html_entity_decode(esc_attr($jobType->jobTypeId)); ?>"> <?php echo html_entity_decode(esc_attr($jobType->name));?> </option>
						<?php } ?>
					</select>
				</div>
			</div>

		<?php }

		if( is_array($formFields) && isset($formFields['type']) && $formFields['type'] == 'select' ){ ?> 
			<div class="text_bg jobtype_drop">
				<div class="label"> <span class="absp_jobsearch_label"><?php echo esc_attr($formFields['showtext']); ?> </span> </div>
				<div class="field">
					<select class="absp_jobsearch_select" value="" name="<?php echo esc_attr($formFields['fieldid']); ?>" id="<?php echo esc_attr($formFields['fieldid']); ?>"  border="0" style="border: 0px none; width: 168px; height: 21px; margin-top: 2px;">
						<option value="0" style="">Select  <?php echo esc_attr($formFields['showtext']);?></option>
						<?php foreach($optionvalues as $opt_val){
							$opt_value = strtoupper(trim($opt_val)); 
							$opt_value = str_replace(" ","_",$opt_value);
						?>                       
						<option value="<?php echo esc_attr($opt_value); ?>" style=""><?php echo esc_attr($opt_val); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>             
		<?php }

		if( is_array($formFields) && isset($formFields['type']) && $formFields['type'] == 'checkbox' ){ ?> 

				<div class="text_bg jobtype_chk">
					<div class="label"> <span class="absp_jobsearch_label"><?php echo esc_attr($formFields['showtext']); ?> </span> </div>
					<div class="field">
						<?php 


						/*  foreach($optionvalues as $opt_val)
						{   $opt_value = strtoupper(trim($opt_val)); 
						$opt_value = str_replace(" ","_",$opt_value);    
						*/
									
						foreach($jobTypeLists as $opt_type){  
							$jobTypeId = $opt_type->jobTypeId; 
							$jobTypeVal = $opt_type->name;

						?>                       
							<input class="absp_jobsearch_input_checkbox" value="<?php echo esc_attr($jobTypeId); ?>" type="checkbox" name="<?php echo esc_attr($formFields['fieldid']).'[]';?>" /> &nbsp;&nbsp;<label><?php echo esc_attr($jobTypeVal); ?> </label><br />
						<?php } ?>
					</div>
				</div>

		<?php }

		if( is_array($formFields) && isset($formFields['type']) && $formFields['type'] == 'radio' ){ ?> 

		<div class="text_bg jobtype_radio">
			<div class="label"> <span class="absp_jobsearch_label"><?php echo esc_attr($formFields['showtext']); ?> </span> </div>
			<div class="field">
				<?php foreach($optionvalues as $opt_val)
				{ ?>                       
					<option value="<?php echo esc_attr($opt_val); ?>" style=""><?php echo esc_attr($opt_val); ?></option>
					<input class="absp_jobsearch_input_radio" type="radio" name="<?php echo esc_attr($formFields['fieldid']);?>" />&nbsp;&nbsp;<label><?php echo esc_attr($opt_val); ?></label><br />
				<?php } ?>
			</div>
		</div>

	<?php }
      //}
	}

	if($jobsearchform['submit_button_type']=="submit" &&($jobsearchform['submit_button_val'])!=""){
        $button_value = 'value="'.$jobsearchform['submit_button_val'].'"';
      }
      else{
        $hrjobsform = (!empty($hrjobsform)) ? $hrjobsform : [];
        $hrjobsform['submit_button_val'] = (array_key_exists('submit_button_val',$hrjobsform)) ? $hrjobsform['submit_button_val'] : '';
      	if($hrjobsform['submit_button_val'] == '' || empty($hrjobsform['submit_button_val'])) :
      		$hrjobsform['submit_button_val'] = awp_image('submit_button');
      	endif;
      	
        $button_value = 'src="'.$jobsearchform['submit_button_val'].'"';
      }
      $html = '<div class="jobsrch_submit"><input type="'.$jobsearchform['submit_button_type'].'" class="absp_jobsearch_button_submit submit" '.$button_value.' name="awp_jobsearchform_submit_'.$jobsearchform['name'].'"  id="awp_jobsearchform_submit_'.$jobsearchform['name'].'" /></div>';
      echo html_entity_decode(esc_attr($html));
?>
</form>
  </div>
</div>
<?php }else{
		
	?>
<div class="cnt_srch">
<?php
if($allJobs['0'] != ''){
                $count = count($allJobs);
                for($i=0;$i<$count;$i++){
                ?>
                  <p><a title="<?php echo esc_attr($allJobs[$i]->title); ?>" href="<?php echo esc_attr(add_query_arg('vacancyno', $allJobs[$i]->positionNumber, get_permalink($target_pageid)));?>"><b><?php echo esc_attr($allJobs[$i]->title); ?></b></a></p>
                  <p class="readmore"><?php echo esc_attr(substr(strip_tags($allJobs[$i]->description),0,280)) ?>... <a title="<?php echo esc_attr($allJobs[$i]->title); ?>" href="<?php echo esc_attr(add_query_arg('vacancyno', $allJobs[$i]->positionNumber, get_permalink($target_pageid)));?>">Read More..</a></p>
                 <?php } 
 }else{
                 echo 'No jobs are found with the selected keywords. Please modify your search and try again';
                   
              }
  ?>
</div>
<?php 
}
    
?>

<script type= "text/javascript">
$(document).ready(function(){
	$("#industry_select").change(function(){
		 var selectedValue = $(this).val();
		 $('#industry_select option[value="'+selectedValue+'"]').attr('selected', 'selected');
		});

});

</script>
