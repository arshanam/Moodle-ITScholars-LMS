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

    require_once($CFG->dirroot.'/mod/quiz/editlib.php');

    list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) = question_edit_setup('categories');

    // get values from form for actions on this page
    $param = new stdClass();

    // Added: Parameters for Special Course Form
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->edit = optional_param('edit', 0, PARAM_INT);
    $param->confadd = optional_param('confadd', 0, PARAM_INT);
    $param->confremove = optional_param('confremove', 0, PARAM_INT);
    $param->quizcat = optional_param('viewavailquizs', 0, PARAM_INT);
    $param->rmquiz = optional_param('rmquiz', 0, PARAM_INT);
    $param->svquiz = optional_param('svquiz', 0, PARAM_INT);
    $param->quizid = optional_param('cmid', 0, PARAM_INT);
    $param->addrandom = optional_param('addrandom', 0, PARAM_INT);

    $qcobject = new question_category_object($pagevars['cpage'], $thispageurl, $contexts->having_one_edit_tab_cap('categories'), $param->edit, $pagevars['cat'], $param->delete,
                                $contexts->having_cap('moodle/question:add'));

    $strspecialcategories = "Special Category";
    $editspecialcategory = "Edit Special Category";
    $addspecialcategory = "Add Special Category";
    $removespecialcategory = "Remove Special Category";

  
