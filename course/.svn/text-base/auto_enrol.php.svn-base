<?php // $Id: enrol.php,v 1.50.2.1 2008/03/03 05:27:36 moodler Exp $
      // Depending on the current enrolment method, this page
      // presents the user with whatever they need to know when
      // they try to enrol in a course.

    require_once("../config.php");
    require_once("lib.php");
    require_once("$CFG->dirroot/enrol/enrol.class.php");

    if (isloggedin() and !isguest()) {
        if(!iscreator() and !isteacherinanycourse()){
            redirect($CFG->wwwroot .'/index.php');
        }
    } else {
        redirect($CFG->wwwroot .'/index.php');
    }

    $id           = required_param('id', PARAM_INT);
    // Masoud Sadjadi: PARAM_ALPHANUM changed to PARAM_RAW to support emails being passed as user names.
    // $username     = required_param('username', PARAM_ALPHANUM);
    $username     = required_param('username', PARAM_RAW);
    $loginasguest = optional_param('loginasguest', 0, PARAM_BOOL); // hmm, is this still needed?

    if (!isloggedin()) {
        // do not use require_login here because we are usually comming from it
        redirect(get_login_url());
    }

    if (!$course = get_record('course','id',$id)) {
        print_error("That's an invalid course id");
    }

    if (!$context = get_context_instance(CONTEXT_COURSE, $course->id) ) {
        print_error("That's an invalid course id");
    }

    if(!$USER = get_record('user','username',$username)) {
        print_error("That's an invalid username");
    }
    
/// Users can't enroll to site course
    if ($course->id != SITEID) {
        if (!enrol_into_course($course, $USER, 'auto')) {
            print_error('couldnotassignrole');
        }else{
            echo "Enrolled - ".$username;
        }

    }


?>
