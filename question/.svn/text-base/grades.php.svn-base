<?php // $Id: category.php,v 1.24.2.4 2008/11/27 07:30:11 tjhunt Exp $
/**
 * Allows a teacher to create, edit and delete categories
 *
 * @author Martin Dougiamas and many others.
 *         {@link http://moodle.org}
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package questionbank
 */

    require_once("../config.php");
    require_once($CFG->dirroot."/question/editlib.php");
    require_once($CFG->dirroot."/question/category_class.php");

    require_once($CFG->dirroot . '/mod/quiz/editlib.php');

    list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) = question_edit_setup('categories');

    // get values from form for actions on this page
    $param = new stdClass();

    // get values from form for actions on this page
    $param = new stdClass();
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->uid = optional_param('uid', 0, PARAM_RAW);
    $param->quizid = optional_param('quizid', 0, PARAM_INT);
    $param->edit = optional_param('edit', 0, PARAM_INT);
    $param->savegrades = optional_param('savegrades', false, PARAM_BOOL);

    $grades = "Grades";
    $save = "Edit Grades";
    $detail = "Student Detail";

    if($param->uid){
        $userinfo = get_record('quiz_course_activation','username',$param->uid);
        if(empty($userinfo)){
            $param->uid = 0;
        }
    }

    if(!$param->uid){
        if(!$param->edit){
            $navlinks[] = array('name' => $grades, 'link' => '', 'type' => 'title');
        }else{
            $navlinks[] = array('name' => $grades, 'link' => $thispageurl->out(), 'type' => 'title');
            $navlinks[] = array('name' => $save, 'link' => '', 'type' => 'title');
        }
    } else {
        $navlinks[] = array('name' => $grades, 'link' => $thispageurl->out(), 'type' => 'title');
        //$navlinks[] = array('name' => $detail, 'link' => '', 'type' => 'title');
        $navlinks[] = array('name' => $userinfo->username, 'link' => '', 'type' => 'title');
    }

    $navigation = build_navigation($navlinks);
    print_header_simple($streditingcategories, '', $navigation, "", "", true, $strupdatemodule);


    // Set Course Object
    $course = get_record('course','id',$param->courseid);
    $options  = array('courseid'=>$course->id);
    $url = "$CFG->wwwroot/question/grades.php";

?>
<link rel="stylesheet" type="text/css" href="../css/style2.css">
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/tablefilter.js"></script>

<style type="text/css">
.flt{ /* filter (input) appearance */
	background-color:#f4f4f4; border:1px inset #ccc;
	margin:0; width:100%;
}
table.grades {
	width: 1020px;
	border-collapse:collapse;
	border:1px solid #FFCA5E;
}
.grades caption {
	font: 1.8em/1.8em Arial, Helvetica, sans-serif;
	text-align: left;
	text-indent: 10px;
	background: url(../images/bg_caption.jpg) right top;
	height: 45px;
	color: #FFAA00;

}
.grades thead th {
	background: url(../images/bg_th.jpg) no-repeat;
	height: 47px;
	color: #FFFFFF;
	font-size: 0.8em;
	font-weight: bold;
	padding: 0px 7px;
	margin: 20px 0px 0px;
	text-align: left;
	border-right: 1px solid #FCF1D4;
}
.grades tbody tr {
background: url(../images/bg_td1.jpg) repeat-x top;
}
.grades tbody tr.odd {
	background: #FFF8E8 url(../images/bg_td2.jpg) repeat-x;
}

.grades tbody th, .grades tbody thtd {
	font-size: 0.8em;
	line-height: 1.4em;
	font-family: Arial, Helvetica, sans-serif;
	color: #777777;
	padding: 10px 7px;
	border-top: 1px solid #FFCA5E;
	border-right: 1px solid #DDDDDD;
	text-align: left;
}
.grades td a {
	color: #777777;
	font-weight: bold;
	text-decoration: underline;
}
.grades td a:hover {
	color: #F8A704;
	text-decoration: underline;
}
.grades tfoot th {
	background: url(../images/bg_total.jpg) repeat-x bottom;
	color: #FFFFFF;
	height: 30px;
}
.grades tfoot td {
	background: url(../images/bg_total.jpg) repeat-x bottom;
	color: #000000;
	height: 30px;
}
</style>

