<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/user/profile/lib.php');

require_once($CFG->libdir .'/ddllib.php');

require_once('parser.php');
require_once 'xmlserializer/serialize.php';
require_once 'xmlserializer/classes.php';

ini_set("soap.wsdl_cache_enabled", "0");


$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";

		
if (isset($_POST['action'])){
	$action = $_POST['action'];   
}else{
	$action = "";
}


if (isset($_POST['arr'])){
	$arr = $_POST['arr'];   
}else{
	$arr = "";
} 

echo $arr;


//************************************************************************************************

function availableQuota($username, $course){

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";

	$result = false;
	
	//Get the user role
	$user = get_record('user', 'username', $username);
	$userRole = get_record('role_assignments', 'userid', $user->id, 'contextid', 1);
	//$role = get_record('role', 'id', $userRole->roleid);
	
	if ($userRole->roleid != 1) {

		try {
						
			$params = array('username' => $username,
					'course' => $course ); 			
	
			$client=new SoapClient($wsdl,array('location'=>$location));
			
			$result = $client->doesUserHaveAvailableQuota($params);
			
			//echo $result;
	
		} catch (Exception $e) {
		
			//echo $e->getMessage()."(Error: 1)";
			return false;
		
		}catch (SoapFault $soapfault) {
		
			//echo $soapfault->getMessage()."(Error: 2)";
			return false;
		}
	
		return $result->quotaIsAvailable;
		
	}else{
		return true;
	}
}

function addQSUser($user){					//**** addUser *****

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	try {
		
						
		$usernew = array('id' => $user->id,
						'username' => $user->username,
						'email' => $user->email ); 
		
		//$params = array('user' => $usernew);
					

		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$client->addUser($usernew);

		$result = true;
   
    } catch (Exception $e) {
    	$result = false;
    } catch (SoapFault $soapfault) {
    	$result = false;
    }

	return $result;
}

function modifyQSUser($user){					//**** modifyUser *****

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	try {
		$usernew = array('id' => $user->id,
						'username' => $user->username,
						'email' => $user->email ); 
		
		//$params = array('user' => $usernew);

		$client=new SoapClient($wsdl,array('location'=>$location));

		$result = $client->modifyUser($usernew);

		$result = true;
    } catch (Exception $e) {
		//echo "Exception: ".$e."<br/>";
    	$result = false;
    } catch (SoapFault $soapfault) {
		//echo "SoapFault: ".$soapfault."<br/>";
    	$result = false;
    }
	
	return $result;

}

function deleteQSUser($user){						//**** deleteUser *****
	
	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	try {
					
		//$params = array('id' => $user->id);
						
		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$result = $client->deleteUser($user->id);

		$result = true;
   
    } catch (Exception $e) {
    	$result = false;
    } catch (SoapFault $soapfault) {
    	$result = false;
    }

	return $result;
}

function addQSCourse($course){						//**** addCourse *****
	
	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	try {
	
		$coursenew = array('id' => $course->id,
						'shortname' => $course->shortname,
						'fullname' => $course->fullname );
						
		//$params = array('course' => $coursenew);

		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$result = $client->addCourse($coursenew);

		$result = true;
   
    } catch (Exception $e) {
    	$result = false;
    } catch (SoapFault $soapfault) {
    	$result = false;
    }

	return $result;
}

function modifyQSCourse($course){						//**** modifyCourse *****

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	try {
						
		$coursenew = array('id' => $course->id,
						'shortname' => $course->shortname,
						'fullname' => $course->fullname );
						
		//$params = array('course' => $coursenew);
						
		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$result = $client->modifyCourse($coursenew);

		$result = true;
   
    } catch (Exception $e) {
    	$result = false;
    } catch (SoapFault $soapfault) {
    	$result = false;
    }

	return $result;
}

function deleteQSCourse($course){					//**** deleteCourse *****

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	try {
		
		//$params = array('id' => $course->id);

		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$result = $client->deleteCourse($course->id);

		$result = true;
   
    } catch (Exception $e) {
    	$result = false;
    } catch (SoapFault $soapfault) {
    	$result = false;
    }

	return $result;

}

function enrollQSUser($user, $course){						//**** enrollUser *****

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	$success = false;
	
	$context = get_record('context','contextlevel',50,'instanceid',$course->id);
	
	$role = get_record('role_assignments','userid',$user->id,'contextid',$context->id);

	try {
						
		$enrollment = array('enrollmentId' => $role->id,	// int
						'courseId' => $course->id,
						'userId' => $user->id );
						
		//$params = array('requestingUser' => $requestingUser,
		//				'enrollment' => $enrollment);

		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$result = $client->enrollUser($enrollment);

		//echo json_encode($result);
		
		$success = $result->success;

	} catch (Exception $e) {
	
		//echo $e->getMessage();
	
	}catch (SoapFault $soapfault) {
	
		//echo $soapfault->getMessage();
	}
	
	return $success;
}

function unenrollQSUser($user, $course){				//**** unenrollUser *****

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem";
	
	$result = false;
	
	$context = get_record('context','contextlevel',50,'instanceid',$course->id);
	
	$role = get_record('role_assignments','userid',$user->id,'contextid',$context->id);
	
	try {
	
		$enrollment = array('enrollmentId' => $role->id,	// int
						'courseId' => $course->id,
						'userId' => $user->id );
						
		//$params = array('requestingUser' => $requestingUser,
		//				'enrollment' => $enrollment);

		$client=new SoapClient($wsdl,array('location'=>$location));
		
		$result = $client->unenrollUser($enrollment);

		$result = true;
   
    } catch (Exception $e) {
    	$result = false;
    } catch (SoapFault $soapfault) {
    	$result = false;
    }

	return $result;
}


//************************************************************************************************


?>