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

    if (isloggedin() and !isguest()) {
        if(!iscreator() and !isteacherinanycourse()){
            redirect($CFG->wwwroot .'/index.php');
        }
    } else {
        redirect($CFG->wwwroot .'/index.php');
    }

    // get values from form for actions on this page
    $param = new stdClass();

    // Added: Parameters for Special Course Form
    $param->register = optional_param('register', null, PARAM_INT);
    $param->courseid = optional_param('courseid', 0, PARAM_INT);
    $param->quizid = optional_param('quizid', 0, PARAM_INT);
    // Masoud Sadjadi: PARAM_ALPHANUM to PARAM_RAW to support emails as usernames. 
    // $param->username = optional_param('username', 0, PARAM_ALPHANUM);
    $param->username = optional_param('username', 0, PARAM_RAW);
    $param->url = optional_param('url', "", PARAM_RAW);
    $param->starttime = optional_param('starttime', 0, PARAM_INT);
    $param->endtime = optional_param('endtime', 0, PARAM_INT);
    $param->test = optional_param('test', null, PARAM_INT);
    $param->timeselect = optional_param('timeselect', null, PARAM_INT);
    $param->current = optional_param('current', null, PARAM_INT);
    // For Date-Times
    $param->shour = optional_param('shour', 0, PARAM_INT);
    $param->smin = optional_param('smin', 0, PARAM_INT);
    $param->smon = optional_param('smon', 0, PARAM_INT);
    $param->sday = optional_param('sday', 0, PARAM_INT);
    $param->syear = optional_param('syear', 0, PARAM_INT);
    $param->ehour = optional_param('ehour', 0, PARAM_INT);
    $param->emin = optional_param('emin', 0, PARAM_INT);
    $param->emon = optional_param('emon', 0, PARAM_INT);
    $param->eday = optional_param('eday', 0, PARAM_INT);
    $param->eyear = optional_param('eyear', 0, PARAM_INT);


    /*
     *
    $param->register = optional_param('register', null, PARAM_INT);
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->quizid = required_param('quizid', PARAM_INT);
    $param->username = required_param('username', PARAM_ALPHANUM);
    $param->url = required_param('url', PARAM_RAW);
    $param->starttime = required_param('starttime', PARAM_INT);
    $param->endtime = required_param('endtime', PARAM_INT);
    $param->test = optional_param('test', null, PARAM_INT);
    $param->timeselect = optional_param('timeselect', null, PARAM_INT);
    $param->current = optional_param('current', null, PARAM_INT);
     */

    print_header($SITE->fullname, $SITE->fullname, 'Register');

    //$param->courseid = 2;
    //$param->quizid = 1;
    //$param->username = "user";
    //$param->url = "http://localhost:8888/moodle20/";
    //$param->starttime =
    //$param->endtime =

    //$course = $DB->get_record('course', array('id'=>$param->courseid));

?>
<script type="text/javascript">

// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

//var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' //SSI method of getting server date
var currenttime = '<? print date("F d, Y H:i:s", time())?>' //PHP method of getting server date

///////////Stop editting here/////////////////////////////////

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
//document.getElementById("servertime").innerHTML=datestring+" "+timestring
document.getElementById("servertime").innerHTML=timestring
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

</script>

<!-- <p><b>Current Server Time:</b> <span id="servertime"></span></p> -->
<font size='1' color='gray'>&nbsp;&nbsp; Current Time:&nbsp;&nbsp;</font><br/>
<font size='3' face='Arial'>&nbsp;&nbsp; <b><span id="servertime"></span></b></font><br/><br/>
<?php


// Exam Status
$status = "Registered";
$enrolled = "";

