<?php  // $Id: lib.php,v 1.7.2.5 2009/04/22 21:30:57 skodak Exp $

/**
 * Library of functions and constants for module deva
 *
 */

require_once("$CFG->dirroot/mod/deva/lib.php");


/**
 *
 * 
 * @param 
 * @return 
 */
function deva_newfunction($param) {

}

// 07.11.2011 - jam
function isAttemptLeftOpen($cmid, $userid){
    $result = false;
	
	$cm = get_record('course_modules','id',$cmid);
	$sql = 'select * from mdl_quiz_attempts where userid = '.$userid.' and quiz ='.$cm->instance.' and timefinish = 0';
	
	if(record_exists_sql($sql)){
	
		$result = true;
		
	}
    return $result;
}


?>
