<?php  // $Id: index.php,v 1.232 2009-05-06 09:15:05 tjhunt Exp $
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
    require_once($CFG->libdir .'/filelib.php');

    // for prepareQuizAttempt Function
    require_once($CFG->dirroot.'/mod/quiz/locallib.php');

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
    if (empty($CFG->version) or (int)$CFG->version < 2009011900 or !empty($CFG->adminsetuppending)) { //1.9 or older
        @require_logout();
        redirect("$CFG->wwwroot/$CFG->admin/");
    }

    if ($CFG->forcelogin) {
        require_login();
    } else {
        user_accesstime_log();
    }

    if (has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {
        if (moodle_needs_upgrading()) {
            redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
        }
    } else if (!empty($CFG->mymoodleredirect)) {    // Redirect logged-in users to My Moodle overview if required
        if (isloggedin() && $USER->username != 'guest') {
            redirect($CFG->wwwroot .'/my/index.php');
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

    $PAGE->set_pagetype('site-index');
    $PAGE->set_course($SITE);
    $PAGE->set_other_editing_capability('moodle/course:manageactivities');
    $PAGE->set_url('');
    $PAGE->set_docs_path('');
    $pageblocks = blocks_setup($PAGE);
    $editing = $PAGE->user_is_editing();
    $preferred_width_left  = bounded_number(BLOCK_L_MIN_WIDTH, blocks_preferred_width($pageblocks[BLOCK_POS_LEFT]),
                                            BLOCK_L_MAX_WIDTH);
    $preferred_width_right = bounded_number(BLOCK_R_MIN_WIDTH, blocks_preferred_width($pageblocks[BLOCK_POS_RIGHT]),
                                            BLOCK_R_MAX_WIDTH);

    //Build Home Page Navigation - depending on login status
    if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
       // Logged-In
       $navlinks = array(array('name' => "Home", 'link' => null, 'type' => 'misc'));
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


    /*
                 print_header($SITE->fullname, $SITE->fullname, 'home', '',
                 '<meta name="description" content="'. strip_tags(format_text($SITE->summary, FORMAT_HTML)) .'" />',
                 true, '', user_login_string($SITE).$langmenu);

     */

?>


<span id="liveclock"></span>

<script language="JavaScript">
<!--

/*
Upper Left Corner Live Clock Script- © Dynamic Drive (www.dynamicdrive.com)
For full source code, 100's more DHTML scripts, and TOS,
visit http://www.dynamicdrive.com
*/

function show5(){
 if (!document.layers&&!document.all&&!document.getElementById)
 return
 var Digital=new Date()
 var hours=Digital.getHours()
 var minutes=Digital.getMinutes()
 var seconds=Digital.getSeconds()
 var dn="AM"
 if (hours>12){
 dn="PM"
 hours=hours-12
 }
 if (hours==0)
 hours=12
 if (minutes<=9)
 minutes="0"+minutes
 if (seconds<=9)
 seconds="0"+seconds
//change font size here to your desire
myclock="<font size='3' face='Arial'><b> <?php //echo date("l F d Y",time())."<br/>"; ?><font size='1' color='gray'>&nbsp;&nbsp; Current Time:&nbsp;&nbsp;</font></br>"+hours+":"+minutes+":"
 +seconds+" "+dn+"</b></font>"
if (document.layers){
document.layers.liveclock.document.write(myclock)
document.layers.liveclock.document.close()
}
else if (document.all)
liveclock.innerHTML=myclock
else if (document.getElementById)
document.getElementById("liveclock").innerHTML=myclock
setTimeout("show5()",1000)
 }

//-->
</script>

<table id="layout-table" summary="layout">
  <tr>
  <?php
    $lt = (empty($THEME->layouttable)) ? array('left', 'middle', 'right') : $THEME->layouttable;
    foreach ($lt as $column) {
        switch ($column) {
            case 'left':
                if (blocks_have_content($pageblocks, BLOCK_POS_LEFT) || $editing) {
                    //echo '<td style="width: '.$preferred_width_left.'px;" id="left-column">';
                    echo '<td style="width: 150px;" id="left-column">';
                    print_container_start();
                    blocks_print_admin($PAGE, $pageblocks, BLOCK_POS_LEFT);
                    print_container_end();
                    echo '</td>';
                }
            break;
            case 'middle':
                echo '<td id="middle-column">'. skip_main_destination();

                    //// Middle Column
                        print_container_start();

                        // Get USER role information (admin)
                        $userRole = $DB->get_record('role_assignments', array('userid'=>$USER->id));
                        // Check if user was an admin
                        $url = $CFG->wwwroot;
                        $newuserurl = "$CFG->wwwroot/user/editadvanced.php?id=-1";
                        $newcourseurl = "$CFG->wwwroot/course/index.php?categoryedit=on";

                        if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
                            //echo "Logged-In";
                        } else {
                            //echo "Logged-Out";

                            //redirect("$CFG->wwwroot/login/index.php");
                            $url = "$CFG->wwwroot";

                            //displayLoginBox($url);
                            displayWelcome();

                        }

                   //echo(date("U",mktime(9,0,0,8,17,2009))."<br />");
                   //echo(date("U",mktime(11,0,0,8,19,2009))."<br />");
                   //echo $USER->id." - ".$USER->username."<br />";
                   //echo date("l dS \of F Y",time())."<br/><br/>";

                   if (!empty($userRole)) {
                        if ($userRole->roleid == 1) {   // Administrator Role
                            echo "<h3>Admin User Options</h3>";

                            echo "<ol>";
                            echo "<li><a href='".$newuserurl."'>Add a New User</a></li>";
                            echo "<li><a href='".$newcourseurl."'>Add a New Course</a></li>";
                            echo "</ol>";

                            echo "<b>Existing Courses</b><br/><br/>";

                            $courses  = get_my_courses($USER->id, 'visible DESC,sortorder ASC', array('summary'));


                            if (!empty($courses)) {
                                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                                foreach ($courses as $course) {
                                    if ($course->id == SITEID) {
                                        continue;
                                    }
                                    echo '<li>';
                                    echo "<b>".$course->fullname."</b><br/>".$course->summary."<br />";
                                    echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>View Grades</a>";
                                    echo " | <a href='$CFG->wwwroot/course/view.php?id=$course->id'>View Course</a>";
                                    echo " | <a href='$CFG->wwwroot/course/modedit.php?add=quiz&type=&course=$course->id&section=0&return=0'>Add Quiz</a>";
                                    echo " | <a href='$CFG->wwwroot/question/edit.php?courseid=$course->id'>Edit Course Options</a>";
                                    echo "<br /><br />";
                                    echo '</li>';
                                }
                                echo "</ul>";
                            }else{
                                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                                    echo '<li>';
                                    echo "Currently, No Courses available";
                                    echo '</li>';
                                
                                echo "</ul>";
                            }

                        }else if ($userRole->roleid == 2 || $userRole->roleid == 3) { // Course Creator/Teacher Role

                            echo "<h3>Teacher User Options</h3>";

                            echo "<ol>";
                            echo "<li><a href='".$newcourseurl."'>Add a New Course</a></li>";
                            echo "</ol>";

                            echo "<b>Existing Courses</b><br/><br/>";

                            $courses  = get_my_courses($USER->id, 'visible DESC,sortorder ASC', array('summary'));


                            if (!empty($courses)) {
                                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                                foreach ($courses as $course) {
                                    if ($course->id == SITEID) {
                                        continue;
                                    }
                                    echo '<li>';
                                    echo "<b>".$course->fullname."</b><br/>".$course->summary."<br />";
                                    echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>View Grades</a>";
                                    echo " | <a href='$CFG->wwwroot/course/view.php?id=$course->id'>View Course</a>";
                                    echo " | <a href='$CFG->wwwroot/course/modedit.php?add=quiz&type=&course=$course->id&section=0&return=0'>Add Quiz</a>";
                                    echo " | <a href='$CFG->wwwroot/question/edit.php?courseid=$course->id'>Edit Course Options</a>";
                                    echo "<br /><br />";
                                    echo '</li>';
                                }
                                echo "</ul>";
                            }else{
                                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                                    echo '<li>';
                                    echo "Currently, No Courses available";
                                    echo '</li>';
                                
                                echo "</ul>";
                            }





                    /*
                    /// Print Section
                        if ($SITE->numsections > 0) {

                            if (!$section = $DB->get_record('course_sections', array('course'=>$SITE->id, 'section'=>1))) {
                                $DB->delete_records('course_sections', array('course'=>$SITE->id, 'section'=>1)); // Just in case
                                $section->course = $SITE->id;
                                $section->section = 1;
                                $section->summary = '';
                                $section->sequence = '';
                                $section->visible = 1;
                                $section->id = $DB->insert_record('course_sections', $section);
                            }

                            if (!empty($section->sequence) or !empty($section->summary) or $editing) {
                                print_box_start('generalbox sitetopic');

                                /// If currently moving a file then show the current clipboard
                                if (ismoving($SITE->id)) {
                                    $stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
                                    echo '<p><font size="2">';
                                    echo "$stractivityclipboard&nbsp;&nbsp;(<a href=\"course/mod.php?cancelcopy=true&amp;sesskey=".sesskey()."\">". get_string('cancel') .'</a>)';
                                    echo '</font></p>';
                                }

                                $context = get_context_instance(CONTEXT_COURSE, SITEID);
                                $summarytext = file_rewrite_pluginfile_urls($section->summary, 'pluginfile.php', $context->id, 'course_section', $section->id);
                                $summaryformatoptions = new object();
                                $summaryformatoptions->noclean = true;

                                echo format_text($summarytext, FORMAT_HTML, $summaryformatoptions);

                                if ($editing) {
                                    $streditsummary = get_string('editsummary');
                                    echo "<a title=\"$streditsummary\" ".
                                         " href=\"course/editsection.php?id=$section->id\"><img src=\"$CFG->pixpath/t/edit.gif\" ".
                                         " class=\"iconsmall\" alt=\"$streditsummary\" /></a><br /><br />";
                                }

                                get_all_mods($SITE->id, $mods, $modnames, $modnamesplural, $modnamesused);
                                print_section($SITE, $section, $mods, $modnamesused, true);

                                if ($editing) {
                                    print_section_add_menus($SITE, $section->section, $modnames);
                                }
                                print_box_end();
                            }
                        }
                     *


                        if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
                            $frontpagelayout = $CFG->frontpageloggedin;
                        } else {
                            $frontpagelayout = $CFG->frontpage;
                        }

                        foreach (explode(',',$frontpagelayout) as $v) {

                            switch ($v) {     /// Display the main part of the front page.
                                case FRONTPAGENEWS:
                                    if ($SITE->newsitems) { // Print forums only when needed
                                        require_once($CFG->dirroot .'/mod/forum/lib.php');

                                        if (! $newsforum = forum_get_course_forum($SITE->id, 'news')) {
                                            print_error('cannotfindorcreateforum', 'forum');
                                        }

                                        if (!empty($USER->id)) {
                                            $SESSION->fromdiscussion = $CFG->wwwroot;
                                            $subtext = '';
                                            if (forum_is_subscribed($USER->id, $newsforum)) {
                                                if (!forum_is_forcesubscribed($newsforum)) {
                                                    $subtext = get_string('unsubscribe', 'forum');
                                                }
                                            } else {
                                                $subtext = get_string('subscribe', 'forum');
                                            }
                                            print_heading_block($newsforum->name);
                                            echo '<div class="subscribelink"><a href="mod/forum/subscribe.php?id='.$newsforum->id.'">'.$subtext.'</a></div>';
                                        } else {
                                            print_heading_block($newsforum->name);
                                        }

                                        forum_print_latest_discussions($SITE, $newsforum, $SITE->newsitems, 'plain', 'p.modified DESC');
                                    }
                                break;

                                case FRONTPAGECOURSELIST:

                                    if (isloggedin() and !has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM)) and !isguest() and empty($CFG->disablemycourses)) {
                                        print_heading_block(get_string('mycourses'));
                                        print_my_moodle();
                                    } else if ((!has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM)) and !isguest()) or ($DB->count_records('course') <= FRONTPAGECOURSELIMIT)) {
                                        // admin should not see list of courses when there are too many of them
                                        print_heading_block(get_string('availablecourses'));
                                        print_courses(0);
                                    }
                                break;

                                case FRONTPAGECATEGORYNAMES:

                                    print_heading_block(get_string('categories'));
                                    print_box_start('generalbox categorybox');
                                    print_whole_category_list(NULL, NULL, NULL, -1, false);
                                    print_box_end();
                                    print_course_search('', false, 'short');
                                break;

                                case FRONTPAGECATEGORYCOMBO:

                                    print_heading_block(get_string('categories'));
                                    print_box_start('generalbox categorybox');
                                    print_whole_category_list(NULL, NULL, NULL, -1, true);
                                    print_box_end();
                                    print_course_search('', false, 'short');
                                break;

                                case FRONTPAGETOPICONLY:    // Do nothing!!  :-)
                                break;

                            }

                            echo '<br />';
                        }
                     *
                     */
                        }else if ($userRole->roleid == 5) { // Student Role

                            if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {

                                $courses  = get_my_courses($USER->id, 'visible DESC,sortorder ASC', array('summary'));


                                if (!empty($courses)) {

                                ?>

                                <script language="JavaScript">
                                show5();
                                </script>

                                <?php

                                    echo '<h3>Courses:<br/></h3>';
                                    echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                                    foreach ($courses as $course) {
                                        if ($course->id == SITEID) {
                                            continue;
                                        }
                                        echo '<li>';
                                        //print_course($course);
                                        echo "<b>".$course->fullname."</b><br/>".$course->summary."<br /><br />";

                                        // Display Exam Info
                                        if($DB->record_exists('quiz_course_activation', array('username'=>$USER->username))){
                                            $records = $DB->get_recordset('quiz_course_activation', array('username'=>$USER->username));
                                            if (!empty($records)) {
                                                $format = "l dS \of F Y - h:i:s A";
                                                $currentTime = time();
                                                $currentExam = "";
                                                $prevExam = "";

                                                //$currentTime = date("U",mktime(9,31,0,8,20,2009));

                                                //echo($currentTime . "<br />");
                                                //echo(date("D F d Y",$currentTime));
                                                //echo(date($format,$currentTime) . "<br />");

                                                
                                                foreach ($records as $record) {

                                                //echo $currentTime. "<br />";
                                                //if(compareDates(date("Y-m-d",$currentTime),date("Y-m-d",$record->starttime)) > 0){
                                                //   echo date($format,$currentTime)." is after ".date($format,$record->starttime). "<br />";
                                                //}else

                                                    if(compareDates(date("Y-m-d",$currentTime),date("Y-m-d",$record->starttime)) < 0){
                                                    //if(compareDates($currentTime,$record->starttime) < 0){
                                                        //echo "Your Exam is has NOT started<br />";
                                                        //echo date($format,$currentTime)." is before ".date($format,$record->starttime). "<br />";

                                                        $currentExam .= print_container_start(false,'', '', true);
                                                        $currentExam .= "Your Exam is on:<br/><br/>";
                                                        displayExamInfo($record, 30); // 30 Minute Diff.

                                                        $currentExam .= print_single_button('', null, 'Exam Instructions', 'get', '', true, '', true);
                                                        $currentExam .= "<br/>";
                                                        $currentExam .= print_single_button('', null, 'Start Exam', 'get', '', true, '', true);
                                                        $currentExam .= "<br/>";
                                                        $currentExam .= print_single_button('', null, 'Practical Instructions', 'get', '', true, '', true);
                                                        $currentExam .= "<br/>";
                                                        $currentExam .= print_container_end(true);
                                                        $currentExam .= "<hr>";

                                                    }else if(compareDates(date("Y-m-d",$currentTime),date("Y-m-d",$record->endtime)) > 0){
                                                        //echo "Your Exam is OVER.<br />";
                                                        //echo date($format,$currentTime)." is after ".date($format,$record->starttime). "<br />";

                                                        $cm = $DB->get_record('course_modules', array('course'=>$course->id,'module'=>'12','instance'=>$record->quizid));
                                                        $quiz = $DB->get_record('quiz', array('id'=>$cm->instance));

                                                        $caption = "<i>".$quiz->name.":</i>";
                                                        $id = "prev_".$record->id;

                                                        $prevExam .= print_collapsible_region_start("", $id, $caption, false, true, true);
                                                        //$prevExam .= "<b>Your Previous Grades for: </b><i>".$quiz->name.":</i><br/><br/>";
                                                        $prevExam .= $record->grade1."/".$quiz->sumgrades;
                                                        $prevExam .= print_collapsible_region_end(true);

                                                    }else{
                                                        //Exam Day

                                                        //$starttime = date('H:i:s', $record->starttime);
                                                        //$starttime = str_replace(':', '', $starttime);
                                                        $starttime = $record->starttime;
                                                        

                                                        //$endtime = date('H:i:s', $record->endtime);
                                                        //$endtime = str_replace(':', '', $endtime);
                                                        $endtime = $record->endtime;

                                                        //$now = date('His', $currentTime);
                                                        //$testdate = date("His",$currentTime);
                                                        //$now = $testdate;
                                                        $now = $currentTime;
                                                        //echo "TEST DATE:".date($format,mktime(8,45,0,8,20,2009))."<br/>";
                                                        //$longnow = date('H:i:s', $currentTime);

                                                        //echo "----------<br />";
                                                        //echo "S".$starttime."<br />";
                                                        //echo "E".$endtime."<br />";
                                                        //echo "N".$now."<br />";
                                                        //echo ($starttime-7000)."<br />";
                                                        //echo "----------<br />";

                                                        // compare strings
                                                        if($now < $starttime){
                                                            echo "Your Exam is has NOT STARTED<br />";
                                                            //echo date($format,$currentTime)." is before ".date($format,$record->starttime). "<br />";
                                                            //echo date($format,$currentTime)." is before ".date($format,$record->endtime). "<br />";

                                                            $currentExam .= print_container_start(false,'', '', true);
                                                            $currentExam .= "Your Exam is on:<br/><br/>";
                                                            displayExamInfo($record, 30); // 30 Minute Diff.
                                                            
                                                            // Exam Instructions
                                                            //if($now >= $starttime-7000){
                                                            if($now >= $starttime-1800){
                                                                $currentExam .= "<font color='green'>Your Exam Instructions are available.</font><br/><br/>";
                                                            }else{
                                                                $currentExam .= "<font color='red'>Your Exam Instructions will be available at: </font>".date("h:i:s A",$record->starttime - 1800)."<br/><br/>";
                                                            }
                                                            // If 30 minutes to the Exam - make intructions available
                                                            // subtract 30 minutes (7000 seconds) (His)
                                                            //if($now >= $starttime-7000){
                                                            if($now >= $starttime-1800){
                                                                //// Check if Quiz Context exists, if not get Random Context
                                                                checkContext($record,$course);
                                                                //echo "TEST";
                                                                $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>1);
                                                                $currentExam .= print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Exam Instructions', 'get', '', true, '', false);
                                                            }else{
                                                                $currentExam .= print_single_button('', null, 'Exam Instructions', 'get', '', true, '', true);
                                                            }

                                                            
                                                            $currentExam .= "<br/>";
                                                            $currentExam .= print_single_button('', null, 'Start Exam', 'get', '', true, '', true);
                                                            $currentExam .= "<br/>";
                                                            $currentExam .= print_single_button('', null, 'Practical Instructions', 'get', '', true, '', true);
                                                            $currentExam .= "<br/>";
                                                            $currentExam .= print_container_end(true);
                                                            $currentExam .= "<div id='info_$record->id'></div><br/>";
                                                            $currentExam .= "<hr>";

                                                        }else if($now >= $starttime && $now <= $endtime){
                                                            echo "Your Exam is has STARTED<br />";
                                                            //echo date($format,$currentTime)." is after ".date($format,$record->starttime). "<br />";
                                                            //echo date($format,$currentTime)." is before ".date($format,$record->endtime). "<br />";
                                                           
                                                           // Check if Quiz Context exists, if not get Random Context
                                                           checkContext($record,$course);

                                                           // echo date('H:i:s', $starttime+1800);

                                                           // Button are available
                                                            print_container_start(false,'', '', false);
                                                            echo "<b><font color='red'>Your Exam has Started:</font></b><br/><br/>";
                                                            displayExamInfo($record, 30); // 30 Minute Diff.
                                                            
                                                            // If 30 minutes after the Exam Started - make Practical Intructions available (disable Exam)
                                                            // add 30 minutes (7000 seconds) (His)
                                                            if($now >= $starttime+1800){
                                                                print_single_button('', null, 'Exam Instructions', 'get', '', false, '', true);
                                                                echo "<br/>";
                                                                print_single_button('', null, 'Start Exam', 'get', '', false, '', true);
                                                                echo "<font size='2' color='green'>&radic;</font> <font size='2' color='red'>Your exam has been submitted</font><br/>";
                                                                echo "<br/>";
                                                                $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>2);
                                                                print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Practical Instructions', 'get', '', false, '', false);
                                                                echo "<br/>";
                                                            }else{
                                                                // Starttime to + 30 mins
                                                                $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>1);
                                                                print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Exam Instructions', 'get', '', false, '', false);
                                                                echo "<br/>";
                                                                // Start Exam Button
                                                                prepareQuizAttempt($record->quizid);
                                                                echo "<br/>";
                                                                print_single_button("", null, 'Practical Instructions', 'get', '', false, '', true);
                                                                echo "<br/>";
                                                            }

                                                            
                                                            //$currentExam .= print_single_button($link, $options, $label, 'get', '', true, '', $disabled);
                                                            print_container_end(false);
                                                            echo "<div id='info_$record->id'></div><br/>";
                                                            echo "<hr>";

                                                            // Button are available
                                                            //$currentExam .= print_container_start(false,'', '', true);
                                                            //$currentExam .= "<b><font color='red'>Your Exam has Started:</font></b><br/><br/>";
                                                            //$currentExam .= date($format,$record->starttime) . " to<br/>".date($format,$record->endtime)."<br/><br/>";
                                                            //$currentExam .= print_single_button('url', null, 'Exam Instructions', 'get', '', true, '', false);
                                                            //$currentExam .= "<br/>";

                                                            //$currentExam .= print_single_button('url', null, 'Start Exam', 'get', '', true, '', false);
                                                            //$currentExam .= "<br/>";
                                                            //$currentExam .= print_single_button('url', null, 'Practical Instructions', 'get', '', true, '', false);
                                                            //$currentExam .= "<br/>";
                                                            ////$currentExam .= print_single_button($link, $options, $label, 'get', '', true, '', $disabled);
                                                            //$currentExam .= print_container_end(true);
                                                            //$currentExam .= "<div id='info_$record->id'></div><br/>";
                                                            //$currentExam .= "<hr>";

                                                        }else{
                                                            echo "Your Exam is OVER2<br />";
                                                            //echo date($format,$currentTime)." is after ".date($format,$record->endtime). "<br />";
                                                            

                                                            $cm = $DB->get_record('course_modules', array('course'=>$course->id,'module'=>'12','id'=>$record->quizid));
                                                            $quiz = $DB->get_record('quiz', array('id'=>$cm->instance));

                                                            $caption = "<i>".$quiz->name.":</i>";
                                                            $id = "prev_".$record->id;

                                                            $prevExam .= print_collapsible_region_start("", $id, $caption, false, true, true);
                                                            //$prevExam .= "<b>Your Previous Grades for: </b><i>".$quiz->name.":</i><br/><br/>";
                                                            $prevExam .= $record->grade1."/".$quiz->sumgrades;
                                                            $prevExam .= " | <a href='$CFG->wwwroot/mod/quiz/summary.php?attempt=$record->attemptid'>Summary</a>";
                                                            $prevExam .= print_collapsible_region_end(true);
                                                        }
                                                    }




                                                }
                                                $records->close();

                                                // Counter
                                                //echo "<script language='JavaScript'>";
                                                //echo "var countDownInterval=$timeleft;";   //configure refresh interval (in seconds) 1800 = 30 min
                                                //echo "var c_reloadwidth=200;";   //configure width of displayed text, in px (applicable only in NS4)
                                                //echo "</script>";
                                                //echo "<ilayer id='c_reload' width=&{c_reloadwidth}; ><layer id='c_reload2' width=&{c_reloadwidth}; left=0 top=0></layer></ilayer>";


                                                echo $currentExam;
                                                if(!empty($prevExam)){
                                                echo "<b>Your Previous Grades for: </b>";
                                                echo $prevExam;
                                                }
                                            }

                                        }else{
                                            echo "<i><b>You do not have any exams for this course.</b></i><br/>";
                                            /*
                                            $starttime = time();
                                            echo "Start time:".$starttime."<br/>";
                                            $endtime = $starttime + 68854;
                                            echo "End time:".$endtime."<br/>";
                                            $endtime = $starttime + 68854 + 68854;
                                            echo "End time2:".$endtime."<br/>";
                                             
                                             */
                                        }



                                        // Display Link button to quiz
                                        /*
                                        $quizzes = $DB->get_records('quiz', array('course'=>$course->id));
                                        foreach ($quizzes as $quiz) {
                                            echo " - ".$quiz->name."<br />";
                                            // Print Start Attempt Link Button

                                            // Get Activation
                                            $active = $DB->get_record('quiz_course_activation', array('username'=>$USER->username,'quizid'=>$quiz->id));
                                            //if (!empty($active)) {
                                                // Get Module
                                                $cm = $DB->get_record('course_modules', array('course'=>$course->id,'module'=>'12','instance'=>$quiz->id));
                                                if (!empty($cm)) {
                                                    //echo $cm->instance;
                                                    $options  = array('cmid'=>$cm->id,'sesskey'=>sesskey());
                                                    $link = "$CFG->wwwroot/mod/quiz/startattempt.php";
                                                    print_single_button($link, $options, "Attempt Quiz");
                                                }
                                            //}
                                        }
                                         */

                                        echo "</li>\n";
                                    }
                                    echo "</ul>\n";
                                }else{
                                    echo '<h3>Courses:<br/></h3>';
                                    echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                                    echo '<li>Currently, not enrolled in any courses.</li>';
                                    echo '</ul>';
                                }
                           }

                        }

                   } else { // END - Check if admin/course creator

                        // Student Users - with no courses
                        if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
                            echo '<h3>Courses:<br/></h3>';
                            echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                            echo '<li>Currently, not enrolled in any courses.</li>';
                            echo '</ul>';
                        }
                            
                   }
                        print_container_end();

                    echo '</td>';
            break;
            case 'right':
                // The right column
                if (blocks_have_content($pageblocks, BLOCK_POS_RIGHT) || $editing || $PAGE->user_allowed_editing()) {
                    echo '<td style="width: '.$preferred_width_right.'px;" id="right-column">';
                    print_container_start();
                    if ($PAGE->user_allowed_editing()) {
                        echo '<div style="text-align:center">'.update_course_icon($SITE->id).'</div>';
                        echo '<br />';
                    }
                    //blocks_print_group($PAGE, $pageblocks, BLOCK_POS_RIGHT);
                    print_container_end();
                    echo '</td>';
                }
            break;
        }
    }

