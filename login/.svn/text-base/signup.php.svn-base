<?php  // $Id: signup.php,v 1.56.2.2 2008/09/25 07:40:54 skodak Exp $

    require_once('../config.php');
	
    require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/calendar.php');		// Added: JAM - 11.19.2010
    require_once($CFG->dirroot.'/mod/scheduler/fullcalendar/quotasystem.php');
	
	/**
     * Returns whether or not the captcha element is enabled, and the admin settings fulfil its requirements.
     * @return bool
     */
    function signup_captcha_enabled() {
        global $CFG;
        return !empty($CFG->recaptchapublickey) && !empty($CFG->recaptchaprivatekey) && get_config('auth/email', 'recaptcha');
    }
    
    require_once('signup_form.php');
    

    if (empty($CFG->registerauth)) {
        error("Sorry, you may not use this page.");
    }
    $authplugin = get_auth_plugin($CFG->registerauth);

    if (!$authplugin->can_signup()) {
        error("Sorry, you may not use this page.");
    }

    //HTTPS is potentially required in this page
    httpsrequired();

    $mform_signup = new login_signup_form();

    if ($mform_signup->is_cancelled()) {
        redirect($CFG->httpswwwroot.'/login/index.php');

    } else if ($user = $mform_signup->get_data()) {
        $user->confirmed   = 0;
        $user->lang        = current_language();
        $user->firstaccess = time();
        $user->mnethostid  = $CFG->mnet_localhost_id;
        $user->secret      = random_string(15);
        $user->auth        = $CFG->registerauth;
		
		if(createUserProfile($user->username, $user, true)){	// Added: JAM - 11.19.2010 
			$authplugin->user_signup($user, true); // prints notice and link to login/index.php
			/*
			$user = get_record('user','username',$username);
			
			if(!addQSUser($user)){
				admin_signuperror_email($user);			// Added: JAM - 01.06.2011 
				error('An error has occured, please try again shortly.');
			}
			*/
		}else{
			//deleteQSUser($user);
			admin_signuperror_email($user);			// Added: JAM - 12.17.2010 
			error('Your request was processed and an administrator will contact you shortly.');
		}
		
		exit; //never reached
    }

    $newaccount = get_string('newaccount');
    $login      = get_string('login');

    if (empty($CFG->langmenu)) {
        $langmenu = '';
    } else {
        $currlang = current_language();
        $langs    = get_list_of_languages();
        $langmenu = popup_form ("$CFG->wwwroot/login/signup.php?lang=", $langs, "chooselang", $currlang, "", "", "", true);
    }

    $navlinks = array();
    $navlinks[] = array('name' => $login, 'link' => "index.php", 'type' => 'misc');
    $navlinks[] = array('name' => $newaccount, 'link' => null, 'type' => 'misc');
    $navigation = build_navigation($navlinks);
    print_header($newaccount, $newaccount, $navigation, $mform_signup->focus(), "", true, "<div class=\"langmenu\">$langmenu</div>");
echo '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>';
echo '<script type="text/javascript" src="../js/jquery.qtip-1.0.0-rc3.min.js"></script>';
echo '<script type="text/javascript">';
echo '$(document).ready(function(){';

$tipParam = ", position: { corner: { target: 'rightMiddle', tooltip: 'leftMiddle' } }, style: { name: 'light' }, style: { width: 400, tip: 'leftMiddle' }";

echo '$("input#id_username").qtip({content: "Please do not use an email address and avoid these characters: \'\" \/ \\ [ ] : ; | = , + * ? < > @ [space]"'.$tipParam.'});';
echo '$("input#id_password").qtip({content: "Password length must be less than or equal to 14 characters. <br/>Please do not use a password that you may be very concerned if it is compromised. We cannot take responsibility for losing your password."'.$tipParam.'});';
echo '$("input#id_email2, input#id_email").qtip({content: "Please use your work email."'.$tipParam.'});';
echo '$("input#id_profile_field_kaseyacustomerid").qtip({content: "Your Kaseya Customer ID is the first 6 letters of your Kaseya License Code, which can be found on the System > License Manager page. If you are a Kaseyan, please enter \"Kaseya\" for your Customer ID."'.$tipParam.'});';
echo '$("input#id_profile_field_kaseyasalesrep").qtip({content: "If you do not know what is your Kaseya Sales Rep\'s email, just enter: \"training@kaseya.com\". If you are a Kaseyan, please enter your own kaseya.com email for this."'.$tipParam.'});';
echo '$("input#id_profile_field_skypeid").qtip({content: "If you do not have a Skype or Google Talk account, just enter: \"Do not have one!\""'.$tipParam.'});';
echo '$("select#id_profile_field_zone").qtip({content: "If you do not choose the correct time zone, later when you try to schedule your virtual labs, you will receive an invalid appointment error message. So, do make sure to select the correct time zone now."'.$tipParam.'});';
echo '$("input#id_submitbutton").qtip({content: "Click to \"Create your new account\"."'.$tipParam.'});';
echo '$("fieldset#category_1").after("<fieldset class=\"clearfix\"><legend class=\"ftoggler\">Information</legend><div class=\"fcontainer clearfix\"><p>Note that if you received a \"Session Key Error\" message, it is related to the Cookie security settings in your browser. By adding our web site to your trusted sites in your browser security settings, this issue should be resolved. Alternatively, you can use Chrome, which by default has less strict security settings.</p><p>Once successfully submitted, you will receive an email confirmation. Open the confirmation email. You should see a link in that email. Browse to that link to confirm your account. Note that if there is no link in your email, it means that the Site Administrator would need to confirm your account. In that case, you need to wait until the admin approves your request.</p></div></fieldset>");';








//echo '$("input#id_username").qtip({content: ""'.$tipParam.'});';
//id_email id_email2

//id_email
echo '});';
echo '</script>';
    $mform_signup->display();
    print_footer();


?>
