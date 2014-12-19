<?php  // $Id: attempt.php,v 1.161 2009-03-23 01:59:30 tjhunt Exp $
/**
 * This page prints a particular instance of quiz
 *
 * @author Martin Dougiamas and many others. This has recently been completely
 *         rewritten by Alex Smith, Julian Sedding and Gustav Delius as part of
 *         the Serving Mathematics project
 *         {@link http://maths.york.ac.uk/serving_maths}
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package quiz
 */

    require_once(dirname(__FILE__) . '/../../config.php');
    require_once($CFG->dirroot . '/mod/quiz/locallib.php');


/// Get submitted parameters.
    $attemptid = required_param('attempt', PARAM_INT);
    $page = optional_param('page', 0, PARAM_INT);

    $attemptobj = new quiz_attempt($attemptid);

/// Check login.
    require_login($attemptobj->get_courseid(), false, $attemptobj->get_cm());

/// Check if the User is an Admin - to review Answers
    $userRole = $DB->get_record('role_assignments', array('userid'=>$USER->id));
    $admin = false;
    if (!empty($userRole)) {
        if ($userRole->roleid == 1 || $userRole->roleid == 2 || $userRole->roleid == 3) {
            $admin = true;
        }else{

            /// Check that this attempt belongs to this user.
            if ($attemptobj->get_userid() != $USER->id) {
                redirect("$CFG->wwwroot/index.php");
            }
        }
    }


    require_js(array('yui_yahoo','yui_event'));
    require_js('mod/quiz/quiz.js');
    //$title = get_string('attempt', 'quiz', $attemptobj->get_attempt_number());

    $userinfo = $DB->get_record('quiz_course_activation', array('attemptid'=>$attemptid));
    $course = $DB->get_record('course', array('id'=>$attemptobj->get_courseid()));

    // Build Navigation
    $navlinks[] = array('name' => $attemptobj->get_quiz_name(), 'link' => null, 'type' => 'misc');
    $navlinks[] = array('name' => $userinfo->username, 'link' => null, 'type' => 'misc');
    //$navlinks[] = array('name' => $title, 'link' => null, 'type' => 'misc');

    $navigation = build_navigation($navlinks);
    
    print_header($SITE->fullname, $SITE->fullname, $navigation);

?>



<style type="text/css">
table.grades {
	width: 650px;
	border-collapse:collapse;
	border:1px solid #FFCA5E;
}
.grades caption {
	font: 1.8em/1.8em Arial, Helvetica, sans-serif;
	text-align: left;
	text-indent: 10px;
	background: url(../../images/bg_caption.jpg) right top;
	height: 45px;
	color: #FFAA00;

}
.grades thead th {
	background: url(../../images/bg_th.jpg) no-repeat;
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
background: url(../../images/bg_td1.jpg) repeat-x top;
}
.grades tbody tr.odd {
	background: #FFF8E8 url(../../images/bg_td2.jpg) repeat-x;
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
	background: url(../../images/bg_total.jpg) repeat-x bottom;
	color: #FFFFFF;
	height: 30px;
}
.grades tfoot td {
	background: url(../../images/bg_total.jpg) repeat-x bottom;
	color: #000000;
	height: 30px;
}
</style>

