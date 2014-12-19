<?php

/**
 * @author Martin Dougiamas
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle multiauth
 *
 * Authentication Plugin: Email Authentication
 *
 * Standard authentication function.
 *
 * 2006-08-28  File created.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');

/**
 * Email authentication plugin.
 */
class auth_plugin_email extends auth_plugin_base {

    /**
     * Constructor.
     */
    function auth_plugin_email() {
        $this->authtype = 'email';
        $this->config = get_config('auth/email');
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        global $CFG;
        if ($user = get_record('user', 'username', $username, 'mnethostid', $CFG->mnet_localhost_id)) {
            return validate_internal_user_password($user, $password);
        }
        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object  (with system magic quotes)
     * @param  string  $newpassword Plaintext password (with system magic quotes)
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        return update_internal_user_password($user, $newpassword);
    }

    function can_signup() {
        return true;
    }

    /**
     * Sign up a new user ready for confirmation.
     * Password is passed in plaintext.
     *
     * @param object $user new user object (with system magic quotes)
     * @param boolean $notify print notice with link and terminate
     */
    function user_signup($user, $notify=true) {
        global $CFG;
        require_once($CFG->dirroot.'/user/profile/lib.php');
        
        $user->password = hash_internal_user_password($user->password);

        if (! ($user->id = insert_record('user', $user)) ) {
            print_error('auth_emailnoinsert','auth');
        }
        
        /// Save any custom profile field information
        profile_save_data($user);
		
		//Added by JAM: 12.02.2010 - Call the set user time-zone for WS, cannot set time-zone until, user is created
		setWSUserDefaultTimeZone($user->username, $user);	

        $user = get_record('user', 'id', $user->id);
        events_trigger('user_created', $user);

		//Added by JAM: 01.06.2011 - this is where the user id exists
		if(!addQSUser($user)){
			admin_signuperror_email($user);			// Added: JAM - 01.06.2011 
			//error('An error has occured, please try again shortly.');
		}

        if (! send_confirmation_email($user)) {
            print_error('auth_emailnoemail','auth');
        }

        if ($notify) {
            global $CFG;
            $emailconfirm = get_string('emailconfirm');
            $navlinks = array();
            $navlinks[] = array('name' => $emailconfirm, 'link' => null, 'type' => 'misc');
            $navigation = build_navigation($navlinks);

            print_header($emailconfirm, $emailconfirm, $navigation);
			// Added by SMS: 7/28/2011
			$data = new object();
			$data->useremail = $user->email;
			$supportuser = generate_email_supportuser();
			$data->adminemail = $supportuser->email;

			// Edited by SMS: 7/28/2011
            // notice(get_string('emailconfirmsent', '', $user->email), "$CFG->wwwroot/index.php");
            notice(get_string('emailconfirmsent', '', $data), "$CFG->wwwroot/index.php");
        } else {
            return true;
        }
    }

    /**
     * Returns true if plugin allows confirming of new users.
     *
     * @return bool
     */
    function can_confirm() {
        return true;
    }

    /**
     * Confirm the new user as registered.
     *
     * @param string $username (with system magic quotes)
     * @param string $confirmsecret (with system magic quotes)
     */
    function user_confirm($username, $confirmsecret) {
        $user = get_complete_user_data('username', $username);

        if (!empty($user)) {
            if ($user->confirmed) {
                return AUTH_CONFIRM_ALREADY;

            } else if ($user->auth != 'email') {
                return AUTH_CONFIRM_ERROR;

            } else if ($user->secret == stripslashes($confirmsecret)) {   // They have provided the secret key to get in
                if (!set_field("user", "confirmed", 1, "id", $user->id)) {
                    return AUTH_CONFIRM_FAIL;
                }
                if (!set_field("user", "firstaccess", time(), "id", $user->id)) {
                    return AUTH_CONFIRM_FAIL;
                }
                return AUTH_CONFIRM_OK;
            }
        } else {
            return AUTH_CONFIRM_ERROR;
        }
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return mixed
     */
    function change_password_url() {
        return ''; // use dafult internal method
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return true;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {
        include "config.html";
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        // set to defaults if undefined
        if (!isset($config->recaptcha)) { 
            $config->recaptcha = false; 
        }
        
        // save settings
        set_config('recaptcha', $config->recaptcha, 'auth/email');
        return true;
    }
    
    /**
     * Returns whether or not the captcha element is enabled, and the admin settings fulfil its requirements.
     * @abstract Implement in child classes
     * @return bool
     */
    function is_captcha_enabled() {
        return false;
    }

}

?>
