<?php  // $Id: setcerttest.php

///////////////////////////////////////////////////////////////////////////

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

    // get values from form for actions on this page
    $param = new stdClass();

    // Added: Parameters for Special Course Form
    $param->action = optional_param('action', null, PARAM_ALPHANUM);
    $param->courseid = optional_param('courseid', 0, PARAM_INT);
    $param->quizid = optional_param('quizid', 0, PARAM_INT);

if(!empty($param->action)){

    if(!empty($param->quizid)){
        if($param->action == "add"){
				assignCertificationTest($param->quizid,$param->courseid);
        }else if($param->action == "del"){
            removeCertificationTest($param->quizid);
        }
    }else if($param->action == "showtable"){
        getAllCourseQuizzes();
    }

}else{

    print_header($SITE->fullname, $SITE->fullname, 'Managing Certification Tests');
    
    $entries = get_records('certificationtest');

    print_box_start('boxwidthwide boxaligncenter generalbox questioncategories contextlevel');
    echo "<div id='certlist'>";
    getAllCourseQuizzes();
    echo "</div>";
    print_box_end();
    
    print_footer('Managing Certification Tests');     // Please do not modify this line

?>

<link rel='stylesheet' type='text/css' href='mod/scheduler/fullcalendar/css/custom-theme/jquery-ui-1.8.1.custom.css' />
<script type='text/javascript' src='mod/scheduler/fullcalendar/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='mod/scheduler/fullcalendar/jquery/jquery-ui-1.8.1.custom.min.js'></script>
<script type="text/javascript">

$(document).ready(function() {
    var index = 0;	
	
    $('#certlist').accordion({
	collapsible: true,
	active:-1,
	autoHeight: false
    });
   
    $("h3 a").click(function(){
        index = parseInt(this.id);
    });
    $("span.del").click(function(){
        $("span#"+this.id).html('');
        sendRequest("setcerttest.php?action=del&quizid="+this.id);
    });
    $("span.add").click(function(){
		var course = $("span.add").attr("course");
        $("span#"+this.id).html('');
		
        sendRequest("setcerttest.php?action=add&quizid="+this.id+"&courseid="+course);
    });

    function sendRequest(requestedPage) {
    
        $.ajax({
            type: 'POST',
            url: requestedPage,
            dataType: 'text',
            async: true,
            success: function(message) {
            	
		if(message == "NO-VLAB"){
			alert("This course does not have any Virtual Labs. \n In order to create a certificate test the course must have a Virtual Lab.");
		}
		var content = getTable();
               
                $('#certlist').accordion("destroy");
                $('#certlist').html(content);
                $('#certlist').accordion({
		    collapsible: true,
		    active: index,
		    autoHeight: false
		});
		
               // $('#certlist').accordion( "activate" ,index );
                
                $("h3 a").click(function(){
                    index = parseInt(this.id);
                });
                          
                $("span.del").click(function(){
                    $("span#"+this.id).html('');
                    sendRequest("setcerttest.php?action=del&quizid="+this.id);
                });
                $("span.add").click(function(){
		    var course = $("span.add").attr("course");
                    $("span#"+this.id).html('');
                    sendRequest("setcerttest.php?action=add&quizid="+this.id+"&courseid="+course);
                });
                              
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                
               alert("Your request could not be completed.");         
            }
        });
    }
    function getTable() {
        var html = "";
        $.ajax({
            type: 'POST',
            url: "setcerttest.php?action=showtable",
            dataType: 'text',
            async: false,
            success: function(data) {
                html = data;
            }
        });
        return html;
    }
	
});



</script>


<?php
    

}

function getAllCourseQuizzes(){

    //$courses = get_records('course','','','fullname');
    $courses = get_records('course','','','timecreated desc');
    $count = 0;
    foreach($courses as $course){
        
        $entries = get_records('quiz','course',$course->id,'course');
         
        echo "<h3><a href='#' id='".$count."'>".getCourseInfo($course->id)."</a></h3>";
        echo "<div>";
        echo "<table border='0' cellpadding='5' width='100%'>";
        echo "<tr><td><b>Quiz Id</b></td><td><b>Name</b></td><td><b>Status</b></td><td></td></tr>";
        if(!empty($entries)){   
            foreach($entries as $entry){
                $record = get_record('certificationtest','quizid',$entry->id);
                echo "<tr>";
                echo "<td>$entry->id</td>";
                echo "<td>$entry->name</td>";
                
                if(!empty($record)){
                    echo "<td><span style='color:green;'>active</span></td>";
                    echo "<td><span class='del' style='cursor:pointer;' id='$record->quizid'>delete</span></td>";
                }else{
                    echo "<td><span style='color:red;'>inactive</span></td>";
                    echo "<td><span class='add' style='cursor:pointer;' id='$entry->id' course='$course->id'>activate</span></td>";
                }
                echo "</tr>";
            }
        }else{
            echo "<td colspan='4'>There is no quiz for this course.</td>";
        }
        echo "</table>";
        echo "</div>";
        
        $count++;
    }
    
    $js = '<script type="text/javascript">' .
        '$("span.del").click(function(){ sendRequest("setcerttest.php?action=del&quizid="+this.id);});' .
        '$("span.add").click(function(){sendRequest("setcerttest.php?action=add&quizid="+this.id);});' .
        '</script>';
    //echo $js;
}

function getCourseInfo($courseid){
    $course = get_record('course','id',$courseid);
    $str = "$course->shortname : $course->fullname";

   // if(!empty($course)){
   //    $str = "<tr><td colspan='4'><b>$course->shortname : $course->fullname </b></td></tr>";
   //}
    return $str;
}
function removeCertificationTest($quizid){

    if(record_exists('certificationtest','quizid',$quizid)){
        //echo delete_records('certificationtest','quizid',$quizid);
        $sql_str = "DELETE FROM mdl_certificationtest WHERE quizid = $quizid";
        echo execute_sql($sql_str);
    }else{
        echo "no record to delete.";
    }
}
function assignCertificationTest($quizid, $courseid){


	if(courseHasVirtualLabs($courseid)){
			
   // $record = get_record('certificationtest','quizid',$quizid);

   /* if(!empty($record)){
    
        $sql_str = "UPDATE mdl_certificationtest SET active=$status WHERE id=$quizid";

        execute_sql($sql_str, false);
    }else{*/
        //$record =
        //echo insert_record('certificationtest',array('quizid'=>$param->quizid));
        $sql_str = "INSERT INTO mdl_certificationtest (quizid) VALUES($quizid)";
        echo execute_sql($sql_str);
    //}
	}else{
		echo "NO-VLAB";
	}
}

function courseHasVirtualLabs($courseid){
    $result = false;
	
	if(record_exists('course_modules','course',$courseid,'module',43)){
		$result = true;
	}

    return $result;
}


?>
