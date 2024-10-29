<?php
function isUserSet(){
	return isset($_SESSION['userEmail']);
}

function setUserEmail($userEmail){
	$_SESSION['userEmail'] = $userEmail;
}

function getUserEmail(){
	return $_SESSION['userEmail'];
}


function setUserDetails($response){
	
	$_SESSION['userDetails'] = $response;
	
}

function getUserDetails(){
	return $_SESSION['userDetails'];
}

function setLeadSource($leadsources){
	$_SESSION['leadSource'] = $leadsources;
	
}

function getLeadSource(){
	return $_SESSION['leadSource'];
}


?>