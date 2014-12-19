<?php  // $Id: index.php,v 1.201.2.10 2009/04/25 21:18:24 stronk7 Exp $
       // index.php - the front page.

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////


    if (!file_exists('./config.php')) {
        header('Location: install.php');
        die;
    }

    require_once('config.php');
    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');
    require_once($CFG->dirroot .'/mod/quiz/locallib.php');

    if (empty($SITE)) {
        redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
    }

    // Bounds for block widths
    // more flexible for theme designers taken from theme config.php
    $lmin = (empty($THEME->block_l_min_width)) ? 100 : $THEME->block_l_min_width;
    $lmax = (empty($THEME->block_l_max_width)) ? 210 : $THEME->block_l_max_width;
    $rmin = (empty($THEME->block_r_min_width)) ? 100 : $THEME->block_r_min_width;
    $rmax = (empty($THEME->block_r_max_width)) ? 210 : $THEME->block_r_max_width;

    define('BLOCK_L_MIN_WIDTH', $lmin);
    define('BLOCK_L_MAX_WIDTH', $lmax);
    define('BLOCK_R_MIN_WIDTH', $rmin);
    define('BLOCK_R_MAX_WIDTH', $rmax);

    // check if major upgrade needed - also present in login/index.php
    if ((int)$CFG->version < 2006101100) { //1.7 or older
        @require_logout();
        redirect("$CFG->wwwroot/$CFG->admin/");
    }
    // Trigger 1.9 accesslib upgrade?
    if ((int)$CFG->version < 2007092000
        && isset($USER->id)
        && is_siteadmin($USER->id)) { // this test is expensive, but is only triggered during the upgrade
        redirect("$CFG->wwwroot/$CFG->admin/");
    }

    if ($CFG->forcelogin) {
        require_login();
    } else {
        user_accesstime_log();
    }

    if ($CFG->rolesactive) { // if already using roles system
        if (has_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM))) {
            if (moodle_needs_upgrading()) {
                redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
            }
        } else if (!empty($CFG->mymoodleredirect)) {    // Redirect logged-in users to My Moodle overview if required
            if (isloggedin() && $USER->username != 'guest') {
                redirect($CFG->wwwroot .'/my/index.php');
            }
        }
    } else { // if upgrading from 1.6 or below
        if (isadmin() && moodle_needs_upgrading()) {
            redirect($CFG->wwwroot .'/'. $CFG->admin .'/index.php');
        }
    }


    if (get_moodle_cookie() == '') {
        set_moodle_cookie('nobody');   // To help search for cookies on login page
    }

    if (!empty($USER->id)) {
        add_to_log(SITEID, 'course', 'view', 'view.php?id='.SITEID, SITEID);
    }

    if (empty($CFG->langmenu)) {
        $langmenu = '';
    } else {
        $currlang = current_language();
        $langs = get_list_of_languages();
        $langlabel = get_accesshide(get_string('language'));
        $langmenu = popup_form($CFG->wwwroot .'/index.php?lang=', $langs, 'chooselang', $currlang, '', '', '', true, 'self', $langlabel);
    }

    $PAGE       = page_create_object(PAGE_COURSE_VIEW, SITEID);
    $pageblocks = blocks_setup($PAGE);
    $editing    = $PAGE->user_is_editing();
    $preferred_width_left  = bounded_number(BLOCK_L_MIN_WIDTH, blocks_preferred_width($pageblocks[BLOCK_POS_LEFT]),
                                            BLOCK_L_MAX_WIDTH);
    $preferred_width_right = bounded_number(BLOCK_R_MIN_WIDTH, blocks_preferred_width($pageblocks[BLOCK_POS_RIGHT]),
                                            BLOCK_R_MAX_WIDTH);

   //Build Home Page Navigation - depending on login status
    if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
       // Logged-In
       $navlinks = array(array('name' => "Home", 'link' => null, 'type' => 'misc'));
       $navigation = build_navigation($navlinks);
    } else {
        // Logged-Out
        $loginsite = get_string("loginsite");
        $navlinks = array(array('name' => $loginsite, 'link' => null, 'type' => 'misc'));
        $navigation = build_navigation($navlinks);
    }
    print_header($SITE->fullname, $SITE->fullname, $navigation, '',
                 '<meta name="description" content="'. strip_tags(format_text($SITE->summary, FORMAT_HTML)) .'" />',
                 true, '', user_login_string($SITE).$langmenu);

    // Get USER role information (admin)
    //$userRole = get_record('role_assignments', array('userid'=>$USER->id));
    $userRole = get_record('role_assignments', 'userid', $USER->id);

    // Clear the course Exams for a particular course
    $clear = optional_param('clrtabs', 0, PARAM_INT);
    $cid = optional_param('id', 0, PARAM_INT);
    if((!empty($clear) && $clear == 1)&&(!empty($cid) && $cid > 0)){
        clearExamTables($cid);
        redirect($CFG->wwwroot);
    }
?>

<span id="liveclock"></span>
<script type="text/javascript">


//var str=document.location;
//alert(str);
//var str="Visit W3Schools!";
//document.write(str.search(/active/));

function focusActiveExam()
{
    //alert("Active");
    document.getElementById('active').focus();

}
</script>

<script type="text/javascript">

// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

//var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' //SSI method of getting server date
var currenttime = '<? print date("F d, Y H:i:s", time())?>' //PHP method of getting server date

///////////Stop editting here/////////////////////////////////

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
//document.getElementById("servertime").innerHTML=datestring+" "+timestring
document.getElementById("servertime").innerHTML=timestring
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

</script>

<!-- <p><b>Current Server Time:</b> <span id="servertime"></span></p> -->
<font size='1' color='gray'>&nbsp;&nbsp; Current Time:&nbsp;&nbsp;</font><br/>
<font size='3' face='Arial'>&nbsp;&nbsp; <b><span id="servertime"></span></b></font>


