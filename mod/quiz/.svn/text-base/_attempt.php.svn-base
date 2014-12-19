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

/// Look for old-style URLs, such as may be in the logs, and redirect them to startattemtp.php 
    if ($id = optional_param('id', 0, PARAM_INTEGER)) {
        redirect($CFG->wwwroot . '/mod/quiz/startattempt.php?cmid=' . $id . '&sesskey=' . sesskey());
    } else if ($qid = optional_param('q', 0, PARAM_INTEGER)) {
        if (!$cm = get_coursemodule_from_instance('quiz', $qid)) {
            print_error('invalidquizid', 'quiz');
        }
        redirect($CFG->wwwroot . '/mod/quiz/startattempt.php?cmid=' . $cm->id . '&sesskey=' . sesskey());
    }

/// Get submitted parameters.
    $attemptid = required_param('attempt', PARAM_INT);
    $page = optional_param('page', 0, PARAM_INT);

    $attemptobj = new quiz_attempt($attemptid);

/// Check login.
    require_login($attemptobj->get_courseid(), false, $attemptobj->get_cm());

/// Check that this attempt belongs to this user.
    if ($attemptobj->get_userid() != $USER->id) {
        redirect($attemptobj->review_url(0, $page));
    }

/// Update QCA Object -- New
    $record = $DB->get_record('quiz_course_activation', array('username'=>$USER->username,'attemptid'=>NULL));
    if(!empty($record)){
        $record->attemptid = $attemptid;
        $DB->update_record('quiz_course_activation',$record);
    }

/// Check capabilites.
    if ($attemptobj->is_preview_user()) {
    } else {
        $attemptobj->require_capability('mod/quiz:attempt');
    }

/// If the attempt is already closed, send them to the review page.
    if ($attemptobj->is_finished()) {
        redirect($attemptobj->review_url(0, $page));
    }

/// Check the access rules.
    $accessmanager = $attemptobj->get_access_manager(time());
    $messages = $accessmanager->prevent_access();
    if (!$attemptobj->is_preview_user() && $messages) {
        print_error('attempterror', 'quiz', $quizobj->view_url(),
                $accessmanager->print_messages($messages, true));
    }
    $accessmanager->do_password_check($attemptobj->is_preview_user());

/// This action used to be 'continue attempt' but the database field has only 15 characters.
    add_to_log($attemptobj->get_courseid(), 'quiz', 'continue attemp',
            'review.php?attempt=' . $attemptobj->get_attemptid(),
            $attemptobj->get_quizid(), $attemptobj->get_cmid());

/// Get the list of questions needed by this page.
    $questionids = $attemptobj->get_question_ids($page);

/// Check.
    if (empty($questionids)) {
        quiz_error($quiz, 'noquestionsfound');
    }

/// Load those questions and the associated states.
    $attemptobj->load_questions($questionids);
    $attemptobj->load_question_states($questionids);

/// Print the quiz page ////////////////////////////////////////////////////////

    // Print the page header
    require_js(array('yui_yahoo','yui_event'));
    require_js('mod/quiz/quiz.js');
    $title = get_string('attempt', 'quiz', $attemptobj->get_attempt_number());
    $headtags = $attemptobj->get_html_head_contributions($page);
    if ($accessmanager->securewindow_required($attemptobj->is_preview_user())) {
        $accessmanager->setup_secure_page($attemptobj->get_course()->shortname . ': ' .
                format_string($attemptobj->get_quiz_name()), $headtags);
    } else {
        print_header_simple(format_string($attemptobj->get_quiz_name()), '', $attemptobj->navigation($title),
                '', $headtags, true, $attemptobj->update_module_button());
    }
    echo '<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>'; // for overlib

    if ($attemptobj->is_preview_user()) {
    /// Show the tab bar.
        $currenttab = 'preview';
        include('tabs.php');

    /// Heading and tab bar.
        print_heading(get_string('previewquiz', 'quiz', format_string($quiz->name)));
        $attemptobj->print_restart_preview_button();

    /// Inform teachers of any restrictions that would apply to students at this point.
        if ($messages) {
            print_box_start('quizaccessnotices');
            print_heading(get_string('accessnoticesheader', 'quiz'), '', 3);
            $accessmanager->print_messages($messages);
            print_box_end();
        }
    } else {
    /// Just a heading.
        if ($attemptobj->get_num_attempts_allowed() != 1) {
            print_heading(format_string($attemptobj->get_quiz_name()).' - '.$title);
        } else {
            print_heading(format_string($attemptobj->get_quiz_name()));
        }
    }

    // Start the form
    echo '<form id="responseform" method="post" action="', $attemptobj->processattempt_url(),
            '" enctype="multipart/form-data" accept-charset="utf-8">', "\n";
    echo '<div>';
    print_js_call('init_quiz_form');

