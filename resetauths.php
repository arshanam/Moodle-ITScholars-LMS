<?php
// $Id: forgot_password.php,v 1.45.2.4 2008/12/01 22:37:12 skodak Exp $
// forgot password routine.
// find the user and call the appropriate routine for their authentication
// type.

require_once('config.php');
require_once('login/forgot_password_form.php');

    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');

	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');

    if (isloggedin() and !isguest()) {
        if(!iscreator() and !isteacherinanycourse()){
            redirect($CFG->wwwroot .'/index.php');
        }
    } else {
        redirect($CFG->wwwroot .'/index.php');
    }


	


httpsrequired();

$systemcontext = get_context_instance(CONTEXT_SYSTEM);


$users = get_users_listing("username");
	
$counter = 0;
$errors = 0;
$errmessage = "";

foreach ($users as $u){

	if($u->username != 'admin'){
		$counter++;

		//update_login_count();
	
		$user = get_complete_user_data('username', $u->username);
		if (!empty($user)) {
	
			// make sure user is allowed to change password
			require_capability('moodle/user:changeownpassword', $systemcontext, $user->id);
	
			// override email stop and mail new password
			$user->emailstop = 0;
			if (!reset_password_and_mail($user)) {
				//error('Error resetting password and mailing you');
				$errors++;
				$errmessage .= $u->username."<br/>";
			}
		}
	
	}
   
}

echo $counter." user account password(s) have been reset.<br/>";

if($errors > 0){
	echo "<br/><b>".$errors." errors have occured for the following accounts:<br/></b>".$errmessage;
}
   

?>
