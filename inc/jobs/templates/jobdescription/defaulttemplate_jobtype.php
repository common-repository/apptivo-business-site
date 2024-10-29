<?php
/*
 Template Name:Description with type
 Template Type: Shortcode
 */ 
?>


<?php 

if($jobDetail->status == 'SUCCESS'){
	$jobDescription = $jobDetail->data[0]->description;
	$jobType =  $jobDetail->data[0]->categoryName;
   $jobTitle =  $jobDetail->data[0]->title;
   $jobNumber = $jobDetail->data[0]->positionNumber; 
   $jobId =  $jobDetail->data[0]->positionId;
    
}else{
	$jobDescription = "";
}
   

?>

		  <div class="content_cnt">
          <div class="cnt_top job_title">
            <p><b>Job Title : </b><span> <?php echo esc_attr($jobTitle); ?></span></p>
          </div>
          <div class="cnt_mdl">
             
              <div class="job_type">
             <div class="job_type1">
               <p><b>Job Type : </b><span> <?php echo esc_attr($jobType); ?></span></p>
              </div>              
              <b>Job Description:</b><br />
              <?php echo html_entity_decode(esc_attr($jobDescription)); ?>
              </div>

           <div style="width:100px;margin:0 auto;">
           <?php $applicantpageUrl = (!empty($applicantpageUrl)) ? $applicantpageUrl : ''; ?>     
           <form action="<?php echo get_permalink( esc_attr($applicantpageUrl )); ?>" method="post">
                <input id="jobNo" name="jobNo" type="hidden" value="<?php echo esc_attr($jobNumber);?>">
                <input id="jobId" name="jobId" type="hidden" value="<?php echo esc_attr($jobId);?>">
                <input id="jobName" name="jobName" type="hidden" value="<?php echo esc_attr($jobTitle);?>">
                <input title="Apply For this job" alt="Apply For this job" name="applyjobs" id="applyjobs" type="<?php echo esc_attr($jobs_settings['submit_type']); ?>"  <?php echo html_entity_decode(esc_attr($value));?> <?php echo esc_attr($imageSrc); ?>> 
            </form> 
          </div>
          </div>
          <div class="cnt_bottom">
          </div>
        </div>