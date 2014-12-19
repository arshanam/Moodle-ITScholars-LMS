<?php // $Id: editadvanced.php,v 1.21.2.12 2008/12/22 03:16:51 tjhunt Exp $

    require_once('../config.php');
    require_once($CFG->libdir.'/gdlib.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_once($CFG->dirroot.'/user/editadvanced_form.php');
    require_once($CFG->dirroot.'/user/editlib.php');
    require_once($CFG->dirroot.'/user/profile/lib.php');
	
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');
	require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/quotasystem.php');

    httpsrequired();

    $id     = optional_param('id', $USER->id, PARAM_INT);    // user id; -1 if creating new user
    $course = optional_param('course', SITEID, PARAM_INT);   // course id (defaults to Site)

    if (!$course = get_record('course', 'id', $course)) {
        error('Course ID was incorrect');
    }
    require_login($course->id);

    if ($course->id == SITEID) {
        $coursecontext = get_context_instance(CONTEXT_SYSTEM);   // SYSTEM context
    } else {
        $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);   // Course context
    }
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);

    if ($id == -1) {
        // creating new user
        require_capability('moodle/user:create', $systemcontext);
        $user = new object();
        $user->id = -1;
        $user->auth = 'manual';
        $user->confirmed = 1;
        $user->deleted = 0;
    } else {
        // editing existing user
        require_capability('moodle/user:update', $systemcontext);
        if (!$user = get_record('user', 'id', $id)) {
            error('User ID was incorrect');
        }
    }

    // remote users cannot be edited
    if ($user->id != -1 and is_mnet_remote_user($user)) {
        redirect($CFG->wwwroot . "/user/view.php?id=$id&course={$course->id}");
    }

    if ($user->id != $USER->id and is_primary_admin($user->id)) {  // Can't edit primary admin
        print_error('adminprimarynoedit');
    }

    if (isguestuser($user->id)) { // the real guest user can not be edited
        print_error('guestnoeditprofileother');
    }

    if ($user->deleted) {
        print_header();
        print_heading(get_string('userdeleted'));
        print_footer($course);
        die;
    }

    //load user preferences
    useredit_load_preferences($user);

    //Load custom profile fields data
    profile_load_data($user);

    //user interests separated by commas
    if (!empty($CFG->usetags)) {
        require_once($CFG->dirroot.'/tag/lib.php');
        $user->interests = tag_get_tags_csv('user', $id, TAG_RETURN_TEXT); // formslib uses htmlentities itself
    }

    //create form
    $userform = new user_editadvanced_form();
    $userform->set_data($user);

    if ($usernew = $userform->get_data()) {
        add_to_log($course->id, 'user', 'update', "view.php?id=$user->id&course=$course->id", '');

        if (empty($usernew->auth)) {
            //user editing self
            $authplugin = get_auth_plugin($user->auth);
            unset($usernew->auth); //can not change/remove
        } else {
            $authplugin = get_auth_plugin($usernew->auth);
        }
	
        $usernew->username     = trim($usernew->username);
        $usernew->timemodified = time();

        if ($usernew->id == -1) {
            //TODO check out if it makes sense to create account with this auth plugin and what to do with the password
            unset($usernew->id);
            $usernew->mnethostid = $CFG->mnet_localhost_id; // always local user
            $usernew->confirmed  = 1;
            $usernew->password = hash_internal_user_password($usernew->newpassword);
			
			// Added: to call calendar ws to create user.
			//Added: 01.03.2011 - quotasystem.php
			//if(addQSUser($usernew)){
			
			if(createUserProfile($USER->username, $usernew)){
				if (!$usernew->id = insert_record('user', $usernew)) {
					admin_moodlefailed_email($usernew,'addUser',$course);
					error('Error creating user record');
				}
				
				$myuser = get_record('user', 'id', $usernew->id);
	
				if(!addQSUser($myuser)){							//Added: 01.06.2011 - quotasystem.php
		
					admin_signuperror_email($usernew);			// Added: JAM - 01.06.2011 
					//error('An error has occured, please try again shortly.');
				}
				
				$usercreated = true;
			}else{
				$usercreated = false;
				//deleteQSUser($usernew);
				admin_webservicefailed_email($usernew, 'addUser', $course);	
			}
					
			/*	
			}else{
				$usercreated = false;
				deleteUserProfile($USER->username, $usernew);			//Added: 11.05.2010 - calendar.php
				deleteQSUser($usernew);
			}	
			*/
        } else {
	    
			// Added: to call calendar ws to edit user.
			//Added: 12.28.2010 - quotasystem.php
			if(modifyQSUser($usernew)){
				
				$olduserinfo = get_record('user','id',$usernew->id);

				if(editUserProfile($USER->username, $usernew)){
				    
					if (!update_record('user', $usernew)) {
						admin_moodlefailed_email($usernew,'modifyUser',$course);
						error('Error updating user record');
					}								
					// pass a true $userold here
					if (! $authplugin->user_update($user, $userform->get_data(false))) {
						// auth update failed, rollback for moodle
						update_record('user', addslashes_object($user));
						error('Failed to update user data on external auth: '.$user->auth.
								'. See the server logs for more details.');
					}
		
					//set new password if specified
					if (!empty($usernew->newpassword)) {
						if ($authplugin->can_change_password()) {
							if (!$authplugin->user_update_password($usernew, $usernew->newpassword)){
								admin_moodlefailed_email($usernew,'modifyUserPassword',$course);
								error('Failed to update password on external auth: ' . $usernew->auth .
										'. See the server logs for more details.');
							}
						}
					}
				}else{
					modifyQSUser($olduserinfo);
					admin_webservicefailed_email($olduserinfo,'modifyUser',$course);
				}
			}
            $usercreated = false;
				

        }
	
        //update preferences
        useredit_update_user_preference($usernew);

        // update tags
        if (!empty($CFG->usetags)) {
            useredit_update_interests($usernew, $usernew->interests);
        }

        //update user picture
        if (!empty($CFG->gdversion)) {
            useredit_update_picture($usernew, $userform);
        }

        // update mail bounces
        useredit_update_bounces($user, $usernew);

        // update forum track preference
        useredit_update_trackforums($user, $usernew);

        // save custom profile fields data
        profile_save_data($usernew);

        // reload from db
        $usernew = get_record('user', 'id', $usernew->id);
		
		setWSUserDefaultTimeZone($USER->username, $usernew);	//Added: 01.03.2011 - calendar.php

        // trigger events
        if ($usercreated) {
            events_trigger('user_created', $usernew);
        } else {
            events_trigger('user_updated', $usernew);
        }

        if ($user->id == $USER->id) {
            // Override old $USER session variable
            foreach ((array)$usernew as $variable => $value) {
                $USER->$variable = $value;
            }
            if (!empty($USER->newadminuser)) {
                unset($USER->newadminuser);
                // apply defaults again - some of them might depend on admin user info, backup, roles, etc.
                admin_apply_default_settings(NULL , false);
                // redirect to admin/ to continue with installation
                redirect("$CFG->wwwroot/$CFG->admin/");
            } else {
                redirect("$CFG->wwwroot/user/view.php?id=$USER->id&course=$course->id");
            }
        } else {
            redirect("$CFG->wwwroot/$CFG->admin/user.php");
        }
        //never reached
    }