if(!empty($param->register)){
    if(record_exists('user','username',$param->username)){

        if(record_exists('course','id',$param->courseid)){

            $course = get_record('course','id',$param->courseid);
            $newuser = get_record('user','username',$param->username);

            // Enroll the user if not enrolled
            if ($course->id != SITEID) {
                //if (!enrol_into_course($param->courseid, $USER, 'auto')) {
                //if (!enrol_user_into_course($course, $USER, 'auto')) {
                if (!enrol_user_into_course($course, $newuser->id)) {
                    $enrolled = "Could Not be Enrolled - ".$param->username;
                }else{
                    $enrolled = "Enrolled - ".$param->username;
                }

            }
            echo $enrolled."</br>";
            if(record_exists('quiz','id',$param->quizid,'course',$param->courseid)){

                if(empty($param->timeselect)){
                    $record = insert_record('quiz_course_activation',array(
                                                'courseid'=>$param->courseid,
                                                'quizid'=>$param->quizid,
                                                'username'=>$param->username,
                                                'url'=>$param->url,
                                                'starttime'=>$param->starttime,
                                                'endtime'=>$param->endtime,
                                                'status'=>$status
                                            ));
                    setExamInstructions($record);

                    if(!empty($record)){
                        echo "<table border='0' cellpadding='5' cellspacing='5' class='boxwidthwide boxaligncenter generalbox questioncategories contextlevel'>";
                        echo "<tr><td colspan='2'><b>$status</b></td></tr>";
                        echo "<tr><td>Course ID:</td><td>".$param->courseid."</td></tr>";
                        echo "<tr><td>Quiz ID:</td><td>".$param->quizid."</td></tr>";
                        echo "<tr><td>Username:</td><td>".$param->username."</td></tr>";
                        echo "<tr><td>URL:</td><td>".$param->url."</td></tr>";
                        echo("<tr><td>Start:</td><td>".date("M-d-Y h:i:s A", $param->starttime)."</td></tr>");
                        echo("<tr><td>End:</td><td>".date("M-d-Y h:i:s A", $param->endtime)."</td></tr>");
                        echo "</table>";
                    }else{
                        echo "<b>Unable to Register</b><br/>";
                    }

                }else{

                    //Use DateSelector Tool

                    $startdate = date("U",mktime($param->shour,$param->smin,0,$param->smon,$param->sday,$param->syear));
                    $enddate = date("U",mktime($param->ehour,$param->emin,0,$param->emon,$param->eday,$param->eyear));

                    $record = insert_record('quiz_course_activation',array(
                                                'courseid'=>$param->courseid,
                                                'quizid'=>$param->quizid,
                                                'username'=>$param->username,
                                                'url'=>$param->url,
                                                'starttime'=>$startdate,
                                                'endtime'=>$enddate,
                                                'status'=>$status
                                            ));
                    setExamInstructions($record);

                    //echo $startdate." - "."$param->shour,$param->smin,0,$param->smon,$param->sday,$param->syear<br/>";
                    //echo $enddate." - "."$param->ehour,$param->emin,0,$param->emon,$param->eday,$param->eyear<br/>";

                    if(!empty($record)){
                        echo "<table border='0' cellpadding='5' cellspacing='5' class='boxwidthwide boxaligncenter generalbox questioncategories contextlevel'>";
                        echo "<tr><td colspan='2'><b>Registered</b></td></tr>";
                        echo "<tr><td>Course ID:</td><td>".$param->courseid."</td></tr>";
                        echo "<tr><td>Quiz ID:</td><td>".$param->quizid."</td></tr>";
                        echo "<tr><td>Username:</td><td>".$param->username."</td></tr>";
                        echo "<tr><td>URL:</td><td>".$param->url."</td></tr>";
                        echo("<tr><td>Start:</td><td>".date("M-d-Y h:i:s A", $startdate)."</td></tr>");
                        echo("<tr><td>End:</td><td>".date("M-d-Y h:i:s A", $enddate)."</td></tr>");
                        echo "</table>";
                    }else{
                        echo "<b>Unable to Register</b><br/>";
                    }

                    
                }

            }else{
                echo "<b>Unable to Register</b><br/>";
            }
        }else{
            echo "<b>Unable to Register</b><br/>";
        }
    }else{
        echo "<b>Unable to Register</b><br/>";
    }
}else{

    if(!empty($param->current)){
        // Display a List of Entries

        $entries = get_records('quiz_course_activation');

        print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
        echo "<table border='0' cellpadding='5' width='100%'>";
        echo "<tr><th>Course ID</th><th>Quiz ID</th><th>User</th><th>Status</th><th>URL</th><th>Start</th><th>End</th><th>Time</th></tr>";
        foreach($entries as $entry){
            echo "<tr>";
            echo "<td>$entry->courseid</td>";
            echo "<td>$entry->quizid</td>";
            echo "<td>$entry->username</td>";
            echo "<td>$entry->status</td>";
            echo "<td>$entry->url</td>";
            echo "<td>".date("M-d-Y h:i:s A", $entry->starttime)."<br/>".date("U", $entry->starttime)."</td>";
            echo "<td>".date("M-d-Y h:i:s A", $entry->endtime)."<br/>".date("U", $entry->endtime)."</td>";
            echo "<td>".format_time($entry->endtime - $entry->starttime)."</td>";
            //format_time(time_after - time_before)
            echo "</tr>";
        }
        echo "</table>";
        print_box_end();

    }else{

        print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
        echo "<b>Create Exam Entry</b><br/><br/>";
        $options  = array('courseid'=>$course->id);
        print_form_start($url);

        echo "<table border='0' cellpadding='5' cellspacing='5'>";
      
        echo "<tr>";
        echo "<td>Course ID:</td>";
        echo "<td colspan='2'>";
        print_textfield ('courseid', '','',25);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Quiz ID:</td>";
        echo "<td colspan='2'>";
        print_textfield ('quizid', '','',25);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Username:</td>";
        echo "<td colspan='2'>";
        print_textfield ('username', '','',25);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>URL:</td>";
        echo "<td colspan='2'>";
        print_textfield ('url', '','',25);
        echo "</td>";
        echo "</tr>";

        echo "<tr><td></td><td>Date (DD/MM/YYYY)</td><td>Time (HH:MM)</td></tr>";

        echo "<tr>";
        echo "<td>Start Time:</td>";
        echo "<td>";
        print_date_selector("sday", "smon", "syear", 0, false);
        echo "</td>";
        echo "<td>";
        print_time_selector("shour", "smin", 0, 5, false);
        echo "</td>";
        echo "</tr>";

        echo "<tr><td></td><td>Date (DD/MM/YYYY)</td><td>Time (HH:MM)</td></tr>";

        echo "<tr>";
        echo "<td>End Time:</td>";
        echo "<td>";
        print_date_selector("eday", "emon", "eyear", 0, false);
        echo "</td>";
        echo "<td>";
        print_time_selector("ehour", "emin", 0, 5, false);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='3' align='right'>";
        print_button('Create Entry',array('register'=>1,'timeselect'=>1));
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        print_form_end();
        print_box_end();
    }

}

    //$datetime = date_create(now());
    //echo $datetime->format("Y-m-d\TH:i:s\Z");
    //echo getdate();
    //print_r(getdate());
    //$my_t=getdate(date("U"));
    //$my_t=getdate(time());
    //print("$my_t[weekday], $my_t[month] $my_t[mday], $my_t[year]");


    /*
     * hour  	Optional. Specifies the hour
     * minute 	Optional. Specifies the minute
     * second 	Optional. Specifies the second
     * month 	Optional. Specifies the numerical month
     * day      Optional. Specifies the day
     * year 	Optional. Specifies the year.
     *          The valid range for year is on some systems between 1901 and 2038.
     *          However this limitation is overcome in PHP 5
     */

    //echo(date("M-d-Y h:i:s A",mktime(9,0,0,8,19,2009))."<br />");
    //echo(date("l F d, Y h:i:s A",mktime(9,0,0,8,19,2009))."<br />");

    //echo(mktime(9,0,0,8,19,2009)."<br />");
    //echo(mktime(11,0,0,8,19,2009)."<br />");
    //echo(mktime(9,0,0,8,20,2009)."<br />");
    //echo(mktime(11,0,0,8,20,2009)."<br />");

    //echo "<br/>register";

    print_footer('Register');     // Please do not modify this line

