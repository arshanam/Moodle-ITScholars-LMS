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

    // for prepareQuizAttempt Function
    require_once($CFG->dirroot.'/mod/quiz/locallib.php');

    //list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) = question_edit_setup('categories');

    // get values from form for actions on this page
    $param = new stdClass();

    // Added: Parameters for Instructions Form
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->edit = optional_param('edit', 0, PARAM_INT);
    $param->add = optional_param('add', 0, PARAM_INT);
    $param->name = optional_param('name', '', PARAM_ALPHA);
    $param->desc = optional_param('desc', '', PARAM_ALPHA);
    $param->instr = optional_param('instr', '', PARAM_ALPHA);
    $param->typeid = optional_param('typeid', 0, PARAM_INT);
    $param->addInstr = optional_param('addInstr', 0, PARAM_INT);

    $param->view = required_param('view', PARAM_INT);
    $param->eid = required_param('eid', PARAM_INT);

    // Build Navigation
    $strspecialcategories = "Exam Orientation: Part ".$param->view;
    $navlinks[] = array('name' => $strspecialcategories, 'link' => null, 'type' => 'misc');

    $navigation = build_navigation($navlinks);
    //print_header_simple($streditingcategories, '', $navigation, "", "", true, $strupdatemodule);
    //print_header($SITE->fullname, $SITE->fullname, $navigation);
    print_header($SITE->fullname, $SITE->fullname, null);

    // Check if the User is an Admin - to Display accordingly
    $userRole = get_record('role_assignments','userid',$USER->id);
    $admin = false;
    if (!empty($userRole)) {
        if ($userRole->roleid == 1 || $userRole->roleid == 2 || $userRole->roleid == 3) {
            $admin = true;
        }
    }

    // display UI
    if($param->view && $param->eid){ // Redundant
        
      if(record_exists('quiz_course_activation','id',$param->eid)){
        // Set QCA Object
        $qca = get_record('quiz_course_activation','id',$param->eid);

        if($param->courseid == $qca->courseid){
            // Set Course Object
            $course = get_record('course','id',$qca->courseid);

            $options  = array('courseid'=>$course->id);
            $url = "$CFG->wwwroot/question/view_instructions.php";

            // Check if Context Exist
            checkContext($qca,$course);

            // Reset $qca - Incase changes where made from checkContext()
            $qca = get_record('quiz_course_activation','id',$param->eid);

            // Set Exam Context
            if($qca->exam_context != NULL){
                $eqc = get_record('exam_quiz_context','id',$qca->exam_context);
            }else{
                //redirect("$CFG->wwwroot/index.php");
                close_this_window();
            
            }
            
            if($admin){
                $student = get_record('quiz_course_activation','exam_context',$eqc->id);
                if (!empty($student)){
                    echo "<b>Instructions for:</b> $student->username<br/>";
                }
            }

            // display Instructions / Pages
            if (!empty($param->view)){
                if($param->view == 1){
                    // Instructions part 1
                    $instructions = get_record('instructions','id',$eqc->instr1_id);

                    $quiz = get_record('quiz','course', $course->id,'id',$qca->quizid);

                    $exam_length = $quiz->timelimit;
                    if($exam_length == 0){
                        $exam_length = 30;
                    }

                    // 30 mins = 1800
                    // 2 hours = 7200
                    //$currenttime = mktime(8,59,50,8,20,2009);
                    $currenttime = time();
                    //$currenttime = date("U",mktime(14,0,01,10,7,2009));
                    //$currenttime = date("U",mktime(13,27,0,10,7,2009));
                    //$currenttime = date("U",mktime(22,29,45,9,27,2009));
                    //$currenttime = date("U",mktime(1,0,0,9,13,2009));
                    $timeleft = $qca->starttime - $currenttime;
                    $timeover = ($qca->starttime - $currenttime) + ($exam_length * 60);
                    //$examover = ($qca->starttime - $currenttime) + 7200;

               

                    if($currenttime >= ($qca->starttime + ($exam_length * 60)) && $currenttime <= $qca->endtime){
                        $practover = 0;
                    }else{
                        $practover = 1;
                    }
                    if($currenttime >= $qca->starttime && $currenttime <= ($qca->starttime + ($exam_length * 60))){
                        $examover = 0;
                    }else{
                        $examover = 1;
                    }
                    
                    //$difference =($target-$today) ;
                    //$days =(int) ($difference/86400) ;
                    //$hours =(int) ($difference/3600) ;
                    //$minutes =(int) ($difference/60) ;
                    //$seconds =(int) ($difference/1) ;

                    print_container_start();
                    echo "<div id='displaybar1'></div><br/><br/>";
                    echo "<h3>Instructions : Part 1</h3>";

                    echo "<script language='javascript'>";
                    echo "var countDownInterval=$timeleft;";   //configure refresh interval (in seconds) 1800 = 30 min
                    echo "var c_reloadwidth=200;";   //configure width of displayed text, in px (applicable only in NS4)
                    echo "</script>";

                    ?>


                    <ilayer id="c_reload" width=&{c_reloadwidth}; ><layer id="c_reload2" width=&{c_reloadwidth}; left=0 top=0></layer></ilayer>


                    <?php


                    echo "<table border='0' cellpadding='5' cellspacing='5' width='650px'>";
                    echo "<tr><td valign='top'>";
                    echo replace_keywords_view($eqc->contextid,$instructions->text,$course,$qca)."<br/><br/>";
                    echo "</td></tr>";
                    echo "<tr><td align='right'>";
                    echo "<div id='attemptoption1' style='display: ;'>";
                    //print_single_button('', null, 'Exam Part I: Concepts!', 'get', '', false, '', true);
                    echo "</div>";
                    echo "<div id='attemptoption2' style='display: none;'>";

                    $options = array('courseid'=>$param->courseid,'view'=>2,'eid'=>$param->eid);
                    $practical = print_single_button($url, $options, 'Exam Part II: Implementation!', 'get', '', true, '', false);


                    // Check if Attempt is closed.
                    if($qca->attemptid != null && isAttemptClosed($eqc->quizid,$USER->id,$qca->attemptid)){
                        echo "<font color='green'><b>Exam Part I: Concepts</b> has been submitted.</font><br/>";
                        echo $practical;
                    }
                    /*else{
                        if($qca->attemptid != null){
                            $examoptions = array('q'=>$eqc->quizid);
                            print_single_button("$CFG->wwwroot/mod/quiz/attempt.php", $examoptions, 'Exam Part I: Concepts!', 'get', '', false, '', false);
                        }else{
                            $examoptions = array('forcenew'=>1,'q'=>$eqc->quizid);
                            print_single_button("$CFG->wwwroot/mod/quiz/attempt.php", $examoptions, 'Exam Part I: Concepts!', 'get', '', false, '', false);
                        }
                    }
                    */
                    echo "</div>";
                    echo "</td></tr>";
                    echo "</table>";
                    //echo "<div id='displaybar1'></div>";
                    print_container_end();

                    
                    if(!$admin){
                    ?>

                    <script language='javascript' type="text/javascript">

                        var countDownTime=countDownInterval+1;
                        var cdminutes = "";
                        var cdseconds = "";

                        var currtime = <?php echo $currenttime; ?>;
                        var overtime = <?php echo $timeover; ?>;
                        var examover = <?php echo $examover; ?>;
                        var practover = <?php echo $practover; ?>;

                        var atm=document.getElementById('attemptoption2');
                        var div=document.getElementById('attemptoption1');
                        var bar=document.getElementById('displaybar1');
                        var attemptopt = atm.innerHTML;

                        var practical = '<?php echo $practical; ?>';

                        atm.innerHTML = "";
                        //alert('JS');
                        function countDown(){
                            countDownTime--;

                            if (countDownTime <=0){
                                if(!examover){
                                    countDownTime=countDownInterval;
                                    //clearTimeout(counter);
                                    //window.location.reload();
                                    div.style.display='none';
                                    atm.innerHTML = attemptopt;
                                    atm.style.display='inline';
                                    bar.innerHTML = "<b><font color='green'>Your Exam has STARTED.</font></b>";
                                    //window.location = "/index.php";
                                    //alert('examover = T');
                                }else{
                                    //alert('examover = F');
                                   if(practover){
                                        //alert('practover = T');
                                        div.style.display='none';
                                        //bar.innerHTML = "<b><font color='red'>Your Exam &amp; Practical is OVER.</font></b>";
                                         bar.innerHTML = "<b><font color='green'>Your Exam has STARTED.</font></b>";
                                        //window.location = "<?php echo "$CFG->wwwroot/index.php"; ?>";
                                        //window.close();
                                   }else{
                                       //alert('practover = F');
                                        div.innerHTML = practical;
                                        bar.innerHTML = "<b><font color='red'>Your Exam is OVER.</font><br/><font color='green'>Your Practical has STARTED.</font></b>";
                                   }
                                }
                                return
                            }
                            cdminutes = "" + parseInt(countDownTime / 60) % 60 + " minutes ";
                            cdseconds = "" + countDownTime % 60 + " seconds ";
                            //alert(cdminutes);
                            //alert(cdseconds);
                            if (document.all) //if IE 4+
                                document.all.countDownText.innerText = cdminutes+" "+cdseconds+" ";
                            else if (document.getElementById) //else if NS6+
                                document.getElementById("countDownText").innerHTML=cdminutes+" "+cdseconds+" ";
                            else if (document.layers){ //CHANGE TEXT BELOW TO YOUR OWN
                                document.c_reload.document.c_reload2.document.write('Exam starts in <b id="countDownText">'+cdminutes+' '+cdseconds+'</b>');
                                document.c_reload.document.c_reload2.document.close();
                            }
                                counter=setTimeout("countDown()", 1000);
                        }

                        function startit(){
                            if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN
                            bar.innerHTML = 'Exam starts in <b id="countDownText">'+cdminutes+' '+cdseconds+'</b>';
                            //document.write('<div id="displaybar1">Exam starts in <b id="countDownText">'+cdminutes+' '+cdseconds+'</b></div>');
                            countDown();
                        }

                        if (document.all||document.getElementById)
                            startit();
                        else
                            window.onload=startit;

                    </script>




                    <?php
                    }

                }else if($param->view == 2){
                    // Instructions part 2
                    $instructions = get_record('instructions','id',$eqc->instr2_id);

                    print_container_start();
                    echo "<h3>Instructions : Part 2</h3>";
                    echo "<table border='0' cellpadding='5' cellspacing='5' width='650px'>";
                    echo "<tr><td valign='top'>";
                    echo replace_keywords_view($eqc->contextid,$instructions->text,$course,$qca)."<br/><br/>";
                    //echo "</td></tr>";
                    //echo "<tr><td align='right'>";
                    //if(!$admin){
                    //    print_single_button($qca->url, null, 'Start Practical', 'get', '', false, '', false);
                    //}
                    echo "</td></tr>";
                    echo "</table>";
                    print_container_end();
                    ?>

                    <?php

                }else{
                    // Send user back to main page
                    //redirect("$CFG->wwwroot/index.php");
                    close_this_window();
                }
            }else{
                // Send user back to main page - no veiw param
                //redirect("$CFG->wwwroot/index.php");
                close_this_window();
            }
        }else{
            // Invalid Course ID - Does not match Exam
            //redirect("$CFG->wwwroot/index.php");
            close_this_window();
        }
      }else{
          //redirect
          redirect("$CFG->wwwroot/index.php");
      }
    }

    echo close_window_button();

    //print_footer($COURSE);
    print_footer();



