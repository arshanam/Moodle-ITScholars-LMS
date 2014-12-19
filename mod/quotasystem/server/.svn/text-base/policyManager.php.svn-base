<?php

//***********************************************************
//						Policy Management
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

    
if ($action == "getPolicies") 
{
	session_start(); 
	$userId = $_SESSION["userid"];  
	$timeZoneId = db_getUserTimeZone($userId)->data;
	$response = pol_getPolicies($timeZoneId);
	echo json_encode($response);
		
}
else if ($action == "getAssignablePolicies") 
{
	session_start(); 
	$userId = $_SESSION["userid"];  
	$timeZoneId = db_getUserTimeZone($userId)->data; 
	$response = pol_getAssignablePolicies($timeZoneId);
	echo json_encode($response);
	
}
else if($action == "addPolicy")
{
	$name = isset($_POST['name']) ? $_POST['name'] : "";
	$description = isset($_POST['description']) ? $_POST['description'] : ""; 
	$type = isset($_POST['typePolicy']) ? $_POST['typePolicy'] : "";
	$startDate = null;
	$noDaysInPeriod = 0; 
	$noPeriods = 0;
	$absoluteVal = isset($_POST['absolute']) ? $_POST['absolute'] : "" ;
    $activeVal = isset($_POST['active']) ? $_POST['active'] : "" ;
    $assignableVal = isset($_POST['assignable']) ? $_POST['assignable'] : "" ;
    $maximum = 0;
	$minimum = 0;
	$quotaInPeriod = isset($_POST['quotaInPeriod']) ? $_POST['quotaInPeriod'] : 0;
	
	$absolute = $absoluteVal=="true" ? TRUE : FALSE ;
    $active = $activeVal=="true" ? TRUE : FALSE ;
    $assignable = $assignableVal=="true" ? TRUE : FALSE ;

   	if($type == "NOEXPIRATION"){

  		if($absolute)
	    {
		   	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
		   	$daysToRelStart = null;
		}
		else
		{
		   	$startDate = null;
		   	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;		     	  
		}
		$policy = array('name' => $name,  
				        'description' => $description, 
				        'policyType' => $type,
        				'absolute' => $absolute,
				        'startDate' => $startDate,
	        			'daysToRelStart' => $daysToRelStart, 
        				'active' => $active,
                		'assignable' => $assignable,
				        'quotaInPeriod' => $quotaInPeriod);
		        	        
    }
  	else if($type == "FIXED"){
	    
  		$noDaysInPeriod = isset($_POST['noDays']) ? $_POST['noDays'] : 0;
  		
  		if($absolute)
		{
		   	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
			$daysToRelStart = null;
		}
		else
		{
		   	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;
		}
	    $policy = array('name' => $name,  
				        'description' => $description, 
				        'policyType' => $type,
        				'absolute' => $absolute,
				        'startDate' => $startDate, 
	        			'daysToRelStart' => $daysToRelStart, 
				        'daysInPeriod' => $noDaysInPeriod, 
				        'numberOfPeriods' => 1, 
        				'active' => $active,
                		'assignable' => $assignable,
				        'quotaInPeriod' => $quotaInPeriod);
		        	        
    }else if($type == "GRADUAL"){
        $maxQuota = isset($_POST['maxQuota']) ? $_POST['maxQuota'] : 0;
	    $noPeriods = isset($_POST['noPeriods']) ? $_POST['noPeriods'] : 0;
		$noDaysInPeriod = isset($_POST['noDaysInPeriod']) ? $_POST['noDaysInPeriod'] : 0;		            	

		if($absolute)
		{
		  	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
		  	//$startDate = getFormattedDate("/",$startDate);
		    $daysToRelStart = null;
		}
		else
		{
			$startDate = null;
		  	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;
		}    	
	    
	    $policy = array('name' => $name, 
				        'description' => $description, 
			        	'policyType' => $type,
	       				'absolute' => $absolute,
			        	'startDate' => $startDate, 
	       				'daysToRelStart' => $daysToRelStart,
				        'daysInPeriod' => $noDaysInPeriod, 
				        'numberOfPeriods' => $noPeriods, 
	       				'active' => $active,
	               		'assignable' => $assignable,
	       				'maximum' => $maxQuota,  
				        'quotaInPeriod' => $quotaInPeriod);
	        
    }else if($type == "MINMAX"){
   	    $maxQuota = isset($_POST['maxQuota']) ? $_POST['maxQuota'] : 0;
   	    $minQuota = isset($_POST['minQuota']) ? $_POST['minQuota'] : 0;
	    $noPeriods = isset($_POST['noPeriods']) ? $_POST['noPeriods'] : 0;
		$noDaysInPeriod = isset($_POST['noDaysInPeriod']) ? $_POST['noDaysInPeriod'] : 0;
  		        
	    if($absolute)
	    {    
	    	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
	    	//$startDate = getFormattedDate("/",$startDate);
		    $daysToRelStart = null;
	    }
	    else
	    {
	    	$startDate = null;
	    	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;
	    } 
        $policy = array('name' => $name,  
				        'description' => $description, 
			        	'policyType' => $type,
	       				'absolute' => $absolute,
			        	'startDate' =>$startDate, 
	       				'daysToRelStart' => $daysToRelStart, 
				        'daysInPeriod' => $noDaysInPeriod, 
				        'numberOfPeriods' => $noPeriods, 
	       				'active' => $active,
	               		'assignable' => $assignable,
	       				'maximum' => $maxQuota,  
				        'minimum' => $minQuota,
				        'quotaInPeriod' => $quotaInPeriod);

    	}
    	session_start(); 
		$userId = $_SESSION["userid"];  
		$timeZoneId = db_getUserTimeZone($userId)->data;
		$response = pol_addPolicy($policy, $timeZoneId);
        echo json_encode($response);

}
else if($action == "modifyPolicy")
{
	$id = isset($_POST['id']) ? $_POST['id'] : 0;
	$name = isset($_POST['name']) ? $_POST['name'] : "";
	$description = isset($_POST['description']) ? $_POST['description'] : ""; 
	$type = isset($_POST['typePolicy']) ? $_POST['typePolicy'] : "";
	$startDate = null;
	$noDaysInPeriod = 0; 
	$noPeriods = 0;
	$absoluteVal = isset($_POST['absolute']) ? $_POST['absolute'] : "" ;
    $activeVal = isset($_POST['active']) ? $_POST['active'] : "" ;
    $assignableVal = isset($_POST['assignable']) ? $_POST['assignable'] : "" ;
    $maximum = 0;
	$minimum = 0;
	$quotaInPeriod = isset($_POST['quotaInPeriod']) ? $_POST['quotaInPeriod'] : 0;

	$absolute = $absoluteVal=="true" ? TRUE : FALSE ;
    $active = $activeVal=="true" ? TRUE : FALSE ;
    $assignable = $assignableVal=="true" ? TRUE : FALSE ;

   	if($type == "NOEXPIRATION"){
  		if($absolute)
	    {
		   	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
		   	//$startDate = getFormattedDate("/",$startDate);
		   	$daysToRelStart = null;
		}
		else
		{
		   	$startDate = null;
		   	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;		     	  
		}
		$policy = array('id' => $id,
						'name' => $name,  
				        'description' => $description, 
				        'policyType' => $type,
        				'absolute' => $absolute,
				        'startDate' => $startDate,
	        			'daysToRelStart' => $daysToRelStart, 
        				'active' => $active,
                		'assignable' => $assignable,
				        'quotaInPeriod' => $quotaInPeriod);
		        	        
    }
  	else if($type == "FIXED"){
  		
  	  	$noDaysInPeriod = isset($_POST['noDays']) ? $_POST['noDays'] : 0;
  		
  		if($absolute)
		{
		   	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
			$daysToRelStart = null;
		}
		else
		{
		   	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;
		}
		
	    $policy = array('id' => $id,
	    				'name' => $name,  
				        'description' => $description, 
				        'policyType' => $type,
        				'absolute' => $absolute,
				        'startDate' => $startDate, 
	        			'daysToRelStart' => $daysToRelStart, 
				        'daysInPeriod' => $noDaysInPeriod, 
				        'numberOfPeriods' => 1, 
        				'active' => $active,
                		'assignable' => $assignable,
				        'quotaInPeriod' => $quotaInPeriod);
		        	        
    }else if($type == "GRADUAL"){
        $maxQuota = isset($_POST['maxQuota']) ? $_POST['maxQuota'] : 0;
	    $noPeriods = isset($_POST['noPeriods']) ? $_POST['noPeriods'] : 0;
		$noDaysInPeriod = isset($_POST['noDaysInPeriod']) ? $_POST['noDaysInPeriod'] : 0;		            	

		if($absolute)
		{
		  	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
		  	$startDate = getFormattedDate("/",$startDate);
		    $daysToRelStart = null;
		}
		else
		{
			$startDate = null;
		  	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;
		}    	
	    
	    $policy = array('id' => $id,
	    				'name' => $name, 
				        'description' => $description, 
			        	'policyType' => $type,
	       				'absolute' => $absolute,
			        	'startDate' => $startDate, 
	       				'daysToRelStart' => $daysToRelStart,
				        'daysInPeriod' => $noDaysInPeriod, 
				        'numberOfPeriods' => $noPeriods, 
	       				'active' => $active,
	               		'assignable' => $assignable,
	       				'maximum' => $maxQuota,  
				        'quotaInPeriod' => $quotaInPeriod);
	        
    }else if($type == "MINMAX"){
   	    $maxQuota = isset($_POST['maxQuota']) ? $_POST['maxQuota'] : 0;
   	    $minQuota = isset($_POST['minQuota']) ? $_POST['minQuota'] : 0;
	    $noPeriods = isset($_POST['noPeriods']) ? $_POST['noPeriods'] : 0;
		$noDaysInPeriod = isset($_POST['noDaysInPeriod']) ? $_POST['noDaysInPeriod'] : 0;
  		        
	    if($absolute)
	    {    
	    	$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
	    	//$startDate = getFormattedDate("/",$startDate);
		    $daysToRelStart = null;
	    }
	    else
	    {
	    	$startDate = null;
	    	$daysToRelStart = isset($_POST['daysToRelStart']) ? $_POST['daysToRelStart'] : 0;
	    } 
        $policy = array('id' => $id,
        				'name' => $name,  
				        'description' => $description, 
			        	'policyType' => $type,
	       				'absolute' => $absolute,
			        	'startDate' =>$startDate, 
	       				'daysToRelStart' => $daysToRelStart, 
				        'daysInPeriod' => $noDaysInPeriod, 
				        'numberOfPeriods' => $noPeriods, 
	       				'active' => $active,
	               		'assignable' => $assignable,
	       				'maximum' => $maxQuota,  
				        'minimum' => $minQuota,
				        'quotaInPeriod' => $quotaInPeriod);

    	}
    	session_start(); 
		$userId = $_SESSION["userid"];  
		$timeZoneId = db_getUserTimeZone($userId)->data;
		$response = pol_modifyPolicy($policy, $timeZoneId);
        echo json_encode($response);
	
}
else if ($action == "getPolicy") 
{
	session_start(); 
	$userId = $_SESSION["userid"];  
	$timeZoneId = db_getUserTimeZone($userId)->data;
	$id = isset($_POST['id']) ? $_POST['id'] : "";
	$response = pol_getPolicy($id,$timeZoneId);
	echo json_encode($response);
}
else if ($action == "deletePolicy") 
{
	$id = isset($_POST['id']) ? $_POST['id'] : "";
	$response = pol_deletePolicy($id);
	echo json_encode($response);
}

//***********************************************************
//						Auxiliary
//***********************************************************
function dateDiff($dformat, $endDate, $beginDate)
{
    $date_parts1=explode($dformat, $beginDate);
    $date_parts2=explode($dformat, $endDate);
    $start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
    $end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
    return $end_date - $start_date;
}

function getFormattedDate($dformat,$date){
	$date_parts=explode($dformat, $date);
	return date(DATE_ATOM, mktime(0, 0, 0,$date_parts[0], $date_parts[1], $date_parts[2]));
	
}
?>