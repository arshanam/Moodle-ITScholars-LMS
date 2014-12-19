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
    $param->edit = optional_param('edit', null, PARAM_INT);
	$param->add = optional_param('add', null, PARAM_INT);
	$param->saved = optional_param('saved', null, PARAM_INT);
    $param->codeid = optional_param('codeid', 0, PARAM_INT);
	
	$param->code = optional_param('code', 0, PARAM_RAW);
	$param->description = optional_param('description', 0, PARAM_RAW);
	


    print_header($SITE->fullname, $SITE->fullname, 'Add Course Promo Code');

if(!empty($param->saved)){

	if(!empty($param->edit)){

		$sql_coursepromocodes = "SELECT id, code, description FROM mdl_course_promocodes WHERE id = ".$param->codeid;
		$coursepromocode = get_record_sql($sql_coursepromocodes);
	
		$sql_coursepromocodes = "UPDATE mdl_course_promocodes SET code = '".$param->code."', description = '".$param->description."' WHERE id = ".$param->codeid;
		$result = execute_sql($sql_coursepromocodes, false);
		
		print_box_start('');
		if($result > 0){			
			echo "<br/><b>Record Updated</b><br/><br/>";
			//echo "Id: ".$param->codeid."<br/>";
			echo "Code: ".$param->code."<br/>";
			echo "Description: ".$param->description;
			echo "<br/><br/><a href='edit_promocodes.php?codeid=".$param->codeid."'>Edit ".$param->code." </a> | <a href='edit_promocodes.php'>Return to Promo Codes</a>";
		}else{
			echo "<br/><b>Record could Not be created</b><br/>";
		}
        print_box_end();
	
	}else if(!empty($param->add)){	
	
		$sql_coursepromocodes = "INSERT INTO mdl_course_promocodes (code, description) VALUES ('".$param->code."', '".$param->description."')";
		$result = execute_sql($sql_coursepromocodes, false);
		
		print_box_start('');
		if($result > 0){			
			echo "<br/><b>Record Created</b><br/>";
			echo "Code: ".$param->code."<br/>";
			echo "Description: ".$param->description;
		}else{
			echo "<br/><b>Record could Not be created</b><br/>";
		}
        print_box_end();
		
	}

}else{

	if(!empty($param->codeid)){
	
		$sql_coursepromocodes = "SELECT id, code, description FROM mdl_course_promocodes WHERE id = ".$param->codeid;
		$coursepromocode = get_record_sql($sql_coursepromocodes);
		

        print_box_start('');
        echo "<br/><b>Edit: Course Promo Code Entry</b><br/><br/>";
        $options  = array('codeid'=>$coursepromocode->id);
        print_form_start($url);
		
		echo '<input name="codeid" id="codeid" type="hidden" value="'.$coursepromocode->id.'">';
		
        echo "<table border='0' cellpadding='5' cellspacing='5'>";
        echo "<tr>";
        echo "<td>Promo Code:</td>";
        echo "<td colspan='2'>";
        print_textfield ('code', $coursepromocode->code,'',25);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Description:</td>";
        echo "<td colspan='2'>";
		print_textarea(true, 14, 58, 0, 0, 'description',$coursepromocode->description);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='3' align='right'>";
        print_button('Update Entry',array('saved'=>1,'edit'=>1));
		echo "<a href='edit_promocodes.php'>Cancel</a>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        print_form_end();
        print_box_end();
    
	}else{
	
		$sql_coursepromocodes = "SELECT id, code, description FROM mdl_course_promocodes";
		$coursepromocodes = get_records_sql($sql_coursepromocodes);
		
		echo "<br/><b>Existing Course Promo Codes:</b>";
		echo "<ol>";
		foreach ($coursepromocodes as $coursepromocode) {
			echo "<li><a href='edit_promocodes.php?codeid=".$coursepromocode->id."'>".$coursepromocode->code."</a></li>";
		}
		echo "</ol>";
	
		print_box_start('');
		
		
		
        echo "<br/><b>Create: Course Promo Code</b><br/><br/>";
        print_form_start($url);

        echo "<table border='0' cellpadding='5' cellspacing='5'>";
      

        echo "<tr>";
        echo "<td>Promo Code:</td>";
        echo "<td colspan='2'>";
        print_textfield ('code', '','',25);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Description:</td>";
        echo "<td colspan='2'>";
		print_textarea(true, 14, 58, 0, 0, 'description');
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='3' align='right'>";
        print_button('Create Entry',array('saved'=>1,'add'=>1));
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        print_form_end();
        print_box_end();
	}
	
}

    print_footer('PromoCodes');     // Please do not modify this line

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