<?php
if (!empty($userRole) && $userRole->roleid == 5) {
?>
<script language="JavaScript">

//Refresh page script- By Brett Taylor (glutnix@yahoo.com.au)
//Modified by Dynamic Drive for NS4, NS6+
//Visit http://www.dynamicdrive.com for this script

//configure refresh interval (in seconds)
var countDownInterval=30;
//configure width of displayed text, in px (applicable only in NS4)
var c_reloadwidth=200

</script>

<br/><br/>
<ilayer id="c_reload" width=&{c_reloadwidth}; ><layer id="c_reload2" width=&{c_reloadwidth}; left=0 top=0></layer></ilayer>

<script>

var countDownTime=countDownInterval+1;
function countDown(){
countDownTime--;
if (countDownTime <=0){
countDownTime=countDownInterval;
clearTimeout(counter)
window.location.reload()
return
}
if (document.all) //if IE 4+
document.all.countDownText.innerText = countDownTime+" ";
else if (document.getElementById) //else if NS6+
document.getElementById("countDownText").innerHTML=countDownTime+" "
else if (document.layers){ //CHANGE TEXT BELOW TO YOUR OWN
document.c_reload.document.c_reload2.document.write('Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds')
document.c_reload.document.c_reload2.document.close()
}
counter=setTimeout("countDown()", 1000);
}

function startit(){
if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN
document.write('<b id="countDownText" style="display:none">'+countDownTime+' </b>')
//document.write('Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds')
countDown()
}

if (document.all||document.getElementById)
startit()
else
window.onload=startit

</script>

<!-- Universal Countdown Script -->
<style style="text/css">

.lcdstyle2{ /*Example CSS to create LCD countdown look*/
background-color:black;
color:lime;
font: bold 18px MS Sans Serif;
padding: 3px;
}

.lcdstyle{
color: #000000;
font: bold 16px MS Sans Serif;
padding: 3px;
}

.lcdstyle sup{ /*Example CSS to create LCD countdown look*/
font-size: 80%
}

</style>

<script type="text/javascript">

/***********************************************
* Universal Countdown script- © Dynamic Drive (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function cdLocalTime(container, servermode, offsetMinutes, targetdate, debugmode){
if (!document.getElementById || !document.getElementById(container)) return
this.container=document.getElementById(container)
var servertimestring=(servermode=="server-php")? '<? echo date("F d, Y H:i:s", time()); ?>' : (servermode=="server-ssi")? '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' : '<%= Now() %>'
this.localtime=this.serverdate=new Date(servertimestring)
this.targetdate=new Date(targetdate)
this.debugmode=(typeof debugmode!="undefined")? 1 : 0
this.timesup=false
this.localtime.setTime(this.serverdate.getTime()+offsetMinutes*60*1000) //add user offset to server time
//this.localtime.setTime(this.serverdate.getTime()) //add user offset to server time
this.updateTime()
}

cdLocalTime.prototype.updateTime=function(){
var thisobj=this
//this.localtime.setSeconds(this.localtime.getSeconds())
this.localtime.setSeconds(this.localtime.getSeconds()+1)
setTimeout(function(){thisobj.updateTime()}, 1000) //update time every second
}

cdLocalTime.prototype.displaycountdown=function(baseunit, functionref){
this.baseunit=baseunit
this.formatresults=functionref
this.showresults()
}

cdLocalTime.prototype.showresults=function(){
var thisobj=this
var debugstring=(this.debugmode)? "<p style=\"background-color: #FCD6D6; color: black; padding: 5px\"><big>Debug Mode on!</big><br /><b>Current Local time:</b> "+this.localtime.toLocaleString()+"<br />Verify this is the correct current local time, in other words, time zone of count down date.<br /><br /><b>Target Time:</b> "+this.targetdate.toLocaleString()+"<br />Verify this is the date/time you wish to count down to (should be a future date).</p>" : ""

var timediff=(this.targetdate-this.localtime)/1000 //difference btw target date and current date, in seconds
if (timediff<0){ //if time is up
this.timesup=true
this.container.innerHTML=debugstring+this.formatresults()
return
}
var oneMinute=60 //minute unit in seconds
var oneHour=60*60 //hour unit in seconds
var oneDay=60*60*24 //day unit in seconds
var dayfield=Math.floor(timediff/oneDay)
var hourfield=Math.floor((timediff-dayfield*oneDay)/oneHour)
var minutefield=Math.floor((timediff-dayfield*oneDay-hourfield*oneHour)/oneMinute)
var secondfield=Math.floor((timediff-dayfield*oneDay-hourfield*oneHour-minutefield*oneMinute))
if (this.baseunit=="hours"){ //if base unit is hours, set "hourfield" to be topmost level
hourfield=dayfield*24+hourfield
dayfield="n/a"
}
else if (this.baseunit=="minutes"){ //if base unit is minutes, set "minutefield" to be topmost level
minutefield=dayfield*24*60+hourfield*60+minutefield
dayfield=hourfield="n/a"
}
else if (this.baseunit=="seconds"){ //if base unit is seconds, set "secondfield" to be topmost level
var secondfield=timediff
dayfield=hourfield=minutefield="n/a"
}
this.container.innerHTML=debugstring+this.formatresults(dayfield, hourfield, minutefield, secondfield)
setTimeout(function(){thisobj.showresults()}, 1000) //update results every second
}

/////CUSTOM FORMAT OUTPUT FUNCTIONS BELOW//////////////////////////////

//Create your own custom format function to pass into cdLocalTime.displaycountdown()
//Use arguments[0] to access "Days" left
//Use arguments[1] to access "Hours" left
//Use arguments[2] to access "Minutes" left
//Use arguments[3] to access "Seconds" left

//The values of these arguments may change depending on the "baseunit" parameter of cdLocalTime.displaycountdown()
//For example, if "baseunit" is set to "hours", arguments[0] becomes meaningless and contains "n/a"
//For example, if "baseunit" is set to "minutes", arguments[0] and arguments[1] become meaningless etc

//1) Display countdown using plain text
function formatresults(){
if (this.timesup==false){//if target date/time not yet met
var displaystring="<span style='background-color: #CFEAFE'>"+arguments[1]+" hours "+arguments[2]+" minutes "+arguments[3]+" seconds</span>"
}
else{ //else if target date/time met
var displaystring=""; // Launch time!"
window.location.reload();
}
return displaystring
}

//2) Display countdown with a stylish LCD look, and display an alert on target date/time
function formatresults2(){
if (this.timesup==false){ //if target date/time not yet met
var displaystring="<span class='lcdstyle'>"+arguments[0]+" <sup>days</sup> "+arguments[1]+" <sup>hours</sup> "+arguments[2]+" <sup>minutes</sup>"+arguments[3]+" <sup>seconds</sup></span>"
}
else{ //else if target date/time met
var displaystring=""; //Don't display any text
window.location.reload();
//alert("You time!") //Instead, perform a custom alert
}
return displaystring
}

</script>




<?php
}
?>
<table id="layout-table" summary="layout">
  <tr>
  <?php
    $lt = (empty($THEME->layouttable)) ? array('left', 'middle', 'right') : $THEME->layouttable;
    foreach ($lt as $column) {
        switch ($column) {
            case 'left':
// Start - Left Container Content
    if (blocks_have_content($pageblocks, BLOCK_POS_LEFT) || $editing) {
        echo '<td style="width: '.$preferred_width_left.'px;" id="left-column">';
        print_container_start();
        blocks_print_admin($PAGE, $pageblocks, BLOCK_POS_LEFT);
        print_container_end();
        echo '</td>';
    }
// End - Left Container Content
            break;
            case 'middle':
    echo '<td id="middle-column">'. skip_main_destination();

// Start - Middle Container Content
    print_container_start();

    // Get USER role information (admin)
    //$userRole = get_record('role_assignments', array('userid'=>$USER->id));
    //$userRole = get_record('role_assignments', 'userid', $USER->id);
    // Check if user was an admin
    $url = $CFG->wwwroot;
    $newuserurl = "$CFG->wwwroot/user/editadvanced.php?id=-1";
    $newcourseurl = "$CFG->wwwroot/course/index.php?categoryedit=on";
    $regexam = "$CFG->wwwroot/reg_exam.php";
    $unregexam = "$CFG->wwwroot/unreg_exam.php";
    $coursestatus = "$CFG->wwwroot/courseinfo.php";
    $quizattempts = "$CFG->wwwroot/quizattempts.php";
    // Temp
    $clearexams = "$CFG->wwwroot/index.php?clrtabs=1";

    if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
        //echo "Logged-In: $userRole->roleid";
    } else {
        $url = "$CFG->wwwroot";
        displayWelcome();
    }

    if (!empty($userRole)) {

        if ($userRole->roleid == 1) {   // Administrator Role
            echo "<h3>Admin User Options</h3>";

            echo "<ol>";
            echo "<li><a href='".$newuserurl."'>Add a New User</a></li>";
            echo "<li><a href='".$newcourseurl."'>Add a New Course</a></li>";
            echo "<li><a href='".$regexam."'>Register Student for Exam</a></li>";
            echo "<li><a href='$coursestatus'>Courses Status</a></li>";
            echo "<li><a href='$quizattempts'>Quiz Attempts (Debug Tool)</a></li>";
            echo "</ol>";

            echo "<b>Existing Courses</b><br/><br/>";

            //$courses = get_my_courses($USER->id, 'visible DESC,sortorder ASC');
            //$courses = get_records('course','category',1);
            $courses = get_records('course');

            if (!empty($courses)) {
                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                foreach ($courses as $course) {
                    if ($course->id == SITEID) {
                        continue;
                    }

                    echo '<li>';

                    // Get Course Category
                    if($course->category != 0){
                        //$course_category = $courses = get_record('course_categories','id',$course->category);
                        $course_category = get_record('course_categories','id',$course->category);
                        echo $course_category->name." - ";
                    }

                    echo "<b>".$course->fullname."</b> (<a href='$unregexam?courseid=$course->id'>$course->id</a>) <br/>".$course->summary."<br />";
                    echo courseExams($course->id,$unregexam);
                    echo "<a href='$clearexams&id=$course->id'>clear exams</a><br/><br/>";
                    echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>View Grades</a>";
                    echo " | <a href='$CFG->wwwroot/course/view.php?id=$course->id'>View Course</a>";
                    echo " | <a href='$CFG->wwwroot/course/modedit.php?add=quiz&type=&course=$course->id&section=1&return=0'>Add Quiz</a>";
                    echo " | <a href='$CFG->wwwroot/question/edit.php?courseid=$course->id'>Edit Course Options</a>";
                    echo "<br /><br />";
                    echo "<hr/>";
                    echo '</li>';
                }
                echo "</ul>";
            }else{
                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                    echo '<li>';
                    echo "Currently, No Courses available";
                    echo '</li>';

                echo "</ul>";
            }


        }else if ($userRole->roleid == 2 || $userRole->roleid == 3) { // Course Creator/Teacher Role

            echo "<h3>Teacher User Options</h3>";

            echo "<ol>";
            echo "<li><a href='".$newcourseurl."'>Add a New Course</a></li>";
            echo "<li><a href='".$regexam."'>Register Student for Exam</a></li>";
            echo "<li><a href='$coursestatus'>Courses Status</a></li>";
            echo "</ol>";

            echo "<b>Existing Courses</b><br/><br/>";

            $courses  = get_my_courses($USER->id, 'visible DESC,sortorder ASC', array('summary'));


            if (!empty($courses)) {
                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                foreach ($courses as $course) {
                    if ($course->id == SITEID) {
                        continue;
                    }

                    echo '<li>';

                    // Get Course Category
                    if($course->category != 0){
                        $course_category = $courses = get_record('course_categories','id',$course->category);
                        echo $course_category->name." - ";
                    }

                    echo "<b>".$course->fullname."</b> (<a href='$unregexam?courseid=$course->id'>$course->id</a>) <br/>".$course->summary."<br />";
                    echo courseExams($course->id,$unregexam);
                    echo "<a href='$clearexams&id=$course->id'>clear exams</a><br/><br/>";
                    echo "<a href='$CFG->wwwroot/question/grades.php?courseid=$course->id'>View Grades</a>";
                    echo " | <a href='$CFG->wwwroot/course/view.php?id=$course->id'>View Course</a>";
                    echo " | <a href='$CFG->wwwroot/course/modedit.php?add=quiz&type=&course=$course->id&section=1&return=0'>Add Quiz</a>";
                    echo " | <a href='$CFG->wwwroot/question/edit.php?courseid=$course->id'>Edit Course Options</a>";
                    echo "<br /><br />";
                    echo "<hr/>";
                    echo '</li>';
                }
                echo "</ul>";
            }else{
                echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                    echo '<li>';
                    echo "Currently, No Courses available";
                    echo '</li>';

                echo "</ul>";
            }


        }else if ($userRole->roleid == 5) { // Student Role

?>

<!-- Tabbed Course Listing -->
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="js/jquery.session.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript">


    $(document).ready(function () {
            var current = getCookie('currCourseSelected');
            //alert(getCookie('currCourseSelected'));
            if(current != null && current != ""){
                $(".coursediv").hide("fast");
                $('#c'+current).show("slow");
            }
            /*
            try{
                $(function() {
                    current = eval($.session('currCourseSelected'));
                });
                //alert("SESSION::" + $.session('currCourseSelected'));
            }catch(err){
                current = 0;
            }finally{
                //alert(current);
            }
            */

            

            //Set the height of the block
            $('#menu .block').height($('#menu li').height());

            //go to the default selected item
            topval = $('#menu .selected').position()['top'];
            $('#menu .block').stop().animate({top: topval}, {easing: '', duration:500});

            $('#menu li').hover(

                function() {

                    //get the top position
                    topval = $(this).position()['top'];

                    //animate the block
                    //you can add easing to it
                    $('#menu .block').stop().animate({top: topval}, {easing: '', duration:500});

                    //add the hover effect to menu item
                    $(this).addClass('hover');
                },

                function() {
                    //remove the hover effect
                    $(this).removeClass('hover');
                }
            );
            $('#menu li').click(
                function() {
                    //remove the hover effect
                    var id = $(this).attr("id");
                    //$.session("currCourseSelected", id);
                    //alert("SESSION:" + $.session('currCourseSelected'));

                    setCookie('currCourseSelected',id,1);
                    
                    $(".coursediv").hide("slow");

                    $('#c'+id).show("slow");
                    //alert(id);
                }

            );

    });

