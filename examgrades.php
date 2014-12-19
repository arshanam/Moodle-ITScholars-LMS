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

    // Parameters: for create a url that displays the grades for a user (eg. 26/30 95/100) 
    // Masoud Sadjadi: PARAM_ALPHANUM to PARAM_RAW to support emails as usernames. 
    // $param->username = required_param('username', PARAM_ALPHANUM);
    $param->username = required_param('username', PARAM_RAW);
    $param->courseid = optional_param('courseid', 0, PARAM_INT);
    $param->quizid = optional_param('quizid', 0, PARAM_INT);
    $param->starttime = optional_param('starttime', 0, PARAM_INT);


if(!empty($param->username)){
    
    if(record_exists('user','username',$param->username)){
        
        $sql = "";
        $order = "";
        if(!empty($param->courseid)){
            $sql .= " AND courseid = '$param->courseid'";
            $order = "courseid DESC";
        }
        if(!empty($param->quizid)){
            $sql .= " AND quizid = '$param->quizid'";
            $order = "quizid DESC";
        }
        if(!empty($param->starttime)){
            $sql .= " AND starttime = '$param->starttime'";
            $order = "starttime DESC";
        }

        $records = get_records_select('quiz_course_activation', "username = '$param->username'$sql", "$order");
        
        foreach ($records as $record){
            $quiz = get_record('quiz', 'id', $record->quizid);

            $grade1 = "NA";
            $grade2 = "NA";
            $message = "";

            if($record->grade1 != null){
                $grade1 = $record->grade1;
                $message .= "$grade1/$quiz->sumgrades ";
            }else{
                $message .= "$grade1 ";
            }

            if($record->grade2 != null){
                $grade2 = $record->grade2;
                $message .= "$grade2/100";
            }else{
                $message .= "$grade2"; 
            }

            echo "$message<br/>";
            //echo "$grade1/$quiz->sumgrades $grade2/100<br/>";

        }

    }
}
   

?>
