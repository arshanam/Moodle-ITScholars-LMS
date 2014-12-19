<?php // $Id: index.php,v 1.2 2009/05/08 13:25:59 adelamarre Exp $

    require_once("../../config.php");
    require_once("lib.php");

    $id = required_param('id',PARAM_INT);   // course

    //redirect("$CFG->wwwroot/course/view.php?id=$id");
    
    if (! $course = get_record("course", "id", $id)) {
        error("Course ID is incorrect");
    }

    require_course_login($course, true);

    if ($course->id != SITEID) {
        require_login($course->id);
    }
    add_to_log($course->id, "ezproxy", "view all", "index.php?id=$course->id", "");
    
    $strezproxy = get_string("modulename", "ezproxy");
    $strezproxys = get_string("modulenameplural", "ezproxy");
    $strweek = get_string("week");
    $strtopic = get_string("topic");
    $strname = get_string("name");
    $strlastmodified = get_string("lastmodified");

    $navlinks = array();
    $navlinks[] = array('name' => $strezproxys, 'link' => '', 'type' => 'activityinstance');
    $navigation = build_navigation($navlinks);

    print_header("$course->shortname: $strezproxys", $course->fullname, $navigation,
                 "", "", true, "", navmenu($course));

    if (! $ezproxys = get_all_instances_in_course("ezproxy", $course)) {
        notice(get_string('thereareno', 'moodle', $strezproxys), "../../course/view.php?id=$course->id");
        exit;
    }

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname);
        $table->align = array ("center", "left");
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname);
        $table->align = array ("center", "left");
    } else {
        $table->head  = array ($strlastmodified, $strname);
        $table->align = array ("left", "left");
    }

    $currentsection = "";
    $options->para = false;
    foreach ($ezproxys as $ezproxy) {
        if ($course->format == "weeks" or $course->format == "topics") {
            $printsection = "";
            if ($ezproxy->section !== $currentsection) {
                if ($ezproxy->section) {
                    $printsection = $ezproxy->section;
                }
                if ($currentsection !== "") {
                    $table->data[] = 'hr';
                }
                $currentsection = $ezproxy->section;
            }
        } else {
            $printsection = '<span class="smallinfo">'.userdate($ezproxy->timemodified)."</span>";
        }
        if (!empty($ezproxy->extra)) {
            $extra = urldecode($ezproxy->extra);
        } else {
            $extra = "";
        }
        if (!$ezproxy->visible) {      // Show dimmed if the mod is hidden
            $table->data[] = array ($printsection, 
                    "<a class=\"dimmed\" $extra href=\"view.php?id=$ezproxy->coursemodule\">".format_string($ezproxy->name,true)."</a>");

        } else {                        //Show normal if the mod is visible
            $table->data[] = array ($printsection, 
                    "<a $extra href=\"view.php?id=$ezproxy->coursemodule\">".format_string($ezproxy->name,true)."</a>");
        }
    }
    
    echo "<br />";

    print_table($table);

    print_footer($course);

?>