?>

  </tr>
</table>


<?php
    print_footer('home');     // Please do not modify this line
?>

<?php

function displayExamInfo($record, $diff){
    // Diff is time in minutes
    echo "<b>".date("l dS \of F Y",$record->starttime). "</b> <br/>";
    echo date("H:i:s A",$record->starttime) . " to ".date("H:i:s A",$record->endtime)."<br/><br/>";

    echo "<b>Instructions: </b>".date("H:i:s A",$record->starttime-($diff*60)) . " to ".date("H:i:s A",$record->starttime)."<br/>";
    echo "<b>Exam: </b>".date("H:i:s A",$record->starttime) . " to ".date("H:i:s A",$record->starttime+($diff*60))."<br/>";
    echo "<b>Practical: </b>".date("H:i:s A",$record->starttime+($diff*60)) . " to ".date("H:i:s A",$record->endtime)."<br/><br/>";

}

/*
 * <0 --> if date a< date b
 * 0 --> date a== date b
 * >0 --> date a > date b respectively.
 */
function compareDates($start_date,$end_date) {
/*
$exp_date = date("Y-m-d",$end_date);
$todays_date = date("Y-m-d",$start_date);
$today = strtotime($todays_date);
$expiration_date = strtotime($exp_date);
if ($expiration_date > $today) {
    $valid = 1;
} else if ($expiration_date == $today) {
    $valid = 0;
} else {
    $valid = -1;
}
*/
  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
  //return $valid;
}