/// Print the navigation panel in a left column.
    print_container_start();
    echo '<div id="left-column">';
    $attemptobj->print_navigation_panel('quiz_attempt_nav_panel', $page);
    echo '</div>';
    print_container_end();

/// Start the main column.
    echo '<div id="middle-column">';
    ?>
    <style type="text/css">
<!--  
#cd {
	margin: auto;
	height: 40px;
	width: 250px;
	font-family: "Courier New", Courier, mono;
	font-size: 24pt;
	color: #ffffff;
	text-align: center;
	font-weight: bold;
	background-image: url(../../count/back.jpg);
	vertical-align: middle;
}
#cd_end {
	margin: auto;
	height: 50px;
	width: 250px;
	font-family: "Courier New", Courier, mono;
	font-size: 24pt;
	color: #000;
	text-align: center;
	font-weight: bold;
	background-image: url(../../count/back2.jpg);
	vertical-align: middle;
}
-->
</style>

    <?php

    //echo "<SCRIPT language='JavaScript' SRC='$CFG->wwwroot/count/countdown.php?countto=1250744400'></SCRIPT>";
    print_container_start();
    echo skip_main_destination();

/// Print all the questions
    foreach ($attemptobj->get_question_ids($page) as $id) {
        $actual = $attemptobj->print_question($id, false, $attemptobj->attempt_url($id, $page));
        //$actual = $attemptobj->get_actual_id($id, false, $attemptobj->attempt_url($id, $page));
        saveQCAQuestions($id,$actual);
    }

/// Print a link to the next page.
    echo '<div class="submitbtns">';
    if ($attemptobj->is_last_page($page)) {
        $nextpage = -1;
        $nextpageforie = 'gotosummary';
    } else {
        $nextpage = $page + 1;
        $nextpageforie = 'gotopage' . $nextpage;
    }
    echo '<input type="submit" name="' . $nextpageforie . '" value="' . get_string('next') . '" />';
    echo "</div>";

    // Some hidden fields to trach what is going on.
    echo '<input type="hidden" name="attempt" value="' . $attemptobj->get_attemptid() . '" />';
    echo '<input type="hidden" name="nextpage" value="' . $nextpage . '" />';
    echo '<input type="hidden" name="timeup" id="timeup" value="0" />';
    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';

    // Add a hidden field with questionids. Do this at the end of the form, so
    // if you navigate before the form has finished loading, it does not wipe all
    // the student's answers.
    echo '<input type="hidden" name="questionids" value="' .
            implode(',', $attemptobj->get_question_ids($page)) . "\" />\n";

    // End middle column.
    print_container_end();

    // Finish the form
    echo '</div>';
    echo '</div>';
    echo "</form>\n";

    echo '<div class="clearer"></div>';

    // Finish the page
    $accessmanager->show_attempt_timer_if_needed($attemptobj->get_attempt(), time());
    if ($accessmanager->securewindow_required($attemptobj->is_preview_user())) {
        print_footer('empty');
    } else {
        print_footer($attemptobj->get_course());
    }


// Save Question IDs to QCA
function saveQCAQuestions($id,$actual){
    //echo $id."->".$actual;
}

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
