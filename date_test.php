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

    print_header($SITE->fullname, $SITE->fullname, 'Register');

    //$now = date("U",mktime(9,30,0,9,26,2009));
    //$now = date("U",mktime(9,30,0,9,27,2009));
    //$now = date("U",mktime(9,45,11,9,27,2009));
    //$now = date("U",mktime(10,15,0,9,27,2009));
    //$now = date("U",mktime(10,45,0,9,27,2009));
    //$now = date("U",mktime(10,46,0,9,27,2009));
    $now = date("U",mktime(12,15,10,9,27,2009));
    //$now = date("U",mktime(22,00,0,9,27,2009));
    //$now = date("U",mktime(22,30,0,9,27,2009));
    //$now = date("U",mktime(23,00,0,9,27,2009));
    //$now = date("U",mktime(23,01,0,9,27,2009));
    //$now = date("U",mktime(00,00,0,9,28,2009));
    //$now = date("U",mktime(00,15,0,9,28,2009));
    //$now = date("U",mktime(00,30,10,9,28,2009));

    $exam_length = 30;

    $records = get_records_select('quiz_course_activation', "username = 'jessica' AND courseid = '4'");
/*
    $record = new stdClass();
    $record->starttime = date("U",mktime(9,30,0,9,27,2009));
    $record->endtime = date("U",mktime(11,30,0,9,27,2009));
    $records[0] = array($record);
    $record->starttime = date("U",mktime(22,30,0,9,27,2009));
    $record->endtime = date("U",mktime(0,30,0,9,28,2009));
    $records[1] = array($record); 
*/
    
    echo date('U', $now)."<br />";
    echo date('l dS \of F Y', $now)."<br />";
    echo date('h:i:s A', $now)."<br /><br /><br />";
    
    foreach($records as $record){
        //echo date('U', $record->starttime)."<br />";
        if(compareDates(date("Y-m-d",$now),date("Y-m-d",$record->starttime)) < 0){
            //echo "Your Exam is has NOT started<br />";
            examStatus(array(1));
            echo "Your Exam is on:<br/>";
            displayExamInfo($record, $exam_length);

        }else if(compareDates(date("Y-m-d",$now),date("Y-m-d",$record->endtime)) > 0){
            //echo "Your Exam is OVER. Practical is Over. -- View Grades<br />";
            examStatus(array(6));
            displayExamInfo($record, $exam_length);
            showGrades($record);

        }else{
            //Exam Day

            $starttime = $record->starttime;
            $endtime = $record->endtime;

            if($now < $starttime){
                //echo "Your Exam is has NOT STARTED<br />";
                //examStatus(1);

                // Exam Instructions
                if($now >= $starttime - 1800){
                    //echo"<font color='green'>Your Exam Instructions are available.</font><br/><br/>";
                    examStatus(array(1,2));
                }else{
                    //echo "<font color='red'>Your Exam Instructions will be available at: </font>".date("h:i:s A",$record->starttime - ($exam_length * 60))."<br/><br/>";
                    examStatus(array(1,3),date("h:i:s A",$record->starttime - ($exam_length * 60)));
                    //echo date("h:i:s A",$record->starttime - ($exam_length * 60))."<br/>";
                }

                echo "Your Exam is on:<br/>";
                displayExamInfo($record, $exam_length);


            }else if($now >= $starttime && $now <= $endtime){

                //echo "<b><font color='red'>Your Exam has Started:</font></b><br/><br/>";

                if($now >= $starttime + ($exam_length * 60)){
                    //echo "Exam is Over - Practical Instructions Available <br/>";
                    examStatus(array(5));
                }else{
                    // Starttime to + 30 mins
                    examStatus(array(4));
                }
                displayExamInfo($record, $exam_length);

            }else{
                //echo "Your Exam is OVER - Practical is Over - View Grades<br />";
                if($record->grade1 != null){
                    examStatus(array(6));
                }else{
                    examStatus(array(0));
                }
                
                displayExamInfo($record, $exam_length);

                showGrades($record);


            }
        }
        echo "<hr/>";
    }

    print_footer('Register');     // Please do not modify this line

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

function compareDates($start_date,$end_date) {

  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
}

function compareTimes($start_date,$end_date) {
  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
}

function showGrades($record, $return=false){
    $report = "";
    $quiz = get_record('quiz', 'id', $record->quizid);

    $grade1 = "No Grade";
    $grade2 = "Not Graded";
    if($record->grade2 != null){
        $grade2 = $record->grade2;
    }
    if($record->grade1 != null){
        $grade1 = $record->grade1;
    }

    $report .= "<table border='0' cellpadding='5' cellspacing='5' width='450px'>";
    $report .= "<tr><th colspan='4' style='background-color:#CCCCCC;; color:#333333'>$quiz->name</th></tr>";
    $report .= "<tr><td><b>Exam:</b></td><td>$grade1 / $quiz->sumgrades</td><td><b>Practical:</b></td><td>$grade2</td></tr>";
    $report .= "</table>";

    if($return){
        return $report;
    }else{
        echo $report;
    }

}

function examStatus($codes,$value="",$return=false){
    
    $message = "";

    foreach($codes as $code){
        switch($code){
            case 1:
                $message .= "<font color='red'>Exam has not started</font><br/>";
                break;
            case 2:
                $message .= "<font color='grey'>Orientation materials are ready for review.</font><br/>";
                break;
            case 3:
                $message .= "<font color='grey'>Orientation materials will available for review at: <b>$value</b></font><br/>";
                break;
            case 4:
                $message .= "<font color='green'>Exam Part I is now open.</font><br/>";
                break;
            case 5:
                $message .= "<font color='green'>Exam Part II is now open.</font><br/>";
                break;
            case 6:
                $message .= "<font color='grey'>Exam is finished.</font><br/>";
                break;
            case 7:
                $message .= "<font color='grey'>Not graded.</font><br/>";
                break;
            case 8:
                $message .= "<font color='green'>Graded.</font><br/>";
                break;
            default:
                $message .= "<font color='orange'>Exam was not taken.</font><br/>";
        }
    }

    $container = "<table><tr><td valign='top'><b>Status: </b></td><td>$message</td></tr></table><br/><br/>";

    if($return){
        return $container;
    }else{
        echo $container;
    }

}


function examStatuses($code,$return=false){

    $message = "";
    switch($code){
        case 1:
            $message = "<b>Status: </b> <font color='red'>Exam has not started</font><br/>";
            break;
        case 2:
            $message = "<b>Status: </b> <font color='grey'>Orientation materials are ready for review.</font><br/>";
            break;
        case 3:
            $message = "<b>Status: </b> <font color='grey'>Orientation materials will available for review at: </font>";
            break;
        case 4:
            $message = "<b>Status: </b> <font color='green'>Exam Part I is now open.</font><br/>";
            break;
        case 5:
            $message = "<b>Status: </b> <font color='green'>Exam Part II is now open.</font><br/>";
            break;
        case 6:
            $message = "<b>Status: </b> <font color='grey'>Exam is finished.</font><br/>";
            break;
        case 7:
            $message = "<b>Status: </b> <font color='grey'>Not graded.</font><br/>";
            break;
        case 8:
            $message = "<b>Status: </b> <font color='green'>Graded.</font><br/>";
            break;
        default:
            $message = "<b>Status: </b> <font color='orange'>Exam was not taken.</font><br/>";
    }

    if($return){
        return $message;
    }else{
        echo $message;
    }

}


?>