$navlinks = array();
    if ($cm!==null) {
        // Page header
        $strupdatemodule = has_capability('moodle/course:manageactivities', $contexts->lowest())
            ? update_module_button($cm->id, $COURSE->id, get_string('modulename', $cm->modname))
            : "";
        $navlinks[] = array('name' => get_string('modulenameplural', $cm->modname),
                            'link' => "$CFG->wwwroot/mod/{$cm->modname}/index.php?id=$COURSE->id",
                            'type' => 'activity');
        $navlinks[] = array('name' => format_string($module->name),
                            'link' => "$CFG->wwwroot/mod/{$cm->modname}/view.php?id={$cm->id}",
                            'type' => 'title');
    } else {
        // Print basic page layout.
        $strupdatemodule = '';
    }

    if(!$param->edit){
        if (!$param->confadd && !$param->confremove){
            $navlinks[] = array('name' => $strspecialcategories, 'link' => '', 'type' => 'title');
        }else if(!$param->confadd){
            $navlinks[] = array('name' => $strspecialcategories, 'link' => $thispageurl->out(), 'type' => 'title');
            $navlinks[] = array('name' => $removespecialcategory, 'link' => '', 'type' => 'title');
        }else{
            $navlinks[] = array('name' => $strspecialcategories, 'link' => $thispageurl->out(), 'type' => 'title');
            $navlinks[] = array('name' => $addspecialcategory, 'link' => '', 'type' => 'title');
        }


    } else {
        $navlinks[] = array('name' => $strspecialcategories, 'link' => $thispageurl->out(), 'type' => 'title');
        $navlinks[] = array('name' => $editspecialcategory, 'link' => '', 'type' => 'title');
    }

    $navigation = build_navigation($navlinks);
    print_header_simple($streditingcategories, '', $navigation, "", "", true, $strupdatemodule);

    // print tabs
    $currenttab = 'special';
    $context = $contexts->lowest();
    include('tabs.php');

    // Set Course Object
    $course = get_record('course','id',$param->courseid);
    $options  = array('courseid'=>$course->id);
    $url = "$CFG->wwwroot/question/special.php";

    // display UI
    if (!empty($param->addrandom) && !empty($param->quizid)) {
        
        list($quiz, $cm) = get_module_from_cmid($param->quizid);
        
        if (!empty($param->confadd)){
            //$mod = get_record('course_modules', 'id', $param->quizid);
            //echo $mod->instance;
             /// Add random questions to the quiz
             // - $param->quizid = CMOD id
             // - $param->addrandom = CAT id
            print_heading_block("Generate Quiz Questions");
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel' . $list->context->contextlevel);
            //echo $param->addrandom;
            if(addRandomQuizQuestions($param->quizid, $param->addrandom)){
                echo "Random questions were added to the quiz '$quiz->name'. <br/><br/>";
            }else{
                echo " Could not add the random questions were added to the quiz '$quiz->name'. <br/><br/>";
            }

            print_single_button($url, $options);
            print_box_end();

        }else{

            // Print the Add Random Quiz Questions to Selected Quiz Confirmation Page



            $cat = get_record('question_categories','id',$param->addrandom);

            $strconfirm = "Are you sure you want to add random quiz questions to selected quiz '$quiz->name' from the category '$cat->name'?";
            $optionsyes = array('courseid'=>$course->id, 'cmid'=>$param->quizid, 'confadd'=>1, 'addrandom'=>$param->addrandom);
            $optionsno  = array('courseid'=>$course->id);
            print_heading("Generate Quiz Questions");
            //print_header_simple($addspecialcategory, '', build_navigation(array(array('name'=>'Confirm Add', 'link'=>'', 'type'=>'misc'))));
            print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');
            notice_yesno($strconfirm, $url, $url, $optionsyes, $optionsno, 'get', 'get');
            print_simple_box_end();
        }

    } else if (!empty($param->confadd)) {

        // Print the Confirmation Add Page

        $id = $param->confadd;
        if(!record_exists('spc_question_categories','categoryid',$id)){
            //$cat = $DB->get_record('question_categories', array('id' => $id));
            editSPCourse($id,$course->id,true,$url,$options);
        }

    } else if (!empty($param->confremove)) {

        // Print the Confirmation Removal Page

        $id = $param->confremove;
        if(record_exists('spc_question_categories','categoryid',$id)){
            //$cat = $DB->get_record('question_categories', array('id' => $id));
            editSPCourse($id,$course->id,false,$url,$options);
        }

    } else if (!empty($param->quizcat)) {

        // Print the Available Course Page

        //print_Course_info($param->courseid,$param->quizcat);

            //Display Quizs for Course
        print_heading("Available Course Quizzes");
        print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');

        $quizzes = get_records('quiz','course',$course->id);

        echo "Select One of the following:";
        echo "<ul type='none'>";

        if(!empty($quizzes)){
            foreach ($quizzes as $quiz) {
                $mod = get_record('course_modules', 'course', $course->id, 'module', '12', 'instance', $quiz->id);

                echo "<li>";
                echo "<a href='$CFG->wwwroot/question/special.php?courseid=$course->id&svquiz=$param->quizcat&cmid=$mod->id'>";
                echo $quiz->name;
                echo "</a>";
                echo "</li>";
            }
            $quizzes->close();
        }else{
            echo "<li>There are no available quizs.<br/>Please create a quiz on the main course page.</li>";
            
        }

        echo "</ul>";
        echo "<a href='$CFG->wwwroot/question/special.php?courseid=$course->id'>Return</a>";

        print_simple_box_end();

    } else if (!empty($param->rmquiz)) {
        // Remove Quiz from SPCourse
        $spc = get_record_select('spc_courses', "courseid = '$course->id' AND id = '$param->rmquiz'");

        print_heading_block("Special Course Quiz Selection");
        print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel' . $list->context->contextlevel);


        if (!empty($spc)) {
            $mod = get_record('course_modules', 'id', $spc->quizid);
            $quiz = get_record_select('quiz', "course = '$course->id' AND id = '$mod->instance'");
            $spc->quizid = null;
            update_record('spc_courses',$spc);
            echo "The quiz '$quiz->name' was removed from the Special Course.<br/><br/>";
        }else{
            echo "Sorry, your request could not be processed.<br/><br/>";
        }

        print_single_button($url, $options);
        print_box_end();


    } else if (!empty($param->svquiz)) {
        // Add Quiz to SPCourse
        $spc = get_record_select('spc_courses', "courseid = '$course->id' AND id = '$param->svquiz'");

        print_heading_block("Special Course Quiz Selection");
        print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel' . $list->context->contextlevel);

        if (!empty($spc) && !empty($param->quizid)) {

            $mod = get_record('course_modules', 'id', $param->quizid);
            $quiz = get_record_select('quiz', "course = '$course->id' AND id = '$mod->instance'");
            $spc->quizid = $param->quizid;
            update_record('spc_courses',$spc);
            echo "The quiz '$quiz->name' was added to the Special Course.<br/><br/>";
        }else{
            echo "Sorry, your request could not be processed.<br/><br/>";
        }

        print_single_button($url, $options);
        print_box_end();


    } else if (!empty($param->edit)) {

        // Print the Add Special Categories / Remove Add Special Categories (Edit) Page

        $id = $param->edit;
        $cat = get_record('question_categories','id',$id);

        print_heading($editspecialcategories);
        print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel' . $list->context->contextlevel);
        print_heading_block($cat->name);
        //$r = $DB->get_record('spc_question_categories', array('categoryid' => $param->edit));

        if(record_exists('spc_question_categories','categoryid',$id)){
            //print_button($course->id,$id,$url,"Remove");

            $strconfirm = "Are you sure you want to remove the special course '$cat->name'?";
            $optionsyes = array('courseid'=>$course->id, 'confremove'=>$id);
            $optionsno  = array('courseid'=>$course->id);
            print_heading("Remove Special Category");
            //print_header_simple($addspecialcategory, '', build_navigation(array(array('name'=>'Confirm Add', 'link'=>'', 'type'=>'misc'))));
            print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');
            notice_yesno($strconfirm, $url, $url, $optionsyes, $optionsno, 'get', 'get');
            print_simple_box_end();

        }else{
            //print_button($course->id,$id,$url,"Add");

            $strconfirm = "Are you sure you want to add the special course '$cat->name'?";
            $optionsyes = array('courseid'=>$course->id, 'confadd'=>$id);
            $optionsno  = array('courseid'=>$course->id);
            print_heading("Add Special Category");
            //print_header_simple($addspecialcategory, '', build_navigation(array(array('name'=>'Confirm Add', 'link'=>'', 'type'=>'misc'))));
            print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');
            notice_yesno($strconfirm, $url, $url, $optionsyes, $optionsno, 'get', 'get');
            print_simple_box_end();

        }
        //print_button($param->edit,"Add");
        print_box_end();

    } else {

        // Print the Main Special Categories Page

        //print_heading_with_help($strspecialcategories, 'categories', 'question');
        //print_heading_block($strspecialcategories);
        print_heading($strspecialcategories);
        //$qcobject->editlists;

        foreach ($qcobject->editlists as $context => $list){
           // echo $qcobject->str."<br/>";

            $listhtml = $list->to_html(0, array('str'=>$qcobject->str));

            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel' . $list->context->contextlevel);

            // Parses the Course Info from Objects
            preg_match_all("/(<([\w]+)[^>]*>)(.*)(<\/\\2>)/", $listhtml, $matches, PREG_SET_ORDER);

            foreach ($matches as $val) {

                if(preg_match("/edit=[0-9]*/", $val[0], $match)){
                    //echo str_replace("edit=","add=",$val[0])."<br/>";

                    $id = parseCourseId($val[0]);

                    if(isSPCourse($id)){
                        echo "$val[0] &rarr; ";
                        //print_SPCategory_info($id,$param->courseid);
                        print_SPCategory_quiz_info($id,$param->courseid);

                        echo "<br/>";
                    }else{
                         echo "$val[0]<br/>";
                    }

                }
            }

            print_box_end();

        }
    }

    print_footer($COURSE);
