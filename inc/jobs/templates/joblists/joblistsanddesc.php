<?php
/*
 Template Name:job List with Description
 Template Type: Shortcode
 */ 
?>
<div class="cnt_srch">
<?php
if($allJobs['0'] != '')
{
                $count = count($allJobs);
                for($i=0;$i<$count;$i++){
                ?>
                  <p><a title="<?php echo esc_attr($allJobs[$i]->title); ?>" href="<?php echo esc_attr(add_query_arg('vacancyno', $allJobs[$i]->positionNumber, get_permalink(esc_attr($target_pageid))));?>" ><b><?php echo esc_attr($allJobs[$i]->title); ?></b></a></p>
                  <p class="readmore"><?php echo esc_attr(substr(strip_tags($allJobs[$i]->description),0,280)) ?>... <a title="<?php echo esc_attr($allJobs[$i]->title); ?>" href="<?php echo esc_attr(add_query_arg('vacancyno', $allJobs[$i]->positionNumber, get_permalink(esc_attr($target_pageid))));?>">Read more ..</a></p>
                 <?php } ?>
 

  <?php }else {     
  				  echo 'No jobs are found';                 
              }
             
  ?>
</div>
