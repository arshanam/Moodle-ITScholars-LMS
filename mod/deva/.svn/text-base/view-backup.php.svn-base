<?php
// $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of deva
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/deva
 */
/// (Replace deva with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a = optional_param('a', 0, PARAM_INT);  // deva instance ID

if ($id) {
    if (!$cm = get_coursemodule_from_id('deva', $id)) {
        error('Course Module ID was incorrect');
    }

    if (!$course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (!$deva = get_record('deva', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }
} else if ($a) {
    if (!$deva = get_record('deva', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (!$course = get_record('course', 'id', $deva->course)) {
        error('Course is misconfigured');
    }
    if (!$cm = get_coursemodule_from_instance('deva', $deva->id, $course->id)) {
        error('Course Module ID was incorrect');
    }
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, "deva", "view", "view.php?id=$cm->id", "$deva->id");

/// Print the page header
$strdevas = get_string('modulenameplural', 'deva');
$strdeva = get_string('modulename', 'deva');

$navlinks = array();
$navlinks[] = array('name' => $strdevas, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($deva->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($deva->name), '', $navigation, '', '', true,
        update_module_button($cm->id, $course->id, $strdeva), navmenu($course, $cm));

/// Print the main part of the page
//**********************************************************************************************
// DEVA
        
        
//Role
$sql = "SELECT * FROM {$CFG->prefix}role_assignments WHERE userid =" . $USER->id;
$role_assignment = get_record_sql($sql);
$sql2 = "SELECT * FROM {$CFG->prefix}role WHERE id = " . $role_assignment->roleid;
$role_obj = get_record_sql($sql2);
$role = $role_obj->shortname;

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
?>
<link rel='stylesheet' type='text/css' href='fullcalendar/css/jquery.ptTimeSelect.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/css/custom-theme/jquery-ui-1.8.1.custom2.css' />
<link href="fullcalendar/css/cmxformTemplate.css" rel="stylesheet" type="text/css" />
<link href="fullcalendar/css/cmxform.css" rel="stylesheet" type="text/css" />

<script type='text/javascript' src='fullcalendar/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery-ui-1.8.1.custom.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.ptTimeSelect.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/dateFormat.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.blockUI.js'></script>
<script type="text/javascript" src="fullcalendar/jquery/jquery.qtip-1.0.0-rc3.min.js"></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.jookie.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.validate.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/cookie.js'></script>

<script type='text/javascript' src='fullcalendar/servicecalls.js'></script> <!-- NEW -->
<script type='text/javascript' src='fullcalendar/user-admin.js'></script -->
<script type='text/javascript' src='fullcalendar/dialogboxes.js'></script>
<script type='text/javascript' src='fullcalendar/recurring-events.js'></script>
<script type='text/javascript' src='fullcalendar/jquery.loader.js'></script>



<!--Libraries-->
<link type="text/css" href="jquery-ui/css/redmond-light/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
<script type="text/javascript" src="jquery-ui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jquery-ui/js/jquery-ui-1.8.4.custom.min.js"></script>
<script type='text/javascript' src='jquery-ui/dataTables/media/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='jquery-ui/dataTables/examples/examples_support/jquery.jeditable.js'></script>
    
<script type='text/javascript' src="http://code.jquery.com/jquery-1.4.4.js"></script>

<!--Our scripts-->
<script type='text/javascript' src='fullcalendar/dialogboxes.js'></script>
<script type='text/javascript' src='fullcalendar/recurring-events.js'></script>
<script type='text/javascript' src='fullcalendar/jquery.loader.js'></script> 
<script type='text/javascript' src='js/message.js'></script>
<script type='text/javascript' src='js/deva-tabs.js'></script>



<!--CSS-->
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<!-- <link rel="stylesheet" type="text/css" href="css/devacss/style.css" /> -->

<style type="text/css" media="screen">
	
	@import "jquery-ui/dataTables/media/css/demo_table_jui.css";
	
	/*
	 * Override styles needed due to the mix of three different CSS sources! For proper examples
	 * please see the themes example in the 'Examples' section of this site
	 */
	.dataTables_info { padding-top: 0; }
	.dataTables_paginate { padding-top: 0; }
	.css_right { float: right; }
	#example_wrapper .fg-toolbar { font-size: 0.8em }
	#theme_links span { float: left; padding: 2px 10px; }
	
</style>


<link rel="stylesheet" href="jquery/jquery.tabs.css" type="text/css" media="print, projection, screen">
<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
<!--[if lte IE 7]->
<link rel="stylesheet" href="jquery.tabs-ie.css" type="text/css" media="projection, screen">
<![endif]-->


<style type="text/css">
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
    .ui-widget {
        font-family:Lucida Grande,Lucida Sans,Arial,sans-serif;
        font-size:0.8em;
    }

</style>

<!-- $CFG, $SESSION, $USER, $COURSE, $SITE, $PAGE, $DB and $THEME -->
<input id ="userid" type="hidden" value="<?= $USER->id ?>" />
<input id ="username" type="hidden" value="<?= $USER->username ?>" />
<input id ="role" type="hidden" value="<?= $role ?>" />
<input id ="email" type="hidden" value="<?= $USER->email ?>" />
<input id ="url" type="hidden" value="<?= curPageURL(); ?>" />
<input id ="course" type="hidden" value="<?= $COURSE->fullname ?>" />
<input id="resourcetype" type="hidden"
	value="<?php 
switch ($id) {
    case 293:
    case 329:
    	$resourcetype = 'VIRTUAL LAB';
        break;
 
    case 328:
    	$resourcetype = 'CERTIFICATE';
    	break;
 
    default:
    	$resourcetype = 'VIRTUAL LAB';
       	break;
}
echo $resourcetype; ?>" />

<style type="text/css">
A.devaTabs:link { border: 1px solid #DFEFFC; padding: 2px 5px 2px 5px; background: #dfeffc url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #2e6e9e; font-size:80%;}
A.devaTabs:visited { border: 1px solid #DFEFFC; padding: 2px 5px 2px 5px; background: #dfeffc url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #2e6e9e; font-size:80%;}
A.devaTabs:active { border: 1px solid #DFEFFC; padding: 2px 5px 2px 5px; background: #dfeffc url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #2e6e9e; font-size:80%;}
A.devaTabs:hover {border: 1px solid #DFEFFC; padding: 2px 5px 2px 5px; background: #dfeffc url(images/ui-bg_glass_85_dfeffc_1x400.png) 50% 50% repeat-x; font-weight: bold; color: #2e6e9e; font-size:80%;}
</style>
	
<!-- style type="text/css">
A.devaTabs:link {
    background: #86B3D5;
    border: 1px solid #C5DBEC;
    color: #2E6E9E;
    font-weight: bold;
}
A.devaTabs:visited {
    background: #86B3D5;
    border: 1px solid #C5DBEC;
    color: #2E6E9E;
    font-weight: bold;
}
A.devaTabs:active {
    background: #86B3D5;
    border: 1px solid #C5DBEC;
    color: #DFEFFC;
    font-weight: bold;
}
A.devaTabs:hover {
    background: #D0E5F5;
    border: 1px solid #79B7E7;
    color: #1D5987;
    font-weight: bold;
}
</style -->
	

<div id="wrapper">
    <div id="message" title="System message"></div>
    <div id="createitem-form" class="form" title="Item Details"></div>
    <div id="createpackage-form" class="form" title="Package Information"></div>
    <div id="additemtopkg-form" class="form"  title="Package Item details"></div>
    <div id="tabs-wrapper" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
    <center><div id="devaTabs"></center></div></div>
    <div id="devaTabContent"></div>
</div>	

<style type="text/css">
#tabs-wrapper { padding: 5px 5px 5px 5px; }
</style>


<?php
//**********************************************************************************************
/// Finish the page
// print_footer($course);
?>