?>

<?php

function parseCourseId($str){
    if(preg_match("/edit=[0-9]*/", $str, $match)){
        $str = substr($match[0],5);
    }
    return $str;
}

function isSPCourse($id){
    if(record_exists('spc_courses','categoryid',$id)){
        $exists =  true;
    }else{
        $exists = false;
    }
    return $exists;
}

function print_SPCategory_info($id,$cid){
    global  $CFG;
    $scurl = "$CFG->wwwroot/question/special.php?courseid=$cid&viewavailquizs=$id";
    echo "<a href='".$scurl."'><font color='green'>Available Quiz's</font></a>";
}

function print_SPCategory_quiz_info($id,$cid){
    global $CFG;
    //echo "ID:$id";
    $spc = get_record_select('spc_courses', "courseid = '$cid' AND categoryid = '$id'");
    if(!empty($spc)){
        // MODULES TABLE - Quiz - 13
        //$mod = get_record_select('course_modules', "course = '$cid' AND module = '13' AND instance = '$spc->quizid'");
        $mod = get_record('course_modules', 'id',$spc->quizid);

        if(!empty($spc->quizid)){
            $quiz = get_record_select('quiz', "id = '$spc->quizid' AND course = '$cid'");
            echo "<font color='red'>$quiz->name</font> - ";
            echo "<a href='$CFG->wwwroot/question/special.php?courseid=$cid&rmquiz=$spc->id'>remove</a>";
            echo " &rarr; ";
            echo "<a href='$CFG->wwwroot/mod/quiz/edit.php?cmid=$mod->id' target='_blank'>view quiz</a><br/>";
            echo "<font size='2'>Generate Questions from Categories: ";
            echo "<ol>"; //<li><a href='$CFG->wwwroot/question/special.php?cmid=$mod->id&courseid=$cid&addrandom=$id'>Top</a></li>";
            $cats = get_records_select('question_categories', "id = '$id' OR parent = '$id'");
            foreach($cats as $c){
                echo "<li><a href='$CFG->wwwroot/question/special.php?cmid=$mod->id&courseid=$cid&addrandom=$c->id'>$c->name</a></li>";
            }
            echo "</ol></font>";
        }else{
            echo "<a href='$CFG->wwwroot/question/special.php?courseid=$cid&viewavailquizs=$spc->id'>";
            echo "<font color='green'>Select Quiz</font></a>";
        }
    }

}

