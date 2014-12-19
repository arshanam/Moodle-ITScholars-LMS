<?php
require_once('db.php');
require_once ('webserviceconfig.php');

//***********************************************************
//						Policy Management
//***********************************************************

function pol_getPolicies($timeZoneId){
    try {
    	$param = array("timeZoneId"=>$timeZoneId);
        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
        $result = $client->getPolicies($param);
        
        //print_r($param);
        //print_r($result);

    	$policiesResp = $result->policy;

        $policies = array();

        if (is_array($policiesResp)) {
            $policies = array_merge($policiesResp, $policies);
        } else if ($policiesResp!= null) {
            array_push($policies, $policiesResp);
        }

         $formattedPolicies= array();
         
        foreach ($policies as $policy) {
            	$p = array($policy->id,
                $policy->name ,
                $policy->policyType,
                $policy->absolute,
                $policy->active,
                $policy->assignable,
                $policy->description,
                $policy->startDate,
                $policy->daysInPeriod,
                $policy->numberOfPeriods,
                $policy->maximum,
                $policy->minimum,
                $policy->quotaInPeriod,
                $policy->daysToRelStart);
            array_push($formattedPolicies, $p);
        }
        
        return $formattedPolicies;
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
}

function pol_getAssignablePolicies($timeZoneId){
    try {
    	$param = array("timeZoneId"=>$timeZoneId);
        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
        $result = $client->getAssignablePolicies($param);

        $policiesResp = $result->policy;

        $policies = array();

        if (is_array($policiesResp)) {
            $policies = array_merge($policiesResp, $policies);
        } else if ($policiesResp != null) {
            array_push($policies, $policiesResp);
        }

        $formattedPolicies= array();
         
        foreach ($policies as $policy) {
            	$p = array(
            	"id" => $policy->id,
                "name" => $policy->name ,
                "type" => $policy->policyType);
            array_push($formattedPolicies, $p);
        }
        return $formattedPolicies;
        
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
}

function pol_addPolicy($policy, $timeZoneId){
	try{
		$client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS)); 
		$request = array("policy"=>$policy, "timeZoneId"=>$timeZoneId); 
		$response = $client->addPolicy($request);
		return array("success"=>true, "id"=>$response);
	} catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
}

function pol_modifyPolicy($policy, $timeZoneId){
	try{
		$client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS)); 
		$request = array("policy"=>$policy, "timeZoneId"=>$timeZoneId); 
		$client->modifyPolicy($request);
		
		return array("success"=>true);
	} catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
}

function pol_getPolicy($id, $timeZoneId){
	try{
		$param = array("policyId"=>$id,"timeZoneId"=>$timeZoneId);
		$client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
        $policy = $client->getPolicyById($param);

        return array("policy"=>$policy);
    } catch (Exception $e) {
        return $e->getMessage();
    } catch (SoapFault $soapfault) {
        return $soapfault->getMessage();
    }    
}

function pol_deletePolicy($id){
	try{
	    $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
        $policy = $client->deletePolicy($id);

        return array("success"=>true);
    } catch (Exception $e) {
      return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
      return array("success"=>false, "message"=>$soapfault->getMessage());
    }  
}


//***********************************************************
//						Credit Management
//***********************************************************

function ct_getResources(){
    try {
        $params = array('userRole' => 'STUDENT');
        $client = new SoapClient(WSDL_VL, array('location' => LOCATION_VL));
        $result = $client->getResourceTypes($params);
        $resources = $result->resourceType;
        return $resources;
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
    
}

function ct_getCourses(){
	return db_getAllCourses();
}

function ct_getCreditTypes(){
	try {
        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));

        $result = $client->getCreditTypes();
        $credittype = $result->creditType;
        $policy = $result->policy;

        $credittypes = array();
        $policies = array();

        if (is_array($credittype)) {
            $credittypes = array_merge($credittypes, $credittype);
        } else if ($credittype != null) {
            array_push($credittypes, $credittype);
        }
        
        if (is_array($policy)) {
            $policies = array_merge($policies, $policy);
        } else if ($policy != null) {
            array_push($policies, $policy);
        }

        $formattedCreditTypes= array();

        foreach ($credittypes as $credittype) {
        	$course = db_getCourseById($credittype->courseId);

        	foreach ($policies as $policy)
        	{
        		if($policy->id == $credittype->policyId)
        		{
        			$policyName = $policy->name." : ".$policy->policyType;
        			break;
        		}
        	}

          	$c = array($credittype->id,
          				$credittype->name,			
            			$credittype->resource ,
            			$course->shortname,
            			$policyName,
          				$credittype->active,
          				$credittype->assignable);
                    
            array_push($formattedCreditTypes, $c);
        }
        return $formattedCreditTypes;
	} catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
    
}