function setCookie(c_name,value,expiredays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate()+expiredays);
    document.cookie=c_name+ "=" +escape(value)+
    ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1)
    {
    c_start=c_start + c_name.length+1;
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
  }
return "";
}

</script>

<style>
#menu {
    font-family:verdana;
    font-size:12px;
    position:relative;
    margin:0 auto;
    width:200px;
}

#menu ul {
    /* remove list style */
    list-style:none;
    padding:0;
    margin:0;

    /* set the layer position */
    position:relative;
    z-index:5;
}

    #menu li {
        /* set the styles */
        background:#ccc url(images/bg.gif) no-repeat 0 0;
        padding:5px;
        margin:2px;
        cursor:pointer;
        border:1px solid #ccc;
    }

    #menu li.hover {
        /* on hover, change it to this image */
        background-image:url(images/bg_hover.gif) !important;
    }

    #menu li a {
        text-decoration:none;
        color:#888;
    }


#menu .block {
    /* allow javascript to move the block */
    position:absolute;
    top:0;

    /* set the left position */
    left:150px;

    /* display above the #menu */
    z-index:10;

    /* the image and the size */
    background:transparent url(images/arrow.png) no-repeat top right;
    width:39px;
    padding:4px;
    cursor:pointer;
}

/* fast png fix */
* html .png{
    position:relative;
    behavior: expression((this.runtimeStyle.behavior="none")&&(this.pngSet?this.pngSet=true:(this.nodeName == "IMG" && this.src.toLowerCase().indexOf('.png')>-1?(this.runtimeStyle.backgroundImage = "none",
this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.src + "', sizingMethod='image')",
this.src = "transparent.gif"):(this.origBg = this.origBg? this.origBg :this.currentStyle.backgroundImage.toString().replace('url("','').replace('")',''),
this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.origBg + "', sizingMethod='crop')",
this.runtimeStyle.backgroundImage = "none")),this.pngSet=true));
}