function print_Course_info($cid,$catid){
    global  $DB, $CFG;

    //Display Quizs for Course
    print_heading("Available Course Quiz's");
    print_simple_box_start('center', '60%', '#FFFFFF', 20, 'noticebox');
    echo "<table border='0' cellpadding='5' cellspacing='5'>";

    if ($rs = get_records('course_modules','course',$cid)) {

        echo "<tr><th>Quiz Name</th><th colspan='2'>Options</th></tr>";

        $quizurl = "$CFG->wwwroot/mod/quiz/edit.php?cmid=";
        $scurl = "$CFG->wwwroot/question/special.php?cmid=";
        foreach ($rs as $record) {
         //"$CFG->wwwroot/question/category.php?cmid=";
         $cmod = get_record('modules','id',$record->module);

         if($cmod->name == "quiz"){
         //if($cmod->module == "12"){

            list($module, $cm) = get_module_from_cmid($record->id);


            echo "<tr>";
            echo "<td>$module->name</td>";
            echo "<td><a href='".$quizurl.$cm->id."' target='_blank'>view quiz</a></td>";
            echo "<td><a href='".$scurl.$cm->id."&courseid=".$cid."&addrandom=$catid'>generate questions</a></td>";
            echo "</tr>";


         }

        }
        $rs->close(); /// Don't forget to close the recordset!
    }else{
        echo "<tr><td>There are no available quizs.<br/>Please create a quiz on the main course page.</td></tr>";
    }

    echo "</table>";
    print_simple_box_end();
}


function editSPCourse($catid,$cid,$adding, $link, $options){
    global  $DB;
    $output = '';
    $cat = get_record('question_categories','id',$catid);

    if($adding){
        // Create new Object
        $new_spc_qc->name = 'spc_'.$cat->name;
        $new_spc_qc->categoryid = $cat->id;

        $record = insert_record('spc_question_categories', $new_spc_qc);

        $r = get_record('spc_question_categories','id',$record);

        // Create new Object
        $new_spc_course->courseid = $cid;
        $new_spc_course->categoryid = $cat->id;
        $new_spc_course->name = $r->name;

        $record = insert_record('spc_courses', $new_spc_course);

        $r = get_record('spc_courses','id',$record);

        //echo "Record".$record." ID:".$r->id." Name:".$r->name." courseid:".$r->courseid." categoryid:".$r->categoryid."<br/>";
        $output .= "Special Course '".$r->name."' was added. <br/>";
    }else{
        delete_records('spc_courses','categoryid',$catid);
        delete_records('spc_question_categories','categoryid',$catid);
        
        if(record_exists('spc_question_categories','categoryid',$catid)){
            $output .= "Special Course '".$cat->name."' could not be deleted. <br/>";
        }else{
            $output .= "Special Course '".$cat->name."' was deleted. <br/>";
        }
    }

    print_heading_block($cat->name);
    print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel' . $list->context->contextlevel);
    echo $output."<br/>";
    print_single_button($link, $options);
    print_box_end();
}

 /// Add random questions to the quiz