function ct_getCreditType($id){
    try {
        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
        $credittype = $client->getCreditTypeById($id);

        return array("creditType"=>$credittype);
        
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
    
}

function ct_addCreditType($name, $resource, $policyId, $courseId, $activeval, $assignval){
    try {
    	if($policyId=="")
        	$credittype = array('id' => null, 'name' => $name, 'resource' => $resource, 'policyId'=>null,'courseId' => $courseId, 'active' => $activeval, 'assignable' => $assignval);
        else
        	$credittype = array('id' => null, 'name' => $name, 'resource' => $resource, 'policyId'=>$policyId,'courseId' => $courseId, 'active' => $activeval, 'assignable' => $assignval);

        $client = new SoapClient(WSDL_QS, array('cache_wsdl' => 0,'location' => LOCATION_QS));
        $response = $client->addCreditType($credittype);
        
        return array("id"=>$response);
        
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
    
}

function ct_modifyCreditType($id, $name, $resource, $policyId, $courseId, $activeval, $assignval){
    try {
    	if($policyId=="")
        	$credittype = array('id' =>$id, 'name' => $name, 'resource' => $resource, 'policyId'=>null,'courseId' => $courseId, 'active' => $activeval, 'assignable' => $assignval);
        else
        	$credittype = array('id' =>$id, 'name' => $name, 'resource' => $resource, 'policyId'=>$policyId,'courseId' => $courseId, 'active' => $activeval, 'assignable' => $assignval);

        $client = new SoapClient(WSDL_QS, array('cache_wsdl' => 0,'location' => LOCATION_QS));
        $client->modifyCreditType($credittype);
        return array("success"=>true);
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
}

function ct_deleteCreditType($id){
    try {
        $client = new SoapClient(WSDL_QS, array('cache_wsdl' => 0,'location' => LOCATION_QS));
        $client->deleteCreditType($id);
        return array("success"=>true);
    } catch (Exception $e) {
    	return array("success"=>false, "message"=>$e->getMessage());
    } catch (SoapFault $soapfault) {
    	return array("success"=>false, "message"=>$soapfault->getMessage());
    }
}

//***********************************************************
//						Report Management
//***********************************************************

	/*
   	 * Make a webservice call to get the current period's quota 
   	 * summary for all credit types or only for one credit type
   	 * if the @param courseId is set
	 * @param  int $courseId optional
	 * @return array $quotaSummary
   	 */
	function rm_getCurrentPeriodQuotaSummary($courseId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = null;
	        if($courseId != null)
	        	$result = $client->getCurrentPeriodQuotaSummary(array('courseId' => $courseId));
	        else
	        	$result = $client->getCurrentPeriodQuotaSummary();
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
    /*
   	 * Make a webservice call to get current period's quota 
   	 * summary for @param creditTypeId
     * @param int $creditTypeId
	 * @return array $quotaSummary
   	 */
	function rm_getCurrentPeriodQuotaSummaryByCreditType($creditTypeId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = $client->getCurrentPeriodQuotaSummaryByCreditType(array('creditTypeId' => $creditTypeId));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
	/*
     * Make a webservice call to get current period's quota 
     * summary for a user @param userId
	 * @param int $userId
	 * @return array $quotaSummary
     */
	function rm_getCurrentReportByUser($userId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = $client->getCurrentPeriodQuotaSummaryByUser(array('userId' => $userId));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
	/*
     * Make a webservice call to get current period's quota 
     * summary for all the credit types in a course for a user
     * @param int $userId
	 * @param int $courseId
	 * @return array $quotaSummary
     */
	function rm_getCurrentReportByUserAndCourse($userId, $courseId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = $client->getCurrentPeriodQuotaSummaryByUserAndCourse(array('userId' => $userId, 'courseId' => $courseId));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
    /*
   	 * Make a webservice call to get the historic quota summary
	 * for all credit types or only for one credit type
   	 * if the @param courseId is set
	 * @param int $courseId optional
	 * @return array $quotaSummary
   	 */
	function rm_getHistoricQuotaSummary($courseId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = null;
	        if($courseId != null)
	        	$result = $client->getHistoricQuotaSummary(array('courseId' => $courseId));
	        else
	        	$result = $client->getHistoricQuotaSummary();
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
    /*
   	 * Make a webservice call to get the historic quota summary
   	 * of a credit type per users
   	 * @param int $creditTypeId
   	 * @param int $userId optional when getHistoricQuotaSummaryPerUsers
   	 * has been called previously
   	 * @return array $quotaSummary
   	 */
	function rm_getHistoricQuotaSummaryPerPeriods($creditTypeId, $userId, $timeZoneId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = $client->getHistoricQuotaSummaryPerPeriods(array('creditTypeId' => $creditTypeId, 'userId' => $userId, 'timeZoneId'=>$timeZoneId));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
    /*
   	 * Make a webservice call to get the historic quota summary
   	 * of a credit type per users
   	 * @param int $creditTypeId
   	 * @param int $periodNumber optional when getHistoricQuotaSummaryPerPeriods
   	 * has been called previously
   	 * @return array $quotaSummary
	 */
	function rm_getHistoricQuotaSummaryPerUsers($creditTypeId, $periodNumber){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = $client->getHistoricQuotaSummaryPerUsers(array('creditTypeId' => $creditTypeId, 'periodNumber' => $periodNumber));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
	
	/*
     * Make a webservice call to get the historic quota summary
     * for a user per credit types
     * @param int $userId 
     * @param int $courseId optional to filter the resulting credit types
     * @return array $quotaSummary
	 */
	function rm_getHistoricQuotaSummaryByUser($userId, $courseId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = null;
	        if($courseId != null)
	        	$result = $client->getHistoricQuotaSummaryByUser(array('userId' => $userId, 'courseId' => $courseId));
	       	else 
	       		$result = $client->getHistoricQuotaSummaryByUser(array('userId' => $userId));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
	
	/*
     * Make a webservice call to get the historic quota summary
     * of a credit type for a user per period
     * @param int $userId
     * @param int $creditTypeId
     * @return array $quotaSummary
	 */
	function rm_getHistoricQuotaSummaryByUserAndCreditType($userId, $creditTypeId, $timeZoneId){
		try {
	        $client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
	        $result = $client->getHistoricQuotaSummaryByUserAndCreditType(array('userId' => $userId, 'creditTypeId' => $creditTypeId, 'timeZoneId'=>$timeZoneId));
	        return $result->quotaSummary;
		        
	    } catch (Exception $e) {
	    	return array("success"=>false, "message"=>$e->getMessage());
	    } catch (SoapFault $soapfault) {
	    	return array("success"=>false, "message"=>$soapfault->getMessage());
	    }
	}
?>