?>
<script type="text/javascript">


    var footer = document.getElementById('footer');

    footer.style.display='none';
</script>

<?php

  // REVISED:Mar 01 . 2010
function checkContext($qca,$course){

    if(!empty($qca)){
        //$eqc = get_record('exam_quiz_context', 'id', $qca->exam_context);
        //$quizcontext = $DB->get_record('quiz_course_activation', array('exam_context' => $course->id, 'id' => $spc->quizid));
        if($qca->exam_context == NULL){
            // Old Version : 03.01.2010
            $ec = getRandomContextGroup($course);

            if($ec != 0){

                // Get Selected Instructions
                //$intr1 = getRandomInstructions($course,1);
                //$intr2 = getRandomInstructions($course,2);
                $intr1 = assignInstructions($qca->courseid,1,$qca->quizid);
                $intr2 = assignInstructions($qca->courseid,2,$qca->quizid);

                // Check if valid instruction
                if($intr1 == 0) $intr1 = NULL;
                if($intr2 == 0) $intr2 = NULL;


                $record_id = insert_record('exam_quiz_context', array(
                                            'courseid'=>$qca->courseid,
                                            'quizid'=>$qca->quizid,
                                            'contextid'=>$ec,
                                            'instr1_id'=>$intr1,
                                            'instr2_id'=>$intr2
                                        ));

                $qca->exam_context = $record_id;

                update_record('quiz_course_activation',$qca);

            }
        }else{
            // Added: Instructions assigned on registeration
            $eqc = get_record('exam_quiz_context', 'id', $qca->exam_context);
            //$quizcontext = $DB->get_record('quiz_course_activation', array('exam_context' => $course->id, 'id' => $spc->quizid));
            if($eqc->contextid == NULL){

                $ec = getRandomContextGroup($course);

                if($ec != 0){

                    $eqc->contextid = $ec;

                    update_record('exam_quiz_context',$eqc);

                }
            }

        }
    }
}


