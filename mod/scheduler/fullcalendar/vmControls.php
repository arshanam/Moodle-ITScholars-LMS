<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/user/profile/lib.php');

require_once($CFG->libdir .'/ddllib.php');

require_once('parser.php');
require_once 'xmlserializer/serialize.php';
require_once 'xmlserializer/classes.php';

ini_set("soap.wsdl_cache_enabled", "0");


$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";

		
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

//echo $arr;


//*****************************************************************************************

if($action == 'getState'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
	
		echo instanceCmdRequest($instanceId, $vmName, 'getstate','');
	}
	
}else if ($action=='powerOff'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
		
		instanceCmdRequest($instanceId, $vmName, 'stop', 'hard');
	}
	
}else if ($action=='shutdown'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
		
		instanceCmdRequest($instanceId, $vmName, 'stop', 'soft');
	}
	
}else if ($action=='suspend'){
	header('Content-Type: text/x-json');	
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
	
		instanceCmdRequest($instanceId, $vmName, 'suspend', 'soft');
	}

}else if ($action=='powerOn'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
		
		instanceCmdRequest($instanceId, $vmName, 'start', 'soft');	
	}
	
}else if ($action=='restart'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
		
		instanceCmdRequest($instanceId, $vmName, 'reset', 'hard');	
	}
	
}else if ($action=='refresh'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId']) && isset($_POST['vmName'])){
		$instanceId = $_POST['instanceId']; 
		$vmName = $_POST['vmName'];
	
		refreshInstanceRequest($instanceId, $vmName);
	}
		
}
else if ($action=='getAppointmentTimer'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['instanceId'])){
		$instanceId = $_POST['instanceId']; 
	
		getAppointmentTimer($instanceId);	
	}
}

//*****************************************************************************************

function instanceCmdRequest($devaInsId, $vmName, $cmd1, $cmd2){

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";
	
	//$devaInsId = 'c9bfaa50-2036-4b93-a701-848a6671864f';
	//$vmName = 'xp-4';
	
	//$devaInsId = 'c9bfaa50-2036-4b93-a701-848a6671864f';
	//$vmName = 'Laptop 1 (laptop1)';
	
	try {
					
		$params = array('devaInsId' => $devaInsId,
						'vmName' => $vmName,
						'cmdParam1' => $cmd1,
						'cmdParam2' => $cmd2 ); 			

		$client=new SoapClient($wsdl,array('location'=>$location));

		$result = $client->RunVMCmd($params);
		

	} catch (Exception $e) {
	
		echo $e->getMessage();
	
	}catch (SoapFault $soapfault) {
	
		echo $soapfault->getMessage();
	}	
	return $result->returnValue;
}



function refreshInstanceRequest($devaInsId, $vmName){

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";
	
	//$devaInsId = 'c9bfaa50-2036-4b93-a701-848a6671864f';
	//$vmName = 'Laptop 1 (laptop1)';
	
	try {
					
		$params = array('devaInsId' => $devaInsId,
						'vmName' => $vmName); 			

		$client=new SoapClient($wsdl,array('location'=>$location));

		$result = $client->refreshVM($params);
		

	} catch (Exception $e) {
	
		echo $e->getMessage();
	
	}catch (SoapFault $soapfault) {
	
		echo $soapfault->getMessage();
	}	
	return $result;
}


function getAppointmentTimer($devaInsId){

	$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
	$location="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";
	
	//$devaInsId = 'c9bfaa50-2036-4b93-a701-848a6671864f';
	
	try {
					
		$params = array('devaInsId' => $devaInsId); 			

		$client=new SoapClient($wsdl,array('location'=>$location));

		$result = $client->getEndDate4CurrentDevaIns($params);
		

	} catch (Exception $e) {
	
		echo $e->getMessage();
	
	}catch (SoapFault $soapfault) {
	
		echo $soapfault->getMessage();
	}	
	return $result;
}


//*****************************************************************************************


?>