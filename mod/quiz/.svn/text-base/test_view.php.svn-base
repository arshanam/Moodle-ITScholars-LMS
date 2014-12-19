<?php  // $Id: view.php,v 1.165 2009-05-08 09:00:42 tjhunt Exp $

/// This page prints a particular instance of quiz

    //require_once(dirname(__FILE__) . '/../../config.php');
    require_once("../../config.php");
    //require_once($CFG->libdir.'/gradelib.php');
    require_once($CFG->dirroot.'/mod/quiz/locallib.php');
    //require_once($CFG->libdir . '/completionlib.php');

    $id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
    $q = optional_param('q',  0, PARAM_INT);  // quiz ID

    prepareQuizAttempt(1);


function prepareQuizAttempt($q){
    global $DB;

    if ($id) {
        if (! $cm = get_coursemodule_from_id('quiz', $id)) {
            print_error('invalidcoursemodule');
        }
        if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
            print_error('coursemisconf');
        }
        if (! $quiz = $DB->get_record('quiz', array('id' => $cm->instance))) {
            print_error('invalidcoursemodule');
        }
    } else {
        if (! $quiz = $DB->get_record('quiz', array('id' => $q))) {
            print_error('invalidquizid', 'quiz');
        }
        if (! $course = $DB->get_record('course', array('id' => $quiz->course))) {
            print_error('invalidcourseid');
        }
        if (! $cm = get_coursemodule_from_instance("quiz", $quiz->id, $course->id)) {
            print_error('invalidcoursemodule');
        }
    }

/// Check login and get context.
    require_login($course->id, false, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    require_capability('mod/quiz:view', $context);

/// Cache some other capabilites we use several times.
    $canattempt = has_capability('mod/quiz:attempt', $context);
    $canreviewmine = has_capability('mod/quiz:reviewmyattempts', $context);
    $canpreview = has_capability('mod/quiz:preview', $context);

/// Create an object to manage all the other (non-roles) access rules.
    $timenow = time();
    $accessmanager = new quiz_access_manager(new quiz($quiz, $cm, $course), $timenow,
            has_capability('mod/quiz:ignoretimelimits', $context, NULL, false));


/// Print information about the student's best score for this quiz if possible.
    $moreattempts = $unfinished || !$accessmanager->is_finished($numattempts, $lastfinishedattempt);


/// Determine if we should be showing a start/continue attempt button,
/// or a button to go back to the course page.
    print_box_start('quizattempt');
    $buttontext = ''; // This will be set something if as start/continue attempt button should appear.
    if (!$quiz->questions) {
        print_heading(get_string("noquestions", "quiz"));
    } else {
        if ($unfinished) {
            if ($canattempt) {
                $buttontext = get_string('continueattemptquiz', 'quiz');
            } else if ($canpreview) {
                $buttontext = get_string('continuepreview', 'quiz');
            }
        } else {
            if ($canattempt) {
                $messages = $accessmanager->prevent_new_attempt($numattempts, $lastfinishedattempt);
                if ($messages) {
                    $accessmanager->print_messages($messages);
                } else if ($numattempts == 0) {
                    $buttontext = get_string('attemptquiznow', 'quiz');
                } else {
                    $buttontext = get_string('reattemptquiz', 'quiz');
                }
            } else if ($canpreview) {
                $buttontext = get_string('previewquiznow', 'quiz');
            }
        }

        // If, so far, we think a button should be printed, so check if they will be allowed to access it.
        if ($buttontext) {
            if (!$moreattempts) {
                $buttontext = '';
            } else if ($canattempt && $messages = $accessmanager->prevent_access()) {
                $accessmanager->print_messages($messages);
                $buttontext = '';
            }
        }
    }

/// Now actually print the appropriate button.
    if ($buttontext) {
        $accessmanager->print_start_attempt_button($canpreview, $buttontext, $unfinished);
    } else {
        print_continue($CFG->wwwroot . '/course/view.php?id=' . $course->id);
    }
    print_box_end();
}


?>