</style>

<?php


            if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {

                $courses  = get_my_courses($USER->id, 'visible DESC,sortorder ASC', array('summary'));

                if (!empty($courses)) {

                ?>

                <script type="text/javascript">
                //show5();
                </script>

                <?php

                    echo '<h3>Courses:<br/></h3>';

                    // Setup of Tab Layout
                    $selected = "class='selected'";
                    $listitems = "";
                    $divcourses = "";


                    //echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                    foreach ($courses as $course) {
                        if ($course->id == SITEID) {
                            continue;
                        }
                        //echo '<li>';

                        // NOTE: *** This loop cannot contain echos unless they are apart of the <ul>

                        $listitems .= "<li id='$course->id' $selected><b>".$course->fullname."</b></li>";
                        //echo "<b><u>".$course->fullname."</u></b> ($course->id)<br/>".$course->summary."<br/><hr/><hr/>";
                        $selected = ""; // Clear selected option - can only have one

                        // calculate the IN clause (the list of courses we are going to search)
                        $in_sql = "IN('Registered','Orientation','Concepts','Implementation','Graded','Canceled')";

                        $divcourses .= "<div id='c$course->id' class='coursediv' style='display: none'>";

                        // Display Exam Info
                        //if(record_exists('quiz_course_activation','username',$USER->username,'courseid',$course->id)){
                        if(record_exists_select('quiz_course_activation',"username = '$USER->username' AND courseid = '$course->id' AND status $in_sql ", "starttime DESC, endtime DESC")){
                            //$records = get_recordset('quiz_course_activation', array('username'=>$USER->username));
                            $records = get_records_select('quiz_course_activation', "username = '$USER->username' AND courseid = '$course->id' AND status $in_sql ", "starttime DESC, endtime DESC");

                            $currentTime = time();

                            // Check for current exam to add anchor
                            $anc_sql = " IN('Registered','Orientation','Concepts','Implementation') AND $currentTime >= starttime AND $currentTime <= endtime"; //$now >= $starttime && $now <= $endtime
                            $anchor_record = get_record_select('quiz_course_activation', "username = '$USER->username' AND courseid = '$course->id' AND status $anc_sql ORDER BY starttime, endtime DESC");

                            if (!empty($records)) {
                                $format = "l dS \of F Y - h:i:s A";
                                //$currentTime = time();
                                //$currentTime = date("U",mktime(6,0,1,10,16,2009));
                                $currentExam = "";
                                $prevExam = "";

                                foreach ($records as $record) {

                                    $quiz = get_record('quiz','course', $course->id,'id',$record->quizid);

                                    $exam_length = $quiz->timelimit;
                                    if($exam_length == 0){
                                        $exam_length = 30;
                                    }

                                    if($record->status == "Canceled"){

                                        $currentExam .= "<table border='0' cellpadding='5' cellspacing='5'>";
                                        $currentExam .= "<tr><td valign='top'>";
                                        $currentExam .= displayInfo($record, $exam_length, true);
                                        $currentExam .= "<br/><font color='red'><i>** This exam has been canceled.</i></font><br/><br/><hr/><br/>";

                                        $currentExam .= "</td></tr>";
                                        $currentExam .= "</table>";

                                    }else{

                                        if(compareDates(date("Y-m-d",$currentTime),date("Y-m-d",$record->starttime)) < 0){
                                            // Exam is has NOT started
                                            //echo "NOT STARTED";
                                            $currentExam .= print_container_start(false, '', '', true);

                                            // Page Structure
                                            $currentExam .= "<table border='0' cellpadding='5' cellspacing='5'>";
                                            $currentExam .= "<tr><td valign='top' colspan='2'>";
                                            // Exam Status
                                            $currentExam .= displayInfo($record, $exam_length, true);

                                            // Check if active record
                                            $currentExam .= checkActiveExam($record,$anchor_record,examStatus($record,array(1), "", true));
                                            //$currentExam .= examStatus($record,array(1), "", true);

                                            $currentExam .= "</td></tr>";
                                            //$currentExam .= "<tr><td colspan='2'>";

                                            //$currentExam .= "Your Exam is on:<br/>";
                                            //$currentExam .= displayExamInfo($record, $exam_length, true);

                                            //$currentExam .= "</td></tr>";
                                            $currentExam .= "<tr><td valign='top'>";
                                            $currentExam .= print_single_button('', null, 'Exam Orientation', 'get', '', true, '', true);
                                            $currentExam .= "</td><td>";

                                            //$currentExam .= "<br/>";
                                            $currentExam .= showOrientation($record, $exam_length, true);

                                            $currentExam .= "</td></tr>";
                                            $currentExam .= "<tr><td valign='top'>";
                                            $currentExam .= print_single_button('', null, 'Exam Part I: Concepts!', 'get', '', true, '', true);
                                            $currentExam .= "</td><td>";

                                            //$currentExam .= "<br/>";
                                            $currentExam .= showConcepts($record, $exam_length, true);

                                            $currentExam .= "</td></tr>";
                                            $currentExam .= "<tr><td valign='top'>";
                                            $currentExam .= print_single_button('', null, 'Exam Part II: Implementation!', 'get', '', true, '', true);
                                            $currentExam .= "</td><td>";
                                            //$currentExam .= "<br/>";
                                            $currentExam .= showImplementation($record, $exam_length, true);

                                            $currentExam .= "</td></tr>";
                                            $currentExam .= "</table>";
                                            // End Structure


                                            $currentExam .= print_container_end(true);
                                            $currentExam .= "<hr/>";


                                        }else if(compareDates(date("Y-m-d",$currentTime),date("Y-m-d",$record->endtime)) > 0){
                                            // Exam Over 1";


                                            // Page Structure
                                            $prevExam .= "<table border='0' cellpadding='5' cellspacing='5'>";
                                            $prevExam .= "<tr><td valign='top'>";

                                            // Print Status - Checks if the exam was taken, via the quiz grade
                                            if($record->grade1 != null){
                                                $prevExam .= examStatus($record,array(6),"",true);
                                            }else{
                                                $prevExam .= examStatus($record,array(0),"",true);
                                            }

                                            $prevExam .= "</td></tr>";
                                            $prevExam .= "<tr><td>";

                                            $prevExam .= displayExamInfo($record, $exam_length, true);

                                            $prevExam .= "</td></tr>";
                                            $prevExam .= "<tr><td>";

                                            $prevExam .= showGrades($record,true);

                                            $prevExam .= "</td></tr>";
                                            $prevExam .= "</table>";
                                            // End Structure

                                            $prevExam .= "<hr/>";

                                        }else{
                                            //Exam Day

                                            //echo "STARTED: $record->id";

                                            // Set Time Variables
                                            $starttime = $record->starttime;
                                            $endtime = $record->endtime;
                                            $now = $currentTime;

                                            // Compare Date Strings
                                            if($now < $starttime){
                                                // Exam has NOT STARTED";

                                                $currentExam .= print_container_start(false,'', '', true);


                                                // Page Structure
                                                $currentExam .= "<table border='0' cellpadding='5' cellspacing='5'>";
                                                $currentExam .= "<tr><td valign='top' colspan='2'>";

                                                $currentExam .= displayInfo($record, $exam_length, true);

                                                $currentExam .= "</td></tr>";
                                                $currentExam .= "<tr><td colspan='2'>";

                                                // Exam Status
                                                if($now >= $starttime - 1800){
                                                    // Check if active record
                                                    $currentExam .= checkActiveExam($record,$anchor_record,examStatus($record,array(2),"",true));
                                                    //$currentExam .= examStatus($record,array(2),"",true); //Exam Orientation are available
                                                }else{
                                                    //Exam Orientation will be available
                                                    // Check if active record
                                                    $currentExam .= checkActiveExam($record,$anchor_record,examStatus($record,array(1,3),date("h:i:s A",$record->starttime - ($exam_length * 60)),true));
                                                    //$currentExam .= examStatus($record,array(1,3),date("h:i:s A",$record->starttime - ($exam_length * 60)),true);
                                                }

                                                $currentExam .= "</td></tr>";
                                                //$currentExam .= "<tr><td colspan='2'>";

                                                //$currentExam .= "Your Exam is on:<br/>";
                                                //$currentExam .= displayExamInfo($record, $exam_length, true);


                                                //$currentExam .= "</td></tr>";
                                                $currentExam .= "<tr><td valign='top'>";

                                                // If 30 minutes to the Exam - make intructions available
                                                // subtract 30 minutes (7000 seconds) (His)
                                                //if($now >= $starttime-7000){
                                                if($now >= $starttime - 1800){
                                                    //// Check if Quiz Context exists, if not get Random Context
                                                    checkContext($record,$course);

                                                    // Update the Exam Status
                                                    $record = updateExamStatus($record,"Orientation"); //'Orientation','Concepts','Implementation'

                                                    //element_to_popup_window ($type=null, $url=null, $name=null, $linkname=null,$height=400, $width=500, $title=null,$options=null, $return=false, $id=null, $class=null);

                                                    $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>1);
                                                    $url = "/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=1";
                                                    $currentExam .= element_to_popup_window("button", $url, "orientation", "Exam Orientation",900, 800, "Exam Orientation",true, true);
                                                    //$currentExam .= "<br/><br/>";
                                                    //$currentExam .= print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Exam Orientation', 'get', '', true, '', false);
                                                }else{
                                                    $currentExam .= print_single_button('', null, 'Exam Orientation', 'get', '', true, '', true);
                                                    //$currentExam .= "<br/>";
                                                }
                                                $currentExam .= "</td><td>";
                                                $currentExam .= showOrientation($record, $exam_length, true);

                                                $currentExam .= "</td></tr>";
                                                $currentExam .= "<tr><td valign='top'>";
                                                $currentExam .= print_single_button('', null, 'Exam Part I: Concepts!', 'get', '', true, '', true);
                                                $currentExam .= "</td><td>";
                                                //$currentExam .= "<br/>";
                                                $currentExam .= showConcepts($record, $exam_length, true);

                                                $currentExam .= "</td></tr>";
                                                $currentExam .= "<tr><td valign='top'>";
                                                $currentExam .= print_single_button('', null, 'Exam Part II: Implementation!', 'get', '', true, '', true);
                                                $currentExam .= "</td><td>";
                                                //$currentExam .= "<br/>";
                                                $currentExam .= showImplementation($record, $exam_length, true);

                                                $currentExam .= "</td></tr>";
                                                $currentExam .= "</table>";
                                                // End Structure



                                                $currentExam .= print_container_end(true);
                                                //$currentExam .= "<div id='info_$record->id'></div><br/>";
                                                $currentExam .= "<hr/>";


                                            }else if($now >= $starttime && $now <= $endtime){
                                                //Exam has Started

                                                // Check if Quiz Context exists, if not get Random Context
                                                checkContext($record,$course);


                                                $currentExam .=  print_container_start(false,'', '', true);

                                                // Page Structure
                                                $currentExam .= "<table border='0' cellpadding='5' cellspacing='5'>";
                                                $currentExam .= "<tr><td valign='top' colspan='2'>";

                                                $currentExam .= displayInfo($record, $exam_length,true);

                                                $currentExam .= "</td></tr>";
                                                /*
                                                echo  "<tr><td colspan='2'>";

                                                // Print Status
                                                if($now >= $starttime + ($exam_length * 60)){
                                                    //echo "Exam is Over - Exam Part II: Implementation! Available <br/>";
                                                    examStatus($record,array(5),$exam_length);
                                                }else{
                                                    // Starttime to + 30 mins
                                                    examStatus($record,array(4),$exam_length);
                                                }

                                                //echo  "</td></tr>";
                                                // echo  "<tr><td colspan='2'>";
                                                //displayExamInfo($record, $exam_length);

                                                echo  "</td></tr>";
                                                */

                                                // If 30 minutes after the Exam Started - make Practical Intructions available (disable Exam)
                                                // add 30 minutes (7000 seconds) (His)
                                                if(($record->attemptid != null && isAttemptClosed($record->quizid,$USER->id,$record->attemptid)) || $now > $starttime + ($exam_length * 60)){

                                                    $currentExam .= "<tr><td colspan='2'>";
                                                    // Check if active record
                                                    $currentExam .= checkActiveExam($record,$anchor_record,examStatus($record,array(5),$exam_length,true));
                                                    //$currentExam .= examStatus($record,array(5),$exam_length,true);
                                                    $currentExam .= "</td></tr>";

                                                    $currentExam .= "<tr><td valign='top'>";

                                                    //$currentExam .=  $record->attemptid."<br/>";
                                                    //$currentExam .=  $record->starttime."<br/>";
                                                    //$currentExam .=  $record->endtime."<br/>";
                                                    //$currentExam .=  $record->status."<br/>";

                                                    $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>1);
                                                    $url = "/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=1";
                                                    //$currentExam .= print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Exam Orientation', 'get', '', true, '', false);
                                                    //element_to_popup_window($type=null, $url=null, $name=null, $linkname=null,$height=400, $width=500, $title=null,$options=null, $return=false, $id=null, $class=null)
                                                    $currentExam .= element_to_popup_window("button", $url, "orientation", "Exam Orientation",900, 800, "Exam Orientation",true, true);
                                                    //print_single_button('', null, 'Exam Orientation', 'get', '', false, '', true);
                                                    $currentExam .= "</td><td>";
                                                    //echo "<br/><br/>";
                                                    $currentExam .= showOrientation($record, $exam_length,true);

                                                    $currentExam .= "</td></tr>";
                                                    $currentExam .= "<tr><td valign='top'>";

                                                    $currentExam .= "<font size='2' color='green'>&radic;</font> <font size='1' color='red'>Your exam has been submitted</font><br/>";
                                                    $currentExam .= print_single_button('', null, 'Exam Part I: Concepts!', 'get', '', true, '', true);
                                                    $currentExam .= "</td><td>";
                                                    //echo "<br/>";
                                                    $currentExam .= showConcepts($record, $exam_length,true);

                                                    $currentExam .= "</td></tr>";
                                                    $currentExam .= "<tr><td valign='top'>";

                                                    $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>2);
                                                    //print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Exam Part II: Implementation!', 'get', '', false, '', false);
                                                    $url = "/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=2";
                                                    $currentExam .= element_to_popup_window("button", $url, "orientation", "Exam Part II: Implementation!",900, 800, "Exam Part II: Implementation",true, true);
                                                    $currentExam .= "</td><td>";
                                                    //echo "<br/><br/>";
                                                    $currentExam .= showImplementation($record, $exam_length,true);

                                                    $currentExam .= "</td></tr>";

                                                    // Update the Exam Status
                                                    $record = updateExamStatus($record,"Implementation"); //'Orientation','Concepts','Implementation'

                                                }else{

                                                    $currentExam .= "<tr><td colspan='2'>";
                                                    // Check if active record
                                                    $currentExam .= checkActiveExam($record,$anchor_record,examStatus($record,array(4),$exam_length,true));
                                                    //$currentExam .= examStatus($record,array(4),$exam_length,true);
                                                    $currentExam .= "</td></tr>";
                                                    $currentExam .= "<tr><td valign='top'>";

                                                    // Starttime to + 30 mins
                                                    $opts = array('courseid'=>$course->id,'eid'=>$record->id, 'view'=>1);
                                                    $url = "/question/view_instructions.php?courseid=$course->id&eid=$record->id&view=1";
                                                    //print_single_button("$CFG->wwwroot/question/view_instructions.php", $opts, 'Exam Orientation', 'get', '', false, '', false);
                                                    $currentExam .= element_to_popup_window("button", $url, "orientation", "Exam Orientation",900, 800, "Exam Orientation",true, true);
                                                    $currentExam .= "</td><td>";
                                                    //echo "<br/><br/>";
                                                    $currentExam .= showOrientation($record, $exam_length,true);



                                                    $currentExam .= "</td></tr>";
                                                    $currentExam .= "<tr><td valign='top'>";

                                                    // Update the Exam Status - is updated in the quiz attempt

                                                    //$record = updateExamStatus($record,"Concepts"); //'Orientation','Concepts','Implementation'
                                                    //echo "STATUS:$record->status";
                                                    // Exam Part I: Concepts! Button

                                                    // Was : $qca->attemptid
                                                    if($record->attemptid != null || $record->status == "Concepts"){    // To prevent a duplicate user attempt when the quiz is in process.
                                                        $examoptions = array('q'=>$record->quizid,'qcaid'=>$record->id);
                                                        $currentExam .= print_single_button("$CFG->wwwroot/mod/quiz/attempt.php", $examoptions, 'Exam Part I: Concepts!', 'get', '', true, '', false);
                                                    }else{
                                                        $examoptions = array('forcenew'=>1,'q'=>$record->quizid,'qcaid'=>$record->id);
                                                        $currentExam .= print_single_button("$CFG->wwwroot/mod/quiz/attempt.php", $examoptions, 'Exam Part I: Concepts!', 'get', '', true, '', false);
                                                    }
                                                    $currentExam .= "</td><td>";
                                                    //echo "<br/>";
                                                    $currentExam .= showConcepts($record, $exam_length,true);

                                                    $currentExam .= "</td></tr>";
                                                    $currentExam .= "<tr><td valign='top'>";

                                                    $currentExam .= print_single_button('', null, 'Exam Part II: Implementation!', 'get', '', true, '', true);
                                                    $currentExam .= "</td><td>";
                                                    //echo "<br/>";
                                                    $currentExam .= showImplementation($record, $exam_length,true);

                                                    $currentExam .= "</td></tr>";

                                                }

                                                $currentExam .= "</table>";
                                                // End Structure

                                                $currentExam .= print_container_end(true);
                                                //echo "<div id='info_$record->id'></div><br/>";
                                                $currentExam .= "<hr/>";


                                            }else{
                                                // Exam Over 2


                                                // Page Structure
                                                $prevExam .= "<table border='0' cellpadding='5' cellspacing='5'>";
                                                $prevExam .= "<tr><td valign='top'>";

                                                $prevExam .= displayInfo($record, $exam_length, true);

                                                $prevExam .= "</td></tr>";
                                                $prevExam .= "<tr><td>";


                                                // Print Status - Checks if the exam was taken, via the quiz grade
                                                if($record->status == "Graded" || ($record->grade1 != null && $record->status == "Implementation")){
                                                    $prevExam .= examStatus($record,array(6),"",true);
                                                }else{
                                                    $prevExam .= examStatus($record,array(0),"",true);
                                                }

                                                $prevExam .= "</td></tr>";
                                                $prevExam .= "<tr><td>";

                                                $prevExam .= displayExamInfo($record, $exam_length, true);

                                                $prevExam .= "</td></tr>";
                                                $prevExam .= "<tr><td>";

                                                $prevExam .= showGrades($record,true);

                                                $prevExam .= "</td></tr>";
                                                $prevExam .= "</table>";
                                                // End Structure

                                                $prevExam .= "<hr/>";


                                                // -- old
                                                //$quiz = get_record('quiz', 'id', $record->quizid);

                                                //$prevExam .= "<div><b>".$quiz->name.": </b>";
                                                //$prevExam .= $record->grade1."/".$quiz->sumgrades;
                                                //$prevExam .= " | <a href='$CFG->wwwroot/mod/quiz/review.php?attempt=$record->attemptid'>Summary</a></div>";

                                            }
                                        }
                                    }

                                } // End of QCA - Loop
                                //$divcourses .= "<div id='c$course->id' class='coursediv' style='display: none'>";
                                $divcourses .= "<b>".$course->fullname."</b> ($course->id)<br/>".$course->summary."<br/><hr/>";
                                $divcourses .= $currentExam;

                                //echo $currentExam;
                                if(!empty($prevExam)){
                                    $divcourses .= $prevExam;
                                    //echo $prevExam;
                                }

                                $divcourses .= "</div>";

                                //echo "<br/>";
                            } // End - if records not empty


                        }else{
                            //echo "<i>** You do not have any exams for this course.</i><br/><br/><hr/><br/>";
                            $divcourses .= "<i>** You do not have any exams for this course.</i><br/><br/>";

                        }
                        $divcourses .= "</div>";

                        //echo "</li>\n";

                    } // End - Loop For Each Course

                    //echo "</ul>\n";

                // Tabbed Page Display
                echo "<table border='0' cellpadding='5' cellspacing='0' width='900'>";
                echo "<tr><td width='196px' valign='top'>";
                echo "<div id='menu'>";
                echo "<ul>";
                echo $listitems;
                echo "</ul>";
                echo "</div>";
                echo "<div class='block png'></div>";
                echo "</td>";
                echo "<td valign='top'>";
                echo "<table bgcolor='#FFFFFF' border='1' cellpadding='5' cellspacing='0' width='785px'>";
                echo "<tr><td>";
                echo "<div class='coursediv'> Please select a course from the course menu.</div>";
                echo $divcourses;
                echo "</td></tr></table>";
                echo "</td></tr></table>";

                    


                }else{
                    echo '<h3>Courses:<br/></h3>';
                    echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
                    echo '<li>Currently, not enrolled in any courses.</li>';
                    echo '</ul>';
                }
           }

        }


    }else{ // END - Check if admin/course creator

        // Student Users - with no courses
        if (isloggedin() and !isguest() and isset($CFG->frontpageloggedin)) {
            echo '<h3>Courses:<br/></h3>';
            echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
            echo '<li>Currently, not enrolled in any courses.</li>';
            echo '</ul>';
        }

    }

    print_container_end();
