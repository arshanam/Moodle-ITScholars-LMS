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

    // Parameters: to cancel the exam registration.
    // Masoud Sadjadi: PARAM_ALPHANUM to PARAM_RAW to support emails as usernames. 
    // $param->username = required_param('username', PARAM_ALPHANUM);
    $param->username = required_param('username', PARAM_RAW);
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->quizid = required_param('quizid', PARAM_INT);
    $param->starttime = required_param('starttime', PARAM_INT);
    $param->endtime = required_param('endtime', PARAM_INT);
    $param->viewstatus = optional_param('viewstatus', 0, PARAM_INT);

if(!empty($param->username)){
    
    if(record_exists('user','username',$param->username)){

        $sql = "";
        
        if(!empty($param->courseid)){
            $sql .= " AND courseid = '$param->courseid'";
        }
        if(!empty($param->quizid)){
            $sql .= " AND quizid = '$param->quizid'";
        }
        if(!empty($param->starttime)){
            $sql .= " AND starttime = '$param->starttime'";
        }
        if(!empty($param->endtime)){
            $sql .= " AND endtime = '$param->endtime'";
        }

        if(!empty($param->viewstatus)){

            $records = get_records_select('quiz_course_activation', "username = '$param->username'$sql", "starttime DESC");

            foreach ($records as $record){

                echo "Status: ".$record->status."<br/>";
                echo "Courseid: ".$record->courseid."<br/>";
                echo "Quizid: ".$record->quizid."<br/>";
                echo "Starttime: ".$record->starttime."<br/>";
                echo "Endtime: ".$record->endtime."<br/>";

            }

        }else{

            $records = get_records_select('quiz_course_activation', "username = '$param->username'$sql", "starttime DESC");

            foreach ($records as $record){

                $record->status = "Canceled"; //"Canceled";
                update_record('quiz_course_activation',$record);

            }
        }
    }
}

/// Example URL:
/// http://64.77.83.36/moodle/cancelexam.php?username=jessica&courseid=4&quizid=2&starttime=1255669200&endtime=1255676400&viewstatus=1
/// http://64.77.83.36/moodle/cancelexam.php?username=jessica&courseid=5&quizid=3&starttime=1255667400&endtime=1255674300&viewstatus=1
   

?>
