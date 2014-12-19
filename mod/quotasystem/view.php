<?php
// $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of quotasystem
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/quotasystem
 */
/// (Replace quotasystem with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a = optional_param('a', 0, PARAM_INT);  // quotasystem instance ID

if ($id) {
    if (!$cm = get_coursemodule_from_id('quotasystem', $id)) {
        error('Course Module ID was incorrect');
    }

    if (!$course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (!$quotasystem = get_record('quotasystem', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }
} else if ($a) {
    if (!$quotasystem = get_record('quotasystem', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (!$course = get_record('course', 'id', $quotasystem->course)) {
        error('Course is misconfigured');
    }
    if (!$cm = get_coursemodule_from_instance('quotasystem', $quotasystem->id, $course->id)) {
        error('Course Module ID was incorrect');
    }
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, "quotasystem", "view", "view.php?id=$cm->id", "$quotasystem->id");

/// Print the page header
$strquotasystems = get_string('modulenameplural', 'quotasystem');
$strquotasystem = get_string('modulename', 'quotasystem');

$navlinks = array();
$navlinks[] = array('name' => $strquotasystems, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($quotasystem->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($quotasystem->name), '', $navigation, '', '', true,
        update_module_button($cm->id, $course->id, $strquotasystem), navmenu($course, $cm));

/// Print the main part of the page
//**********************************************************************************************
// Quota System

//User logged in
session_start();
$_SESSION["userid"] = $USER->id; 
$_SESSION["timeZone"] = $USER->zone;        
//Role
$query = "SELECT * FROM {$CFG->prefix}role WHERE id IN "."(SELECT roleid FROM {$CFG->prefix}role_assignments WHERE userid = ".$USER->id.")";
$role_obj = get_record_sql($query);

$role = $role_obj->shortname;
$_SESSION["role"] = $role;
?>

<!-- CSS -->
<link type="text/css" href="jquery/css/custom-theme/jquery-ui-1.8.6.custom.css" rel="stylesheet" />
<link type="text/css" href="jquery/dataTables/media/css/demo_table_jui.css" rel="stylesheet" />
<link type="text/css" href="jquery/css/jquery-ui-timepicker.css" rel="stylesheet" />
<link type="text/css" href="css/reports.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link type="text/css" href="css/validation.css" rel="stylesheet" />

<!-- js Libraries -->
<script type="text/javascript" src="jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="jquery/jquery-ui-1.8.4.custom.min.js"></script>
<script type='text/javascript' src='jquery/dataTables/media/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='js/DateFormat.js'></script>
<script type='text/javascript' src='js/date.js'></script>
<script type="text/javascript" src="jquery/jquery.ui.timepicker.js"></script>

<!-- Reports (flot) -->
<script type="text/javascript" src="jquery/flot/excanvas.js"></script>
<script type="text/javascript" src="jquery/flot/jquery.flot.js"></script>
<script type="text/javascript" src="jquery/flot/jquery.flot.navigate.js"></script>
<script type="text/javascript" src="jquery/flot/jquery.flot.selection.js"></script>
<script type="text/javascript" src="jquery/flot/jquery.flot.stack.js"></script>

<!-- LiveValidation -->
<script type="text/javascript" src="js/livevalidation/livevalidation.js"></script>

<!-- Our scripts -->
<script type='text/javascript' src='js/timezone.js'></script>
<script type='text/javascript' src='js/loading.js'></script>
<script type='text/javascript' src='js/credits.js'></script>
<script type='text/javascript' src='js/transactions.js'></script>
<script type='text/javascript' src='js/policies.js'></script>
<script type='text/javascript' src='js/reports.js'></script>

<div id="wrapper" >
    <div id="tabs">
    	<ul id="tabsul">
    	<?php
    		if($role == "admin"){
    			echo '<li><a href="#policiesTab" id="policiesTabLink"><span>Policies</span></a></li>'."\n";
    			echo '<li><a href="#creditsTab" id="creditsTabLink"><span>Credit Types</span></a></li>'."\n";
    			// SMS: 3/15/2012
    			// echo '<li><a href="#currentReportTab" id="currentReportTabLink"><span>Current Period Reports</span></a></li>'."\n";
    			// echo '<li><a href="#histReportTab" id="histReportTabLink"><span>All-periods Reports</span></a></li>'."\n";
    			//
    		}else{
    			echo '<li><a href="#currentReportTab" id="currentReportTabLink"><span>Current Period Report</span></a></li>'."\n";
    			echo '<li><a href="#histReportTab" id="histReportTabLink"><span>All-periods Report</span></a></li>'."\n";
    		}
    	?>
    	</ul>
    	<?php
    		if($role == "admin"){
    			echo '<div id="policiesTab">'."\n";
		        echo '	<div class="container">'."\n";
		        echo '		<p class="tableTop">'."\n";
		        echo '			<span class="pageTitle">Policies</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<span class="addButton"><button id="add-policy">Add Policy</button></span>'."\n";
		        echo '		<div class="messageContainer" style="display:none"></div>'."\n";
		        echo '		<div id="addPolicyForm" class="addForm" style="display:none"></div>'."\n";
		        echo '		<div id="policiesTableContainer"></div>'."\n";
		        echo '	</div>'."\n";
		        echo '</div>'."\n";

		        echo '<div id="creditsTab">'."\n";
		        echo '	<div class="container">'."\n";
		        echo '		<p class="tableTop">'."\n";
		        echo '			<span class="pageTitle">Credit Types</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<span class="addButton"><button id="add-creditType" >Add Credit Type</button></span>'."\n";
		        echo '		<div class="messageContainer" style="display:none"></div>'."\n";
		        echo '		<div id="addCreditTypeForm" class="addForm" style="display:none"></div>'."\n";
		        echo '		<div id="creditsTableContainer"></div>'."\n";
		        echo '	</div>'."\n";
		        echo '</div>'."\n";

		        // SMS: 3/14/2012
		        /*
		        echo '<div id="currentReportTab">'."\n";
		        echo '	<div class="container">'."\n";
		        echo '		<p class="tableTop">'."\n";
		        echo '			<span class="pageTitle">Current Period Reports</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="radios">'."\n";
		        echo '			<input type="radio" name="currentView" value="byCourse" checked="checked" /><span>By Course</span>'."\n";
		        echo '			<input type="radio" name="currentView" value="byStudent" /><span>By Student</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="byCourseSelects select breadcrumb">'."\n";
		        echo '			<span>Course: </span><select class="course"><option value="all">All</option></select>'."\n";
		        echo '			<span class="breadcrumb"></span>'."\n";
		        echo '			<span class="loadingId"></span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="byStudentSelects select breadcrumb">'."\n";
		        echo '			<span>Student: </span><select class="student"><option value="none">--Select a student--</option></select>'."\n";
		        echo '			<span> >> Course: </span><select class="course"><option value="all">All</option></select>'."\n";
		        echo '			<span class="breadcrumb"></span>'."\n";
				echo '			<span class="loadingId"></span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<div id="currentReport">'."\n";
		        echo '    		<div class="chart" ></div>'."\n";
		        echo '			<div class="panLeft"></div>'."\n";
		        echo '			<div class="panRight"></div>'."\n";
		        echo '			<div class="sorter">'."\n";
		        echo '				<div class="checkPercentage">'."\n";
		        echo '					<input type="checkbox" name="currentPercentage" checked="checked" /><span>Percentage</span>'."\n";
		        echo '				</div>'."\n";
		        echo '				<ul class="sortable"></ul>'."\n";
		        echo '				<div class="ascDescDiv" id="currentAscDescDiv">'."\n";
				echo '					<p><span>Sort by: </span><select class="sortBy"></select></p>'."\n";
		        echo '					<p><input type="radio" name="currentAscDesc" value="asc"  checked="checked"/><span>Ascendant</span></p>'."\n";
		        echo '					<p><input type="radio" name="currentAscDesc" value="desc"/><span>Descendant</span></p>'."\n";
		        echo '				</div>'."\n";
		        echo '			</div>'."\n";
		        echo '			<div style="clear:both" id="debugC"></div>'."\n";
		        echo '		</div>'."\n";
		        echo '	</div>'."\n";
		        echo '</div>'."\n";

		        echo '<div id="histReportTab">'."\n";
		        echo '	<div class="container">'."\n";
		        echo '		<p class="tableTop">'."\n";
		        echo '			<span class="pageTitle">All-periods Reports</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="radios">'."\n";
		        echo '			<input type="radio" name="histView" value="byCourse" checked="checked"/><span>By Course</span>     '."\n";
		        echo '			<input type="radio" name="histView" value="byStudent" /><span>By Student</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="byCourseSelects select breadcrumb">'."\n";
		        echo '			<span>Course: </span><select class="course"><option value="all">All</option></select>'."\n";
		        echo '			<span class="breadcrumb level1"></span>'."\n";
		        echo '			<span class="breadcrumb level2"></span>'."\n";
				echo '			<span class="loadingId"></span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="byStudentSelects select breadcrumb">'."\n";
		        echo '			<span>Student: </span><select class="student"><option value="none">--Select a student--</option></select>'."\n";
		        echo '			<span> >> Course: </span><select class="course"><option value="all">All</option></select>'."\n";
		        echo '			<span class="breadcrumb"></span>'."\n";
				echo '			<span class="loadingId"></span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<div id="histReport">'."\n";
		        echo '    		<div class="chart" ></div>'."\n";
		        echo '			<div class="panLeft"></div>'."\n";
		        echo '			<div class="panRight"></div>'."\n";
		        echo '			<div class="sorter">'."\n";
		        echo '				<div class="checkPercentage">'."\n";
		        echo '					<input type="checkbox" name="histPercentage" checked="checked" /><span>Percentage</span>'."\n";
		        echo '				</div>'."\n";
		        echo '				<ul class="sortable"></ul>'."\n";
		        echo '				<div class="ascDescDiv" id="histAscDescDiv">'."\n";
				echo '					<p><span>Sort by: </span><select class="sortBy"></select></p>'."\n";
		        echo '					<p><input type="radio" name="histAscDesc" value="asc" checked="checked"/><span>Ascendant</span></p>'."\n";
		        echo '					<p><input type="radio" name="histAscDesc" value="desc"/><span>Descendant</span></p>'."\n";
		        echo '				</div>'."\n";
		        echo '			</div>'."\n";
		        echo '			<div style="clear:both" id="debugH"></div>'."\n";
		        echo '		</div>'."\n";
		        echo '	</div>'."\n";
		        echo '</div>'."\n";
		        */


    		}else{
		        echo '<div id="currentReportTab">'."\n";
		        echo '	<div class="container">'."\n";
		        echo '		<p class="tableTop">'."\n";
		        echo '			<span class="pageTitle">Current Period Report</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="byCourseSelects select breadcrumb">'."\n";
		        echo '			<span>Course: </span><select class="course"><option value="all">All</option></select>'."\n";
		        echo '			<span class="breadcrumb"></span>'."\n";
				echo '			<span class="loadingId"></span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<div id="currentReport">'."\n";
		        echo '    		<div class="chart" ></div>'."\n";
		        echo '			<div class="panLeft"></div>'."\n";
		        echo '			<div class="panRight"></div>'."\n";
		        echo '			<div class="sorter">'."\n";
		        echo '				<div class="checkPercentage">'."\n";
		        echo '					<input type="checkbox" name="currentPercentage" checked="checked" /><span>Percentage</span>'."\n";
		        echo '				</div>'."\n";
		        echo '				<ul class="sortable"></ul>'."\n";
		        echo '				<div class="ascDescDiv" id="currentAscDescDiv">'."\n";
				echo '					<p><span>Sort by: </span><select class="sortBy"></select></p>'."\n";
		        echo '					<p><input type="radio" name="currentAscDesc" value="asc"  checked="checked"/><span>Ascendant</span></p>'."\n";
		        echo '					<p><input type="radio" name="currentAscDesc" value="desc"/><span>Descendant</span></p>'."\n";
		        echo '				</div>'."\n";
		        echo '			</div>'."\n";
		        echo '			<div style="clear:both" id="debugC"></div>'."\n";
		        echo '		</div>'."\n";
		        echo '	</div>'."\n";
		        echo '</div>'."\n";

		        echo '<div id="histReportTab">'."\n";
		        echo '	<div class="container">'."\n";
		        echo '		<p class="tableTop">'."\n";
		        echo '			<span class="pageTitle">All-periods Report</span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<p class="byCourseSelects byStudentSelects select breadcrumb">'."\n";
		        echo '			<span>Course: </span><select class="course"><option value="all">All</option></select>'."\n";
		        echo '			<span class="breadcrumb"></span>'."\n";
				echo '			<span class="loadingId"></span>'."\n";
		        echo '		</p>'."\n";
		        echo '		<div id="histReport">'."\n";
		        echo '    		<div class="chart" ></div>'."\n";
		        echo '			<div class="panLeft"></div>'."\n";
		        echo '			<div class="panRight"></div>'."\n";
		        echo '			<div class="sorter">'."\n";
		        echo '				<div class="checkPercentage">'."\n";
		        echo '					<input type="checkbox" name="histPercentage" checked="checked" /><span>Percentage</span>'."\n";
		        echo '				</div>'."\n";
		        echo '				<ul class="sortable"></ul>'."\n";
		        echo '				<div class="ascDescDiv" id="histAscDescDiv">'."\n";
		        echo '					<p><span>Sort by: </span><select class="sortBy"></select></p>'."\n";
		        echo '					<p><input type="radio" name="histAscDesc" value="asc" checked="checked"/><span>Ascendant</span></p>'."\n";
		        echo '					<p><input type="radio" name="histAscDesc" value="desc"/><span>Descendant</span></p>'."\n";
		        echo '				</div>'."\n";
		        echo '			</div>'."\n";
		        echo '			<div style="clear:both" id="debugH"></div>'."\n";
		        echo '		</div>'."\n";
		        echo '	</div>'."\n";
		        echo '</div>'."\n";


    		}
    	?>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#page").css("min-width","940px");
		tz_init();
    	<?php
			echo "\n";
			if($role == "admin"){
				echo "\t\t".'pol_init();'."\n";
		        echo "\t\t".'ct_init();'."\n";
		        // SMS: 3/14/2012
		        // echo "\t\t".'initCurrentReport("");'."\n";
		        // echo "\t\t".'initHistoricReport("");'."\n";
		        //
			}else{
				echo "\t\t".'initCurrentReport("'.$USER->id.'");'."\n";
		        echo "\t\t".'initHistoricReport("'.$USER->id.'");'."\n";
			}
			echo "\n";
		?>
		$('#tabs').tabs();
	});
</script>
<?php
//**********************************************************************************************
/// Finish the page
print_footer($course);
?>