// End - Middle Container Content
    echo '</td>';
            break;
            case 'right':
// Start - Right Container Content
    // The right column
    if (blocks_have_content($pageblocks, BLOCK_POS_RIGHT) || $editing || $PAGE->user_allowed_editing()) {
        echo '<td style="width: '.$preferred_width_right.'px;" id="right-column">';
        print_container_start();
        if ($PAGE->user_allowed_editing()) {
            echo '<div style="text-align:center">'.update_course_icon($SITE->id).'</div>';
            echo '<br />';
        }
        //blocks_print_group($PAGE, $pageblocks, BLOCK_POS_RIGHT);
        print_container_end();
// End - Right Container Content
        echo '</td>';
    }
            break;
        }
    }
?>

  </tr>
</table>



<?php
    print_footer('home');     // Please do not modify this line
?>



<?php

////////////////////////////////
/// Additional Page Funtions ///
////////////////////////////////
function displayInfo($record, $diff, $return=false){
    $info = "A certificate exam is scheduled on ";
    // Diff is time in minutes
    $info .= "<b>".date("l dS \of F Y",$record->starttime)."</b> from ";
    $info .= date("h:i:s A",$record->starttime) . " to ".date("h:i:s A",$record->endtime);

    if($return){
        return $info;
    }else{
        echo $info;
    }
}



