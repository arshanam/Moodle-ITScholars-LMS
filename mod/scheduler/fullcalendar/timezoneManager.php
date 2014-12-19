<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/user/profile/lib.php');

require_once($CFG->libdir .'/ddllib.php');

require_once('parser.php');
require_once 'xmlserializer/serialize.php';
require_once 'xmlserializer/classes.php';

ini_set("soap.wsdl_cache_enabled", "0");
header("Content-type: text/x-json");

define('WSDL_VL', 'http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl');
define('LOCATION_VL','http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs');

define('WSDL_QS', 'http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl');
define('LOCATION_QS','http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem');
/*
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/user/profile/lib.php');

require_once($CFG->libdir .'/ddllib.php');

require_once('db.php');
require_once ('webserviceconfig.php');

ini_set("soap.wsdl_cache_enabled", "0");
*/

if (isset($_GET['action'])) 
    $action = $_GET['action'];
else 
    $action = "";


$username = $_GET["username"];

    
//Handle the available actions
if($action == "getTimeZones"){
	
	$client = new SoapClient(WSDL_VL, array('location' => LOCATION_VL));
	$result = $client->getAvailableTimeZoneIds();
	echo json_encode($result);
	
}else if($action == "getUserTimeZone"){
	
	$user = get_record('user','username',$username);

	$zone = get_record('user_info_data','userid',$user->id,'fieldid',4);
	
	echo json_encode($zone->data);
	
}else if($action == "setUserTimeZone"){
	
	if (isset($_GET['timezone'])) 
	    $timeZoneId = $_GET['timezone'];
	else 
	    $timeZoneId = "";
	
	$success = false;
	
	$client = new SoapClient(WSDL_VL, array('location' => LOCATION_VL));
	$params = array("requestingUser"=>$username, "userName"=>$username, "timeZoneId"=>$timeZoneId);
	$result = $client->setUserDefaultTimeZoneId($params);

	$user = get_record('user','username',$username);

	if($result->success==1){
		setUserTimeZone($user->id, $timeZoneId);
		$success = true;
	}
	//$response = array("role"=>$userRole, "userid"=>$user->id);
	echo json_encode($success);
}


function setUserTimeZone($userId, $timeZoneId){
   	try {
   		
   		$sql = "UPDATE mdl_user_info_data SET data ='".$timeZoneId."' WHERE userid = ".$userId." and fieldid = 4";
	    execute_sql($sql,false);
	    
    } catch (Exception $e) {
        echo $e->getMessage();
    }	
}
?>