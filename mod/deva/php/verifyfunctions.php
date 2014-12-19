<?php



require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/user/profile/lib.php');

require_once($CFG->libdir .'/ddllib.php');

ini_set("soap.wsdl_cache_enabled", "0");


$wsdl="http://ita-provisioner.cis.fiu.edu:8100/axis2/services/VirtualLabs?wsdl";
$location="http://ita-provisioner.cis.fiu.edu:8100/axis2/services/VirtualLabs";

		
if (isset($_POST['action'])){
	$action = $_POST['action'];   
}else{
	$action = "";
}


if (isset($_POST['arr'])){
	$arr = $_POST['arr'];   
}else{
	$arr = "";
} 

//echo $arr;

/*
if(fileDelete("../../../verifycert/cert_files/","1318549367362.pdf")){
	echo "File deleted.";
}else{
	echo "Cannot delete file.";
}

*/
//*****************************************************************************************

if($action == 'getEmailAddress'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['userId'])){
		$userId = $_POST['userId']; 
	
		echo getEmailAddress($userId);
	}
	
}else if ($action=='getCertDesc'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['descId'])){
		$descId = $_POST['descId'];
		
		echo getCertDesc($descId);
	}
	
}else if ($action=='getCertInfo'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['descId'])){
		$descId = $_POST['descId'];
		
		echo json_encode(getCertInfo($descId));
	}
	
}else if ($action=='getCertUserRecords'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['id'])){
		$id = $_POST['id'];
		
		echo json_encode(getCertUserRecords($id));
	}
	
}else if ($action=='deleteCertUserRecords'){
	header('Content-Type: text/x-json');
	
	if (isset($_POST['id'])){
		$id = $_POST['id'];
		
		echo json_encode(deleteCertUserRecords($id));
	}
	
}

//*****************************************************************************************


function getUsersList($selected){

	//$records = get_records('user','','','username');
	$records = get_users(true,'',false,'','lastname, firstname, username ASC');
    $options = "<option value=''>Select a Student</option>";
	
	foreach ($records as $record){
		if(!empty($selected) && $selected == $record->id)
			$options .= "<option value='$record->id' selected='selected'>$record->lastname, $record->firstname - $record->username</option>";	
		else
			$options .= "<option value='$record->id'>$record->lastname, $record->firstname - $record->username</option>";	
	}
	return $options;
}

function getCertList($showAllOpt, $id){

	$records = get_records('course_certificates','','','title');
    $options = "";
	
	if($showAllOpt)
		$options .= "<option value='0'>All Certificates</option>";
	
	
	foreach ($records as $record){
		if(!empty($id) && $id == $record->id)
			$options .= "<option value='$record->id' selected='selected'>$record->title</option>";	
		else
			$options .= "<option value='$record->id'>$record->title</option>";	
	}
	return $options;
}

function getEmailAddress($id){

	$record = get_record('user','id',$id);
   
	return $record->email;
}

function getCertDesc($id){

	$record = get_record('course_certificates','id',$id);
   
	return $record->description;
}

function getCertInfo($id){

	$record = get_record('course_certificates','id',$id);
   
	return $record;
}

function saveUserCertificate($certid, $issueDate, $expireDate){
	//insert_record('course_certificates',$record);
}

function saveCertificate($title, $desc){
	
	$result = false;
	
	$record = new stdClass();
	$record->title = addslashes($title);
	$record->description = addslashes($desc);
	
	$recordid = insert_record('course_certificates',$record);
	
	if($recordid > 0)
		$result = true;
	
	return $result;
	
	//$sql_str = "INSERT INTO mdl_course_certificates (title,description) VALUES('$title','$desc')";
    //echo $sql_str;
	//return execute_sql($sql_str);
}


function editCertificate($id,$title,$desc){

	$record = get_record('course_certificates','id',$id);

	$record->title = addslashes($title);
	$record->desc = addslashes($desc);
	
	update_record('course_certificates',$record);

}

function saveCertificateUserRecord($userid, $certid, $serial, $version, $issue, $expire, $filename){

	$result = false;
	
		$record = new stdClass();
		$record->userid = parseInt($userid);
		$record->certificateid = parseInt($certid);
		$record->serial_number = addslashes($serial);
		$record->version_code = addslashes($version);
		$record->issue_date = $issue;
		$record->expire_date = $expire;
		$record->filename = $filename;
			
		$recordid = insert_record('certificate_records',$record);
		
	
		if($recordid > 0)
			$result = true;
	
	return $result;
	
	//$sql_str = "INSERT INTO mdl_certificate_records (title,description) VALUES('$title','$desc')";
    //echo $sql_str;
	//return execute_sql($sql_str);
}