function addRandomQuizQuestions($cmid,$cat_id){
    global $QTYPES;
        $result = true;
        $recurse = 1;

        list($quiz, $cm) = get_module_from_cmid($cmid);
        
        if ($rs = get_records('question_categories','parent',$cat_id,'sortorder')) {

            $course = get_record('course','id',$quiz->course);
            foreach ($rs as $cat) {
                
                $categoryid = $cat->id;
                $randomcount = 1;
                // load category
                if (! $category = get_record('question_categories', 'id', $categoryid)) {
                    error('Category ID is incorrect');
                }

                $catcontext = get_context_instance_by_id($category->contextid);
                require_capability('moodle/question:useall', $catcontext);
                $category->name = addslashes($category->name);
                // Find existing random questions in this category that are not used by any quiz.
                if ($existingquestions = get_records_sql(
                        "SELECT * FROM " . $CFG->prefix . "question q
                        WHERE qtype = '" . RANDOM . "'
                            AND category = $category->id
                            AND " . sql_compare_text('questiontext') . " = '$recurse'
                            AND NOT EXISTS (SELECT * FROM " . $CFG->prefix . "quiz_question_instances WHERE question = q.id)
                        ORDER BY id")) {
                    // Take as many of these as needed.
                    while (($existingquestion = array_shift($existingquestions)) and $randomcount > 0) {
                        if(!quiz_add_quiz_question($existingquestion->id, $quiz)){
                            $result = false;
                        }
                        $randomcount--;
                    }
                }

                // If more are needed, create them.
                if ($randomcount > 0) {
                    //echo "NOT EXISTING:".$cat->id."<br/>";
                    $form->questiontext = $recurse; // we use the questiontext field to store the info
                                                    // on whether to include questions in subcategories
                    $form->questiontextformat = 0;
                    $form->image = '';
                    $form->defaultgrade = 1;
                    $form->hidden = 1;

                    for ($i = 0; $i < $randomcount; $i++) {
                        $form->category = "$category->id,$category->contextid";
                        $form->stamp = make_unique_id_code();  // Set the unique code (not to be changed)

                        $question = new stdClass;
                        $question->qtype = RANDOM;

                        $question = $QTYPES[RANDOM]->save_question($question, $form, $course);

                        if(!isset($question->id)) {
                            error('Could not insert new random question!');
                            $result = false;
                        }
                        //quiz_add_quiz_question($question->id, $quiz);
                        if(!quiz_add_quiz_question($question->id, $quiz)){
                            $result = false;
                        }
                    }
                }
                $significantchangemade = true;
            }
        }
        return $result;

/*
    $result = true;
    $recurse = 1;

    list($quiz, $cm) = get_module_from_cmid($cmid);

    if ($rs = get_recordset('question_categories','parent',$cat_id)) {

            $course = get_record('course','id',$quiz->course);
            foreach ($rs as $category) {
                    echo $category->name."<br/>";
                    /// Add random questions to the quiz
                    // - Source from: /mod/quiz/edit.php
                    $randomcount = 1;
                    // load category

                    //$catcontext = get_context_instance_by_id($category->contextid);
                    //require_capability('moodle/question:useall', $catcontext);
                    $category->name = $category->name;
                    // Find existing random questions in this category that are
                    // not used by any quiz.
                    if ($existingquestions = get_records_sql(
                            "SELECT q.id,q.qtype FROM {question} q
                            WHERE qtype = '" . RANDOM . "'
                                AND category = ?
                                AND " . $DB->sql_compare_text('questiontext') . " = ?
                                AND NOT EXISTS (SELECT * FROM {quiz_question_instances} WHERE question = q.id)
                            ORDER BY id", array($category->id, $recurse))) {
                        // Take as many of these as needed.
                        while (($existingquestion = array_shift($existingquestions)) && $randomcount > 0) {
                            //quiz_add_quiz_question($existingquestion->id, $quiz);
                            if(!quiz_add_quiz_question($existingquestion->id, $quiz)){
                                $result = false;
                            }
                            $randomcount--;
                        }
                    }

                    // If more are needed, create them.
                    if ($randomcount > 0) {

                        $form->questiontext = $recurse; // we use the questiontext field
                                // to store the info on whether to include
                                // questions in subcategories
                        $form->questiontextformat = 0;
                        $form->image = '';
                        $form->defaultgrade = 1;
                        $form->hidden = 1;
                        for ($i = 0; $i < $randomcount; $i++) {
                            $form->category = $category->id . ',' . $category->contextid;
                            $form->stamp = make_unique_id_code(); // Set the unique
                                    //code (not to be changed)
                            $question = new stdClass;
                            $question->qtype = RANDOM;
                            $question = $QTYPES[RANDOM]->save_question($question, $form, $course);

                            if(!isset($question->id)) {
                                //print_error('cannotinsertrandomquestion', 'quiz');
                                $result = false;
                            }
                            //quiz_add_quiz_question($question->id, $quiz);
                            if(!quiz_add_quiz_question($question->id, $quiz)){
                                $result = false;
                            }
                        }
                    }

                    quiz_update_sumgrades($quiz);
                    quiz_delete_previews($quiz);

            }
            //$rs->close(); /// Don't forget to close the recordset!
    }
    return $result;

 */
}

?>