?>

<?php
/*
 * <0 --> if date a< date b
 * 0 --> date a== date b
 * >0 --> date a > date b respectively.
 */
function compareDates($start_date,$end_date) {
  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
}

function print_form_start($link, $method='get', $disabled = false, $jsconfirmmessage='', $formid = '') {
    $output = '';
    if ($formid) {
        $formid = ' id="' . s($formid) . '"';
    }
    $link = str_replace('"', '&quot;', $link); //basic XSS protection

    // taking target out, will need to add later target="'.$target.'"
    $output .= '<form action="'. $link .'" method="'. $method .'"' . $formid . '>';
    $output .= '<div>';

    echo $output;
}

function print_form_end() {
    $output = '';

    $output .= '</form></div>';

    echo $output;
}

function print_button($label='OK',$options=null) {
    $output = '';
    $output .= '<br/><div>';
    if ($options) {
        foreach ($options as $name => $value) {
            $output .= '<input type="hidden" name="'. $name .'" value="'. s($value) .'" />';
        }
    }
    $output .= '<input type="submit" value="'. s($label) .'"/></div>';

    echo $output;

}


function enrol_user_into_course($course,$adduser) {
    $sucess = false;
    $extendbase = 0;
    $extendperiod = 0;
    $inmeta = $course->metacourse;
    $hidden = false;
    $roleid = 5;
    $context = get_record("context","contextlevel",50,"instanceid",$course->id);

    if (!$adduser = clean_param($adduser, PARAM_INT)) {
        continue;
    }
    $allow = true;
    if ($inmeta) {
        if (has_capability('moodle/course:managemetacourse', $context, $adduser)) {
            //ok
        } else {
            //$managerroles = get_roles_with_capability('moodle/course:managemetacourse', CAP_ALLOW, $context);
           $allow = false;
        }
    }
    if ($allow) {
        switch($extendbase) {
            case 0:
                $timestart = $course->startdate;
                break;
            case 3:
                $timestart = $today;
                break;
            case 4:
                $timestart = $course->enrolstartdate;
                break;
            case 5:
                $timestart = $course->enrolenddate;
                break;
        }

        if($extendperiod > 0) {
            $timeend = $timestart + $extendperiod;
        } else {
            $timeend = 0;
        }
        if (! role_assign($roleid, $adduser, 0, $context->id, $timestart, $timeend, $hidden)) {
            $errors[] = "Could not add user with id $adduser to this role!";
            echo "ERRORS";
            $sucess = false;
        }else{
            $sucess = true;
        }
    }

    $rolename = get_field('role', 'name', 'id', $roleid);
    add_to_log($course->id, 'role', 'assign', 'admin/roles/assign.php?contextid='.$context->id.'&roleid='.$roleid, $rolename, '', $USER->id);

    return $sucess;
}


