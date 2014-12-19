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



    list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) = question_edit_setup('categories');

    // get values from form for actions on this page
    $param = new stdClass();

    // Added: Parameters for Instructions Form
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->edit = optional_param('edit', 0, PARAM_INT);
    $param->add = optional_param('add', 0, PARAM_INT);
    $param->name = optional_param('name', '', PARAM_ALPHA);
    $param->desc = optional_param('desc', '', PARAM_ALPHA);
    //$param->instr = optional_param('instr', '', PARAM_ALPHA);
    //$param->typeid = optional_param('typeid', 0, PARAM_INT);
    //$param->quizid = optional_param('quizid', 0, PARAM_INT);
    $param->addInstr = optional_param('addInstr', 0, PARAM_INT);
    $param->view = optional_param('view', 0, PARAM_INT);

    $strspecialcategories = "Exam Instructions";
    $editspecialcategory = "Edit Instructions";
    $addspecialcategory = "Add Instructions";
    $removespecialcategory = "Remove Instructions";
  
    $navlinks = array();

    if(!$param->edit){
        if ($param->addInstr){
            $navlinks[] = array('name' => $strspecialcategories, 'link' => '', 'type' => 'title');
            $navlinks[] = array('name' => $addspecialcategory, 'link' => '', 'type' => 'title');
        }else if($param->view){
            $navlinks[] = array('name' => $strspecialcategories, 'link' => $thispageurl->out(), 'type' => 'title');
            $navlinks[] = array('name' => $editspecialcategory, 'link' => '', 'type' => 'title');
        }else{
            $navlinks[] = array('name' => $strspecialcategories, 'link' => $thispageurl->out(), 'type' => 'title');
        }


    } else {
        $navlinks[] = array('name' => $strspecialcategories, 'link' => $thispageurl->out(), 'type' => 'title');
        $navlinks[] = array('name' => $editspecialcategory, 'link' => '', 'type' => 'title');
    }

    $navigation = build_navigation($navlinks);
    print_header_simple($streditingcategories, '', $navigation, "", "", true, $strupdatemodule);

    // print tabs
    $currenttab = 'instructions';
    $context = $contexts->lowest();
    include('tabs.php');

    // Set Course Object
    $course = get_record('course','id',$param->courseid);
    $options  = array('courseid'=>$course->id);
    $url = "$CFG->wwwroot/question/instructions.php";

    // Drop Down box
    $selects = array();
    if ($rs = get_records('instructions_type','courseid',$course->id)) {
        foreach ($rs as $record) {
            $selects[$record->id] = $record->name;
        }
        //$rs->close();
    }

    // display UI
    $usehtmleditor = can_use_richtext_editor();



    if (!empty($param->addInstr)) {
        print_instruction_form($url,2,"Add",$selects,$course,0);

    } else if (!empty($param->view)){
        if(record_exists('instructions','id',$param->view,'courseid',$course->id)){
            $record = get_record('instructions','id',$param->view,'courseid',$course->id);
            print_instruction_form($url,3,"Edit",$selects,$course,$record->id,$record->text,$record->typeid,$record->quizid,true);
        }else{
            displayMainContextPage($course,$selects);
        }
    } else if (!empty($param->add)) {

        if($param->add == 1){
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

            if(!empty($param->name)){
                // Add Instructions Type
                $cnt = count_records('instructions_type','courseid',$course->id);

                $insert = insert_record('instructions_type',array(
                                    'name'=>$param->name,
                                    'courseid'=>$course->id,
                                    'count'=>$cnt+1,
                                ));
                if($insert){
                    echo "The Instruction Type ".$param->name." was created.'<br/><br/>";
                }else{
                    echo "The Instruction Type ".$param->name." could not be created.'<br/><br/>";
                }
            }else{
                echo "Please enter a Instruction Type name.<br/><br/>";
            }
            print_single_button($url, $options);
            print_box_end();
        }else if($param->add == 2){
                // Add Instructions
                print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

                if($form = data_submitted()) {
                    if($form->typeid != 0 && $form->quizid != 0 && $form->instr != ""){
                        $cnt = count_records('instructions','typeid',$form->typeid,'courseid',$course->id);
                        $insert = insert_record('instructions',array(
                                        'typeid'=>$form->typeid,
                                        'text'=>$form->instr,
                                        'count'=>$cnt+1,
                                        'courseid'=>$course->id,
                                        'quizid'=>$form->quizid
                                    ));

                        if(!empty($insert)){
                            echo "The following instructions for 'type ".$form->typeid."' were added:<br/><br/>";
                            echo $form->instr."<br/><br/>";
                        }else{
                            echo "The instructions for 'type ".$form->typeid."' could not be submitted.<br/><br/>";
                        }
                    }else{
                        echo "The instructions could not be submitted.<br/><br/>";
                    }
                }else{
                    echo "The instructions could not be submitted.<br/><br/>";
                }

                print_single_button($url, $options);
                print_box_end();

        }else if($param->add == 3){
                // Edit Instructions
                print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

                if($form = data_submitted()) {
                    if(record_exists('instructions','id',$form->instrid,'courseid',$course->id)){
                        $record = get_record('instructions','id',$form->instrid,'courseid',$course->id);
                        $record->text = $form->instr;
                        $record->quizid = $form->quizid;
                        update_record('instructions',$record);
                    }


                    echo "The following instructions for 'type ".$form->add."' were updated:<br/><br/>";
                    echo $form->instr."<br/><br/>";

                }else{
                    echo "The following instructions for 'type ".$form->add."' could not be updated.<br/><br/>";
                }
                print_single_button($url, $options);
                print_box_end();

        } else {
            displayMainContextPage($course,$selects);
        }
    } else {
        displayMainContextPage($course,$selects);
    }

    if ($usehtmleditor) {
        use_html_editor();
    }

    print_footer($COURSE);
