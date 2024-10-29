<?php
/*
 Template Name: Job List
 Template Type: Shortcode
 */
?>
<div class="list">
<?php
if($allJobs['0'] != '')
{
                $count = count($allJobs);
                ?><ul><?php 
                for($i=0;$i<$count;$i++){
                ?> 
                 <li><a title="<?php echo esc_attr($allJobs[$i]->title); ?>" href="<?php echo esc_attr(add_query_arg('vacancyno', $allJobs[$i]->positionNumber, get_permalink($target_pageid))); ?>" ><?php echo esc_attr($allJobs[$i]->title); ?></a></li>
                 <?php } ?></ul>
 <?php }else {     
  					
                    echo 'No jobs are found';
                   
              }
             
  ?>
</div>
