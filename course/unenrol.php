<?php // $Id: unenrol.php,v 1.32.2.3 2008/05/16 02:07:58 dongsheng Exp $

//  Remove oneself or someone else from a course, unassigning all
//  roles one might have
//
//  This will not delete any of their data from the course,
//  but will remove them from the participant list and prevent
//  any course email being sent to them.

    require_once("../config.php");
    require_once("lib.php");
	
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/quotasystem.php');
	
    $id      = required_param('id', PARAM_INT);               //course
    $userid  = optional_param('user', 0, PARAM_INT);          //course
    $confirm = optional_param('confirm', 0, PARAM_BOOL);

    if($userid == $USER->id){
        // the rest of this code assumes $userid=0 means 
        // you are unassigning yourself, so set this for the
        // correct capabiliy checks & language later
        $userid = 0;
    }

    if (! $course = get_record('course', 'id', $id) ) {
        error('Invalid course id');
    }

    if (! $context = get_context_instance(CONTEXT_COURSE, $course->id)) {
        error('Invalid context');
    }

    require_login($course->id);

    if ($course->metacourse) {
        print_error('cantunenrollfrommetacourse', '', $CFG->wwwroot.'/course/view.php?id='.$course->id);
    }

    if ($userid) {   // Unenrolling someone else
        require_capability('moodle/role:assign', $context, NULL, false);

        $roles = get_user_roles($context, $userid, false);

        // verify user may unassign all roles at course context
        foreach($roles as $role) {
            if (!user_can_assign($context, $role->roleid)) {
                error('Can not unassign this user from role id:'.$role->roleid);
            }
        }

    } else {         // Unenrol yourself
        require_capability('moodle/role:unassignself', $context, NULL, false);
    }

    if (!empty($USER->access['rsw'][$context->path])) {
        print_error('cantunenrollinthisrole', '',
                    $CFG->wwwroot.'/course/view.php?id='.$course->id);
    }

    if ($confirm and confirm_sesskey()) {
        if ($userid) {
			
			$tmpuser = get_record('user','id',$userid);
			
			/// Added: Check value submitted and call calendar ws to unenrol user
			if(unenrollQSUser($tmpuser, $course)){
				if(enrollUserInCourse($USER->username, $tmpuser->username, $course->fullname, false)){
			
						if (! role_unassign(0, $userid, 0, $context->id)) {
							admin_moodlefailed_email($tmpuser,'unenrollUser',$course);
							error("An error occurred while trying to unenroll that person.");
						}
						send_unenrollment_notification($course,$tmpuser);	// Added: 05/08/2012
						add_to_log($course->id, 'course', 'unenrol',
								"view.php?id=$course->id", $course->id);
				
				}else{
					enrollQSUser($tmpuser, $course);
					admin_webservicefailed_email($tmpuser,'unenrollUser',$course);
				}
			}else{
				error("An error occurred while trying to unenroll that person.");
			}
            redirect($CFG->wwwroot.'/user/index.php?id='.$course->id);

        } else {
			/// Added: Check value submitted and call calendar ws to unenrol user
			if(unenrollQSUser($USER, $course)){
				if(enrollUserInCourse($USER->username, $USER->username, $course->fullname, false)){
				
						if (! role_unassign(0, $USER->id, 0, $context->id)) {
							admin_moodlefailed_email($USER,'unenrollUser',$course);
							error("An error occurred while trying to unenroll you.");
							
						}
						
						send_unenrollment_notification($course,$USER);	// Added: 05/08/2012
						
						// force a refresh of mycourses
						unset($USER->mycourses);
						add_to_log($course->id, 'course', 'unenrol',
								"view.php?id=$course->id", $course->id);
					
				}else{
					enrollQSUser($USER, $course);
					admin_webservicefailed_email($USER,'unenrollUser',$course);
				}
			}else{
				error("An error occurred while trying to unenroll you.");
			}
            redirect($CFG->wwwroot);
        }
    }


    $strunenrol = get_string('unenrol');
    $navlinks = array();
    $navlinks[] = array('name' => $strunenrol, 'link' => null, 'type' => 'misc');
    $navigation = build_navigation($navlinks);

    print_header("$course->shortname: $strunenrol", $course->fullname, $navigation);

    if ($userid) {
        if (!$user = get_record('user', 'id', $userid)) {
            error('That user does not exist!');
        }
        $strunenrolsure  = get_string('unenrolsure', '', fullname($user, true));
        notice_yesno($strunenrolsure, "unenrol.php?id=$id&amp;user=$user->id&amp;confirm=yes&amp;sesskey=".sesskey(),
                                      $_SERVER['HTTP_REFERER']);
    } else {
        $strunenrolsure  = get_string('unenrolsure', '', get_string("yourself"));
        notice_yesno($strunenrolsure, "unenrol.php?id=$id&amp;confirm=yes&amp;sesskey=".sesskey(),
                                      $_SERVER['HTTP_REFERER']);
    }

    print_footer($course);

?>
