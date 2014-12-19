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
    
    // Added: Parameters for Special Course Form
    $param->courseid = required_param('courseid', PARAM_INT);
    $param->edit = optional_param('edit', 0, PARAM_INT);
    //$param->editkey = optional_param('editkey', 0, PARAM_INT);
    //$param->removekey = optional_param('removekey', 0, PARAM_INT);
    $param->gid = optional_param('gid', 0, PARAM_INT);
    $param->groupName = optional_param('addgroup', '', PARAM_ALPHA);
    $param->keyword = optional_param('addkeyword', '', PARAM_ALPHA);
    //$param->confeditkey = optional_param('confeditkey', '', PARAM_ALPHA);
    $param->groupid = optional_param('contextgroupid', 0, PARAM_INT);
    $param->editkey = optional_param('editkey', 0, PARAM_INT);
    $param->editkeyword = optional_param('editkeyword', '', PARAM_ALPHANUM);

    
    $strspecialcategories = "Context Keys";
    $editspecialcategory = "Edit Context Key";
    $addspecialcategory = "Add Context Key";
    $removespecialcategory = "Remove Context Key";

  
    $navlinks = array();
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
    $currenttab = 'context';
    $context = $contexts->lowest();
    include('tabs.php');
    
    // Set Course Object
    $course = get_record('course','id',$param->courseid);
    $options  = array('courseid'=>$course->id);
    $url = "$CFG->wwwroot/question/context.php";

    // display UI
    if (!empty($param->keyword)) {

        if(!count_records('quiz_context_keys','key_code',$param->keyword)){
            $cnt = count_records('quiz_context_keys','courseid',$course->id);
            $record = insert_record('quiz_context_keys',array(
                'key_id'=>$cnt + 1,
                'key_code'=>$param->keyword,
                'courseid'=>$course->id
            ));
        }
        displayMainContextPage($course);

    } else if (!empty($param->groupid)) {

        // Define the Context Keyword values
        $cnt = count_records('quiz_context_keys','courseid',$course->id);

        print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

        if($cnt){
            for($i=1;$i<=$cnt;$i++){

                $keyword = optional_param('keyword'.$i, PARAM_INT);

                if (!empty($keyword)){
                    if(record_exists('context_key_words','key_group',$param->groupid,'key_id',$i,'courseid',$course->id)){
                        $record = get_record_select('context_key_words', "key_group = '$param->groupid' AND key_id = '$i' AND courseid = '$course->id'");
                        $record->keyword = $keyword;
                        update_record('context_key_words',$record);
                    }else{
                        $record = insert_record('context_key_words', array(
                            'key_id'=>$i,
                            'key_group'=>$param->groupid,
                            'keyword'=>$keyword,
                            'courseid'=>$course->id
                        ));
                    }
                }
            }
        }
        $record = get_record_select('context_key_groups', "id = '$param->groupid' AND courseid = '$course->id'");
        echo ($i-1)." records updated for the context group '".$record->name."'<br/><br/>";
        print_single_button($url, $options);
        print_box_end();

    } else if (!empty($param->edit)) {

        // Print the Add Context Key Groups / Remove Context Key Groups (Edit) Page
        $id = $param->edit;

        if($id == 2){

            if(!count_records('context_key_groups','courseid',$course->id)){
                print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
                echo "There are no context groups to be deleted.";
                print_single_button($url, $options);
                print_box_end();

            }else{
                print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

                if(record_exists('context_key_groups','id',$param->gid,'courseid',$course->id)){
                    delete_records('context_key_groups','id',$param->gid,'courseid',$course->id);

                    if(record_exists('context_key_groups','id',$param->gid,'courseid',$course->id)){
                        $output .= "The Context Group could not be deleted. <br/>";
                    }else{
                        $output .= "The Context Group was deleted. <br/>";
                    }
                }else{
                    $output .= "There were no Group to be deleted. <br/>";
                }

                echo $output;
                print_single_button($url, $options);
                print_box_end();
            }

        } else if($id == 1) {

            if($param->groupName != ""){
                $record = insert_record('context_key_groups',array(
                    'name'=>$param->groupName,
                    'courseid'=>$course->id
                ));
                $done = true;
            }else{
                $done = false;
            }

            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

            if($done){
                if(record_exists('context_key_groups','id',$record,'courseid',$course->id)){
                    echo "The context group '".$param->groupName."' was created. <br/><br/>";
                }else{
                    echo "The context group '".$param->groupName."' could not be created. <br/><br/>";
                }
            }else{
                echo "Please enter a valid context group name<br/><br/>";
            }
            print_single_button($url, $options);
            print_box_end();
        }

    }else if(!empty($param->editkey)){

        $keyword = get_record('quiz_context_keys','id',$param->editkey);
        if(!empty($param->editkeyword)){

            $options  = array('courseid'=>$course->id);
            $keyword->key_code = $param->editkeyword;

            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
            if(!empty($keyword)){
                if(update_record('quiz_context_keys',$keyword)){
                    echo "The Context Key Name was updated.<br/><br/>";
                }else{
                    echo "The Context Key Name could not be updated.<br/><br/>";
                }
            }else{
                echo "The Context Key Name could not be updated.<br/><br/>";
            }
            print_single_button($url, $options);
            print_box_end();

        }else{

            if(!empty($keyword)){
                print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
                //echo "<b>Edit a Context Keyword</b><br/><br/>";
                $options  = array('courseid'=>$course->id,'editkey'=>$keyword->id);
                print_form_start($url);

                echo "<table border='0' cellpadding='5' cellspacing='5'>";
                echo "<tr><th colspan='2' align='left'>Edit Keyword</th></tr>";
                echo "<tr>";
                echo "<td colspan='2'>Old Keyword Name: $keyword->key_code<b></b> </td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>New Keyword Name:</td>";
                echo "<td>";
                print_textfield ('editkeyword', '','',25);
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='2' align='right'>";
                print_button('Edit Keyword',$options);
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                print_form_end();
                print_box_end();
            }
        }

    } else {
        displayMainContextPage($course);
    }

    print_footer($COURSE);