function enrol_user_into_course_old($course, $user, $enrol) {

  $timestart = time();
  // remove time part from the timestamp and keep only the date part
  $timestart = make_timestamp(date('Y', $timestart), date('m', $timestart), date('d', $timestart), 0, 0, 0);
  if ($course->enrolperiod) {
      $timeend = $timestart + $course->enrolperiod;
  } else {
      $timeend = 0;
  }

  if ($role = get_default_course_role($course)) {

      $context = get_context_instance(CONTEXT_COURSE, $course->id);

      if (!role_assign($role->id, $user->id, 0, $context->id, $timestart, $timeend, 0, $enrol)) {
          return false;
      }

      // force accessdata refresh for users visiting this context...
      //mark_context_dirty($context->path);

      //email_welcome_message_to_user($course, $user);

      //add_to_log($course->id, 'course', 'enrol','view.php?id='.$course->id, $course->id);

      return true;
  }
  return false;
}

function setExamInstructions($qcaid){

    $qca = get_record('quiz_course_activation','id',$qcaid);

    if(!empty($qca)){

        $intr1 = assignInstructions($qca->courseid,1,$qca->quizid);
        $intr2 = assignInstructions($qca->courseid,2,$qca->quizid);

        // Check if valid instruction
        if($intr1 == 0) $intr1 = NULL;
        if($intr2 == 0) $intr2 = NULL;


        $record_id = insert_record('exam_quiz_context', array(
                                    'courseid'=>$qca->courseid,
                                    'quizid'=>$qca->quizid,
                                    'instr1_id'=>$intr1,
                                    'instr2_id'=>$intr2
                                ));

        $qca->exam_context = $record_id;

        update_record('quiz_course_activation',$qca);

    }
}

function assignInstructions($courseid,$typeid,$quizid){
    $type = get_record('instructions_type','courseid',$courseid,'count',$typeid);
    $id = 0;

    $record = get_record('instructions','courseid',$courseid,'typeid',$type->id, 'quizid',$quizid);

    if(!empty($record)){
        $id = $record->id;

        // Set Instruction to Active Status
        if($record->active == NULL){
            $record->active = 1;
        }else{
            $record->active = $record->active + 1;
        }

        $count = $record->active;

        $sql_str = "UPDATE mdl_instructions SET active=$count WHERE id=$id";

        execute_sql($sql_str, false);
    }

    
    //update_record('instructions',$record);

    return $id;
}

?>
