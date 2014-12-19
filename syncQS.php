<?php

    if (!file_exists('./config.php')) {
        header('Location: install.php');
        die;
    }

    require_once('config.php');    
    //require_once('mod/shoppingcart/server/ws/webserviceconfig.php');
    //require_once('mod/shoppingcart/server/db/db.php');


	//sync test
	//define('WSDL_QS', 'http://localhost:8080/axis2/services/QuotaSystem?wsdl');
	//define('LOCATION_QS', 'http://localhost:8080/axis2/services/QuotaSystem?wsdl');
	
	define('WSDL_QS', 'http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl');
	define('LOCATION_QS','http://ita-provisioner.cis.fiu.edu:8080/axis2/services/QuotaSystem?wsdl');


	ini_set("soap.wsdl_cache_enabled", "0");
    $users = array();
	$courses = array();
	$enrollments = array();	

	$sql = "SELECT * FROM mdl_user WHERE deleted=0 ORDER BY id";
	$result  = get_records_sql($sql);
	
	foreach ($result as $u){
		$user = array(
			"id"=>$u->id,
			"username"=>$u->username,
			"email"=>$u->email,
			"role"=>"STUDENT"
		);
		array_push($users, $user);
		
	}

	
	$sql = "SELECT * FROM mdl_course ORDER BY id";
	$result  = get_records_sql($sql);
	
	foreach ($result as $c){
		$course = array(
			"id"=>$c->id,
			"shortname"=>$c->shortname,
			"fullname"=>$c->fullname
		);
		array_push($courses, $course);
		
	}
	
	$sql = "SELECT * FROM moodle.mdl_role_assignments WHERE roleid = 5 and 
		contextid IN (SELECT id FROM moodle.mdl_context WHERE contextlevel = 50) ORDER BY id";
	

	$result  = get_records_sql($sql);

	foreach ($result as $e){
		$sql = "SELECT * FROM mdl_context WHERE id = $e->contextid ORDER BY id";
		$context = get_record_sql($sql);
		
		$enrollment = array(
			"enrollmentId"=>$e->id,
			"courseId"=>$context->instanceid,
			"userId"=>$e->userid
		);
		array_push($enrollments, $enrollment);
		
	}

	try {

    	$params = array("user"=>$users, "course"=>$courses, "enrollment"=>$enrollments);
		$client = new SoapClient(WSDL_QS, array('location' => LOCATION_QS));
        $client->syncUsersAndCourses($params);
        
        //print_r($users);
        //print_r($courses);
        //print_r($enrollments);
        

        echo "Finished";  
      
    } catch (Exception $e) {
        print_r($e->getMessage());

    } catch (SoapFault $soapfault) {
        print_r($soapfault->getMessage());
    }

?>