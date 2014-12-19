<?php

//***********************************************************
//						Credit Management
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
    

if ($action == "getResources") {
	$resources = ct_getResources();
	$courses = db_getAllCourses();
	
	$response = array("resources" => $resources, "courses" => $courses);
	echo json_encode($response);
	
} else if ($action == "getCourses") {
	$response = db_getAllCourses();
	echo json_encode($response);
	
} else if ($action == "getCreditTypes") {
	$response = ct_getCreditTypes();
	echo json_encode($response);
	
} else if ($action == "getCreditType") {
	$id = isset($_POST['id']) ? $_POST['id'] : "";
	
	$response = ct_getCreditType($id);
	echo json_encode($response);
	
} else if ($action == "addCreditType") {
	$name = isset($_POST['name']) ? $_POST['name'] : "";
	$resource =	isset($_POST['resource']) ? $_POST['resource'] : "";
	$policyId = isset($_POST['policyId']) ? $_POST['policyId'] : "";
	$courseId = isset($_POST['courseId']) ? $_POST['courseId'] : "";
	$active = isset($_POST['active']) ? $_POST['active'] : ""; 
	$activeval = $active == "true" ? 1 : 0;
	$assignable = isset($_POST['assignable']) ? $_POST['assignable'] : "";
	$assignval = $assignable=="true" ? 1 : 0;
	
    $response = ct_addCreditType($name, $resource, $policyId, $courseId, $activeval, $assignval);
    echo json_encode($response);

} else if ($action == "modifyCreditType") {
	$id = isset($_POST['id']) ? $_POST['id'] : ""; 
	$name = isset($_POST['name']) ? $_POST['name'] : "";
	$resource =	isset($_POST['resource']) ? $_POST['resource'] : "";
	$policyId = isset($_POST['policyId']) ? $_POST['policyId'] : "";
	$courseId = isset($_POST['courseId']) ? $_POST['courseId'] : "";
	$active = isset($_POST['active']) ? $_POST['active'] : ""; 
	$activeval = $active == "true" ? 1 : 0;
	$assignable = isset($_POST['assignable']) ? $_POST['assignable'] : "";
	$assignval = $assignable=="true" ? 1 : 0;
	
	$response = ct_modifyCreditType($id, $name, $resource, $policyId, $courseId, $activeval, $assignval);
    echo json_encode($response);
    
} else if ($action == "deleteCreditType") {
	$id = isset($_POST['id']) ? $_POST['id'] : "";
	ct_deleteCreditType($id);
}

?>
    	