<?php  // $Id: lib.php,v 1.8 2009/06/11 18:27:32 jfilip Exp $

/// Library of functions and constants for module ezproxy


define('EZPROXY_MAX_NAME_LENGTH', 50);
define('EZPROXY_OFFSET', 1);
define('EZPROXY_TRAIL_FORWARD_SLASH', '/');
define('EZPROXY_HTTP_PROTOCOL_START', 0);
define('EZPROXY_HTTPS_PROTOCOL_START', 0);
define('EZPROXY_HTTP_PROTOCOL_LENGTH', 7);
define('EZPROXY_HTTPS_PROTOCOL_LENGTH', 8);
define('EZPROXY_HTTP_PROTOCOL', 'http://');
define('EZPROXY_HTTPS_PROTOCOL', 'https://');


function get_ezproxy_name($ezproxy) {
    $textlib = textlib_get_instance();

    //$name = addslashes(strip_tags(format_string(stripslashes($ezproxy->name),true)));
    $name = addslashes(strip_tags(format_string(stripslashes($ezproxy->name),true)));
    if ($textlib->strlen($name) > EZPROXY_MAX_NAME_LENGTH) {
        $name = $textlib->substr($name, 0, EZPROXY_MAX_NAME_LENGTH)."...";
    }

    if (empty($name)) {
        // arbitrary name
        $name = get_string('modulename','ezproxy');
    }

    return $name;
}

function ezproxy_add_instance($ezproxy) {
/// Given an object containing all the necessary data, 
/// (defined by the form in mod.html) this function 
/// will create a new instance and return the id number 
/// of the new instance.

    $ezproxy->name = get_ezproxy_name($ezproxy);
    $ezproxy->timemodified = time();

    return insert_record("ezproxy", $ezproxy);
}


function ezproxy_update_instance($ezproxy) {
/// Given an object containing all the necessary data, 
/// (defined by the form in mod.html) this function 
/// will update an existing instance with new data.

    $ezproxy->name = get_ezproxy_name($ezproxy);
    $ezproxy->timemodified = time();
    $ezproxy->id = $ezproxy->instance;

    return update_record("ezproxy", $ezproxy);
}


function ezproxy_delete_instance($id) {
/// Given an ID of an instance of this module, 
/// this function will permanently delete the instance 
/// and any data that depends on it.  

    if (! $ezproxy = get_record("ezproxy", "id", "$id")) {
        return false;
    }

    $result = true;

    if (! delete_records("ezproxy", "id", "$ezproxy->id")) {
        $result = false;
    }

    return $result;
}

function ezproxy_get_participants($ezproxyid) {
//Returns the users with data in one resource
//(NONE, but must exist on EVERY mod !!)

    return false;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 */
function ezproxy_get_coursemodule_info($coursemodule) {
    if ($ezproxy = get_record('ezproxy', 'id', $coursemodule->instance, '', '', '', '', 'id, name')) {
        if (empty($ezproxy->name)) {
            // ezproxy name missing, fix it
            $ezproxy->name = get_string('ezproxymodinfo', 'ezproxy') . "{$ezproxy->id}";
            set_field('ezproxy', 'name', $ezproxy->name, 'id', $ezproxy->id);
        }
        $info = new object();
        $info->name = $ezproxy->name; 
        $info->extra =  urlencode("onclick=\"this.target='ezproxy$ezproxy->id'; return ".
                        "openpopup('/mod/ezproxy/view.php?inpopup=true&amp;id=".
                       $coursemodule->id . "','ezproxy$ezproxy->id','location=yes," .
                       "menubar=yes,resizeable=yes,scrollbars=yes,satus=yes,toolbar=yes');\"");
        return $info;
    } else {
        return null;
    }
}

function ezproxy_get_view_actions() {
    return array();
}

function ezproxy_get_post_actions() {
    return array();
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function ezproxy_reset_userdata($data) {
    return array();
}

/**
 * Returns all other caps used in module
 */
function ezproxy_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

/**
 * Returns a string with 'http://' removed
 * @param string $url URL
 * @return string modified URL
 */
function ezproxy_remove_protocol($url) {
    $newurl = $url;
    $protocol = substr($url, EZPROXY_HTTP_PROTOCOL_START, EZPROXY_HTTP_PROTOCOL_LENGTH);

    if (0 == strcasecmp($protocol, EZPROXY_HTTP_PROTOCOL)) {
        $newurl = str_replace($protocol, '', $newurl);
    }

    return $newurl;
}

/**
 * Adds a trailing forward slash to string if needed
 * 
 * @param string $data URl
 * @return string string with trailling slash
 */
function ezproxy_add_trailing_slash($data) {
    $newstr = $data;
    if (false === strrpos($newstr, '/') or
        strrpos($newstr, EZPROXY_TRAIL_FORWARD_SLASH) + EZPROXY_OFFSET != strlen($newstr)) {
        $newstr .= EZPROXY_TRAIL_FORWARD_SLASH;
    }
    
    return $newstr;
}

function ezproxy_has_protocol($url) {
    $protocol = substr($url, EZPROXY_HTTP_PROTOCOL_START, EZPROXY_HTTP_PROTOCOL_LENGTH);
    if (0 == strcasecmp($protocol, EZPROXY_HTTP_PROTOCOL)) {
    	return true;
    }    	
    $protocol = substr($url, EZPROXY_HTTPS_PROTOCOL_START, EZPROXY_HTTPS_PROTOCOL_LENGTH);
    if (0 == strcasecmp($protocol, EZPROXY_HTTPS_PROTOCOL)) {
    	return true;
    }
    return false;
}
?>
