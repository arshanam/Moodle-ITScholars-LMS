<?php  // $Id: index.php,v 1.201.2.10 2009/04/25 21:18:24 stronk7 Exp $
       // index.php - the front page.

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////


    if (!file_exists('./config.php')) {
        header('Location: install.php');
        die;
    }

    require_once('config.php');
    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');
    require_once($CFG->dirroot .'/mod/quiz/locallib.php');

    if (empty($SITE)) {
        redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
    }

    // Bounds for block widths
    // more flexible for theme designers taken from theme config.php
    $lmin = (empty($THEME->block_l_min_width)) ? 100 : $THEME->block_l_min_width;
    $lmax = (empty($THEME->block_l_max_width)) ? 210 : $THEME->block_l_max_width;
    $rmin = (empty($THEME->block_r_min_width)) ? 100 : $THEME->block_r_min_width;
    $rmax = (empty($THEME->block_r_max_width)) ? 210 : $THEME->block_r_max_width;

    define('BLOCK_L_MIN_WIDTH', $lmin);
    define('BLOCK_L_MAX_WIDTH', $lmax);
    define('BLOCK_R_MIN_WIDTH', $rmin);
    define('BLOCK_R_MAX_WIDTH', $rmax);

    // check if major upgrade needed - also present in login/index.php
    if ((int)$CFG->version < 2006101100) { //1.7 or older
        @require_logout();
        redirect("$CFG->wwwroot/$CFG->admin/");
    }
    // Trigger 1.9 accesslib upgrade?
    if ((int)$CFG->version < 2007092000
        && isset($USER->id)
        && is_siteadmin($USER->id)) { // this test is expensive, but is only triggered during the upgrade
        redirect("$CFG->wwwroot/$CFG->admin/");
    }

    if ($CFG->forcelogin) {
        require_login();
    } else {
        user_accesstime_log();
    }

    if ($CFG->rolesactive) { // if already using roles system
        if (has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {
            if (moodle_needs_upgrading()) {
                redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
            }
        } else if (!empty($CFG->mymoodleredirect)) {    // Redirect logged-in users to My Moodle overview if required
            if (isloggedin() && $USER->username != 'guest') {
                redirect($CFG->wwwroot .'/my/index.php');
            }
        }
    } else { // if upgrading from 1.6 or below
        if (isadmin() && moodle_needs_upgrading()) {
            redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
        }
    }


    if (get_moodle_cookie() == '') {
        set_moodle_cookie('nobody');   // To help search for cookies on login page
    }

    if (!empty($USER->id)) {
        add_to_log(SITEID, 'course', 'view', 'view.php?id='.SITEID, SITEID);
    }

    if (empty($CFG->langmenu)) {
        $langmenu = '';
    } else {
        $currlang = current_language();
        $langs = get_list_of_languages();
        $langlabel = get_accesshide(get_string('language'));
        $langmenu = popup_form($CFG->wwwroot .'/index.php?lang=', $langs, 'chooselang', $currlang, '', '', '', true, 'self', $langlabel);
    }

    $PAGE       = page_create_object(PAGE_COURSE_VIEW, SITEID);
    $pageblocks = blocks_setup($PAGE);
    $editing    = $PAGE->user_is_editing();
    $preferred_width_left  = bounded_number(BLOCK_L_MIN_WIDTH, blocks_preferred_width($pageblocks[BLOCK_POS_LEFT]),
                                            BLOCK_L_MAX_WIDTH);
    $preferred_width_right = bounded_number(BLOCK_R_MIN_WIDTH, blocks_preferred_width($pageblocks[BLOCK_POS_RIGHT]),
                                            BLOCK_R_MAX_WIDTH);

   //Build Home Page Navigation - depending on login status
    if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
       // Logged-In
       //$navlinks = array(array('name' => "Home", 'link' => null, 'type' => 'misc'));
       //$navlinks = array(array('name' => "Home", 'link' => null, 'type' => 'misc'));
       
       $navlinks[] = array('name' => "Scheduled Activities", 'link' => "$CFG->wwwroot/schedule.php", 'type' => 'title');
       $navlinks[] = array('name' => "Clear Exams", 'link' => null, 'type' => 'misc');
       $navigation = build_navigation($navlinks);
    } else {
        // Logged-Out
        $loginsite = get_string("loginsite");
        $navlinks = array(array('name' => $loginsite, 'link' => null, 'type' => 'misc'));
        $navigation = build_navigation($navlinks);
    }
    print_header($SITE->fullname, $SITE->fullname, $navigation, '',
                 '<meta name="description" content="'. strip_tags(format_text($SITE->summary, FORMAT_HTML)) .'" />',
                 true, '', user_login_string($SITE).$langmenu);