function displayExamInfo($record, $diff, $return=false){

    //quizTimeZone($record);

    $info = "";
    //$info .= "<b>Date: </b> ".date("F d, Y H:i:s",$record->starttime)."<br/><br/>";
    //$info .= "<b>Date: </b> ".date("l dS \of F Y",$record->starttime)."<br/><br/>";

    $info .= showOrientation($record, $diff, true);
    $info .= showConcepts($record, $diff, true);
    $info .= showImplementation($record, $diff, true);

    //$info .= "<b>Orientation: </b> available from ".date("h:i:s A",$record->starttime-($diff*60)) . " to ".date("h:i:s A",$record->endtime)."<br/>";
    //$info .= "<b>Part I (Concepts): </b> starts at ".date("h:i:s A",$record->starttime) . " and will be available until ".date("h:i:s A",$record->starttime+($diff*60))."<br/>";
    //$info .= "<b>Part II (Implementation): </b> starts after you complete Exam Part I or if it is ".date("h:i:s A",$record->starttime+($diff*60)) .", and is available until ".date("h:i:s A",$record->endtime)."<br/><br/>";
    if($return){
        return $info;
    }else{
        echo $info;
    }
}

function showOrientation($record, $diff, $return=false){

    $info = "Orientation: available from ".date("h:i:s A",$record->starttime-($diff*60)) . " to ".date("h:i:s A",$record->endtime)."<br/>";
    if($return){
        return $info;
    }else{
        echo $info;
    }
}
function showConcepts($record, $diff, $return=false){

    $info = "Part I (Concepts): starts at ".date("h:i:s A",$record->starttime) . " and will be available until ".date("h:i:s A",$record->starttime+($diff*60))."<br/>";
    if($return){
        return $info;
    }else{
        echo $info;
    }
}
function showImplementation($record, $diff, $return=false){

    $info = "Part II (Implementation): starts after you complete Exam Part I or if it is ".date("h:i:s A",$record->starttime+($diff*60)) .", and is available until ".date("h:i:s A",$record->endtime)."<br/><br/>";
    if($return){
        return $info;
    }else{
        echo $info;
    }
}