function compareTimes($start_date,$end_date) {
  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
}

function checkContext($qca,$course){
    global  $DB;
    if(!empty($qca)){
        //$quizcontext = $DB->get_record('quiz_course_activation', array('exam_context' => $course->id, 'id' => $spc->quizid));
        if($qca->exam_context == NULL){
            
            $ec = getRandomContextGroup($course);
            
            if($ec != 0){

                // Get Selected Instructions
                $intr1 = getRandomInstructions($course,1);
                $intr2 = getRandomInstructions($course,2);
                // Check if valid instruction
                if($intr1 == 0) $intr1 = NULL;
                if($intr2 == 0) $intr2 = NULL;

                // Create Exam Quiz Context
                $record_id = $DB->insert_record('exam_quiz_context', array(
                                            'courseid'=>$course->id,
                                            'quizid'=>$qca->quizid,
                                            'contextid'=>$ec,
                                            'instr1_id'=>$intr1,
                                            'instr2_id'=>$intr2
                                        ));

                $qca->exam_context = $record_id;
               
                $DB->update_record('quiz_course_activation',$qca);

            }
        }
    }
}

function getRandomContextGroup($course){
    global  $DB;

    $cnt = $DB->count_records('context_key_groups', array('courseid'=>$course->id));
    $id = 0;

    if($cnt > 0){
        $rnd = rand(1, $cnt); // random count id
        $cnt = 0; // reset as counter

        $records = $DB->get_recordset('context_key_groups', array('courseid'=>$course->id));
        foreach ($records as $record) {
            $cnt++;
            if($cnt == $rnd){
                $id = $record->id;
            }
        }
        $records->close();
    }else{
        $id = 0;
    }
    return $id;
}
function getRandomInstructions($course,$typeid){
    global  $DB;

    $type = $DB->get_record('instructions_type', array('courseid'=>$course->id, 'count'=>$typeid));
    $cnt = $DB->count_records('instructions', array('courseid'=>$course->id, 'typeid'=>$type->id));
    $id = 0;

    if($cnt > 0){
        $rnd = rand(1, $cnt); // random count id
        $cnt = 0; // reset as counter

        $records = $DB->get_recordset('instructions', array('courseid'=>$course->id, 'typeid'=>$type->id));
        foreach ($records as $record) {
            $cnt++;
            if($cnt == $rnd){
                $id = $record->id;
                // Set Instruction to Active Status
                if($record->active == NULL){
                    $record->active = 1;
                }else{
                    $record->active = $record->active + 1;
                }
                $DB->update_record('instructions',$record);
            }
        }
        $records->close();
    }else{
        $id = 0;
    }
    return $id;
}

