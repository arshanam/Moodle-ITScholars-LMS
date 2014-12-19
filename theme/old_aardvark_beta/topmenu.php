<div id="top_menu_date">
<a href="<?php echo $CFG->wwwroot.'/calendar/view.php' ?>"><script language="Javascript" type="text/javascript">
//<![CDATA[
<!--

// Get today's current date.
var now = new Date();

// Array list of days.
var days = new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

// Array list of months.
var months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');

// Calculate the number of the current day in the week.
var date = ((now.getDate()<10) ? "0" : "")+ now.getDate();

// Calculate four digit year.
function fourdigits(number)     {
        return (number < 1000) ? number + 1900 : number;
                                                                }

// Join it all together
today =  days[now.getDay()] + " " +
              date + " " +
                          months[now.getMonth()] + " " +               
                (fourdigits(now.getYear())) ;

// Print out the data.
document.write("" +today+ " ");
  
//-->
//]]>
</script></a>
	
	</div>
    
<ul>
     
       <li class="home"><div><a href="<?php echo $CFG->wwwroot.'/' ?>"><img width="18" height="17" src="<?php echo $CFG->httpswwwroot.'/theme/'.current_theme() ?>/images/home_icon.png" alt=""/></a></div>
       </li> 

        <?php
            global $DB;
            
            $param->id = optional_param('id',0, PARAM_INT);
            $param->courseid = optional_param('courseid',0, PARAM_INT);
            
            // Check if the User is an Admin - to Display accordingly
            $userRole = $DB->get_record('role_assignments', array('userid'=>$USER->id));
            $admin = false;
            if (!empty($userRole)) {
                if ($userRole->roleid == 1 || $userRole->roleid == 2 || $userRole->roleid == 3) {
                    $admin = true;
                }
            }

            $newuserurl = "$CFG->wwwroot/user/editadvanced.php?id=-1";
            $newcourseurl = "$CFG->wwwroot/course/index.php?categoryedit=on";


            $courses  = get_my_courses($USER->id, 'visible DESC,sortorder ASC', array('summary'));

        ?>


        <?php if($admin){ ?>
        <li><div><a href="<?php echo $CFG->wwwroot.'/' ?>">Options</a>
        <ul>
     
        <li><a href="<?php echo $newuserurl ?>">New User</a></li>
		<li><a href="<?php echo $newcourseurl ?>">New Course</a></li>
        </ul></div>

        <?php if (!empty($courses)) { ?>
            <li><div><a href="<?php echo $CFG->wwwroot.'/' ?>">Courses</a>
            <ul>

            <?php foreach($courses as $course){

                $viewcourse = "$CFG->wwwroot/course/view.php?id=$course->id";
            
            ?>

                <li><a href="<?php echo $viewcourse ?>"> <?php echo $course->fullname ?> </a></li>
            <?php } ?>

            </ul></div>
        <?php } ?>

        <?php if(!empty($param->courseid) || !empty($param->id)){

            if(!empty($param->courseid))
                $id = $param->courseid;
            else
                $id = $param->id;

            $viewcourse = "$CFG->wwwroot/course/view.php?id=$id";
            $questions = "$CFG->wwwroot/question/edit.php?courseid=$id";
            $categories = "$CFG->wwwroot/question/category.php?courseid=$id";
            $scategories = "$CFG->wwwroot/question/special.php?courseid=$id";
            $context = "$CFG->wwwroot/question/context.php?courseid=$id";
            $instructions = "$CFG->wwwroot/question/instructions.php?courseid=$id";
            $grades = "$CFG->wwwroot/question/grades.php?courseid=$id";

            $course = $DB->get_record('course', array('id'=>$id));
            if(!empty($course)){
        ?>
            <li><div><a href="<?php echo $viewcourse ?>"> <?php echo $course->shortname." >>" ?> </a>
            <ul>

            <li><a href="<?php echo $questions ?>">Questions</a></li>
            <li><a href="<?php echo $categories ?>">Categories</a></li>
            <li><a href="<?php echo $scategories ?>">Special Categories</a></li>
            <li><a href="<?php echo $context ?>">Context</a></li>
            <li><a href="<?php echo $instructions ?>">Instructions</a></li>
            <li><a href="<?php echo $grades ?>">Grades</a></li>
            </ul></div>

        <?php }} ?>
    <?php } ?>
 
        