?>

<?php

function displayMainContextPage($course) {
        Global $CFG;

        $url = "$CFG->wwwroot/question/context.php";

        // Set RecordSets
        $group_records = get_records('context_key_groups','courseid',$course->id);
        $key_records = get_records('quiz_context_keys','courseid',$course->id, "key_id ASC");

        $quizkey_count = count_records('quiz_context_keys','courseid',$course->id);
        $group_count = count_records('context_key_groups','courseid',$course->id);

        print_heading($strspecialcategories);

        // Setup Form Values
        $selects = array();
        //if ($group_records = $DB->get_recordset('context_key_groups', array('courseid'=>$course->id))) {
        foreach ($group_records as $record) {
            $selects[$record->id] = $record->name;
        }
        //$group_records->close();

        // Print the Main Context Keys Page

        echo "<table border='0' cellpadding='5' cellspacing='5' class='boxwidthwide boxaligncenter generalbox questioncategories contextlevel'>";
        echo "<tr><td>";

            // Display Add Context Group Form
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

            echo "<b>Add a Context Key Group</b><br/><br/>";
            $options  = array('courseid'=>$course->id,'edit'=>1);

            print_form_start($url);
            print_textfield ('addgroup', '','',25);
            echo "<br/>";
            print_button('Add Group',$options);
            print_form_end();
            print_box_end();

        echo "</td>";
        echo "<td>";

            // Display Remove Context Group Form
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

            echo "<b>Remove a Context Key Group</b><br/><br/>";
            $options  = array('courseid'=>$course->id,'edit'=>2);

            print_form_start($url);
            echo "Select Context Group:<br/>";
            choose_from_menu ($selects, 'gid');
            echo "<br/>";
            print_button('Remove Group',$options);
            print_form_end();
            print_box_end();

        echo "</td></tr>";
        echo "<tr><td>";

            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
            //echo "<b>Add a Context Keyword</b><br/><br/>";
            $options  = array('courseid'=>$course->id);
            print_form_start($url);

            echo "<table border='0' cellpadding='5' cellspacing='5'>";
            echo "<tr><th colspan='2' align='left'>Add a Keyword</th></tr>";
            echo "<tr>";
            echo "<td>Keyword Name:</td>";
            echo "<td>";
            print_textfield ('addkeyword', '','',25);
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='2' align='right'>";
            print_button('Add Keyword',$options);
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            print_form_end();
            print_box_end();

        echo "</td>";
        echo "<td>";

            // KeyWord Scroll Table
            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
            echo "<table summary='' cellpadding='0' cellspacing='1' align='center' title='' width='400' border='0' bgcolor='black'>";
            echo "<tr><td>";
            echo "<table summary='' cellpadding='0' cellspacing='0' width='100%' align='center' border='0'>";
            echo "<tr bgcolor='#FAFAFA'><td colspan='5' align='center'><b>Available Keywords</b></td></tr>";
            echo "<tr bgcolor='silver'>";
            echo "<td width='10%'> </td>";
            echo "<td width='28%'>Order</td>";
            echo "<td width='48%'>Keyword Name</td>";
            echo "<td width='14%'> </td>";
            echo "</tr></table></td></tr>";

            echo "<tr><td>";
            echo "<div style='width:100%; overflow:auto;height:150px;background-color:#FAFAFA'>";
            echo "<table summary='' cellpadding='0' cellspacing='0' width='96%' bgcolor='#FAFAFA'>";

            if ($quizkey_count) {
                //$key_records = $DB->get_recordset('quiz_context_keys', array('courseid'=>$course->id), "key_id ASC");
                foreach ($key_records as $r) {
                    echo "<tr>";
                    echo "<td width='10%'></td>";
                    echo "<td width='30%'>".$r->key_id."</td>";
                    echo "<td width='50%'>".$r->key_code."</td>";
                    echo "<td width='10%'><a href='$url?courseid=$course->id&editkey=$r->id'>edit</a></td>";
                    echo "</tr>";
                }
                //$key_records->close();
            }
            else {
                echo "<tr><td colspan='4' width='100%'><font size='2'>Currently, there are no available keywords.</font></td></tr>";
            }

            echo "</table></div>";
            echo "</td></tr></table>";
            print_box_end();

        echo "</td></tr>";
        echo "<tr><td colspan='2'>";

            $options  = array('courseid'=>$course->id);

            print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');

            echo "<table border='0' cellpadding='5' cellspacing='5'>";
            echo "<tr><th align='left'>Context</th><th></th><th align='left'>Define Keywords</th></tr>";
            echo "<tr>";
            echo "<td valign='top'>";
            // Display the list of Context Groups
            echo "<ol>";
                $group_records = get_records('context_key_groups','courseid',$course->id);
                foreach ($group_records as $r) {
                    echo "<li><a style='color:blue'; onclick='getContext(".$r->id.",".$group_count.");'>".$r->name."</a></li>";
                }
                //$group_records->close();

            echo "</ol>";
            echo "</td>";
            echo "<td width='100px'>";
            echo "</td>";
            echo "<td>";

            echo "<div id='define_keywords' style='display: ;'>Select a Context to edit keywords.</div>";

            // Display the Define Keyword Forms
            $group_records = get_records('context_key_groups','courseid',$course->id);
            foreach ($group_records as $rec) {
                echo "<div id='define_keywords_".$rec->id."' style='display: none;'>";
                print_form_start($url);
                echo $rec->name."<br/>";
                echo "<table border='0' cellpadding='5' cellspacing='5'>";
                echo "<tr><th align='left'>KeyWord</th><th align='left'>Definition</th></tr>";

                $key_records = get_records('quiz_context_keys','courseid',$course->id, "key_id ASC");
                foreach ($key_records as $r) {
                    $record = get_record_select('context_key_words', "key_group = '$rec->id' AND key_id = '$r->key_id' AND courseid = '$course->id'");
                    echo "<tr>";
                    echo "<td>".$r->key_code."</td>";
                    echo "<td>";
                    print_textfield ('keyword'.$r->key_id, $record->keyword,'',25);
                    echo "</td>";
                    echo "</tr>";
                }
                //$key_records->close();
                echo "<tr><td colspan='2' align='right'>";
                echo "<div id='define_keys_".$rec->id."'><input type='hidden' value='".$rec->id."' name='contextgroupid' /></div>";
                echo "</td></tr>";
                echo "<tr><td align='right'><br/><input type='button' onclick='closeDefine(".$rec->id.");' value='Cancel'/>";
                echo "</td><td align='left'>";
                print_button('Define Keywords',$options);
                echo "</td></tr>";
                echo "</table>";
                print_form_end();
                echo "</div>";
            }
            //$group_records->close();
            echo "</td>";
            echo "</table>";
            // JavaScript Functions
            echo "<script type='text/javascript'>";
            echo "function getContext(id,total) {";
            echo "var div;";
            echo "var i=0;";

            $i = 0;
            echo "var mydivs = new Array();";
            $group_records = get_records('context_key_groups','courseid',$course->id);
            foreach ($group_records as $r) {
                echo "mydivs[".$i."] = ".$r->id.";";
                $i++;
            }
            //$group_records->close();
            echo "var cur=document.getElementById('define_keywords_'+id);";
            echo "var spc=document.getElementById('define_keywords');";

            //echo "for(i=1;i<=total;i++){";
            echo "for (i=0;i<mydivs.length;i++){";
            echo "div=document.getElementById('define_keywords_'+mydivs[i]);";
            echo "div.style.display='none';";
            echo "}";
            echo "spc.style.display='none';";
            echo "cur.style.display='';";
            echo "}";
            echo "function closeDefine(id) {";
            echo "var div=document.getElementById('define_keywords_'+id);";
            echo "var spc=document.getElementById('define_keywords');";
            echo "div.style.display='none';";
            echo "spc.style.display='';";
            echo "}";
            echo "</script>";

            print_box_end();

        echo "</td></tr>";
        echo "<tr><td colspan='2'>";
            // Nothing
        echo "</td></tr>";
        echo "<tr><td colspan='2'>";
            // Nothing
        echo "</td></tr>";
        echo "</table>";
}

function replace_keyword($group,$str,$course){

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
    return $str;
}

function get_keyword($group,$keyword,$course){

    $key = get_record_select('quiz_context_keys', "key_code = '$keyword' AND courseid = '$course->id'");

    $record = get_record('context_key_words','key_group',$group,'key_id',$key->key_id,'courseid',$course->id);

    if (!empty($record)) {
        return $record->keyword;
    }else{
        return "";
    }
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
