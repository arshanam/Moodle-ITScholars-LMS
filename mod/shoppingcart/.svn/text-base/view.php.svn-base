<?php
// $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of shoppingcart
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/shoppingcart
 */
/// (Replace shoppingcart with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a = optional_param('a', 0, PARAM_INT);  // shoppingcart instance ID

if ($id) {
    if (!$cm = get_coursemodule_from_id('shoppingcart', $id)) {
        error('Course Module ID was incorrect');
    }

    if (!$course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (!$shoppingcart = get_record('shoppingcart', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }
} else if ($a) {
    if (!$shoppingcart = get_record('shoppingcart', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (!$course = get_record('course', 'id', $shoppingcart->course)) {
        error('Course is misconfigured');
    }
    if (!$cm = get_coursemodule_from_instance('shoppingcart', $shoppingcart->id, $course->id)) {
        error('Course Module ID was incorrect');
    }
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, "shoppingcart", "view", "view.php?id=$cm->id", "$shoppingcart->id");

/// Print the page header
$strshoppingcarts = get_string('modulenameplural', 'shoppingcart');
$strshoppingcart = get_string('modulename', 'shoppingcart');

$navlinks = array();
$navlinks[] = array('name' => $strshoppingcarts, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($shoppingcart->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($shoppingcart->name), '', $navigation, '', '', true,
        update_module_button($cm->id, $course->id, $strshoppingcart), navmenu($course, $cm));

/// Print the main part of the page
//**********************************************************************************************
// Shopping Cart
session_start();
$_SESSION['userid']=$USER->id;        
        
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
<!--Libraries-->
<link type="text/css" href="jquery-ui/css/redmond-light/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
<script type="text/javascript" src="jquery-ui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jquery-ui/js/jquery-ui-1.8.4.custom.min.js"></script>
<script type='text/javascript' src='jquery-ui/dataTables/media/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='jquery-ui/dataTables/examples/examples_support/jquery.jeditable.js'></script>
<link type="text/css" href="css/validation.css" rel="stylesheet" />


<!-- LiveValidation -->
<script type="text/javascript" src="js/livevalidation/livevalidation.js"></script>


<!--Our scripts-->
<script type='text/javascript' src='js/loading.js'></script>
<script type='text/javascript' src='js/tabs.js'></script>
<script type='text/javascript' src='js/shoppingcart.js'></script>
<script type='text/javascript' src='js/packages.js'></script>
<script type='text/javascript' src='js/store.js'></script>
<script type='text/javascript' src='js/checkout.js'></script>
<script type='text/javascript' src='js/orders.js'></script>
<script type='text/javascript' src='js/preassignment.js'></script>
<script type='text/javascript' src='js/timezone.js'></script>


<!--CSS-->
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<!-- <link rel="stylesheet" type="text/css" href="css/shoppingcartcss/style.css" /> -->

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


<!--link rel="stylesheet" href="jquery/jquery.tabs.css" type="text/css" media="print, projection, screen">
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
<input id ="userid" type="hidden" value="<?= $USER->id ?>" />
<input id ="username" type="hidden" value="<?= $USER->username ?>" />
<input id ="role" type="hidden" value="<?= $role ?>" />
<input id ="email" type="hidden" value="<?= $USER->email ?>" />
<input id ="url" type="hidden" value="<?= curPageURL(); ?>" />






<div id="wrapper">
    
    <div id="message" title="System message"></div>
    <div id="createitem-form" class="form" title="Item Details"></div>
    <div id="createpackage-form" class="form" title="Package Information"></div>
    <div id="additemtopkg-form" class="form"  title="Package Item details"></div>
    <div id="tabs"></div>

</div>



<?php
//**********************************************************************************************
/// Finish the page
print_footer($course);
?>