///--------

function get_keyword($group,$keyword){
    global  $DB;

    $key = $DB->get_record('quiz_context_keys', array('key_code'=>$keyword));

    $record = $DB->get_record('context_key_words', array('key_group'=>$group, 'key_id'=>$key->key_id));

    if (!empty($record)) {
        return $record->keyword;
    }else{
        return "";
    }
}

function assignGroupContext($group,$attempt){
    global  $DB;

    $record = $DB->get_record('context_quiz_attempts', array('quiz_attempt'=>$attempt));
    if (!empty($record)) {
        // Attempt Record already Exists
        return $record->key_group;
    }else{
        $record = $DB->insert_record('context_quiz_attempts',(object)array(
            'key_group'=>$group,
            'quiz_attempt'=>$attempt
        ));
        return $record->key_group;
    }

}

function displayWelcome(){
    echo "<div class='clearfix onecolumn'>";
    echo "<h2>Welcome to IT Automation Certification Portal</h2>";
    echo "<p>&nbsp;&nbsp; &raquo; Please Login to begin.</p>";
    echo "</div>";
}


function displayLoginBox($url){

echo "<div class='loginbox clearfix onecolumn'>";
echo "<div class='loginpanel'>";
echo "<h2>Login to Kaseya Course Portal</h2>";
echo "<div class='subcontent loginsub'>";

echo "<div class='desc'>";
echo "Login here using your username and password<br/>(Cookies must be enabled in your browser)";
echo "<span class='helplink'>";
echo "<a onclick=\"this.target='popup'; return openpopup('".$url."/help.php?module=moodle&amp;file=cookies.html', 'popup', 'menubar=0,location=0,scrollbars,resizable,width=500,height=400', 0);\" href='".$url."/help.php?module=moodle&amp;file=cookies.html' title='Help with Cookies must be enabled in your browser (new window)'>";
echo "<img src='".$url."/pix/help.gif' alt='Help with Cookies must be enabled in your browser (new window)' class='iconhelp'/></a></span></div>";

echo "<form id='login' method='post' action='login/index.php'>";
echo "  <div class='loginform'>";
echo "<div class='form-label'><label for='username'>Username</label></div>";
echo "<div class='form-input'>";
echo "  <input type='text' value='admin' size='15' id='username' name='username'/>";
echo "</div>";
echo "<div class='clearer'><!-- --></div>";
echo "<div class='form-label'><label for='password'>Password</label></div>";
echo "<div class='form-input'>";
echo "  <input type='password' value='' size='15' id='password' name='password'/>";
echo "  <input type='submit' value='Login'/>";
echo "  <input type='hidden' value='1' name='testcookies'/>";
echo "</div>";
echo "<div class='clearer'><!-- --></div>";
echo "  </div>";
echo "</form>";
echo "  </div>";

echo "  <div class='subcontent guestsub'>";
echo "<div class='desc'>";
echo "  Some courses may allow guest access</div>";
echo "<form id='guestlogin' method='post' action='login/index.php'>";
echo "  <div class='guestform'>";
echo "<input type='hidden' value='guest' name='username'/>";
echo "<input type='hidden' value='guest' name='password'/>";
echo "<input type='hidden' value='1' name='testcookies'/>";
echo "<input type='submit' value='Login as a guest'/>";
echo "  </div>";
echo "</form>";
echo "  </div>";

echo "  <div class='subcontent forgotsub'>";
echo "<div class='desc'>";
echo "  Forgotten your username or password?</div>";
echo "<form id='changepassword' method='post' action='login/forgot_password.php'>";
echo "  <div class='forgotform'>";
echo "<input type='hidden' value='XpWw72MRsp' name='sesskey'/>";
echo "<input type='submit' value='Yes, help me log in'/>";
echo "  </div>";
echo "</form>";
echo "  </div>";
echo " </div>";
echo "</div>";

}