<?php

    // Get QCA Object -- to Display Details
    if($admin){
        $qca = $DB->get_record('quiz_course_activation', array('attemptid'=>$attemptid));
    }else{
        $qca = $DB->get_record('quiz_course_activation', array('username'=>$USER->username,'attemptid'=>$attemptid));
    }

    // Get User Object
    $userinfo = $DB->get_record('user', array('username'=>$qca->username));

    
    if(!empty($qca)){


    /// If the attempt is closed, results can be view, Otherwise NO.
        if ($attemptobj->is_finished()) {

           /// Get the list of questions needed by this page.
            $questionids = $attemptobj->get_question_ids($page);

        /// Check.
            if (empty($questionids)) {
                quiz_error($quiz, 'noquestionsfound');
            }

        /// Load those questions and the associated states.
            $attemptobj->load_questions($questionids);
            $attemptobj->load_question_states($questionids);

            //print_container_start();

       echo "<table class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5'>";
       $quiz = $DB->get_record('quiz', array('id'=>$attemptobj->get_quizid()));


                    echo "<caption>".$attemptobj->get_quiz_name().": ".$qca->username."</caption>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Question</th>";
                    echo "<th>Answer</th>";
                    echo "<th>Score</th>";
                    echo "</tr>";
                    echo "</thead>";
                   
                    echo "<tbody>";

            $cnt = 0;
            $score = 0;

            // Print Quiz Attempt Review
            foreach ($attemptobj->get_question_ids($page) as $id) {

                $cnt++;
                $score += $attemptobj->get_question_score($id);

                $actual = $attemptobj->get_actual_id($id, false, $attemptobj->attempt_url($id, $page));
                $question = $DB->get_record('question', array('id'=>$actual));
                $responses = get_question_actual_response($attemptobj->get_question($id),$attemptobj->get_question_state($id));

                echo "<tr class='odd'><td colspan='3'>Question: ".$cnt."</td></tr>";
                echo "<tr>";
                // Question
                echo "<td>".replace_keywords($attemptid,$question->questiontext)."</td>";
                echo "<td>";
                // Answer
                foreach($responses as $r){
                    echo replace_keywords($attemptid,$r);
                }
                echo "</td>";
                // Score
                echo "<td>".$attemptobj->get_question_score($id)."</td>";
                echo "</tr>";
            }
           
        echo "</tbody>";

        echo "<tfoot>";
        echo "<tr>";
        echo "<td colspan='2'>";
        echo "<a href='$CFG->wwwroot/'><font color='black'>Return to Main</font></a> | ";
        echo "<a href='$CFG->wwwroot/question/grades.php?courseid=".$attemptobj->get_courseid()."'>Return to Grades</a>";
        echo "</td>";
        echo "<td>$score / $quiz->sumgrades</td>";
        echo "</tr>";
        echo "</tfoot>";

        echo "</table>";
            //print_container_end();


        }else{
            echo "<table class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5'>";
            echo "<caption>Oops!</caption>";
            echo "<tfoot><tr>";
            echo "<td>";
            echo "<a href='$CFG->wwwroot/'><font color='black'>Return to Main</font></a> | ";
            echo "<a href='$CFG->wwwroot/question/grades.php?courseid=".$attemptobj->get_courseid()."'>Return to Grades</a>";
            echo "</td>";
            echo "</tr></tfoot>";
            echo "<tbody><tr class='odd'><td><b>This attempt has not been completed.</b></td></tr>";
            echo "</tbody><table>";
        }
    }else{
        
        echo "<table class='grades boxwidthwide boxaligncenter generalbox' border='0' cellpadding='5' cellspacing='5'>";
        echo "<caption>Oops!</caption>";
        echo "<tfoot><tr>";
        echo "<td>";
        echo "<a href='$CFG->wwwroot/'><font color='black'>Return to Main</font></a>";
        echo "</td>";
        echo "</tr></tfoot>";
        echo "<tbody><tr class='odd'><td><b>Records not available for this attempt.</b></td></tr>";
        echo "</tbody><table>";
    }

    print_footer('empty');



/// Functions: Format Context for Questions
function replace_keywords($attempt,$str){
    global  $DB;

    $qca = $DB->get_record('quiz_course_activation', array('attemptid'=>$attempt));
    if(!empty($qca)){
        $eqc = $DB->get_record('exam_quiz_context', array('id'=>$qca->exam_context));
        if(!empty($eqc)){
            $group = $eqc->contextid;
            $course = $eqc->courseid;

            // << [a-zA-Z0-9]+ >>
            $matched = preg_match_all("/<< [a-zA-Z0-9]+ >>/", $str, $matches);
            if($matched){
                foreach ($matches as $match) {
                        foreach ($match as $val) {
                            $key = get_keyword($group,substr($val,3,strpos($val," >>")-2),$course);
                            $str = str_ireplace($val,$key,$str,$count);
                        }
                }
            }
            // &lt;&lt; [a-zA-Z0-9]+ &gt;&gt;
            $matched = preg_match_all("/&lt;&lt; [a-zA-Z0-9]+ &gt;&gt;/", $str, $matches);
            if($matched){

                foreach ($matches as $match) {
                    foreach ($match as $val) {
                        $key = get_keyword($group,substr($val,9,strpos($val," >>")-8),$course);
                        $str = str_ireplace($val,$key,$str,$count);
                    }
                }
            }
        }
    }
    return $str;
}

function get_keyword($group,$keyword,$course){
    global  $DB;

    $key = $DB->get_record('quiz_context_keys', array('key_code'=>$keyword, 'courseid'=>$course));

    $record = $DB->get_record('context_key_words', array('key_group'=>$group, 'key_id'=>$key->key_id, 'courseid'=>$course));

    if (!empty($record)) {
        return $record->keyword;
    }else{
        return "";
    }
}

?>
