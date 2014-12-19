<?php

//***********************************************************
//						Report Management
//***********************************************************

require_once ('quotaSystem.php');

ini_set("soap.wsdl_cache_enabled", "0");
header("Content-type: text/x-json");
//***********************************************************
//					Listener
//***********************************************************
if (isset($_POST['action'])) 
    $action = $_POST['action'];
else 
    $action = "";
   
    
if ($action == "getCurrentReport")
{
	$quotaSummary = null;
	if(isset($_POST['courseId'])){
		$quotaSummary  = rm_getCurrentPeriodQuotaSummary($_POST['courseId']);
	}else{
		$quotaSummary  = rm_getCurrentPeriodQuotaSummary(null);
	}
	echo json_encode($quotaSummary); 
	   
}
else if ($action == "getCurrentReportByCreditType")
{
	$quotaSummary  = rm_getCurrentPeriodQuotaSummaryByCreditType($_POST['creditTypeId']);
	echo json_encode($quotaSummary);  		
	
}
else if ($action == "getCurrentReportByUser")
{
   	if(isset($_POST['userId'])){
 		$quotaSummary  = rm_getCurrentReportByUser($_POST['userId']);
		echo json_encode($quotaSummary);
  	}
  	
}
else if ($action == "getCurrentReportByUserAndCourse")
{
   	if(isset($_POST['userId']) && isset($_POST['courseId'])){
  		$quotaSummary  = rm_getCurrentReportByUserAndCourse($_POST['userId'],$_POST['courseId']);
		echo json_encode($quotaSummary);  		
   	}
   	
}
else if ($action == "getHistoricReport")
{	
   	$quotaSummary = null;
   	if(isset($_POST['courseId'])){
		$quotaSummary  = rm_getHistoricQuotaSummary($_POST['courseId']);
   	}else{
		$quotaSummary  = rm_getHistoricQuotaSummary(null);
	}
	echo json_encode($quotaSummary);
			
}
else if ($action == "getHistoricReportPerPeriods")
{
	session_start(); 
	$userId = $_SESSION["userid"];  
	$timeZoneId = db_getUserTimeZone($userId)->data; 	
	$quotaSummary  = rm_getHistoricQuotaSummaryPerPeriods($_POST['creditTypeId'], $_POST['userId'], $timeZoneId);
	echo json_encode($quotaSummary);
				
}
else if ($action == "getHistoricReportPerUsers")
{ 	
	$quotaSummary  = rm_getHistoricQuotaSummaryPerUsers($_POST['creditTypeId'],$_POST['periodNumber']);
	echo json_encode($quotaSummary);
	
}
else if ($action == "getHistoricReportByUser")
{ 	
  	$quotaSummary = null;
   	if(isset($_POST['userId'])){
    	if(isset($_POST['courseId'])){
			$quotaSummary  = rm_getHistoricQuotaSummaryByUser($_POST['userId'], $_POST['courseId']);
    	}else{
    		$quotaSummary  = rm_getHistoricQuotaSummaryByUser($_POST['userId'], null);
    	}
		echo json_encode($quotaSummary);
   	}
   	
}
else if ($action == "getHistoricReportByUserAndCreditType")
{ 

	session_start(); 
	$userId = $_SESSION["userid"];  
	$timeZoneId = db_getUserTimeZone($userId)->data; 	
   	$quotaSummary = null;
   	if(isset($_POST['userId']) && isset($_POST['creditTypeId'])){
		$quotaSummary  = rm_getHistoricQuotaSummaryByUserAndCreditType($_POST['userId'],$_POST['creditTypeId'],$timeZoneId);
   	}else{
   		$quotaSummary  = rm_getHistoricQuotaSummaryByUserAndCreditType($_POST['userId'],$_POST['creditTypeId'],$timeZoneId);
   	}
	echo json_encode($quotaSummary);
	
}
else if ($action == "getAllCourses") {
	$courses = db_getAllCourses();
	$response = array("courses" => $courses);
	echo json_encode($response);
	
} 
else if ($action == "getCoursesByUser") {
   	if(isset($_POST['userId'])){
   		$courses = db_getCoursesByUser($_POST['userId']);
   		$response = array("courses" => $courses);
		echo json_encode($response);
   	}
   	
} 
else if ($action == "getAllUsers") {
	$users = db_getAllUsers();
	$response = array("users" => $users);
	echo json_encode($response);
	
}

?>