function prepareQuizAttempt($q){
    global $DB;

    if ($id) {
        if (! $cm = get_coursemodule_from_id('quiz', $id)) {
            print_error('invalidcoursemodule');
        }
        if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
            print_error('coursemisconf');
        }
        if (! $quiz = $DB->get_record('quiz', array('id' => $cm->instance))) {
            print_error('invalidcoursemodule');
        }
    } else {
        if (! $quiz = $DB->get_record('quiz', array('id' => $q))) {
            print_error('invalidquizid', 'quiz');
        }
        if (! $course = $DB->get_record('course', array('id' => $quiz->course))) {
            print_error('invalidcourseid');
        }
        if (! $cm = get_coursemodule_from_instance("quiz", $quiz->id, $course->id)) {
            print_error('invalidcoursemodule');
        }
    }

/// Check login and get context.
    //require_login($course->id, false, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //require_capability('mod/quiz:view', $context);

/// Cache some other capabilites we use several times.
    $canattempt = has_capability('mod/quiz:attempt', $context);
    $canreviewmine = has_capability('mod/quiz:reviewmyattempts', $context);
    $canpreview = has_capability('mod/quiz:preview', $context);

/// Create an object to manage all the other (non-roles) access rules.
    $timenow = time();
    $accessmanager = new quiz_access_manager(new quiz($quiz, $cm, $course), $timenow,
            has_capability('mod/quiz:ignoretimelimits', $context, NULL, false));


