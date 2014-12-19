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

    // Parameters: create a URL that displays the last (largest) quiz id and name. (eg. 2 Test Exam)

    $param->course = optional_param('id', 0, PARAM_INT);
    $param->visible = optional_param('visible', 0, PARAM_INT);

    if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
       // Logged-In

    }

    $navlinks = array(array('name' => "Available Courses", 'link' => null, 'type' => 'misc'));
    $navigation = build_navigation($navlinks);
    print_header($SITE->fullname, $SITE->fullname, $navigation, '',
                 '<meta name="description" content="'. strip_tags(format_text($SITE->summary, FORMAT_HTML)) .'" />',
                 true, '', user_login_string($SITE).$langmenu);

    // Change the Status of the Course (visible)


    if((!empty($param->course) && $param->course > 0)&&(!empty($param->visible))){
        if($param->visible == 1)
            $visible = 1;
        else if($param->visible == 2)
            $visible = 0;


        $course = get_record('course', 'id', $param->course);
        $course->visible = $visible;
        update_record('course',$course);
    }

    $records = get_records('course');


?>

<table border="0" cellpadding="5" cellspacing="0" width="600">
    <tr><td colspan="2"><b>Courses</b></td><td colspan="3"><font color="red" size="2">Changes will appear on the users next login.</font></td></tr>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Summary</th>
        <th>Lastest Quiz</th>
        <th>Visible</th>
    </tr>
<?php
if(!empty($records)){
    foreach ($records as $record){
        echo "<tr>";
        echo "<td>$record->id</td>";
        echo "<td>$record->fullname</td>";
        echo "<td>$record->summary</td>";
        echo "<td><a href='$CFG->wwwroot/latestquiz.php?courseid=$record->id'>Quiz</a></td>";
        if($record->id > 1){
            if($record->visible){
                echo "<td><a href='$CFG->wwwroot/courseinfo.php?id=$record->id&visible=2'><font color='green'>visible</font></a></td>";
            }else{
                echo "<td><a href='$CFG->wwwroot/courseinfo.php?id=$record->id&visible=1'><font color='red'>hidden</font></a></td>";
            }
        }else{
            echo "<td>N/A</td>";
        }
        echo "</tr>";

    }
}else{
    echo "<tr>";
    echo "<td colspan='5'>There are no courses available.</td>";
    echo "</tr>";
}
?>
</table>


<?php print_footer(); ?>
