
<?php

//***********************************************************
//					Quota  Management
//***********************************************************

require_once('db.php');
require_once ('webserviceconfig.php');

ini_set("soap.wsdl_cache_enabled", "0");

//***********************************************************
//					Listener
//***********************************************************  
  
  
  $action = $_POST['action'];
    
    if ($action == "assignQuotaToUser")
    {
   		$userId = $_POST['userId']; 
   		$creditTypeId = $_POST['creditTypeId']; 
   		$quantity = $_POST['quantity']; 
   		   		
    	$success = assignQuotaToUser($userId, $creditTypeId, $quantity);
    	echo json_encode(array("success"=>$success));
    }
    else if ($action == "assignQuotaToCourse")
    {
   		$courseId = $_POST['courseId']; 
   		$creditTypeId = $_POST['creditTypeId']; 
   		$quantity = $_POST['quantity']; 
   		   		
    	$success = assignQuotaToCourse($courseId, $creditTypeId, $quantity);
    	echo json_encode(array("success"=>$success));
    }
	else if ($action == "removeQuotaUser")
    {
   		$userId = $_POST['userId']; 
   		$creditTypeId = $_POST['creditTypeId']; 
   		$quota = $_POST['quota']; 
   		   		
    	$success = removeQuotaFromUser($userId, $creditTypeId, $quota);
    	echo json_encode(array("success"=>$success));
    }
	else if ($action == "removeQuotaCourse")
    {
   		$courseId = $_POST['courseId']; 
   		$creditTypeId = $_POST['creditTypeId']; 
   		$quota = $_POST['quota']; 
   		   		
    	$success = removeQuotaFromCourse($courseId, $creditTypeId, $quota);
    	echo json_encode(array("success"=>$success));
    }
        
//***********************************************************
//						Methods
//***********************************************************

     	/* 
    	 * Make a webservice call to assign quota 
    	 * to a student (user)
    	 * @param	int $userId
    	 * @param	int $creditTypeId
    	 * @param	float $quantity
    	 * $return  boolean $success
    	 */    
    function assignQuotaToUser($userId, $creditTypeId, $quantity){}

        	
    	/* 
    	 * Make a webservice call to assign quota 
    	 * to all students (users) in a course
    	 * @param	int $courseId
    	 * @param	int $creditTypeId
    	 * @param	float $quantity
    	 * $return  boolean $success
    	 */ 
    function assignQuotaToCourse($courseId, $creditTypeId, $quantity){}
    
    
     	/* 
    	 * Make a webservice call to remove quota 
    	 * to a student (user)
    	 * @param	int $userId
    	 * @param	int $creditTypeId
    	 * @param	float $quota
    	 * $return  boolean $success
    	 */       
    
    function removeQuotaFromUser($userId, $creditTypeId, $quota){}
    	
    	
    	/* 
    	 * Make a webservice call to remove quota 
    	 * to all students (users) in a course
    	 * @param	int $courseId
    	 * @param	int $creditTypeId
    	 * @param	float $quota
    	 * $return  boolean $success
    	 */    
    function removeQuotaFromCourse($courseId, $creditTypeId, $quota){}