//-- W3Schools

$dbhost = 'localhost';
$dbuser = 'portal';
$dbpass = 'k4se*prt4l';
$dbname = 'moodle';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$conn){
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbname, $conn);

//mysql_query("DELETE FROM mdl_instructions WHERE LastName='Griffin'");
function clearExamTables($id){
    //delete_records('quiz_course_activation','courseid',$id);
    //delete_records('exam_quiz_context','courseid',$id);
    //delete_records('quiz_attempts','course',$id);
}



/*
mysql_query("ALTER TABLE mdl_instructions ADD quizid Int(10)");
$rc = mysql_affected_rows();
echo "Records affected: " . $rc;
*/

/*
mysql_query("UPDATE mdl_instructions SET quizid=3 WHERE courseid=5");
$rc = mysql_affected_rows();
echo "Records affected: " . $rc."<br/>";
*/

/*
mysql_query("UPDATE mdl_instructions SET active=NULL WHERE id=20");
$rc = mysql_affected_rows();
echo "Records affected: " . $rc."<br/>";
*/

/*

$result = mysql_query("SELECT * FROM mdl_exam_quiz_context");

echo "<table border='1'>
<tr>
<th>id</th>
<th>courseid</th>
<th>quizid</th>
<th>contextid</th>
<th>instr1_id</th>
<th>instr2_id</th>
</tr>";

while($row = mysql_fetch_array($result)){
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['courseid'] . "</td>";
    echo "<td>" . $row['quizid'] . "</td>";
    echo "<td>" . $row['contextid'] . "</td>";
    echo "<td>" . $row['instr1_id'] . "</td>";
    echo "<td>" . $row['instr2_id'] . "</td>";
    echo "</tr>";
}

echo "</table>";
*/




$result = mysql_query("SELECT * FROM mdl_instructions");

echo "<table border='1'>
<tr>
<th>id</th>
<th>typeid</th>
<th>count</th>
<th>courseid</th>
<th>quizid</th>
<th>ACTIVE</th>
</tr>";

while($row = mysql_fetch_array($result)){
    echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "<td>" . $row['typeid'] . "</td>";
  echo "<td>" . $row['count'] . "</td>";
  echo "<td>" . $row['courseid'] . "</td>";
  echo "<td>" . $row['quizid'] . "</td>";
  echo "<td>" . $row['active'] . "</td>";
  echo "</tr>";
}

echo "</table>";


$result = mysql_query("SELECT * FROM mdl_instructions_type");

echo "<table border='1'>
<tr>
<th>id</th>
<th>name</th>
<th>count</th>
<th>courseid</th>
</tr>";

while($row = mysql_fetch_array($result)){
    echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "<td>" . $row['name'] . "</td>";
  echo "<td>" . $row['count'] . "</td>";
  echo "<td>" . $row['courseid'] . "</td>";
  echo "</tr>";
}

echo "</table>";



/*
$result = mysql_query("SELECT DISTINCT quizid FROM mdl_instructions WHERE quizid IS NOT NULL");
$list = "";
$i=0;
while($row = mysql_fetch_array($result)){
    $list .= $row['quizid'].",";
    //$usedquiz[$i] = $row['quizid'];
    //$i++;
}

$list = substr($list,0,strlen($list)-1);
echo "SELECT * FROM mdl_quiz WHERE course = 10 AND id NOT IN ($list)"."<br/>";
$sql = "SELECT * FROM mdl_quiz WHERE course = 10 AND id NOT IN ($list)";

$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
    echo $row['id']."<br/>";
}

*/
mysql_close($conn);