/// Print information about the student's best score for this quiz if possible.
    $moreattempts = $unfinished || !$accessmanager->is_finished($numattempts, $lastfinishedattempt);


/// Determine if we should be showing a start/continue attempt button,
/// or a button to go back to the course page.
    print_box_start('quizattempt');
    $buttontext = ''; // This will be set something if as start/continue attempt button should appear.
    if (!$quiz->questions) {
        print_heading(get_string("noquestions", "quiz"));
    } else {
        if ($unfinished) {
            if ($canattempt) {
                //$buttontext = get_string('continueattemptquiz', 'quiz');
                $buttontext = "Continue Exam";
            } else if ($canpreview) {
                $buttontext = get_string('continuepreview', 'quiz');
            }
        } else {
            if ($canattempt) {
                $messages = $accessmanager->prevent_new_attempt($numattempts, $lastfinishedattempt);
                if ($messages) {
                    $accessmanager->print_messages($messages);
                } else if ($numattempts == 0) {
                    //$buttontext = get_string('attemptquiznow', 'quiz');
                    $buttontext = "Start Exam";
                } else {
                    //$buttontext = get_string('reattemptquiz', 'quiz');
                    $buttontext = "Start Exam";
                }
            } else if ($canpreview) {
                $buttontext = get_string('previewquiznow', 'quiz');
            }
        }

        // If, so far, we think a button should be printed, so check if they will be allowed to access it.
        if ($buttontext) {
            if (!$moreattempts) {
                $buttontext = '';
            } else if ($canattempt && $messages = $accessmanager->prevent_access()) {
                $accessmanager->print_messages($messages);
                $buttontext = '';
            }
        }
    }

/// Now actually print the appropriate button.
    if ($buttontext) {
        $accessmanager->print_start_attempt_button($canpreview, $buttontext, $unfinished);
    } else {
        print_continue($CFG->wwwroot);
    }
    print_box_end();
}


?>
