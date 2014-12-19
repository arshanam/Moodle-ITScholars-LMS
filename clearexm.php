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

    // Get USER role information (admin)
    //$userRole = get_record('role_assignments', array('userid'=>$USER->id));
    $userRole = get_record('role_assignments', 'userid', $USER->id);

    // Clear the course Exams for a particular course
    $clear = optional_param('clrtabs', 0, PARAM_INT);
    $cid = optional_param('id', 0, PARAM_INT);


?>


<?php

////////////////////////////////
/// Additional Page Funtions ///
////////////////////////////////

function clearExamTables($id){
    delete_records('quiz_course_activation','courseid',$id);
    delete_records('exam_quiz_context','courseid',$id);
    delete_records('quiz_attempts','course',$id);
}

?>


<table id="layout-table" summary="layout">
  <tr>
  <?php
    $lt = (empty($THEME->layouttable)) ? array('left', 'middle', 'right') : $THEME->layouttable;
    foreach ($lt as $column) {
        switch ($column) {
            case 'left':
// Start - Left Container Content
    if (blocks_have_content($pageblocks, BLOCK_POS_LEFT) || $editing) {
        echo '<td style="width: '.$preferred_width_left.'px;" id="left-column">';
        print_container_start();
        blocks_print_admin($PAGE, $pageblocks, BLOCK_POS_LEFT);
        print_container_end();
        echo '</td>';
    }
// End - Left Container Content
            break;
            case 'middle':
    echo '<td id="middle-column">'. skip_main_destination();

// Start - Middle Container Content
    print_container_start();

    // Get USER role information (admin)
    //$userRole = get_record('role_assignments', array('userid'=>$USER->id));
    //$userRole = get_record('role_assignments', 'userid', $USER->id);
    // Check if user was an admin
    $url = $CFG->wwwroot;
    $newuserurl = "$CFG->wwwroot/user/editadvanced.php?id=-1";
    $newcourseurl = "$CFG->wwwroot/course/index.php?categoryedit=on";
    $regexam = "$CFG->wwwroot/reg_exam.php";
    $unregexam = "$CFG->wwwroot/unreg_exam.php";
    $coursestatus = "$CFG->wwwroot/courseinfo.php";
    $quizattempts = "$CFG->wwwroot/quizattempts.php";
    // Temp
    $clearexams = "$CFG->wwwroot/schedule.php?clrtabs=1";

    if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
        //echo "Logged-In: $userRole->roleid";
    } else {
        $url = "$CFG->wwwroot";
        displayWelcome();
    }

    if (!empty($userRole)) {

        $clearexams = "$CFG->wwwroot/clearexm.php";
        $mainurl = "$CFG->wwwroot/schedule.php";

        if ($userRole->roleid == 1) {   // Administrator Role ONLY


            if(!empty($cid) && $cid > 0){
                $qca_cnt = count_records('quiz_course_activation','courseid',$cid);
                $eqc_cnt = count_records('exam_quiz_context','courseid',$cid);
                $qa_cnt = count_records('quiz_attempts','courseid',$cid);


                if($qca_cnt > 0 || $qa_cnt > 0 || $eqc_cnt > 0){
             

                    $course = get_record('course','id',$cid);

                    $strconfirm = "Are you sure you want to delete all the records in '$course->fullname'";
                    $optionsyes = array('clrtabs'=>'1', 'id'=>$cid);
                    $optionsno  = null;
                    print_heading_block("Clear Exam Records");

                    //print_header_simple($addspecialcategory, '', build_navigation(array(array('name'=>'Confirm Add', 'link'=>'', 'type'=>'misc'))));
                    print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');
                    echo "<b>Course: </b><i>$course->fullname</i><br/><br/>";
                    echo "Course Quiz Registrations: $qca_cnt<br/>";
                    echo "Course Quiz Attempts: $qa_cnt<br/>";
                    echo "Course Exam Contexts: $eqc_cnt<br/>";
                    notice_yesno($strconfirm, $clearexams, $mainurl, $optionsyes, $optionsno, 'get', 'get');
                    print_simple_box_end();




                    if(!empty($clear) && $clear == 1){

                        echo "DELETED";
                        //clearExamTables($cid);
                        //redirect("$CFG->wwwroot/schedule.php");
                    }
                }else{


                    print_heading_block("Clear Exam Records");
                    print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');

                    echo "There are no record(s) to be deleted.<br/><br/>";

                    print_single_button($mainurl, null);
                    print_simple_box_end();

                }
            }






        }else{ // Course Creator/Teacher Role
            print_heading_block("Clear Exam Records");
            print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');
           
            if ($userRole->roleid == 2 || $userRole->roleid == 3){
                echo "Only an Administrator can delete records.<br/><br/>";
            }else{
                redirect($mainurl);
            }

            print_single_button($mainurl, null);
            print_simple_box_end();

        }

    }

    print_container_end();
// End - Middle Container Content
    echo '</td>';
            break;
            case 'right':
// Start - Right Container Content
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
// End - Right Container Content
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