function compareDates($start_date,$end_date) {

/*
 * <0 --> if date a< date b
 * 0 --> date a== date b
 * >0 --> date a > date b respectively.
 */

  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;

}

function compareTimes($start_date,$end_date) {
  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
}

function showGrades($record, $return=false){
    $report = "";
    $quiz = get_record('quiz', 'id', $record->quizid);

    $grade1 = "NA";
    $grade2 = "NA/100";
    if($record->grade2 != null){
        $grade2 = $record->grade2;
    }
    if($record->grade1 != null){
        $grade1 = $record->grade1;
    }

    $report .= "<table border='0' cellpadding='5' cellspacing='5' width='350px'>";
    $report .= "<tr><td colspan='4' style='background-color:#CCCCCC; color:#333333'><b>Grades: </b>$quiz->name</td></tr>";
    $report .= "<tr><td><b>Exam Part I:</b></td><td>$grade1 / $quiz->sumgrades</td><td><b>Exam Part II:</b></td><td>$grade2</td></tr>";
    $report .= "</table>";

    if($return){
        return $report;
    }else{
        echo $report;
    }

}

function examStatus($record,$codes,$value="",$return=false){

    //cdLocalTime("ID_of_DIV_container", "server_mode", LocaltimeoffsetMinutes, "target_date", "opt_debug_mode")
    //cdLocalTime.displaycountdown("base_unit", formatfunction_reference)

    //Note: "launchdate" should be an arbitrary but unique variable for each instance of a countdown on your page:

    $message = "";
    $timeleft = "";

    foreach($codes as $code){
        switch($code){
            case 1:
                $message .= "<font color='red'>Exam has not started yet!</font><br/>";
                $timeleft = "Exam will start in ";
                $timeleft .= "<span id='cdcontainer.$record->id'></span>";
                $timeleft .= "<script type='text/javascript'>";
                $timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, '".date("F d, Y H:i:s",$record->starttime)."');";
                //$timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, 'April 23, 2010 15:53:00');";
                $timeleft .= "launchdate.displaycountdown('days', formatresults2);";
                $timeleft .= "</script>";
                break;
            case 2:
                //$message .= "<font color='red'>Exam has not started yet!</font><br/>";
                $message .= "<font color='grey'>Orientation materials are ready for review.</font><br/>";
                $timeleft = "Exam will start in ";
                $timeleft .= "<span id='cdcontainer.$record->id'></span>";
                $timeleft .= "<script type='text/javascript'>";
                //$timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, '".date("F d, Y H:i:s",$record->starttime)."');";
                $timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, '".date("F d, Y H:i:s",$record->starttime)."');";
                $timeleft .= "launchdate.displaycountdown('days', formatresults2);";
                $timeleft .= "</script>";
                break;
            case 3:
                $message .= "<font color='grey'>Orientation materials will be available for review at: <b>$value</b>.</font><br/>";
                break;
            case 4:
                $message .= "<font color='green'>Exam Part I is now open.</font><br/>";
                $timeleft = "Remaining time for Exam Part I is ";
                $timeleft .= "<span id='cdcontainer.$record->id'></span>";
                $timeleft .= "<script type='text/javascript'>";
                $exam_length = (int)$value;

                $timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, '".date("F d, Y H:i:s",$record->starttime + ($exam_length * 60))."');";
                //$timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, 'April 23, 2010 15:53:00');";
                $timeleft .= "launchdate.displaycountdown('days', formatresults2);";
                $timeleft .= "</script>";
                break;
            case 5:
                $message .= "<font color='green'>Exam Part II is now open.</font><br/>";
                $timeleft = "Remaining time for Exam Part II is ";
                $timeleft .= "<span id='cdcontainer.$record->id'></span>";
                $timeleft .= "<script type='text/javascript'>";

                $timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, '".date("F d, Y H:i:s",$record->endtime)."');";
                //$timeleft .= "var launchdate=new cdLocalTime('cdcontainer.$record->id', 'server-php', 0, 'April 23, 2010 15:53:00');";
                $timeleft .= "launchdate.displaycountdown('days', formatresults2);";
                $timeleft .= "</script>";
                break;
            case 6:
                $message .= "<font color='grey'>Exam is finished.</font><br/>";
                break;
            case 7:
                $message .= "<font color='grey'>Not graded.</font><br/>";
                break;
            case 8:
                $message .= "<font color='green'>Graded.</font><br/>";
                break;
            default:
                $message .= "<font color='orange'>Exam was not taken!</font><br/>";
        }
    }

    $container = "<table><tr><td valign='top'><b>Status: </b></td><td>$message $timeleft</td></tr></table>";

    if($return){
        return $container;
    }else{
        echo $container;
    }
}

