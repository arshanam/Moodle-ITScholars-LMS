<?php // $Id: unenrol.php,v 1.32.2.3 2008/05/16 02:07:58 dongsheng Exp $

//  Remove oneself or someone else from a course, unassigning all
//  roles one might have
//
//  This will not delete any of their data from the course,
//  but will remove them from the participant list and prevent
//  any course email being sent to them.

    require_once("../../../config.php");
    require_once("../../../course/lib.php");
	
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/quotasystem.php');
	
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

	$userid = $user->id;
	echo "<br> \$userid is $userid";
	
    if ($course->metacourse) {
        print_error('cantunenrollfrommetacourse', '', $CFG->wwwroot.'/course/view.php?id='.$course->id);
    }

	$tmpuser = $user; 
	$USER = $user;
	
	/// Added: Check value submitted and call calendar ws to unenrol user
	if(unenrollQSUser($tmpuser, $course)){
		if(enrollUserInCourse($USER->username, $tmpuser->username, $course->fullname, false)) {
	
				if (! role_unassign(0, $userid, 0, $context->id)) {
					admin_moodlefailed_email($tmpuser,'unenrollUser',$course);
					error("An error occurred while trying to unenroll that person.");
				}
				send_unenrollment_notification($course,$tmpuser);	// Added: 05/08/2012
				add_to_log($course->id, 'course', 'unenrol',
						"view.php?id=$course->id", $course->id);
		
		} else {
			enrollQSUser($tmpuser, $course);
			admin_webservicefailed_email($tmpuser,'unenrollUser',$course);
		}
	}else{
		error("An error occurred while trying to unenroll that person.");
	}

?>