function editCertificateUserRecord($recid, $certid, $serial, $version, $issue, $expire, $filename){

	$result = false;
	
		$record = get_record('certificate_records','id',$recid);
		$record->certificateid = parseInt($certid);
		$record->serial_number = addslashes($serial);
		$record->version_code = addslashes($version);
		$record->issue_date = $issue;
		$record->expire_date = $expire;
		
		if(!empty($filename)){
			if(!empty($record->filename)){
				if(fileDelete("",$record->filename)){
					//echo "File deleted. - ".$record->filename;
					$filechanged = true;
				}else{
					//echo "Cannot delete file. - ".$record->filename;
					$filechanged = false;
				}
			}else{
				$filechanged = true;
			}
		}
		//echo "File: ".$record->filename." -> ".$filename."<br/>";
		
		//if($filechanged){
		if(!file_exists($record->filename) && file_exists($filename)){
			$record->filename = $filename;
		}
		
		$recordid = update_record('certificate_records',$record);	
	
		if($recordid > 0)
			$result = true;
	
	return $result;
	
	//$sql_str = "INSERT INTO mdl_certificate_records (title,description) VALUES('$title','$desc')";
    //echo $sql_str;
	//return execute_sql($sql_str);
}

function getCertUserRecords($id){
	
	$sql = "SELECT cr.id, u.firstname, u.lastname, cr.certificateid, cr.issue_date, cr.expire_date, cr.filename, cc.title, cc.description FROM mdl_user u, mdl_certificate_records cr, mdl_course_certificates cc WHERE u.id = cr.userid AND cr.certificateid = cc.id AND u.id = ".$id;

	$sql .= " ORDER BY issue_date";
		
	$records = array();
	if ($rs = get_recordset_sql($sql)) {
		while ($record = rs_fetch_next_record($rs)) {
			array_push($records,$record);
		}
   }
	
   
	return $records;
}

function getCertUserRecord($id){
	
	$sql = "SELECT cr.id, u.firstname, u.lastname, cr.certificateid, cr.issue_date, cr.expire_date, cr.filename, cc.title, cc.description, cr.userid, cr.serial_number, cr.version_code FROM mdl_user u, mdl_certificate_records cr, mdl_course_certificates cc WHERE u.id = cr.userid AND cr.certificateid = cc.id AND cr.id = ".$id;
	
	$record = get_record_sql($sql);
	
	return $record;
}


function deleteCertUserRecords_old($id){
	// get the record
	
	$record = getCertUserRecord($id);

	if(!empty($record)){
		if(!empty($record->filename)){
			fileDelete("",$record->filename);
		}
	}
	
	return (file_exists($record->filename)) ? false : delete_records('certificate_records','id',$id);
	//return delete_records('certificate_records','id',$id);
}


function deleteCertUserRecords($recid){

	$result = false;

	$record = get_record('certificate_records','id',$recid);
	
	if(!empty($record->filename)){
		if(fileDelete("../../../verifycert/",$record->filename)){
			//echo "File deleted. - ".$record->filename;
			$result = true;
		}else{
			//echo "Cannot delete file. - ".$record->filename;
			$result = false;
		}
	}
	
	//if($result)
	if(!file_exists("../../../verifycert/".$record->filename)){
		delete_records('certificate_records','id',$recid);
		$result = true;
	}
		
	return $result;
}



/*
function getBetweenVersions($from,$to, $certificateid){

	$sql = "SELECT * FROM mdl_certificate_records
WHERE CAST(version_code AS DECIMAL) >= 04000000 AND CAST(version_code AS DECIMAL) <= 9000000
AND certificateid = 1
ORDER BY CAST(version_code AS DECIMAL)";

	$records = get_records_sql($sql);
    $options = "";
	
	foreach ($records as $record){
	
	}

}
*/

function fileDelete($filepath,$filename) {
	$success = FALSE;
	if (file_exists($filepath.$filename)&&$filename!=""&&$filename!="n/a") {
		unlink ($filepath.$filename);
		$success = TRUE;
	}
	return $success;	
}

function parseInt($val){
	while (preg_replace('/([0-9])?[^0-9].*/is','$1',$val)!==$val){
		$val=preg_replace('/([0-9])?[^0-9].*/is','$1',$val);
	}
	if ($val=='') { $val=0; }
	return $val;
}

?>