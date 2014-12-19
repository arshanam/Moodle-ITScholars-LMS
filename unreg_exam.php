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

    $param->course = optional_param('courseid', 0, PARAM_INT);
    $param->exam = optional_param('examid', 0, PARAM_INT);
    $param->examid = optional_param('eid', 0, PARAM_INT);
    $param->delete = optional_param('delete', 0, PARAM_INT);
    $param->cancel = optional_param('cancel', 0, PARAM_INT);


    $navlinks = array(array('name' => "Available Courses", 'link' => null, 'type' => 'misc'));
    $navigation = build_navigation($navlinks);
    print_header($SITE->fullname, $SITE->fullname, $navigation, '',
                 '<meta name="description" content="'. strip_tags(format_text($SITE->summary, FORMAT_HTML)) .'" />',
                 true, '', user_login_string($SITE).$langmenu);

    // Change the Status of the Course (visible)

    $url = "$CFG->wwwroot/unreg_exam.php";
    $qs = "";

    // Delete Record before Display
    if(!empty($param->examid)){
        $qca = get_record('quiz_course_activation', 'id', $param->examid);

        if(!empty($param->delete)){

            if(record_exists('exam_quiz_context','id', $qca->exam_context)){
                delete_records('exam_quiz_context','id', $qca->exam_context);
            }
            if($qca->attemptid != null){
                delete_records('quiz_attempts','id',$qca->attemptid);
            }
            delete_records('quiz_course_activation', 'id', $param->examid);

        }else if(!empty($param->cancel)){

            $qca->status = "Canceled"; //"Canceled";
            update_record('quiz_course_activation',$qca);
        }
    }


    if(!empty($param->course)){
        $records = get_records('quiz_course_activation','courseid',$param->course);
        $qs = "?courseid=$param->course&";
    }else if(!empty($param->exam)){
        $records = get_records('quiz_course_activation','quizid',$param->exam);
        $qs = "?examid=$param->exam&";
    }else{
        $records = get_records('quiz_course_activation');
        $qs = "?";
    }

    


?>

<table border="0" cellpadding="5" cellspacing="15" width="700">
    <tr><td colspan="9"><b>Unregister Exams</b></td></tr>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Course ID</th>
        <th>Quiz ID</th>
        <th>Date</th>
        <th>Starttime</th>
        <th>Endtime</th>
        <th>Status</th>
        <th>Options</th>
    </tr>
    
<?php

if(!empty($records)){
    foreach ($records as $record){
        echo "<tr>";
        echo "<td>$record->id</td>";
        echo "<td>$record->username</td>";
        echo "<td>$record->courseid</td>";
        echo "<td>$record->quizid</td>";
        echo "<td>".date("F d, Y", $record->starttime)."</td>";
        echo "<td>".date("H:i:s", $record->starttime)."</td>";
        echo "<td>".date("H:i:s", $record->endtime)."</td>";
        echo "<td>$record->status</td>";
        echo "<td>";
        echo "<a href='".$url.$qs."eid=$record->id&delete=1'><font color='red'>delete</font></a>  ";
        echo "<a href='".$url."?courseid=$param->course&eid=$record->id&cancel=1'><font color='orange'>cancel</font></a>";
        echo "</td>";
        echo "</tr>";
    }
    
}else{
    echo "<tr>";
    echo "<td colspan='9'>There are no exams available.</td>";
    echo "</tr>";
}
?>
</table>


<?php print_footer(); ?>