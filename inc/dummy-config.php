<?php
/**Default Newsletter configuration Files*/

function dummy_testimonials()
{
	$default_testimonials[] =new stdClass();	
	$default_testimonials[0]->account->accountName = 'Your Customer Name Here';
	$default_testimonials[0]->testimonial = 'This is where a customer can say something great about their experience with your company!';
	$default_testimonials[0]->publishedAt = date('d,M,Y');
	$default_testimonials[0]->readmoretext = 'Readmore...';
	$default_testimonials[0]->readmorelink = get_permalink(get_option('awp_testimonials_pageid'));
		
	return $default_testimonials;
}