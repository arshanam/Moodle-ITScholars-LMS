<?php 

    if (!file_exists('./config.php')) {
        header('Location: install.php');
        die;
    }

    require_once('config.php');
    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');

    if (isloggedin() and !isguest()) {
        if(!iscreator() and !isteacherinanycourse()){
            redirect($CFG->wwwroot .'/index.php');
        }
    } else {
        redirect($CFG->wwwroot .'/index.php');
    }

    print_header($SITE->fullname, $SITE->fullname, 'Register');

?>

<h2>2010 Moodle References:</h2>
<table border="0" cellpadding="5" cellspacing="5">
    <tr><td><hr/></td></tr>
    <tr><td><b>URL that displays the grades for a user (eg. 26/30 95/100)</b></td></tr>
    <tr><td>
    Ex: http://ita-portal.cis.fiu.edu/moodle/examgrades.php?username=jessica&quizid=2<br/>
    Required parameters: username<br/>
    Optional parameters: courseid, quizid, starttime<br/>
    - If you just use the username param, it will display a list of all the grades for that user.<br/>
    - It best to use all the parameters to get 1 value.
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>URL that displays the last (largest) quiz id and name. (eg. 2 Test Exam)</b></td></tr>
    <tr><td>
    Ex: http://ita-portal.cis.fiu.edu/moodle/latestquiz.php?courseid=2<br/>
    Optional parameters: courseid<br/>
    - if you enter no parameters it will return the most recent quiz for all the courses together.<br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>URL that displays ALL the Quizzes</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/listquizzes.php<br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>URL that displays ALL the Quiz Attempts and Register Exams (For testing)</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/quizattempts.php<br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>Page (URL) to cancel the exam registration. (this link is not linked to a valid exam)</b></td></tr>
    <tr><td>
    Ex: http://ita-portal.cis.fiu.edu/moodle/cancelexam.php?username=jessica&courseid=4&quizid=2&starttime=1255669200&endtime=1255676400&viewstatus=1<br/>
    Required parameters: username,courseid,quizid, starttime, endtime<br/>
    Optional parameters: viewstatus (shows the current status of the Exam)
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>URL to display ALL courses, and edit Active status</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/courseinfo.php<br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>page for the course and quiz info.</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/coursequizinfo.php<br/>
    param: courseid (optional) - displays the quizzes for the courseid
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>Add Course Quiz</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/course/modedit.php?add=quiz&type=&course=2&section=0&return=0<br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>Course Enrollment</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/course/auto_enrol.php?id=4&username=user1<br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>Register a User for Course Quiz</b></td></tr>
    <tr><td>
    Register through URL:<br/>
    http://ita-portal.cis.fiu.edu/moodle/reg_exam.php?courseid=4&quizid=2&username=jessica&url=protocal%3A%2F%2Fserver.com&sday=26&smon=9&syear=2009&shour=18&smin=55&eday=26&emon=9&eyear=2009&ehour=20&emin=55&register=1<br/>
    <br/>
    Register through Form:<br/>
    http://ita-portal.cis.fiu.edu/moodle/reg_exam.php<br/>
    <br/>
    View Entries:<br/>
    http://ita-portal.cis.fiu.edu/moodle/reg_exam.php?current=1<br/>
    <br/>
    Parameters:<br/>
    courseid - PARAM_INT<br/>
    quizid - PARAM_INT<br/>
    username - PARAM_ALPHA<br/>
    url - PARAM_ALPHA<br/>
    starttime - PARAM_INT<br/>
    endtime - PARAM_INT<br/>
    <br/>
    </td></tr>
    <tr><td><hr/></td></tr>

    <tr><td><b>Instrunctions, Quiz and Exam</b></td></tr>
    <tr><td>
    http://ita-portal.cis.fiu.edu/moodle/testDB2.php<br/>
    <p>This is for testing purposes only.</p>
    </td></tr>
    <tr><td><hr/></td></tr>
</table>

<?php
    print_footer($COURSE);
?>