function checkContext($qca,$course){

    if(!empty($qca)){
        //$quizcontext = $DB->get_record('quiz_course_activation', array('exam_context' => $course->id, 'id' => $spc->quizid));
        if($qca->exam_context == NULL){

            $ec = getRandomContextGroup($course);

            if($ec != 0){

                // Get Selected Instructions
                $intr1 = getRandomInstructions($course,1);
                $intr2 = getRandomInstructions($course,2);

                // Check if valid instruction
                if($intr1 == 0) $intr1 = NULL;
                if($intr2 == 0) $intr2 = NULL;


                $record_id = insert_record('exam_quiz_context', array(
                                            'courseid'=>$course->id,
                                            'quizid'=>$qca->quizid,
                                            'contextid'=>$ec,
                                            'instr1_id'=>$intr1,
                                            'instr2_id'=>$intr2
                                        ));

                $qca->exam_context = $record_id;

                update_record('quiz_course_activation',$qca);

            }
        }
    }
}

function clearExamTables($id){
    delete_records('quiz_course_activation','courseid',$id);
    delete_records('exam_quiz_context','courseid',$id);
    delete_records('quiz_attempts','course',$id);
}

function checkActiveExam($record,$anchor_record,$tag){
    // Check if active record
    $result = "";
    if($record->id == $anchor_record->id){
        $result .= "<a id='active' name='active'>";
        $result .= $tag;
        $result .= "</a>";
        //echo "ANCHOR:".$anchor_record->id;
    }else{
        $result .= $tag;
    }
    return $result;
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

function getRandomInstructions($course,$typeid){
    $type = get_record('instructions_type','courseid',$course->id,'count',$typeid);
    $cnt = count_records('instructions','courseid',$course->id,'typeid',$type->id);
    $id = 0;

    //echo "Course:$course->id <br/>";
    //echo "INSTR$typeid:$type->id <br/>";
    //echo "Count:$cnt <br/>";

    if($cnt > 0){
        $rnd = rand(1, $cnt); // random count id
        $cnt = 0; // reset as counter

        $records = get_records_select('instructions', "courseid = '$course->id' AND typeid = '$type->id'");

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

function displayWelcome(){
    echo "<div class='clearfix onecolumn'>";
    echo "<h2>Welcome to IT Automation Certification Portal</h2>";
    echo "<p>&nbsp;&nbsp; &raquo; Please Login to begin.</p>";
    echo "</div>";
}


function courseExams($courseid,$unregexam){
    $records = get_records('quiz','course',$courseid);
    echo "Exams: ";
    //echo "<ul style='list-style-type:none; margin:0; padding:5'>";
    //echo "<ul style='list-style-image:none; list-style-position:outside; list-style-type:none; margin:0; padding:10'>";
    $cnt = 0;
    foreach($records as $record){
        //echo "<li>$record->name ($record->id)</li>";
        $cnt++;
        if ($cnt > 1)
            echo " | $record->name (<a href='$unregexam?examid=$record->id'>$record->id</a>)";
        else
            echo "$record->name (<a href='$unregexam?examid=$record->id'>$record->id</a>)";
    }
    //echo "</ul>";
    echo "<br/>";
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

function updateExamStatus($record,$status){
    $record->status = $status;
    update_record('quiz_course_activation',$record);
    return $record;
}

function quizTimeZone($qca){

    //date_format($qca->starttime, DATE_ATOM);
    $date = date("U",$qca->starttime);
    //date.timezone

    //$NewDateTimeZone = date_timezone_get($date);

    //echo "TIMEZONE:".date.timezone."<br/>";


}





?>














