<?php
/*
 Template Name:Default Template
 Template Type: Shortcode
 */
?>
  <div class="content_cnt">
        
          <div class="cnt_mdl">
             
              <div class="job_type">
            
              <?php 
           
              if($jobDetail->status == 'SUCCESS'){
					$jobDescription = $jobDetail->data[0]->description;
					 $jobTitle =  $jobDetail->data[0]->title;
					   $jobNumber = $jobDetail->data[0]->positionNumber; 
					   $jobId =  $jobDetail->data[0]->positionId;
								
				}else{
				$jobDescription = "";
				}
        echo html_entity_decode(esc_attr($jobDescription));              
              ?>
              </div>

              <div style="width:100px;margin:0 auto;">
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