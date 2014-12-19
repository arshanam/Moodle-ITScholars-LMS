<?php  // $Id: editservicecall.php

///////////////////////////////////////////////////////////////


    if (!file_exists('./config.php')) {
        header('Location: install.php');
        die;
    }

    require_once('config.php');
    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');
	
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/quotasystem.php');

    if (isloggedin() and !isguest()) {
        if(!iscreator() and !isteacherinanycourse()){
            redirect($CFG->wwwroot .'/index.php');
        }
    } else {
        redirect($CFG->wwwroot .'/index.php');
    }

    // get values from form for actions on this page
    $param = new stdClass();

    // Parameters: create a URL that displays the last (largest) quiz id and name. (eg. 2 Test Exam)



    $records = get_records('user');		// returns more users.
	
	$users = get_users_listing("username");
	
	$counter = 0;
	$errors = 0;
	$errmessage = "";

	foreach ($users as $user){
	
		if($user->username != 'admin'){
			//echo $user->username."<br/>";
			try{
			
				editUserProfile($USER->username, $user);	
				modifyQSUser($user);
				enrollUsersAvailCourses($USER->username, $user->username);
				$counter++;
			}catch (Exception $e) {
				$errors++;
				$errmessage .= $u->username."<br/>";
			}
		}
       
    }

	echo $counter." user(s) accounts has been updated.<br/>";
	
	if($errors > 0){
		echo "<br/><b>".$errors." errors have occured for the following accounts:<br/></b>".$errmessage;
	}
   

?>