?>

<?php

function displayMainContextPage($course,$selects) {

        print_heading($strspecialcategories);

        // Print the Main Instructions Page

        echo "<table border='0' cellpadding='5' cellspacing='5' class='boxwidthwide boxaligncenter generalbox questioncategories contextlevel'>";
        echo "<tr><td>";

            // Display Add Instruction Type Form
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

            echo "<b>Add Instructions Type</b><br/><br/>";
            $options  = array('courseid'=>$course->id,'add'=>1);

            print_form_start($url);
            echo "Name: ";
            print_textfield ('name', '','',25);
            //echo "Description: ";
            //print_textfield ('desc', '','',25);
            echo "<br/>";
            print_button('Add Type',$options);
            print_form_end();
            print_box_end();

        echo "</td>";
        echo "<td rowspan='2'>";

            // Edit Instructions Scroll Table
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
            echo "<table summary='' cellpadding='0' cellspacing='1' align='center' title='' width='400' border='0' bgcolor='#FAFAFA'>";
            echo "<tr><td>";
            echo "<table summary='' cellpadding='0' cellspacing='0' width='100%' align='center' border='0'>";
            echo "<tr bgcolor='#FAFAFA'><td colspan='5' align='center'><b>Available Instructions</b></td></tr>";
            echo "<tr bgcolor='silver'>";
            echo "<td width='20%'> </td>";
            echo "<td width='28%'> </td>";
            echo "<td width='28%'> </td>";
            echo "<td width='20%'> </td>";
            echo "<td width='4%'> </td>";
            echo "</tr></table></td></tr>";

            echo "<tr><td>";
            echo "<div style='width:100%; overflow:auto;height:150px;background-color:#FAFAFA'>";
            echo "<table summary='' cellpadding='0' cellspacing='0' width='96%' bgcolor='#FAFAFA'>";

            if(count_records('instructions_type','courseid',$course->id)) {
                $records = get_records('instructions_type','courseid',$course->id);
                foreach ($records as $r) {
                    $style = " style='background-color:#555555; color:#ffffff;'";
                    echo "<tr><td colspan='5' width='100%'".$style.">Type ".$r->count.": ".$r->name."</td></tr>";
                    $style = "";

                    $recs = get_records_select('instructions', "typeid = '$r->id' AND courseid = '$course->id'","count ASC");

                    foreach ($recs as $i) {
                        echo "<tr".$style.">";
                        echo "<td width='20%'></td>";
                        echo "<td width='25%'>".$i->count."</td>";

                        if($i->active > 0){
                            echo "<td width='20%'><font color='red'>Active: ".$i->active."</font></td>";
                        }else{
                            echo "<td width='20%'><font color='green'>Inactive</font></td>";
                        }
                        // Get Quiz Name
                        $quiz = get_record('quiz','id',$i->quizid);

                        echo "<td width='20%'><i>$quiz->name</i></td>";
                        echo "<td width='20%'><a href='".$url."?courseid=".$course->id."&view=".$i->id."'>View</a></td>";
                        echo "</tr>";
                        echo "<tr".$style."><td colspan='4' width='100%'>".$r->text."</td></tr>";

                        //Swapping bgcolor of table
                        if(empty($style)){
                            $style = " bgcolor='silver'";
                        }else{
                            $style = "";
                        }
                    }
                    //$recs->close();
                }
                //$records->close();
            }

            else {
                echo "<tr><td colspan='4' width='100%'><font size='2'>Currently, there are no available instructions.</font></td></tr>";
            }

            echo "</table></div>";
            echo "</td></tr></table>";
            print_box_end();

        echo "</td></tr>";
        echo "<tr><td>";

            // Display Add Instruction Form
            $options  = array('courseid'=>$course->id,'addInstr'=>1);
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
            //echo "<b>Add a Context Keyword</b><br/><br/>";
            //$options  = array('courseid'=>$course->id,'add'=>2);
            print_form_start($url);

            echo "<table border='0' cellpadding='5' cellspacing='5'>";
            echo "<tr><th colspan='2' align='left'>Add Instructions</th></tr>";
            echo "<tr>";
            echo "<td colspan='2' align='right'>";
            print_button('Add Instructions',$options);
            //button_to_popup_window("/question/instructions.php?courseid={$course->id}&amp;addInstr=1","addinstr","Instructions",600,650);
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            print_form_end();
            print_box_end();

        echo "</td></tr>";
        echo "</table>";
}



