<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/user/profile/lib.php');

require_once($CFG->libdir."/crypt.php");

require_once($CFG->libdir .'/ddllib.php');

require_once('../php/parser.php');

ini_set("soap.wsdl_cache_enabled", "0");

$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
$location ="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";

$id 				= ''; // $_GET['id'];
$requestingUser 	= $_GET['username']; 
$username 			= $_GET['username'];
$encryptedPassword = $_GET["encrypted_password"]; // password for embedded version
$start 				= ''; // $_GET['start']; 
$end 				= ''; // $_GET['end']; 
$resourceType 		= $_GET['resourceType']; 
$course 			= $_GET['course']; 
$affiliationId 		= ''; // $_GET['affiliationId'];
$availabilityStatus = ''; // $_GET['availabilityStatus'];		
$requestType 		= 'User'; // $_GET['requestType']; 

echo "\$username: $username<br>";
echo "\$encryptedPassword: $encryptedPassword<br>";
echo "\$start: $start<br>";
echo "\$end: $end<br>";
echo "\$resourceType: $resourceType<br>";
echo "\$course: $course<br>";

$password = Crypt::decrypt($encryptedPassword);;
echo '<br>password: ' . $password;

try {
	$appointment = array(
		'id' => $id,
		'userName' => $username,
		'start' => $start,
		'end' => $end,
		'resourceType' => $resourceType,
		'course' => $course,
		'affiliationId' => $affiliationId,
		'availabilityStatus' => $availabilityStatus,
		'action' => NULL
	);
								
	$encryptedCredential = array(
		'userName' => $username,
		'encryptedPassword' => $encryptedPassword);

	$params = array(
		'requestingUser' => $requestingUser,
		'appointment' => $appointment,
		'encryptedCredential' => $encryptedCredential
	);
							
	$client = new SoapClient($wsdl, array('location' => $location));
			
	$result = $client->scheduleUserAppointmentsWithEncryptedPassword($params);
		
	echo json_encode($result->appointment);
						
} catch (SoapFault $soapfault) {
	//echo $soapfault->getMessage();
	echo $soapfault->getTraceAsString();
} catch (Exception $e) {
	echo $e->getMessage();
}

?>