function getRandomContextGroup($course){
    $cnt = count_records('context_key_groups','courseid',$course->id);
    $id = 0;

    if($cnt > 0){
        $rnd = rand(1, $cnt); // random count id
        $cnt = 0; // reset as counter

        $records = get_records('context_key_groups','courseid',$course->id);
        foreach ($records as $record) {
            $cnt++;
            if($cnt == $rnd){
                $id = $record->id;
            }
        }
        //$records->close();
    }else{
        $id = 0;
    }
    return $id;
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

function getRandomInstructions($course,$typeid){
    $type = get_record('instructions_type','courseid',$course->id,'count',$typeid);
    $cnt = count_records('instructions','courseid',$course->id,'typeid',$type->id);
    $id = 0;

    if($cnt > 0){
        $rnd = rand(1, $cnt); // random count id
        $cnt = 0; // reset as counter
        $records = get_records_select('instructions',"courseid = '$course->id' AND typeid = '$type->id'");
        //$records = get_records('instructions','courseid',$course->id,'typeid',$type->id);
        foreach ($records as $record) {
            $cnt++;
            if($cnt == $rnd){
                $id = $record->id;
                // Set Instruction to Active Status
                if($record->active == NULL){
                    $record->active = 1;
                }else{
                    $record->active = $record->active + 1;
                }
                update_record('instructions',$record);
            }
        }
        //$records->close();
    }else{
        $id = 0;
    }
    return $id;
}

function checkHardCodedKeys($str,$record){
    // <<< [a-zA-Z0-9]+ >>>

    //Set the URL for hardcoded key
    $url = $record->url;    // must contain http to view outside pages.

    $matched = preg_match_all("/<<< [a-zA-Z0-9]+ >>>/", $str, $matches);
    if($matched){
        foreach ($matches as $match) {
            foreach ($match as $val) {

                switch($val){
                    case "<<< KServerName >>>":
                        $key = "Kaseya Server";
                        $str = str_ireplace($val,$key,$str,$count);
                        break;
                    case "<<< KServerURL >>>":
                        $key = $url; // URL - from database record
                        $str = str_ireplace($val,$key,$str,$count);
                        break;
                    default:
                        break;
                }
            }
        }
    }
    // &lt;&lt;&lt; [a-zA-Z0-9]+ &gt;&gt;&gt;
    $matched = preg_match_all("/&lt;&lt;&lt; [a-zA-Z0-9]+ &gt;&gt;&gt;/", $str, $matches);
    if($matched){
        foreach ($matches as $match) {
            foreach ($match as $val) {
                switch($val){
                    case "&lt;&lt;&lt; KServerName &gt;&gt;&gt;":
                        $key = "Kaseya Server";
                        $str = str_ireplace($val,$key,$str,$count);
                        break;
                    case "&lt;&lt;&lt; KServerURL &gt;&gt;&gt;":
                        $key = $url; // URL - from database record
                        $str = str_ireplace($val,$key,$str,$count);
                        break;
                    default:
                        break;
                }
            }
        }
    }
    return $str;
}

function replace_keywords_view($group,$str,$course,$record){
    //$matched = preg_match_all("/<< keyword[0-9]+ >>/", $str, $matches);
    //$matched = preg_match_all("/<< [a-zA-Z0-9]+ >>/", $str, $matches);

    //check for Hard Coded Key Words
    $str = checkHardCodedKeys($str,$record);

    // << [a-zA-Z0-9]+ >>
    $matched = preg_match_all("/<< [a-zA-Z0-9]+ >>/", $str, $matches);
    if($matched){
        foreach ($matches as $match) {
            foreach ($match as $val) {
                $key = get_keywords_view($group,substr($val,3,strpos($val," >>")-2),$course);
                $str = str_ireplace($val,$key,$str,$count);
            }
        }
    }
    // &lt;&lt; [a-zA-Z0-9]+ &gt;&gt;
    $matched = preg_match_all("/&lt;&lt; [a-zA-Z0-9]+ &gt;&gt;/", $str, $matches);
    if($matched){
        foreach ($matches as $match) {
            foreach ($match as $val) {
                //echo substr($val,9,strpos($val," >>")-8)." <br/>";
                $key = get_keywords_view($group,substr($val,9,strpos($val," >>")-8),$course);
                $str = str_ireplace($val,$key,$str,$count);
            }
        }
    }



    return $str;
}

function get_keywords_view($group,$keyword,$course){
    $key = get_record('quiz_context_keys','key_code',$keyword,'courseid',$course->id);

    $record = get_record('context_key_words','key_group',$group,'key_id',$key->key_id,'courseid',$course->id);

    if (!empty($record)) {
        return $record->keyword;
    }else{
        return "";
    }
}

function print_instruction_form($url,$opt,$label,$selects,$course,$instrid,$edittext='',$editselect='',$disabled=false){

    // Display Add Instruction Form
    print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

    $options  = array('courseid'=>$course->id,'add'=>$opt,'instrid'=>$instrid);

    echo "<table border='0' cellpadding='5' cellspacing='5' width='650px'>";
    echo "<tr><td>";
        print_form_start($url,"post");
        echo "<table border='0' cellpadding='5' cellspacing='5' width='650px'>";
        echo "<tr><th colspan='2' align='left'>Add Instructions</th></tr>";
        echo "<tr>";
        echo "<td>Instruction Type:</td>";
        echo "<td>";
        choose_from_menu ($selects, 'typeid', $editselect,'choose','','0', false, $disabled);
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Instruction Text:</td>";
        echo "<td>";
        //print_textfield ('addkeyword', '','',25);
        //print_textarea($usehtmleditor, $rows, $cols, $width, $height, $name);
        print_textarea(can_use_html_editor(), 15, 45, 371, 167, 'instr', $edittext);
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        print_button($label." Instructions",$options);
        print_form_end();
        print_single_button($url, array('courseid'=>$course->id),"Cancel");
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    print_box_end();

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

function isAttemptClosed($quizid,$uid,$attemptid){

    $closed = false;

    $attempt = get_record('quiz_attempts','id',$attemptid,'quiz',$quizid,'userid',$uid);
    //echo "$attempt->timefinish";
    if($attempt){
        if($attempt->timefinish > 0){
            $closed = true;
        }
    }

    return $closed;
}

function prepareQuizAttempt($q){

        if (! $quiz = get_record('quiz','id',$q)) {
            print_error('invalidquizid', 'quiz');
        }
        if (! $course = get_record('course','id',$quiz->course)) {
            print_error('invalidcourseid');
        }
        if (! $cm = get_coursemodule_from_instance("quiz", $quiz->id, $course->id)) {
            print_error('invalidcoursemodule');
        }
    

/// Check login and get context.
    //require_login($course->id, false, $cm);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    //require_capability('mod/quiz:view', $context);

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
                //$buttontext = get_string('continueattemptquiz', 'quiz');
                $buttontext = "Continue Exam";
            } else if ($canpreview) {
                $buttontext = get_string('continuepreview', 'quiz');
            }
        } else {
            if ($canattempt) {
                $messages = $accessmanager->prevent_new_attempt($numattempts, $lastfinishedattempt);
                if ($messages) {
                    $accessmanager->print_messages($messages);
                } else if ($numattempts == 0) {
                    //$buttontext = get_string('attemptquiznow', 'quiz');
                    $buttontext = "Start Exam";
                } else {
                    //$buttontext = get_string('reattemptquiz', 'quiz');
                    $buttontext = "Start Exam";
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
        print_continue($CFG->wwwroot);
    }
    print_box_end();
}


?>