function print_instruction_form($url,$opt,$label,$selects,$course,$instrid,$edittext='',$editselect='', $editquizzes='',$disabled=false){

    // Display Add Instruction Form
    print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

    $options = array('courseid'=>$course->id,'add'=>$opt,'instrid'=>$instrid);

    // Sets the List of Quizzes that don't already have instructions assigned.
    $quizzes = array();


/*
    if(!$disabled){
        if ($rs = get_records_sql("SELECT * FROM mdl_quiz WHERE course = $course->id AND id NOT IN (SELECT quizid FROM mdl_instructions WHERE quizid IS NOT NULL)")){
            foreach ($rs as $record) {
                $quizzes[$record->id] = $record->name;
                //echo $record->name."<br/>";
            }
            //echo "TEST:$course->id -> $editquizzes";
        }
    }else{
 *
 */
        if ($rs = get_records('quiz','course',$course->id)) {
            foreach ($rs as $record) {
                $quizzes[$record->id] = $record->name;
            }
        }
    //}
    echo "<table border='0' cellpadding='5' cellspacing='5' width='650px'>";
    echo "<tr><td>";
        print_form_start($url,"post");
        echo "<table border='0' cellpadding='5' cellspacing='5' width='650px'>";
        echo "<tr><th colspan='4' align='left'>Add Instructions</th></tr>";
        echo "<tr>";
        echo "<td>Instruction Type:</td>";
        echo "<td>";
        choose_from_menu ($selects, 'typeid', $editselect,'choose','','0', false, $disabled);
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Course Quiz:</td>";
        echo "<td>";
        choose_from_menu ($quizzes, 'quizid', $editquizzes,'choose','','0', false, false);
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Instruction Text:</td>";
        echo "<td>";
        //print_textfield ('addkeyword', '','',25);
        //print_textarea($usehtmleditor, $rows, $cols, $width, $height, $name);
        print_textarea(true, 15, 45, 371, 167, 'instr', $edittext);
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

?>