/// Display page header
    if ($user->id == -1 or ($user->id != $USER->id)) {
        if ($user->id == -1) {
            admin_externalpage_setup('addnewuser', '', array('id' => -1));
            admin_externalpage_print_header();
        } else {
            admin_externalpage_setup('editusers', '', array('id' => $user->id, 'course' => SITEID), $CFG->wwwroot . '/user/editadvanced.php');
            admin_externalpage_print_header();
            $userfullname = fullname($user, true);
            print_heading($userfullname);
        }
    } else if (!empty($USER->newadminuser)) {
        $strprimaryadminsetup = get_string('primaryadminsetup');
        print_header($strprimaryadminsetup, $strprimaryadminsetup);
        print_simple_box(get_string('configintroadmin', 'admin'), 'center', '50%');
        echo '<br />';
    } else {
        $streditmyprofile = get_string('editmyprofile');
        $strparticipants  = get_string('participants');
        $strnewuser       = get_string('newuser');
        $userfullname     = fullname($user, true);

        $navlinks = array();
        if (has_capability('moodle/course:viewparticipants', $coursecontext) || has_capability('moodle/site:viewparticipants', $systemcontext)) {
            $navlinks[] = array('name' => $strparticipants, 'link' => "index.php?id=$course->id", 'type' => 'misc');
        }
        $navlinks[] = array('name' => $userfullname,
                            'link' => "view.php?id=$user->id&amp;course=$course->id",
                            'type' => 'misc');
        $navlinks[] = array('name' => $streditmyprofile, 'link' => null, 'type' => 'misc');
        $navigation = build_navigation($navlinks);
        print_header("$course->shortname: $streditmyprofile", $course->fullname, $navigation, "");

        /// Print tabs at the top
        $showroles = 1;
        $currenttab = 'editprofile';
        require('tabs.php');
    }

/// Finally display THE form
    $userform->display();

/// and proper footer
    if (!empty($USER->newadminuser)) {
        print_footer('none');
    } else {
        print_footer($course);
    }

?>