<?php

    $mainurl = "schedule.php";

    // display UI / Pages
    if (!empty($param->uid)) {

        if(!empty($param->edit)){
            print_form_start($url, "post");
        }

        

        $cnt = count_records('quiz','course',$course->id);

        if($cnt){
            $quizzes = get_records('quiz','course',$course->id);
            if(!empty($quizzes)){

                $javascript = "";

                foreach ($quizzes as $quiz) {

                    $userview = get_record('user','username',$param->uid);

                    $hasRecords = count_records('quiz_course_activation', 'courseid', $course->id, 'quizid', $quiz->id, 'username', $param->uid);

                    $tablename = "tablesorter_$quiz->id";

                    // Sets the Sortting Functionality for each quiz table
                    echo "<script type='text/javascript'>$(document).ready(function(){";
                    echo "$('#$tablename').tablesorter( {sortList: [[0,0], [1,0]]} );});</script>";


                    echo "<table id='$tablename' class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5'>";

                    if(!empty($userview) && $hasRecords > 0){

                        echo "<caption>$quiz->name</caption>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th class='header headerSortDown'>Username</th>";
                        echo "<th class='header headerSortDown'>Exam Date</th>";
                        echo "<th class='header headerSortDown'>Start-Time</th>";
                        echo "<th class='header'>End-Time</th>";

                        echo "<th class='header'>G1</th>";
                        echo "<th class='header'>G2</th>";

                        echo "<th class='header'>INSTRCT1</th>";
                        echo "<th class='header'>ANSWERS</th>";
                        echo "<th class='header'>INSTRCT2</th>";
                        echo "<th class='header'>PRACTICAL</th>";
                        echo "</tr>";
                        echo "</thead>";
                        
                        echo "<tbody>";

                        //if(!empty($userview)){
                            //$records = get_records('quiz_course_activation','courseid',$course->id,'quizid',$quiz->id,'username',$param->uid);
                            $records = get_records_select('quiz_course_activation',"courseid = '$course->id' AND quizid = '$quiz->id' AND username = '$param->uid'");

                            //if(!empty($records)){
                                echo "<tr class='odd'><td colspan='10'><b>$userview->lastname, $userview->firstname</b></td></tr>";
                                $odd = "";

                                foreach ($records as $record) {

                                    $instr1 = "<a href='$CFG->wwwroot/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=1' target='instr'>View</a>";
                                    $instr2 = "<a href='$CFG->wwwroot/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=2' target='instr'>View</a>";
                                    $practicalLink = "<a href='http://$record->url' target='practical'>Practical</a>";
                                    $answersLink = "<a href='$CFG->wwwroot/mod/quiz/review.php?attempt=$record->attemptid'>Answers</a>"; //$record->attemptid


                                    echo "<tr$odd>";
                                    echo "<td>$record->username</td>";
                                    echo "<td>".date("F d Y",$record->starttime)."</td>";
                                    echo "<td>".date("H:i:s A",$record->starttime)."</td>";
                                    echo "<td>".date("H:i:s A",$record->endtime)."</td>";
                                    //echo "<td>".quiz_format_grade($record->quizid, $record->grade1)."</td>";
                                    echo "<td>".$record->grade1."</td>";
                                    echo "<td id='$record->id'>";
                                    if(!empty($param->edit) && ($quiz->id == $param->edit)){
                                        print_textfield ($record->id, $record->grade2,'',5);
                                    }else{
                                        echo $record->grade2;
                                    }
                                    echo "</td>";
                                    echo "<td>$instr1</td>";
                                    echo "<td>$answersLink</td>";
                                    echo "<td>$instr2</td>";
                                    echo "<td>$practicalLink</td>";
                                    echo "</tr>";
                                    $odd = " class='odd'";
                                }
                                //$records->close();
                            //}
                        //}
                        echo "</tbody>";
                        echo "<tfoot>";
                        echo "<tr>";
                        if(!empty($param->edit) && ($quiz->id == $param->edit)){
                            echo "<td colspan='4'><a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a> | ";
                            echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>Return to Grades</a> | ";
                            echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id&uid=$param->uid'>Cancel</a>";
                            echo "</td>";
                            echo "<td colspan='6'>";
                            $options = array('courseid'=>$course->id,'quizid'=>$quiz->id, 'savegrades'=>true, 'uid'=>$param->uid);
                            print_button('Save Grades',$options);
                            echo "</td>";
                        }else{

                            if($param->savegrades && ($param->quizid == $quiz->id)){

                                echo "<td colspan='4'>";
                                echo "<a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a> | ";
                                echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>Return to Grades</a> | ";
                                echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id&edit=$quiz->id&uid=$param->uid'>Edit Grades</a>";
                                echo "</td>";
                                echo "<td colspan='6'>";
                                if($form = data_submitted()) {
                                    $count = saveGradeRecords($form,$quiz->id,$course->id);
                                    if($count){
                                        echo "<font color='green'>$count Saved.</font>";
                                    }else{
                                        echo "<font color='red'>Grades could not be saved.</font>";
                                    }
                                }
                                echo "</td>";
                            }else{
                                echo "<td colspan='12'>";
                                echo "<a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a>  |  ";
                                echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>Return to Grades</a> | ";
                                echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id&edit=$quiz->id&uid=$param->uid'>Edit Grades</a>";
                                echo "</td>";
                            }
                        }
                        echo "</tr>";
                        echo "</tfoot>";
                    }else{
                        echo "<caption>$quiz->name</caption>";
                        echo "<tfoot><tr><td>";
                        echo "<a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a> | ";
                        echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>Return to Grades</a> ";
                        echo "</td></tr></tfoot>";
                        echo "<tbody><tr class='odd'><td colspan='10'><b>No Records Available</b></td></tr></tbody>";
                    }
                    echo "</table>";

                    // Add the Filter Functionality for each Quiz Table
                    $javascript .= "var $tablename = {";
                    $javascript .= "exact_match: false,alternate_rows: true,col_8: 'none',col_9: 'none',col_10: 'none',col_11: 'none',";
                    $javascript .= "col_width: ['100px','100px','75px','75px','75px','75px','40px','60px'],";
                    $javascript .= "btn_reset: true,bnt_reset_text: 'Clear ',rows_always_visible:[grabTag(grabEBI('$tablename'),'tr').length]};";

                    $javascript .= "setFilterGrid('$tablename',$tablename);";

                }
                //$quizzes->close();
            }
        }else{
            echo "<table class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5'>";
            echo "<caption>Oops!</caption>";
            echo "<tfoot><tr>";
            echo "<td colspan='12'><a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a></td>";
            echo "</tr></tfoot>";
            echo "<tbody><tr class='odd'><td><b>Current Course does not has any Quizzes.</b></td></tr>";
            echo "</tbody></table>";
        }

        

        if(!empty($param->edit)){
            print_form_end();
        }

        echo "<script language='javascript' type='text/javascript'>$javascript</script>";

    } else {

//quiz_format_grade($quiz, $grade)
//quiz_get_user_grades($quiz, $userid=0)


        if(!empty($param->edit)){
            print_form_start($url, "post");
        }

        //echo "<table class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5' width='100%'>";
        //style='border-color:#DDDDDD;'


        $cnt = count_records('quiz','course',$course->id);

        if($cnt){
            $quizzes = get_records('quiz','course',$course->id);

            if(!empty($quizzes)){

                $javascript = "";
                
                foreach ($quizzes as $quiz) {
                    $hasRecords = count_records('quiz_course_activation', 'courseid', $course->id, 'quizid', $quiz->id);

                    $tablename = "tablesorter_$quiz->id";

                    // Sets the Sortting Functionality for each quiz table
                    echo "<script type='text/javascript'>$(document).ready(function(){";
                    echo "$('#$tablename').tablesorter( {sortList: [[0,0], [1,0]]} );});</script>";
        

                    echo "<table id='$tablename' class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5' width='1000px'>";

                    if($hasRecords > 0){
                        
                        echo "<caption>$quiz->name</caption>";
                        printGradeTableHead();  // 12 columns total
                        
                        echo "<tbody>";

                        $records = get_records_select('quiz_course_activation',"courseid = '$course->id' AND quizid = '$quiz->id'");
                        //$records = get_records_select('quiz_course_activation',"courseid = '$course->id' AND quizid = '$quiz->id'");
                        //$records = get_records('quiz_course_activation','courseid',$course->id,'quizid',$quiz->id);

                        $odd = ""; // For table CSS

                        foreach ($records as $record) {

                            $instr1 = "<a href='$CFG->wwwroot/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=1'>View</a>";
                            $instr2 = "<a href='$CFG->wwwroot/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=2'>View</a>";
                            $practicalLink = "<a href='http://$record->url' target='practical'>Practical</a>";
                            $answersLink = "<a href='$CFG->wwwroot/mod/quiz/review.php?attempt=$record->attemptid'>Answers</a>";

                            $userview = get_record('user','username',$record->username);

                            if(!empty($userview)){
                                echo "<tr$odd>";
                                echo "<td>$userview->lastname</td>";
                                echo "<td>$userview->firstname</td>";
                                echo "<td><a href='$CFG->wwwroot/question/grades.php?courseid=$course->id&uid=$record->username'>$record->username</a></td>";
                                echo "<td>".date("m/d/Y",$record->starttime)."</td>";
                                echo "<td>".date("H:i:s A",$record->starttime)."</td>";
                                echo "<td>".date("H:i:s A",$record->endtime)."</td>";
                                //echo "<td>".quiz_format_grade($record->quizid, $record->grade1)."</td>";
                                echo "<td>".$record->grade1."</td>";
                                echo "<td id='$record->id'>";
                                if(!empty($param->edit) && ($quiz->id == $param->edit)){
                                    print_textfield ($record->id, $record->grade2,'',5);
                                }else{
                                    echo $record->grade2;
                                }
                                echo "</td>";
                                echo "<td>$instr1</td>";
                                echo "<td>$answersLink</td>";
                                echo "<td>$instr2</td>";
                                echo "<td>$practicalLink</td>";
                                echo "</tr>";
                            }
                            $odd = " class='odd'";
                        }
                        //$records->close();


                        echo "</tbody>";
                        echo "<tfoot>";
                        echo "<tr>";


                        if(!empty($param->edit) && ($quiz->id == $param->edit)){
                            echo "<td colspan='6'><a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a> | ";
                            echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>Cancel</a>";
                            echo "</td>";
                            echo "<td colspan='6'>";
                            $options = array('courseid'=>$course->id,'quizid'=>$quiz->id, 'savegrades'=>true);
                            print_button('Save Grades',$options);
                            echo "</td>";
                        }else{

                            if($param->savegrades && ($param->quizid == $quiz->id)){

                                echo "<td colspan='6'>";
                                echo "<a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a> | ";
                                echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id&edit=$quiz->id'>Edit Grades</a>";
                                echo "</td>";
                                echo "<td colspan='6'>";
                                if($form = data_submitted()) {
                                    $count = saveGradeRecords($form,$quiz->id,$course->id);
                                    if($count){
                                        echo "<font color='green'>$count Saved.</font>";
                                    }else{
                                        echo "<font color='red'>Grades could not be saved.</font>";
                                    }
                                }
                                echo "</td>";
                            }else{
                                echo "<td colspan='12'>";
                                echo "<a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a>  |  ";
                                echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id&edit=$quiz->id'>Edit Grades</a>";
                                echo "</td>";
                            }
                        }
                        echo "</tr>";
                        echo "</tfoot>";
                    }else{


                        echo "<caption>$quiz->name</caption>";
                        printGradeTableHead();  // 12 columns total
                        echo "<tfoot><tr>";
                        echo "<td colspan='12'><a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a></td>";
                        echo "</tr></tfoot>";
                        echo "<tbody>";
                        echo "<tr><td colspan='12'><b>No Records Available</b></td></tr>";
                        echo "</tbody>";
                    }

                    echo "</table>";

                    // Add the Filter Functionality for each Quiz Table
                    $javascript .= "var $tablename = {";
                    $javascript .= "exact_match: false,alternate_rows: true,col_8: 'none',col_9: 'none',col_10: 'none',col_11: 'none',";
                    $javascript .= "col_width: ['100px','100px','75px','75px','75px','75px','40px','60px'],";
                    $javascript .= "btn_reset: true,bnt_reset_text: 'Clear ',rows_always_visible:[grabTag(grabEBI('$tablename'),'tr').length]};";

                    $javascript .= "setFilterGrid('$tablename',$tablename);";
                }
                //$quizzes->close();
                
            }
        }else{
            //echo "<br clear='all' />";
            echo "<table class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5' width='100%'>";
            echo "<caption>Oops!</caption>";
            echo "<tfoot><tr>";
            echo "<td colspan='12'><a href='$CFG->wwwroot/$mainurl'><font color='black'>Return to Main</font></a></td>";
            echo "</tr></tfoot>";
            echo "<tbody><tr class='odd'><td><b>Current Course does not has any Quizzes.</b></td></tr>";
            echo "</tbody></table>";
        }

        //echo "</tbody></table>";

        if(!empty($param->edit)){
            print_form_end();
        }

        echo "<script language='javascript' type='text/javascript'>$javascript</script>";
    }