echo "<br/><br/>";





/*
$sql = "SELECT * FROM mdl_quiz WHERE course = 10 AND NOT IN ($list)";
echo $sql."<br/>";
$quizzes = get_records_sql("SELECT * FROM mdl_quiz WHERE course = 10 AND id NOT IN (SELECT quizid FROM mdl_instructions WHERE quizid IS NOT NULL)");
//$quizzes = get_records_sql($sql);

echo "NEW:<br/>";
    foreach ($quizzes as $record) {
        echo $record->id."<br/>";
    }

*/

//$cnt = count_records('instructions','courseid',10,'typeid',7, 'quizid',19);
//echo "COUNT:$cnt<br/>";





echo "<table border='1'>
<tr>
<th>id</th>
<th>courseid</th>
<th>quizid</th>
<th>username</th>
<th>url</th>
<th>starttime</th>
<th>endtime</th>
<th>status</th>
<th>exam_context</th>
</tr>";
$records = get_records('quiz_course_activation','courseid','10','id');
foreach($records as $record){
    echo "<tr>";
  echo "<td>".$record->id."</td>";
  echo "<td>".$record->courseid."</td>";
  echo "<td>".$record->quizid."</td>";
  echo "<td>".$record->username."</td>";
  echo "<td>".$record->url."</td>";
  echo "<td>".$record->starttime."</td>";
  echo "<td>".$record->endtime."</td>";
  echo "<td>".$record->status."</td>";
  echo "<td>".$record->exam_context."</td>";
  echo "</tr>";
}
echo "</table>";

echo "<br/><br/>";

echo "<table border='1'>
<tr>
<th>id</th>
<th>courseid</th>
<th>quizid</th>
<th>contextid</th>
<th>instr1_id</th>
<th>instr2_id</th>
</tr>";
$records = get_records('exam_quiz_context','courseid','10','id');
foreach($records as $record){
    echo "<tr>";
  echo "<td>".$record->id."</td>";
  echo "<td>".$record->courseid."</td>";
  echo "<td>".$record->quizid."</td>";
  echo "<td>".$record->contextid."</td>";
  echo "<td>".$record->instr1_id."</td>";
  echo "<td>".$record->instr2_id."</td>";
  echo "</tr>";
}
echo "</table>";


//assignInstructions($course,$typeid,$quizid)

$_cid = 10;
$_qid = 19;
echo "Course: $_cid -> Quiz: $_qid<br/>";
echo "INSTR1:".assignInstructions($_cid,1,$_qid)."<br/>";
echo "INSTR2:".assignInstructions($_cid,2,$_qid)."<br/><br/>";

$_cid = 10;
$_qid = 20;
echo "Course: $_cid -> Quiz: $_qid<br/>";
echo "INSTR1:".assignInstructions($_cid,1,$_qid)."<br/>";
echo "INSTR2:".assignInstructions($_cid,2,$_qid)."<br/><br/>";

$_cid = 10;
$_qid = 21;
echo "Course: $_cid -> Quiz: $_qid<br/>";
echo "INSTR1:".assignInstructions($_cid,1,$_qid)."<br/>";
echo "INSTR2:".assignInstructions($_cid,2,$_qid)."<br/><br/>";

$_cid = 10;
$_qid = 22;
echo "Course: $_cid -> Quiz: $_qid<br/>";
echo "INSTR1:".assignInstructions($_cid,1,$_qid)."<br/>";
echo "INSTR2:".assignInstructions($_cid,2,$_qid)."<br/><br/>";


    print_footer('home');     // Please do not modify this line
?>

<?php


function assignInstructions($course,$typeid,$quizid){
    $type = get_record('instructions_type','courseid',$course,'count',$typeid);
    $id = 0;

    echo "TYPEID:$type->id  REC: <br/>";

    $record = get_record('instructions','courseid',$course,'typeid',$type->id, 'quizid',$quizid);
    $id = $record->id;
/*
    // Set Instruction to Active Status
        if($record->active == NULL){
        $record->active = 1;
    }else{
        $record->active = $record->active + 1;
    }
    update_record('instructions',$record);
    */
    return $id;
}

?>
