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



    $records = get_records('quiz_course_activation');


echo "<table border='0' cellpadding='5' cellspacing='5'>";
echo "<tr>";
echo("<th colspan='9'>quiz_course_activation</th>");
echo "</tr>";
echo "<tr>";
echo "<th>Status:</th>";
echo "<th>ID:</th>";
echo "<th>Course ID:</th>";
echo "<th>Quiz ID:</th>";
echo "<th>Attempt ID:</th>";
echo "<th>Username:</th>";
echo "<th>URL:</th>";
echo("<th>Start:</th>");
echo("<th>End:</th>");
echo "</tr>";

    foreach ($records as $record){
        echo "<tr>";
        echo "<td>$record->status</td>";
        echo "<td>".$record->id."</td>";
        echo "<td>".$record->courseid."</td>";
        echo "<td>".$record->quizid."</td>";
        echo "<td>".$record->attemptid."</td>";
        echo "<td>".$record->username."</td>";
        echo "<td>".$record->url."</td>";
        echo("<td>".date("M-d-Y h:i:s A", $record->starttime)."</td>");
        echo("<td>".date("M-d-Y h:i:s A", $record->endtime)."</td>");
        echo "</tr>";
    }
echo "</table>";

    $records = get_records('quiz_attempts');

echo "<table border='0' cellpadding='5' cellspacing='5'>";
echo "<tr>";
echo("<th colspan='4'>quiz_attempts</th>");
echo "</tr>";
echo "<tr>";
echo "<th>ID:</th>";
echo "<th>Quiz:</th>";
echo "<th>Attempt:</th>";
echo "<th>User ID:</th>";
echo "</tr>";

    foreach ($records as $record){
        echo "<tr>";
        echo "<td>".$record->id."</td>";
        echo "<td>$record->quiz</td>";
        echo "<td>".$record->attempt."</td>";
        echo "<td>".$record->userid."</td>";
        echo "</tr>";
    }
echo "</table>";
    

   

?>
