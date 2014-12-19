<?php

/**
 *
 * @author
 * @version 
 * @package mod/deva
 */
/// (Replace deva with the name of your module and remove this line)

    require_once("../../../config.php");
	require_once($CFG->dirroot.'/group/lib.php');

	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/quotasystem.php');

	require_once($CFG->libdir .'/ddllib.php');

// 	require_once('../php/parser.php');

	ini_set("soap.wsdl_cache_enabled", "0");

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
	$location ="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";

	$username = $_GET["username"]; // username for embedded verions
	$courseid = $_GET["courseid"]; // courseid for embedded version

	// echo " username: $username and courseid: $courseid";

	if (!$username || !$courseid) {
    	error('You must specify a username and courseid.');
		exit();
	}
	echo "<br> \$username is $username and \$courseid is $courseid";
	
	if (!$course = get_record('course', 'id', $courseid)) {
    	error('Course is misconfigured');
       	exit();
    }
	echo "<br> Course is $course->fullname";
	
	// echo "<br> \$course->fullname is $course->fullname";
	// 	print_r($course);

    if (!$context = get_context_instance(CONTEXT_COURSE, $course->id) ) {
		echo 'Count not get the context instance';
        error("That's an invalid course id");
		exit();
    }
	echo "<br> Context id is $context->id";

    if(!$user = get_record('user','username',$username)) {
		echo 'The username ' . $username . ' is invalid';
        error("That's an invalid username");
		exit();
    }
	echo "<br> User is $user->firstname $user->lastname";

	try {
		$params = array(
			'userName' => $username,
			'courseName' => $course->fullname
		);
								
		$client = new SoapClient($wsdl, array('location' => $location));
				
		$result = $client->suspendUserVLab($params);
			
		echo json_encode($result);
							
	} catch (SoapFault $soapfault) {
		//echo $soapfault->getMessage();
		echo $soapfault->getTraceAsString();
	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>