/*
?>
<script language="javascript" type="text/javascript">
//<![CDATA[

        var table_setup = 	{
					exact_match: false,
					alternate_rows: true,
					col_width: ['100px','100px','100px','60px','60px','60px','20px','20px','50px','50px','50px','50px'],
					rows_counter: true,
					rows_counter_text: 'Rows: ',
					btn_reset: true,
					bnt_reset_text: 'Clear '
				};
<?php
        echo $javascript;

?>

//]]>
</script>


<?php
 *
 *
 */


    print_footer($COURSE);
?>

<?php

function saveGradeRecords($form,$quizid,$courseid){
    $success = 0;
    //$records = get_records('quiz_course_activation','courseid',$courseid,'quizid',$quizid);
    $records = get_records_select('quiz_course_activation',"courseid = '$courseid' AND quizid = '$quizid'");

    if(!empty($records)){
        foreach ($records as $record) {
            $str = $_POST["$record->id"];
            //(int)$str
            if($str == "0"){
               $grade = (int)$str;
               $record->grade2 = $grade;
               update_record('quiz_course_activation',$record);
               $success++;
            }else{
                $grade = (int)$str;
                if($grade != 0){
                    $record->grade2 = $grade;
                    update_record('quiz_course_activation',$record);
                    $success++;
                }
            }
        }
    }

    return $success;
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
    $output .= '<div>';
    if ($options) {
        foreach ($options as $name => $value) {
            $output .= '<input type="hidden" name="'. $name .'" value="'. s($value) .'" />';
        }
    }
    $output .= '<input type="submit" value="'. s($label) .'"/></div>';

    echo $output;

}

function printGradeTableHead(){
    echo "<thead>";
    echo "<tr>";
    echo "<th class='header'>Last</th>";
    echo "<th class='header'>First</th>";
    echo "<th class='header headerSortDown'>Username</th>";
    echo "<th class='header headerSortDown'>Exam Date (mm/dd/yyyy)</th>";
    echo "<th class='header headerSortDown'>Start-Time</th>";
    echo "<th class='header'>End-Time</th>";

    echo "<th class='header'>G1</th>";
    echo "<th class='header'>G2</th>";

    echo "<th class='header'>INSTRCT1</th>";
    echo "<th class='header'>ANSWERS</th>";
    echo "<th class='header'>INSTRCT2</th>";
    echo "<th class='header'>PRACTICAL</th>";
    echo "</tr>";
    echo "</thead>";
}
?>
