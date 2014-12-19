<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of scheduler
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/scheduler
 */

/// (Replace scheduler with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/user/profile/lib.php');
require_once(dirname(__FILE__).'/lib.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // scheduler instance ID

if ($id) {
    if (! $cm = get_coursemodule_from_id('scheduler', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (! $scheduler = get_record('scheduler', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }

} else if ($a) {
    if (! $scheduler = get_record('scheduler', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (! $course = get_record('course', 'id', $scheduler->course)) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('scheduler', $scheduler->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, "scheduler", "view", "view.php?id=$cm->id", "$scheduler->id");

/// Print the page header
$strschedulers = get_string('modulenameplural', 'scheduler');
$strscheduler  = get_string('modulename', 'scheduler');

$navlinks = array();
$navlinks[] = array('name' => $strschedulers, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($scheduler->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($scheduler->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strscheduler), navmenu($course, $cm));


 

//**********************************************************************************************
// Scheduler Calendar
// Using FULLCALENDAR plugin
//**********************************************************************************************


?>

<link rel='stylesheet' type='text/css' href='fullcalendar/css/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/css/jquery.ptTimeSelect.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/css/custom-theme/jquery-ui-1.8.1.custom2.css' />
<link href="fullcalendar/css/cmxformTemplate.css" rel="stylesheet" type="text/css" />
<link href="fullcalendar/css/cmxform.css" rel="stylesheet" type="text/css" />
<link rel='stylesheet' type='text/css' href='fullcalendar/css/flexigrid/flexigrid.css' />

<script type='text/javascript' src='fullcalendar/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery-ui-1.8.9.custom.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.ptTimeSelect.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/dateFormat.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.blockUI.js'></script>
<script type="text/javascript" src="fullcalendar/jquery/jquery.qtip-1.0.min.js"></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.jookie.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.validate.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/cookie.js'></script>


<script type='text/javascript' src='fullcalendar/fullcalendar.js'></script>
<script type='text/javascript' src='fullcalendar/gcal.js'></script>
<script type="text/javascript" src="colormanager.js"></script>
<script type='text/javascript' src='fullcalendar/calendar.js'></script>
<script type='text/javascript' src='fullcalendar/filters.js'></script>		
<script type='text/javascript' src='fullcalendar/servicecalls.js'></script> 
<script type='text/javascript' src='fullcalendar/externalObjects.js'></script> <!-- NEW -->
<!-- script type='text/javascript' src='fullcalendar/user-student.js'></script>
<script type='text/javascript' src='fullcalendar/user-admin.js'></script -->
<script type='text/javascript' src='fullcalendar/slider-options-menu.js'></script>
<script type='text/javascript' src='fullcalendar/dialogboxes.js'></script>
<script type='text/javascript' src='fullcalendar/recurring-events.js'></script>
<script type='text/javascript' src='fullcalendar/jquery.loader.js'></script>

<script type='text/javascript' src='fullcalendar/flexigrid.js'></script>
<!-- script type='text/javascript' src='fullcalendar/flexigrid.pack.js'></script -->

<script type='text/javascript' src='timezone.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquerytimer-min.js'></script>	<!-- Timer -->


<!-- Context Menu -->
<script src="fullcalendar/jquery/jquery.contextMenu.js" type="text/javascript"></script>
<link href="fullcalendar/css/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

<!-- Color Manager -->
<link rel="stylesheet" media="screen" type="text/css" href="fullcalendar/colorpicker/css/colorpicker_custom.css" />
<link rel="stylesheet" media="screen" type="text/css" href="fullcalendar/colorpicker/css/layout2.css" />
<script type="text/javascript" src="fullcalendar/colorpicker/js/colorpicker.js"></script>
<script type="text/javascript" src="fullcalendar/colorpicker/js/eye.js"></script>
<script type="text/javascript" src="fullcalendar/colorpicker/js/utils.js"></script>

<style type='text/css'>

	body {
		margin-top: 40px;
		<!--text-align: center;-->
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}

	#calendar {
		width: 900px;
		/*
		float: left;
		margin: 0px 25px 5px 0px;
		*/
		margin: 0 auto;
		}
		
	#host_calendar {
		width: 900px;
		margin: 0 auto;
		}

	/* -- Set here for design purposes. -- */
	/* Accordion ----------------------------------*/
	.ui-accordion .ui-accordion-header { cursor: pointer; position: relative; margin-top: 1px; zoom: 1; }
	.ui-accordion .ui-accordion-li-fix { display: inline; }
	.ui-accordion .ui-accordion-header-active { border-bottom: 0 !important; }
	.ui-accordion .ui-accordion-header a { display: block; font-size: 1em; padding: .5em .5em .5em .7em; color:#ffffff;}
	/* IE7-/Win - Fix extra vertical space in lists */
	.ui-accordion a { zoom: 1; }
	.ui-accordion-icons .ui-accordion-header a { padding-left: 2.2em; }
	.ui-accordion .ui-accordion-header .ui-icon { position: absolute; left: .5em; top: 50%; margin-top: -8px; }
	.ui-accordion .ui-accordion-content { padding: 1em 2.2em; border-top: 0; margin-top: -2px; position: relative; top: 1px; margin-bottom: 2px; overflow: auto; display: none; zoom: 1; }
	.ui-accordion .ui-accordion-content-active { display: block; }

/* actions icons */

	
	.ui-accordion .ui-accordion-content-active .actions-edit{
		float: right;
		display: block;
		width: 25px;
		height: 25px;
		margin: 5px;
		background-image: url(fullcalendar/images/list_edit_25px.png);
		cursor: pointer;
	} 
	.ui-accordion .ui-accordion-content-active .actions-cancel {
		float: right;
		display: block;
		width: 25px;
		height: 25px;
		margin: 5px;
		background-image: url(fullcalendar/images/list_delete_25px.png);
		cursor: pointer;
	} 
	.ui-accordion .ui-accordion-content-active .actions-confirm {
		float: right;
		display: block;
		width: 25px;
		height: 25px;
		margin: 5px;
		background-image: url(fullcalendar/images/list_confirm_25px.png);
		cursor: pointer;
	} 
	.ui-accordion .ui-accordion-content-active .actions-info {
		float: right;
		display: block;
		width: 25px;
		height: 25px;
		margin: 5px;
		background-image: url(fullcalendar/images/list_info_25px.png);
		cursor: pointer;
	} 
	
	.ui-progressbar-value { 
		background-image: url(fullcalendar/images/pbar-ani.gif);
	}

	
/* selected day */
	.fc-state-selected {
		background-color: #BBD9EE;	 /*#9CB6D6;*/
	}
	.fc-state-selected-today {
		background-color: #FFCC66;
	}
	
/* right-click menu */

   .vmenu{border:1px solid #aaa;position:absolute;background:#fff;	display:none;font-size:0.75em;}
	   .vmenu .first_li span{width:100px;display:block;padding:5px 10px;cursor:pointer}
	   .vmenu .inner_li{display:none;margin-left:120px;position:absolute;border:1px solid #aaa;
		border-left:1px solid #ccc;margin-top:-28px;background:#fff;}
	   .vmenu .sep_li{border-top: 1px ridge #aaa;margin:5px 0}
	   .vmenu .fill_title{font-size:11px;font-weight:bold;/height:15px;/overflow:hidden;word-wrap:break-word;}
	   .context-label { text-transform: capitalize; }

/* filter Options menu */

    #filters { display: block; }
    .mixed-state { cursor: pointer; opacity:0.3; filter:alpha(opacity=30); }
	
	#tabs { min-width: 1130px; }
	#progressbarContainer {
		width: 300px;
		z-index: 5001;
		text-align: center;
		position: absolute;
		top: 50%;
		left: 50%;
	}
	
/* Calendar CSS */

/*
	div.available {opacity:0.4; filter:alpha(opacity=40); color: #333333;}
	div.available-accordion h3 a {opacity:0.4; filter:alpha(opacity=40);}
	div.scheduled-accordion h3 a {opacity:0.9; filter:alpha(opacity=90);}
*/


	div.available {opacity:0.5; color: #333333; filter:alpha(opacity=50);}
	div.available-accordion h3 a {opacity:0.5; filter:alpha(opacity=50);}
	div.scheduled-accordion h3 a {opacity:0.9; filter:alpha(opacity=40);}


	div .available-accordion h3 a:visited {color:#ffffff;}
	div .available-accordion h3 a:hover {font-weight:bold;}

	
	div .scheduled-accordion h3 a:visited {color:#ffffff;}
	div .scheduled-accordion h3 a:hover {font-weight:bold;}
	
	/*-- Calendar and Side Panel Container --*/
	/*
	#sidePanel { float: left; width: 150px; padding: 0 10px; border: 1px solid #ccc; background: #eee; text-align: left; }	

	#sidePanel h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;	
	}
	
	.external-event {
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
	}
	
	#sidePanel p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
	}
	
	#sidePanel p input {
		margin: 0;
		vertical-align: middle;
	}

	#calendarWrap {
		width: 1100px;
		margin: 0 auto;
	}
	*/
	
	
</style>


<style type="text/css" id="dynamic_css"></style>

<script language="javascript">

$(document).ready(function() {
	//alert('doSomething');
	$("#timezones").hide();
	//$("#timezone-list").hide();
	//$("#timezone-label").hide();
	tz_init();
	//alert('didSomething');

	//var top = $(document).height() / 2;
	//var left = $(document).width() / 2;
	//var indent = $(document).width() / 4; 

	//$("#progess-overlay").addClass("progess-overlay");
	//$("#progess-overlay").css('height',$(document).height());
	//$("#progess-overlay").css('width',$(document).width());
	
		
	//$("#progressbarContainer").css('top', top);
	//$("#progressbarContainer").css('left', left - indent);
	
	/*

	setTimeout(function() { // making IE happy (and sometimes Safari)
        //alert('doSomething');
		initiateSchedulerModule();
    }, 500);
	
	
	*/

});


	
var courses = new Array(); // initializing the javascript array
var schedColors = new Array();
var availColors = <?= count_records('scheduler_colormap','enabled',1); ?>;

function checkAvailableColors(){

	//alert("countAvailColors: "+countAvailColors());
	availColors = countAvailColors();
}
/*
function getAvailableColors(){
	
	 return getAvailColors();
}
*/

<?php

	// Google calendar Feed
	//$calendarfeed = getGoogleCalURL($USER->username);
	$newuser = get_record('user','username',$USER->username);
	$userprofile = get_record('user_info_data','fieldid',7,'userid',$newuser->id);
	$calendarfeed = $userprofile->data;

	//Colors
	$stack = array();
	$colors = get_records('scheduler_colormap','enabled',1); 
	foreach ($colors as $color)
	{
		array_push($stack, $color->colorcode);		
	}
	$color_str = implode(",", $stack);
	
	//Courses
	
	/*
	$stack = array();
	$courses = get_my_courses($USER->id, 'visible DESC,sortorder ASC', '*', false, 0);
	
	$roles = get_records('role_assignments','userid',$USER->id,'timemodified ASC');
	
	foreach ($roles as $role)
	{
		$context = get_record('context','id',$role->contextid,'contextlevel',50);
		$course =  get_record('course','id',$context->instanceid);
		array_push($stack, $course->fullname);	
	}
	
	$course_str = implode(",", $stack);
	*/
	
	/*
	//$courses = get_my_courses($USER->id, 'enrolstartdate ASC', '*', false, 0);
	$courses = get_my_courses($USER->id, 'visible DESC, sortorder ASC', '*', false, 0);
	
	foreach ($courses as $course)
	{
		array_push($stack, $course->fullname);		
	}
	$course_str = implode(",", $stack);
	*/
	
	$usersArr = array();
	//$users = get_records("user", "confirmed", 1 , "deleted", 0);

	
	//Role
	$sql = "SELECT * FROM {$CFG->prefix}role_assignments WHERE userid =".$USER->id;
	$role_assignment = get_record_sql($sql);
	$sql2 = "SELECT * FROM {$CFG->prefix}role WHERE id = ".$role_assignment->roleid;
	$role_obj = get_record_sql($sql2);
	$role = $role_obj->shortname;
	
	//Users...
	if($role=='admin')
	{
	
		//Users
		$usersArr = array();
		//$sql = "SELECT * FROM {$CFG->prefix}user";
		//$sql = "SELECT * FROM {$CFG->prefix}user WHERE username NOT IN('Guest')";
		//$users = get_records_sql($sql);
		$users = get_users_listing("username");
	
	
		// Added by SMS: 8/7/2011
		// To provide admin with the view of schedules for all the students.
		array_push($usersArr, "ALL_STUDENTS");

		foreach ($users as $user)
		{
			array_push($usersArr, $user->username);		
		}
			
		$users_str = implode(",", $usersArr);		
		
	}

?>




</script>

<form id="moodleInfo">

<?php 
	if($role=='admin')
	{
		echo '<input id="usersList" type="hidden" value="'.$users_str.'" />';
	}
?>
<input id ="colorList" type="hidden" value="<?=$color_str?>" />
<input id ="coursesList" type="hidden" value="<?=$course_str?>" />
<input id ="username" type="hidden" value="<?=$USER->username?>" />
<input id ="role" type="hidden" value="<?=$role?>" />
<input id ="calendarfeed" type="hidden" value="<?=$calendarfeed?>" />

<?php

/*	$user = profile_user_record($USER->id);
	if (!empty($user->zone)) {
		$timezone = $user->zone;
	}else{
		$timezone = "None";
	}
	
	echo "timezone: ".$timezone."<br/>";
	
	$user->state = "FL";
	$user->companyName = "None";
	$user->website = "None";
	$user->zone = "GMT-05:00 America/Nassau";
	
	//print_r($user);
	
	$theuser = clone($USER);

	update_profile_fields($theuser, $user);
	
	
	function update_profile_fields($user, $data){
		
		profile_load_data($user);
		
		$user->profile_field_state = $data->state;
		$user->profile_field_companyName = $data->companyName;
		$user->profile_field_website = $data->website;
		$user->profile_field_zone = $data->zone;
		
		echo "<br/>-".$data->zone."-";
		
		profile_save_data($user);	
		
	}
	
	*/

	
	
?>
</form>

<br />
<div id='debug'></div>
<div class="wrapper">
    
    <div id="tabs">
        <ul id="tabs-labels">
            <li><a href="#tabs-1">Calendar</a></li>
        </ul>
        <div id="tabs-1">
            <div id="timezones"></div>
            <br />	
            <div id="users"></div>
            <br />	
            <div id="filters"></div>
            
            <div id="calendarWrap">
    			<div id="calendar"></div>
	      		<div id="sidePanel"></div>
            	<div style='clear:both'></div>
            </div>
                
    		
        </div>	
    </div>
    
    <div id='menu-wrap'></div>
		
	<div id='dialogboxes'>
		<div id="edit-recur-event-dialog" title="Edit Calendar Recurring Event"> </div>
		<div id="edit-event-dialog" title="Edit Existing Calendar Event">  </div>
		<div id="create-event-dialog" title="Create New Calendar Event">  </div>
		<div id="add-host-dialog" title="Add New Host">  </div>
        
        <div id="progressbarContainer" tabindex="-1">
        	<center><div id="progressbar"></div></center>
        </div>
        <div id="progess-overlay" style="z-index: 5000; position: absolute;"></div>
	</div>
			
	<div id='debug1'></div>
	<div id='debug2'></div>

</div><!-- End wrapper -->

<?php

//**********************************************************************************************
/// Finish the page
print_footer($course);

?>
