<?php  // index.php - the front page.

	require_once('../config.php');
    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');
	
	require_once($CFG->dirroot.'/mod/deva/php/verifyfunctions.php');
/*
    if (isloggedin() and !isguest()) {
        if(!iscreator() and !isteacherinanycourse()){
            redirect($CFG->wwwroot .'/index.php');
        }
    } else {
        redirect($CFG->wwwroot .'/index.php');
    }
*/
    // get values from form for actions on this page
    $param = new stdClass();

    $param->search = optional_param('search', null, PARAM_INT);
	$param->certificate = optional_param('certificate', null, PARAM_INT);
	$param->editcertificate = optional_param('editcertificate', null, PARAM_INT);
	$param->manage = optional_param('manage', null, PARAM_INT);
    $param->lname = optional_param('lname', '', PARAM_ALPHANUM);
    $param->fname = optional_param('fname', '', PARAM_ALPHANUM);
	$param->serial = optional_param('Field5', '', PARAM_CLEAN);
    $param->email = optional_param('email', '', PARAM_CLEAN);
	$param->title = optional_param('title', '', PARAM_CLEAN);
    $param->desc = optional_param('Field10', '', PARAM_CLEAN);
	$param->issue = optional_param('pickIssueDate', '', PARAM_ALPHANUM);
	$param->expire = optional_param('pickExpireDate', '', PARAM_ALPHANUM);
	$param->certi = optional_param('certificates', null, PARAM_INT);
	
	$param->expire_mm = optional_param('expireDate-1', null, PARAM_INT);
	$param->expire_dd = optional_param('expireDate-2', null, PARAM_INT);
	$param->expire_yy = optional_param('expireDate', null, PARAM_INT);
	
	$param->issue_mm = optional_param('issueDate-1', null, PARAM_INT);
	$param->issue_dd = optional_param('issueDate-2', null, PARAM_INT);
	$param->issue_yy = optional_param('issueDate', null, PARAM_INT);
	
	$param->ver1 = optional_param('version-1', null, PARAM_ALPHANUM);
	$param->ver2 = optional_param('version-2', null, PARAM_ALPHANUM);
	$param->ver3 = optional_param('version-3', null, PARAM_ALPHANUM);
	$param->ver4 = optional_param('version', null, PARAM_ALPHANUM);
	
	$param->fver1 = optional_param('fromversion-1', null, PARAM_ALPHANUM);
	$param->fver2 = optional_param('fromversion-2', null, PARAM_ALPHANUM);
	$param->fver3 = optional_param('fromversion-3', null, PARAM_ALPHANUM);
	$param->fver4 = optional_param('fromversion', null, PARAM_ALPHANUM);
	$param->tver1 = optional_param('toversion-1', null, PARAM_ALPHANUM);
	$param->tver2 = optional_param('toversion-2', null, PARAM_ALPHANUM);
	$param->tver3 = optional_param('toversion-3', null, PARAM_ALPHANUM);
	$param->tver4 = optional_param('toversion', null, PARAM_ALPHANUM);
	
	$param->userid = optional_param('username', null, PARAM_INT);
	$param->selected = optional_param('selected', null, PARAM_INT);
	
	$param->add = optional_param('add', null, PARAM_INT);
	$param->edit = optional_param('edit', null, PARAM_INT);
	$param->addcert = optional_param('addcert', null, PARAM_INT);
	$param->editcert = optional_param('editcert', null, PARAM_INT);
	$param->managerecords = optional_param('managerecords', null, PARAM_INT);
	$param->editrecord = optional_param('editrecord', null, PARAM_INT);	
	$param->listmanage = optional_param('listmanage', null, PARAM_INT);	
	$param->certuserrecord = optional_param('certuserrecord',null,PARAM_INT);
	
	$param->browseall = optional_param('browseall',null,PARAM_INT);

	if(isloggedin()){
 		print_header($SITE->fullname, $SITE->fullname, 'Certificate Verification');
	}
?>

<link rel='stylesheet' type='text/css' href='form.css' />
<link rel='stylesheet' type='text/css' href='structure.css' />
<link rel='stylesheet' type='text/css' href='../mod/scheduler/fullcalendar/css/custom-theme/jquery-ui-1.8.1.custom2.css' />
<script type='text/javascript' src='wufoo.js'></script>
<script type='text/javascript' src='../mod/scheduler/fullcalendar/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='../mod/scheduler/fullcalendar/jquery/jquery-ui-1.8.9.custom.min.js'></script>

<script type="text/javascript">

	$(document).ready(function() {
	
			$("select#username").each(function(){
				var selected = $(this).val();
				
				if(selected)
					getCertUserRecords($("select.users option:selected").val());
			});
		
			$("#accordion").accordion();
			
			
			$("#pickIssueDate").each(function(){
				var today;
				
				if($(this).val() == ""){
					today = new Date();
					$(this).val(today.getMonth()+1 +"/"+ today.getDate() +"/"+ today.getFullYear());
				}else{
					today = new Date($(this).val());
				}
				
				$("#issueDate-1").val(today.getMonth()+1);
				$("#issueDate-2").val(today.getDate());
				$("#issueDate").val(today.getFullYear());
			
			});
			
			$("#pickExpireDate").each(function(){
				var today;
				
				if($(this).val() != ""){
					
					today = new Date($(this).val());
				
					$("#expireDate-1").val(today.getMonth()+1);
					$("#expireDate-2").val(today.getDate());
					$("#expireDate").val(today.getFullYear());
				}
			});
		
		//alert($("input[name='email']").val());
		//.search, .desc, .record
		$("form.record").each(function(){
			getUserEmail($("select.users option:selected").val());
			getCertDesc($("select.certs option:selected").val());
			
			$("select.users").change(function(){
				getUserEmail($("select.users option:selected").val());
			});
			
			$("select.certs").change(function(){
				getCertDesc($("select.certs option:selected").val());
			});
			
			setIssueDatePicker();
			setExpireDatePicker();
		});
		
		$("form.editrecord").each(function(){
			getCertDesc($("select.certs option:selected").val());
			$("select.certs").change(function(){
				getCertDesc($("select.certs option:selected").val());
			});
		});
		
		$("form.editdesc").each(function(){
			getCertInfo($("select.certs option:selected").val());
			
			$("select.certs").change(function(){
				getCertInfo($("select.certs option:selected").val());
			});
		});
		
		$("form.desc").each(function(){
		
			$("select.certs").change(function(){
				getCertDesc($("select.certs option:selected").val());
			});
		});
		
		$("form.listmanage").each(function(){
			$("select.users").change(function(){
				getCertUserRecords($("select.users option:selected").val());
			});
		
		});
	
	
		
		$("select, input, textarea").focus(function(){
			$("li").removeClass("focused");
			$(this).closest("li").addClass("focused");
		
		});
		
		
		
		
		
		$(".version").change(function(){
		
			var value = $(this).val();
			
			if(!isNaN(value)){
			
				var num = parseInt(value);
				if(value.length == 1){
				//if(num >= 0 && num < 10){
					$(this).val("0"+value);
				}
			}
		});
		
	/*	
		$("select, input, textarea").change(function(){
			checkState(this);
		});
	*/
		
		
	});
	
	function setIssueDatePicker(){
		$("#pickIssueDate").datepicker({
			showOn: "button",
			buttonImage: "images/icons/calendar.png",
			buttonImageOnly: true,
			onClose: function(dateText, inst) {
				var arrDate = dateText.split("/");
				$("#issueDate-1").val(arrDate[0]);
				$("#issueDate-2").val(arrDate[1]);
				$("#issueDate").val(arrDate[2]);
			}
		});
	}
	function setExpireDatePicker(){
		$("#pickExpireDate").datepicker({
			showOn: "button",
			buttonImage: "images/icons/calendar.png",
			buttonImageOnly: true,
			onClose: function(dateText, inst) {
				var arrDate = dateText.split("/");
				$("#expireDate-1").val(arrDate[0]);
				$("#expireDate-2").val(arrDate[1]);
				$("#expireDate").val(arrDate[2]);
			}
		});
	}
	
	function getUserEmail(id) {
		$("input#email").attr("disabled", "disabled");
	
		$.ajax({
			type: 'POST',
			url: '../mod/deva/php/verifyfunctions.php',
			dataType: 'text',
			async: true,
			data: {
				action: 'getEmailAddress',
				userId: id
			},
			success: function(data) {
				$('input#email').val(data);
			}
		});
	
	}
	
	function getCertDesc(id) {
		$("#Field10").attr("disabled", "disabled");
	
		$.ajax({
			type: 'POST',
			url: '../mod/deva/php/verifyfunctions.php',
			dataType: 'text',
			async: true,
			data: {
				action: 'getCertDesc',
				descId: id
			},
			success: function(data) {
				$('#Field10').val(data);
				validateRange(10, 'character');
			}
		});
	
	}
	
	function getCertInfo(id) {
	
		$.ajax({
			type: 'POST',
			url: '../mod/deva/php/verifyfunctions.php',
			dataType: 'json',
			async: true,
			data: {
				action: 'getCertInfo',
				descId: id
			},
			success: function(data) {
				$('#title').val(data.title);
				$('#Field10').val(data.description);
				validateRange(10, 'character');
			}
		});
	
	}
	
	function getCertUserRecords(id) {
	
		$.ajax({
			type: 'POST',
			url: '../mod/deva/php/verifyfunctions.php',
			dataType: 'json',
			async: true,
			data: {
				action: 'getCertUserRecords',
				id: id
			},
			success: function(records) {
				
				if($.isArray(records)){
					
					addCertLi(records);
					
				}
			}
		});
	
	}
	
	function deleteCertUserRecords(id) {
	
		$.ajax({
			type: 'POST',
			url: '../mod/deva/php/verifyfunctions.php',
			dataType: 'json',
			async: true,
			data: {
				action: 'deleteCertUserRecords',
				id: id
			},
			success: function(result) {
				
				if(result){
					$('#lirec_'+id).empty();
				}else{
					alert("The record could not be deleted.");
				}
				
			}
		});
	
	}
	
	
	function addCertLi(records){
		
		var userlist = $('#userli');
		var ul = $('#userli').parent();
		
		$(ul).empty();
		$($(userlist)).appendTo($(ul));
		
		$("select.users").change(function(){
			getCertUserRecords($("select.users option:selected").val());
		});
	
		
		for (var j=0; j<records.length; j++){
						
			var listr = '';
			
			listr += '<li id="lirec_'+records[j].id+'" class="certrecordli">';
			listr += '<label class="desc" id="title8" for="username">'+records[j].title+'</label>';
			listr += '<div>';
			listr += '<p>'+records[j].description+'</p>';
			listr += '<b>Issue Date: </b>'+records[j].issue_date+'<br/>';
			listr += '<b>Expire Date: </b>'+records[j].expire_date+'<br/>';
			listr += '<br/><a href="'+records[j].filename+'" target="_blank">View Certificate</a> ';
			listr += '| <a href="index.php?editrecord='+records[j].id+'">Edit</a> ';
			//listr += '| <a href="#lirec_'+records[j].id+'" id="'+records[j].id+'" class="editcertrecord">Edit</a> ';
			listr += '| <a href="#" id="'+records[j].id+'" class="deletecertrecord">Delete</a><br/><br/><hr/>';
			listr += '</div></li>';
		
			
			//alert('here');
			$(listr).appendTo($('#userli').parent());
			//$("#userli").add(listr);
			
		}
		
		$(".deletecertrecord").click(function(){
			deleteCertUserRecords(this.id);
		});
	/*
		$(".editcertrecord").click(function(){
			alert(this.id);
		});
	*/
	}
	
	function editCertLi(record){
	
		
					
		var listr = '';
		
		//listr += '<li id="lirec_'+record.id+'" class="certrecordli">';
		listr += '<label class="desc" id="title8" for="username">'+record.title+'</label>';
		listr += '<div>';
		listr += '<p>'+record.description+'</p>';
		listr += '<b>Issue Date: </b>'+record.issue_date+'<br/>';
		listr += '<b>Expire Date: </b>'+record.expire_date+'<br/>';
		listr += '<br/><a href="'+record.filename+'" target="_blank">View Certificate</a> ';
		listr += '<br/>| <a href="index.php?editrecord='+record.id+'">Edit</a>';
		//listr += '| <a href="#lirec_'+record.id+'" id="'+record.id+'" class="editcertrecord">Edit</a> ';
		listr += '| <a href="#" id="'+record.id+'" class="deletecertrecord">Delete</a><br/><br/><hr/>';
		listr += '</div>';	//</li>';
	
		
		//alert('here');
		$('#lirec_'+record.id).html(listr);
		
	
		
		$(".deletecertrecord").click(function(){
			deleteCertUserRecords(this.id);
		});
	/*
		$(".editcertrecord").click(function(){
			alert(this.id);
		});
	*/
	}
	
	
	
	// Form Validation Functions
	
	function handleInput(el){
	
	}
	
	function validateFields(){
		
		var isValid = true;
		var errors = [];
		
		$("select.required, input.required, textarea.required").each(function(){
			
			var value = $(this).val();
			
			if(value.length <= 0){
				errors.push(this.id);
				isValid = false;
			}else{
				/*
				if(this.id == 'Field5'){
					if(value.length != 50){
						errors.push(this.id);
						isValid = false;
					}else{
						$(this).closest("li").removeClass("error");
					}
				}else */
				if($(this).hasClass('version')){
					if(isNaN(value)){
						errors.push(this.id);
						isValid = false;
					}else{
						var num = parseInt(value);
						if(num < 0 || num > 99){
							errors.push(this.id);
							isValid = false;
						}else{
							$(this).closest("li").removeClass("error");
						}
					}
				}else if($(this).hasClass('issueDate')){
				
					if(isNaN($("#issueDate-1").val()) || isNaN($("#issueDate-2").val()) || isNaN($("#issueDate").val())){
						errors.push(this.id);
						isValid = false;
					}else{
						$(this).closest("li").removeClass("error");
					}
					
				}else if($(this).hasClass('expireDate')){
					
					if(isNaN($("#expireDate-1").val()) || isNaN($("#expireDate-2").val()) || isNaN($("#expireDate").val())){
						errors.push(this.id);
						isValid = false;
					}else{
						$(this).closest("li").removeClass("error");
					}
				
				}else{
					$(this).closest("li").removeClass("error");
				}
				
				
			}
				
		});
		
		if(isValid){
			var issue = $("#issueDate-1").val() + "/" + $("#issueDate-2").val() + "/" + $("#issueDate").val();
			var issueDate = new Date(issue);
			if(!issueDate){
				errors.push(this.id);
				isValid = false;
			}else{
				$("#issueDate").closest("li").removeClass("error");
				$("#pickIssueDate").val(issue);
			}

			var expire = $("#expireDate-1").val() + "/" + $("#expireDate-2").val() + "/" + $("#expireDate").val();
			if(expire.length > 2){
				var expireDate = new Date(expire);
				if(!expireDate){
					errors.push(this.id);
					isValid = false;
				}else{
					$("#expireDate").closest("li").removeClass("error");
					$("#pickExpireDate").val(expire);
				}
			}
			
		}
		
		for (i=0;i<errors.length;i++){
			//alert(selections[i]);
			$("#"+errors[i]).closest("li").addClass("error");
		}
		
		return isValid;
	
	}
	
	function validateDescFields(){
		
		var isValid = true;
		
		$("select.required, input.required, textarea.required").each(function(){
			
			var value = $(this).val();
			
			if(value.length <= 0){
				$(this).closest("li").addClass("error");
				isValid = false;
			}else{
			/*
				if(this.id == 'Field5'){
					if(value.length != 50){
						$(this).closest("li").addClass("error");
						isValid = false;
					}else{
						$(this).closest("li").removeClass("error");
					}
				}else */
				if(this.id == 'Field10'){
					if(value.length > 200){
						$(this).closest("li").addClass("error");
						isValid = false;
					}else{
						$(this).closest("li").removeClass("error");
					}
				}else{
					$(this).closest("li").removeClass("error");
				}
				
			}
				
		});
		
		return isValid;
	
	}
	
	
	function validateSearchFields(){
	
		var isValid = true;
		var oneSelection = false;
		var selections = [];
		
		$(":text").each(function(){
			
			var value = $(this).val();
			
			//if(!oneSelection){
				if(value.length <= 0){
					if(!oneSelection){
						$(this).closest("li").addClass("error");
						isValid = false;
					}
				}else{
					oneSelection = true;
					/*if(this.id == 'Field5' || this.id == 'Field10'){
						if(value.length != 50){
							selections.push(this.id);
							//$(this).closest("li").addClass("error");
							isValid = false;
						}else{
							$(this).closest("li").removeClass("error");
						}
					}else */
					if(this.id == 'email'){
						if(!validateEmail(this.id)){
							selections.push(this.id); 
							//$(this).closest("li").addClass("error");
							isValid = false;
						}else{
							$(this).closest("li").removeClass("error");
						}
					}else{
					
						if(this.id == "fname"){
							var nvalue = $("#lname").val();
							if(nvalue.length <= 0){
								selections.push(this.id);
								isValid = false;
							}else{
								$(this).closest("li").removeClass("error");
							}
							
						}else if(this.id == "lname"){
							var nvalue = $("#fname").val();
							if(nvalue.length <= 0){
								selections.push(this.id);
								isValid = false;
							}else{
								$(this).closest("li").removeClass("error");
							}
							
						}else{
							oneSelection = true;
							$(this).closest("li").removeClass("error");
						}
					}
					
				}
			//}
			
			
				
		});
		
		if(oneSelection){
			$(":text").closest("li").removeClass("error");
			if(selections.length > 0)
				isValid = false;
			else
				isValid = true;
		}
		
		for (i=0;i<selections.length;i++){
			//alert(selections[i]);
			$("#"+selections[i]).closest("li").addClass("error");
		}
		
		return isValid;
	
	}
	
	
	function validateEmail(name){
		//var e=document.forms["myForm"]["email"].value;
		e = $("#"+name).val();
		var result = true;
		var atpos=e.indexOf("@");
		var dotpos=e.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=e.length){
			//alert("Not a valid e-mail address");
			result = false;
		}
		return result;
	}
	
	
	function checkState(el){
	
		$(el).each(function(){
			
			var value = $(this).val();
			
			if(value <= 0){
				//alert('invalid: '+this.id);
				$(this).closest("li").addClass("error");
			}else{
				$(this).closest("li").removeClass("error");
			}
			
		});
	
	}
	
	function doSubmitEvents(){
	
		$("form.desc").each(function(){
			if(validateDescFields()){
				$("form").submit();
			}
		});
		
		$("form.editrecord").each(function(){
			if(validateFields()){
				$("form").submit();
			}
		});
		
		$("form.editdesc").each(function(){
			if(validateDescFields()){
				$("form").submit();
				//alert('edit');
			}
		});
		
		$("form.record").each(function(){
			if(validateFields()){
				$("form").submit();
			}
		});
		
		$("form.search").each(function(){
			if(validateSearchFields()){
				$("form").submit();
				//alert('search');
			}
		});
		
		$("form.manage").each(function(){
			if(validateFields()){
				$("form").submit();
				//alert('manage');
			}
		});
		
	}
	
</script>
<style type="text/css">
	/*
	#accordion div {	
		width: 800px;
	}*/
</style>

<?php

if(!isadmin()){


	if(!empty($param->browseall) || !empty($param->search) || isloggedin()){
		
		$usesql = false;
		
		if(!empty($param->search)){
			
			//$sql = "SELECT * FROM mdl_user u, mdl_certificate_records cr WHERE u.id = cr.userid";
			$sql = "SELECT u.id, u.firstname, u.lastname, u.email, cr.certificateid, cr.issue_date, cr.expire_date, cr.filename, cr.serial_number, cr.version_code, cc.title, cc.description FROM mdl_user u, mdl_certificate_records cr, mdl_course_certificates cc WHERE u.id = cr.userid AND cr.certificateid = cc.id";
			
			if(!empty($param->fname) && !empty($param->lname)){
				$sql .= " AND u.firstname like '%".addslashes($param->fname)."%' AND u.lastname like '%".addslashes($param->lname)."%'";
				$usesql = true;
			}else{
				if(!empty($param->fname)){
					$sql .= " AND u.firstname like '%".addslashes($param->fname)."%'";
					$usesql = true;
				}
				if(!empty($param->lname)){
					$sql .= " AND u.lastname like '%".addslashes($param->lname)."%'";
					$usesql = true;
				}
			}
			if(!empty($param->email)){
				$sql .= " AND u.email = '".addslashes($param->email)."'";
				$usesql = true;
			}
			if(!empty($param->serial)){
				$sql .= " AND cr.serial_number = '".addslashes($param->serial)."'";
				$usesql = true;
			}
			
		}elseif(!empty($param->browseall)){
			$sql = "SELECT u.id, u.firstname, u.lastname, u.email, cr.certificateid, cr.issue_date, cr.expire_date, cr.filename, cr.serial_number, cr.version_code, cc.title, cc.description FROM mdl_user u, mdl_certificate_records cr, mdl_course_certificates cc WHERE u.id = cr.userid AND cr.certificateid = cc.id";
			$usesql = true;
		}else{
			$sql = "SELECT u.id, u.firstname, u.lastname, u.email, cr.certificateid, cr.issue_date, cr.expire_date, cr.filename, cr.serial_number, cr.version_code, cc.title, cc.description FROM mdl_user u, mdl_certificate_records cr, mdl_course_certificates cc WHERE u.id = cr.userid AND cr.certificateid = cc.id AND u.id = ".$USER->id;
			$usesql = true;
		}
		
		if($usesql){
			$sql .= " ORDER BY lastname, issue_date DESC";
		
				
			//$records = get_recordset_sql($sql);
			$numrecords = 0;
			
			echo "<table border='0' cellpadding='5' cellspacing='5' class='boxwidthwide boxaligncenter questioncategories contextlevel'>";
			
			echo "<tr><td style='vertical-align:top; min-height:400px;'><div id='accordion'>";
			
			if ($rs = get_recordset_sql($sql)) {
				while ($record = rs_fetch_next_record($rs)) {
				
				//foreach ($records as $record){
					$numrecords++;
					$format = "Y-m-d H:i:s";
				
					//$issueDate = date($format,mktime(0,0,0,$param->issue_mm,$param->issue_dd,$param->issue_yy));
					
					//$idate = new DateTime($record->issue_date);
					$idatestr = substr($record->issue_date,0,10);
					
					if(!empty($record->expire_date)){
						//$edate = new DateTime($record->expire_date);
						//$edatestr = $edate->format('m-d-Y');
						$edatestr = substr($record->expire_date,0,10);
					}
	
					echo "<h3><a href='#'>$record->title - $record->lastname, $record->firstname</a></h3>";
					echo "<div>";
					echo "<b>Student Name: </b> $record->lastname, $record->firstname <br/>";
					echo "<b>Student Email: </b> $record->email <br/><br/>";
					echo "<b>Certificate Title: </b> $record->title <br/>";
					echo "<b>Certification Description: </b> $record->description<br/><br/>";
					echo "<b>Certification Serial Number: </b> $record->serial_number<br/>";
					echo "<b>Version Number: </b> ".parseVersionForDisplay($record->version_code)."<br/><br/>";
					
					echo "<b>Issue Date: </b>".$idatestr."<br/>";
					echo "<b>Expiration Date: </b>".$edatestr."<br/>";
	
					//echo "<br/> user: ".$record->id." - ".$USER->id;
	
					if(!empty($record->filename) ){
						if(isadmin()){
							echo "<br/><a href='".$record->filename."' target='_blank'>View Certificate</a><br/>";
						}elseif(isloggedin() && $USER->id == $record->id){
							//if(!empty($record->filename) && !isguest()){
							echo "<br/><a href='".$record->filename."' target='_blank'>View Certificate</a><br/>";
						}
					}
					echo "</div>";
				//}
				}
    			rs_close($rs);
			}
			
			echo "</div><br/>";
			
			if($numrecords == 0)
				echo "This search has no results.";
				
			if(!isloggedin())
				echo "<br><br/><a href='index.php'>Search Again</a>";
			
			echo "</td></tr></table>";
		}			
	
	}else{   
?>
            <!-- User: Search -->
            <title></title><div id="container" class="ltr">
            <form id="form1" name="form1" class="wufoo topLabel page1 search" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
            
            <header id="header" class="info">
            <h2>Certification Verification: Search</h2>
            <div>providing verification for certifications that are issued through the ITA Portal.</div>
            </header>
            
            <ul>
            
            
            
            
            <li id="fo1li1" class="">
            <label class="desc" id="title1" for="Field1">
            Name
            </label>
            <span>
            <input id="fname" name="fname" type="text" class="field text fn" value="" size="8" tabindex="1" onkeyup="handleInput(this);" onchange="handleInput(this);">
            <label for="Field1">First</label>
            </span>
            <span>
            <input id="lname" name="lname" type="text" class="field text ln" value="" size="14" tabindex="2" onkeyup="handleInput(this);" onchange="handleInput(this);">
            <label for="Field2">Last</label>
            </span>
            </li>
            
            <li><center><b>OR</b></center></li>
            
            
            <li id="fo1li5" class="">
            <label class="desc" id="title5" for="Field5">
            Certification Serial Number
            </label>
             <div>
            <input id="Field5" name="Field5" type="text" class="field text large required" value="" maxlength="255" tabindex="3" onkeyup="handleInput(this); validateRange(5, 'character');" onchange="handleInput(this);">
            <!--label for="Field5">Must be <var id="rangeMinMsg5">50</var> characters.&nbsp;&nbsp;&nbsp;--> <em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg5">0</var> characters.</em></label>
            </div>
            </li>
            
            <li><center><b>OR</b></center></li>
            
            <li id="fo1li6" class="">
            <label class="desc" id="title6" for="Field6">
            Email
            </label>
            <div>
            <input id="email" name="email" type="text" spellcheck="false" class="field text large" value="" maxlength="255" tabindex="4" onkeyup="handleInput(this);" onchange="handleInput(this);"> 
            </div>
            </li>
            
            
             
            
            
            <li class="buttons ">
            <div>
            
            <input id="saveForm" name="saveForm" class="btTxt submit" type="button" value="Search" tabindex="91" onclick="doSubmitEvents();">
            <a href='/verifycert/index.php?browseall=1' target='_blank'>View All Records</a><br/>
							
            </div>
            </li>
            
            <li class="hide">
            <input type="hidden" id="search" name="search" value="1">
            </li>
            </ul>
            </form>
            
            </div>
            
<?php
		
	}

}else if(!empty($param->manage)){
?>
	<!-- Admin: Manage Versions -->
    <div id="container" class="ltr">
    <form id="form1" name="form1" class="wufoo topLabel page1 manage" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
    <h2>Certification Verification: Manage Versions</h2>
    <div>for managing certifications expiration dates by version number.
    <br/><a href="index.php">Add Certication Record</a> | <a href="index.php?certificate=1">Add Certificate</a>
    </div>
    <ul>
    
    
    
    <li id="fo1li8" class="      ">
    <label class="desc" id="title8" for="certificates">
    Course Certificate
    </label>
    <div>
    <select id="certificates" name="certificates" class="field select large required certs" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="4"> 
    <?php echo getCertList(true); ?>
    </select>
    </div>
    </li>
    
     <li id="fo1li4" class=" ">
    <label class="desc" id="title4" for="fromversion" style="float: left; width: 50%;">
	From
    </label>
    <label class="desc" id="title4" for="toversion">
    To
    </label>
   <span>
    <input id="fromversion-1" name="fromversion-1" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="6" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="fromversion-1">NN</label>
    </span> 
    <span class="symbol">.</span>
    <span>
    <input id="fromversion-2" name="fromversion-2" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="7" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="fromversion-2">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="fromversion-3" name="fromversion-3" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="8" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="fromversion-3">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="fromversion" name="fromversion" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="9" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="fromversion">NN</label>
    </span>
	
    <span>&nbsp;&nbsp;&nbsp;</span>
    
   <span>
    <input id="toversion-1" name="toversion-1" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="9" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="toversion-1">NN</label>
    </span> 
    <span class="symbol">.</span>
    <span>
    <input id="toversion-2" name="toversion-2" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="10" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="toversion-2">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="toversion-3" name="toversion-3" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="11" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="toversion-3">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="toversion" name="toversion" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="12" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="toversion">NN</label>
    </span>
    </li>
    
    
    <li id="fo1li12" class="date      ">
    <label class="desc" id="title12" for="expireDate">
    Set Expire Date
    </label>
    <span>
    <input id="expireDate-1" name="expireDate-1" type="text" class="field text expireDate required" value="" size="2" maxlength="2" tabindex="13" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate-1">MM</label>
    </span> 
    <span class="symbol">/</span>
    <span>
    <input id="expireDate-2" name="expireDate-2" type="text" class="field text expireDate required" value="" size="2" maxlength="2" tabindex="14" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate-2">DD</label>
    </span>
    <span class="symbol">/</span>
    <span>
     <input id="expireDate" name="expireDate" type="text" class="field text expireDate required" value="" size="4" maxlength="4" tabindex="15" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate">YYYY</label>
    </span>
    <span id="cal12">
    <input type="hidden" id="pickExpireDate" name="pickExpireDate" />
    <!--img id="pickIssueDatei" class="datepicker" src="images/icons/calendar.png" alt="Pick a date."-->
    </span>
    </li>
    
    <li class="buttons ">
    <div>
    <input id="saveForm" name="saveForm" class="btTxt submit" type="button" value="Submit" tabindex="91" onclick="doSubmitEvents();">
    </div>
    </li>
    
    
    <li class="hide">
    <input type="hidden" id="managerecords" name="managerecords" value="1">
    </li>
    </ul>
    </form> 
    </div>
        
<?php

}else if(!empty($param->listmanage)){
?>
	<!-- Admin: Edit Delete USER CERT Records -->
    <div id="container" class="ltr">
    <form id="form1" name="form1" class="wufoo topLabel page1 listmanage" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
    <h2>Certification Verification: Manage Certificate Records</h2>
    <div>for editing and deleting user certifications.
    <br/><a href="index.php">Add Certication Record</a> | <a href="index.php?listmanage=1">Edit Certication Records</a> | <a href="index.php?manage=1">Manage Certificate Versions</a>
    </div>
    <ul>
    
  
  	<li id="userli" class="      ">
    <label class="desc" id="title8" for="username">
    Student Name
    </label>
    <div>
    <select id="username" name="username" class="field select large required users" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="1"> 
    <?php echo getUsersList($param->selected); ?>
    </select>
    </div>
    </li>
    
    
    
    </ul>
    </form>
    
    <div
    </div>
  
    
    
<?php


}else if(!empty($param->certificate)){
?>
	<!-- Admin: Add Description -->
    <div id="container" class="ltr">
    <form id="form1" name="form1" class="wufoo topLabel page1 desc" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
    <h2>Certification Verification: Add Description</h2>
    <div>providing verification for certifications that are issued through the ITA Portal.
    <br/><a href="index.php?editcertificate=1">Manage Existing Certificates</a> | <a href="index.php">Add Certication Record</a>
    </div>
    <ul>
 
    <li id="fo1li9" class="     ">
    <label class="desc" id="title9" for="title">
    Certification Title
    </label>
    <div>
    <input id="title" name="title" type="text" class="field text large required" value="" maxlength="255" tabindex="4" onkeyup="handleInput(this); " onchange="handleInput(this);">
    </div>
    </li>
    
    <li id="fo1li10" class="     ">
    <label class="desc" id="title10" for="Field10">
    Certification Description
    </label>
    
    <div>
    <textarea id="Field10" name="Field10" class="field textarea medium required" spellcheck="true" rows="10" cols="50" tabindex="5" onkeyup="handleInput(this); validateRange(10, 'character');" onchange="handleInput(this);"></textarea>
    
    <label for="Field10">Maximum Allowed: <var id="rangeMaxMsg10">200</var> characters.&nbsp;&nbsp;&nbsp; <em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg10">0</var> characters.</em></label>
    </div>
    </li>
    <li class="buttons ">
    <div>
    <input id="saveForm" name="saveForm" class="btTxt submit" type="button" value="Submit" tabindex="91" onclick="doSubmitEvents();">
    </div>
    </li>
    
    <li class="hide">
    <input type="hidden" id="addcert" name="addcert" value="1">
    </li>
    </ul>
    </form> 
    </div>
<?php
}else if(!empty($param->editcertificate)){
?>
	<!-- Admin: Edit Description -->
    <div id="container" class="ltr">
    <form id="form1" name="form1" class="wufoo topLabel page1 editdesc" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
    <h2>Certification Verification: Edit Description</h2>
    <div>providing verification for certifications that are issued through the ITA Portal.
    <br/><a href="index.php">Add Certication Record</a> | <a href="index.php?certificate=1">Add Certificate</a>
    </div>
    <ul>
    

    <li id="fo1li8" class="      ">
    <label class="desc" id="title8" for="certificates">
    Current Certificates
    </label>
    <div>
    <select id="certificates" name="certificates" class="field select large certs" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="1"> 
    <?php echo getCertList(false); ?>
    </select>
    </div>
    </li>

    <li id="fo1li9" class="     ">
    <label class="desc" id="title9" for="title">
    Certification Title
    </label>
    <div>
    <input id="title" name="title" type="text" class="field text large required" value="" maxlength="255" tabindex="4" onkeyup="handleInput(this); " onchange="handleInput(this);">
    </div>
    </li>
    
    <li id="fo1li10" class="     ">
    <label class="desc" id="title10" for="Field10">
    Certification Description
    </label>
    
    <div>
    <textarea id="Field10" name="Field10" class="field textarea medium required" spellcheck="true" rows="10" cols="50" tabindex="5" onkeyup="handleInput(this); validateRange(10, 'character');" onchange="handleInput(this);"></textarea>
    
    <label for="Field10">Maximum Allowed: <var id="rangeMaxMsg10">200</var> characters.&nbsp;&nbsp;&nbsp; <em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg10">0</var> characters.</em></label>
    </div>
    </li>
    <li class="buttons ">
    <div>
    <input id="saveForm" name="saveForm" class="btTxt submit" type="button" value="Submit" tabindex="91" onclick="doSubmitEvents();">
    </div>
    </li>
    
    <li class="hide">
    <input type="hidden" id="editcert" name="editcert" value="1">
    </li>
    </ul>
    </form> 
    </div>
        
<?php
}else{

	if(!empty($param->addcert)){
	
		$message = "Your request is invalid.";
		
		if(!empty($param->title) && !empty($param->desc)){
			if(saveCertificate($param->title, $param->desc)){
				$message = "Record added.";
			}else{
				$message = "Record could not be added";
			}
		}
		
		?>
        
        <div id="container" class="ltr" style="padding:20px">
        
            <h2>Certification Verification: Add Description</h2>
            <div id="message">
            	<?php echo $message; ?>
                <br/><br/><a href="index.php?certificate=1">Back</a> 
            </div>
            
        </div>
        
        <?php
		
	}else if(!empty($param->editcert)){
	
		$message = "Your request is invalid.";
		
		$sql = "UPDATE mdl_course_certificates SET title = '$param->title', description = '$param->desc' WHERE id = $param->certi";		
		
		if(!empty($param->certi) && !empty($param->title) && !empty($param->desc)){
			execute_sql($sql,false);
			$message = "Record updated.";
		}else{
			$message = "Record could not be updated";
		}
		
		?>
        
        <div id="container" class="ltr" style="padding:20px">
        
            <h2>Certification Verification: Add Description</h2>
            <div id="message">
            	<?php echo $message; ?>
                <br/><br/><a href="index.php?editcertificate=1">Back</a> 
            </div>
            
        </div>
        
        <?php
		
	}else if(!empty($param->add)){
	//}else if(!empty($param->edit)){
		
		$message = "Your request is invalid.";
		
		// Check for the uploaded file and save it.
		if (!empty($_FILES)) {
			
			if(!empty($param->userid) && !empty($param->serial) && !empty($param->certi) && !empty($param->issue) && !empty($param->ver1) && !empty($param->ver2) && !empty($param->ver3) && !empty($param->ver4)){
				
				$version = $param->ver1.$param->ver2.$param->ver3.$param->ver4;
				$format = "Y-m-d H:i:s";
				
				$issueDate = date($format,mktime(0,0,0,$param->issue_mm,$param->issue_dd,$param->issue_yy));
				if(!empty($param->expire)){
					$expireDate = date($format,mktime(0,0,0,$param->expire_mm,$param->expire_dd,$param->expire_yy));
				}else{
					$expireDate = NULL;
				}
				
				// Save the file
				
				//$upload_path = 'cert_files/'; // The place the files will be uploaded to (currently a 'files' directory).
				$upload_path = $CFG->dirroot .'/verifycert/cert_files/';
		 
				$filename = $_FILES['certfile']['name']; // Get the name of the file (including file extension).
				$uniqueid = $param->userid;
				$new_filename = time() . $uniqueid . ".". strtolower(substr($filename,strpos($filename,".")+1));
				//$new_filename = $_POST['filename'];
			
				$tempFile = $_FILES['certfile']['tmp_name'];
				//$targetPath = $_SERVER['DOCUMENT_ROOT'] .'/'. $_REQUEST['folder'] . '/';
				//$targetPath = $_REQUEST['folder'] . '/';
				//$targetFile =  str_replace('//','/',$targetPath) . $_FILES['certfile']['name'];
			
				$targetPath = 'cert_files/' . $new_filename;
				
				move_uploaded_file($_FILES['certfile']['tmp_name'], $upload_path . $new_filename);
				
				/*if(move_uploaded_file($_FILES['certfile']['tmp_name'], $upload_path . $new_filename)){
					echo "MOVED<br/>";
				}*/
				//echo $upload_path . $new_filename;
				
				if(file_exists($upload_path . $new_filename)){
					
					if($issueDate){	
						
						if(saveCertificateUserRecord($param->userid, $param->certi, $param->serial, $version, $issueDate, $expireDate, $targetPath)){
							$message = "Record added.";
						}else{
							$message = "Record could not be added";
						}
					}else{
						$message = "invalid dates";
					}
				}else{
					$message = "The file could not be saved.";
				}
		
			}
		}
		
		
		?>
        
        <div id="container" class="ltr" style="padding:20px">
        
            <h2>Certification Verification: 
            <?php if(!empty($param->edit)){ ?>
				Add 
            <?php }else{ ?>
            	Edit
            <?php } ?>
            
            Record</h2>
            <div id="message">
            	<?php echo $message; ?>
                <br/><br/><?php echo "<a href='index.php?selected=".$param->userid."'>Back</a>"; ?>
            </div>
            
        </div>
        
        <?php
	
/*		if(!empty($param->expire_mm) && !empty($param->expire_dd) && !empty($param->expire_yy)){
			if(is_int($param->expire_mm) && is_int($param->expire_dd) && is_int($param->expire_yy)){
				$expireDate = date('YYYY-MM-DD HH:MM:SS', mktime(0, 0, 0, $param->expire_mm, $param->expire_dd, $param->expire_yy));
			}
		}
		if(!empty($param->issue_mm) && !empty($param->issue_dd) && !empty($param->issue_yy)){
			if(is_int($param->issue_mm) && is_int($param->issue_dd) && is_int($param->issue_yy)){
				$issueDate = date('YYYY-MM-DD HH:MM:SS', mktime(0, 0, 0, $param->issue_mm, $param->issue_dd, $param->issue_yy));
			}
		}
		
*/
		//saveCertificateUserRecord($param->userid, $param->certificate, $$param->serial, $version, $issueDate, $expireDate)

	}else if(!empty($param->edit)){
	//}else if(!empty($param->edit)){
		
		$message = "Your request is invalid.";
		$filefailed = false;
		
		// Check for the uploaded file and save it.
		/*
		echo "$param->certuserrecord : ".$param->certuserrecord."<br/>";
		echo "$param->serial : ".$param->serial."<br/>";
		echo "$param->certi : ".$param->certi."<br/>";
		echo "$param->issue : ".$param->issue."<br/>";
		echo "$param->ver1 : ".$param->ver1."<br/>";
		echo "$param->ver2 : ".$param->ver2."<br/>";
		echo "$param->ver3 : ".$param->ver3."<br/>";
		echo "$param->ver4 : ".$param->ver4."<br/>";
		*/
		
		if(!empty($param->certuserrecord) && !empty($param->serial) && !empty($param->certi) && !empty($param->issue) && !empty($param->ver1) && !empty($param->ver2) && !empty($param->ver3) && !empty($param->ver4)){
			$message = "invalid.";
			$version = $param->ver1.$param->ver2.$param->ver3.$param->ver4;
			$format = "Y-m-d H:i:s";
			
			$issueDate = date($format,mktime(0,0,0,$param->issue_mm,$param->issue_dd,$param->issue_yy));
			if(!empty($param->expire)){
				$expireDate = date($format,mktime(0,0,0,$param->expire_mm,$param->expire_dd,$param->expire_yy));
			}else{
				$expireDate = NULL;
			}
			
			// Save the file
			if (!empty($_FILES)) {
				$upload_path = $CFG->dirroot .'/verifycert/cert_files/';
				$filename = $_FILES['certfile']['name']; // Get the name of the file (including file extension).
			
				$uniqueid = $param->userid;
				$new_filename = time() . $uniqueid . ".". strtolower(substr($filename,strpos($filename,".")+1));
				//$new_filename = $_POST['filename'];
			
				if(!empty($filename)){
					$tempFile = $_FILES['certfile']['tmp_name'];
				
					$targetPath = 'cert_files/' . $new_filename;
				
					move_uploaded_file($_FILES['certfile']['tmp_name'], $upload_path . $new_filename);
				
				}else{
					$targetPath = "";
				}
				
				if(!file_exists($upload_path . $new_filename)){
					$filefailed = true;
				}
			}
	
			if($issueDate){
			
				if(!$filefailed){
				
					if(editCertificateUserRecord($param->certuserrecord, $param->certi, $param->serial, $version, $issueDate, $expireDate, $targetPath)){
						$message = "Record updated.";
					}else{
						$message = "Record could not be updated";
					}				
				}else{
					$targetPath = "";
					if(editCertificateUserRecord($param->certuserrecord, $param->certi, $param->serial, $version, $issueDate, $expireDate, $targetPath)){
						$message = "File was not updated.<br/>Record updated.";
					}else{
						$message = "File was not updated.<br/>Record could not be updated";
					}
					
				}

			}else{
				$message = "invalid dates";
			}
	
		}
	
		
		
		?>
        
        <div id="container" class="ltr" style="padding:20px">
        
            <h2>Certification Verification: 
            <?php if(!empty($param->edit)){ ?>
				Add 
            <?php }else{ ?>
            	Edit
            <?php } ?>
            
            Record</h2>
            <div id="message">
            	<?php echo $message; ?>
                <br/><br/><?php echo "<a href='index.php?listmanage=1&selected=".$param->userid."'>Back</a>"; ?>
            </div>
        </div>
        
        <?php
	
/*		if(!empty($param->expire_mm) && !empty($param->expire_dd) && !empty($param->expire_yy)){
			if(is_int($param->expire_mm) && is_int($param->expire_dd) && is_int($param->expire_yy)){
				$expireDate = date('YYYY-MM-DD HH:MM:SS', mktime(0, 0, 0, $param->expire_mm, $param->expire_dd, $param->expire_yy));
			}
		}
		if(!empty($param->issue_mm) && !empty($param->issue_dd) && !empty($param->issue_yy)){
			if(is_int($param->issue_mm) && is_int($param->issue_dd) && is_int($param->issue_yy)){
				$issueDate = date('YYYY-MM-DD HH:MM:SS', mktime(0, 0, 0, $param->issue_mm, $param->issue_dd, $param->issue_yy));
			}
		}
		
*/
		//saveCertificateUserRecord($param->userid, $param->certificate, $$param->serial, $version, $issueDate, $expireDate)

	}else if(!empty($param->managerecords)){
	
		$message = "Your request is invalid.";
	
		if($param->certi >= 0 && !empty($param->fver1) && !empty($param->fver2) && !empty($param->fver3) && !empty($param->fver4) && !empty($param->tver1) && !empty($param->tver2) && !empty($param->tver3) && !empty($param->tver4) && !empty($param->expire_mm) && !empty($param->expire_dd) && !empty($param->expire_yy)){
			
			$fromversion = $param->fver1.$param->fver2.$param->fver3.$param->fver4;
			$toversion = $param->tver1.$param->tver2.$param->tver3.$param->tver4;
			$format = "Y-m-d H:i:s";
			
			$expiredDate = date($format,mktime(0,0,0,$param->expire_mm,$param->expire_dd,$param->expire_yy));
			if(!empty($param->expire)){
				$expireDate = date($format,mktime(0,0,0,$param->expire_mm,$param->expire_dd,$param->expire_yy));
			}else{
				$expireDate = NULL;
			}
			
			if($expiredDate){
				
				$sql = "UPDATE mdl_certificate_records SET expire_date = '$expiredDate' WHERE CAST(version_code AS DECIMAL) >= $fromversion AND CAST(version_code AS DECIMAL) <= $toversion";
				
				if($param->certi > 0)
					$sql .= " AND certificateid = $param->certi";
				
				execute_sql($sql,false);
				$message = "records updated";
		
			}else{
				$message = "invalid dates";
			}
		}
		
		
		
	?>
        
        <div id="container" class="ltr" style="padding:20px">
        
            <h2>Certification Verification: Manage Versions</h2>
            <div id="message">
            	<?php echo $message; ?>
                <br/><br/><a href="index.php?manage=1">Back</a> 
            </div>
            
        </div>
        
<?php
	
	}else if(!empty($param->editrecord)){
	
	
		$record = getCertUserRecord($param->editrecord);
?>

<!-- Admin: Edit User Certification Record -->
    <div id="container" class="ltr">
    
    
    <form id="form1" name="form1" class="wufoo topLabel page1 editrecord" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
    
    
    <h2>Certification Verification: Edit Record</h2>
    <div>
        providing verification for certifications that are issued through the ITA Portal.
        <br/><a href="index.php?manage=1">Manage Certificate Versions</a> | <a href="index.php?listmanage=1">Edit Certication Records</a> | <a href="index.php?certificate=1">Add Certificate</a>
    </div>
    <ul>
    
    
    
    
    <li id="fo1li8" class="      ">
    <label class="desc" id="title8" for="username">
    Student Name
    </label>
    <div>
    <?php echo $record->firstname." ".$record->lastname." "; ?>
    
    </div>
    </li>
    
    
    
    <li id="fo1li6" class="     ">
    <label class="desc" id="title6" for="email">
    Student Email
    </label>
    <div>
	<?php echo getEmailAddress($record->userid); ?> 
    </div>
    </li>
    
    

    <li id="fo1li8" class="      ">
    <label class="desc" id="title8" for="certificates">
    Certificate Title
    </label>
    <div>
    <select id="certificates" name="certificates" class="field select large required certs" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="4"> 
    <?php echo getCertList(false,$record->certificateid); ?>
    </select>
    </div>
    </li>
    
    <li id="fo1li10" class="     ">
    <label class="desc" id="title10" for="Field10">
    Certification Description
    </label>
    
    <div>
    <textarea id="Field10" name="Field10" class="field textarea required medium" spellcheck="true" rows="10" cols="50" tabindex="5" onkeyup="handleInput(this); validateRange(10, 'character');" onchange="handleInput(this);"></textarea>
    
    <label for="Field10">Maximum Allowed: <var id="rangeMaxMsg10">200</var> characters.&nbsp;&nbsp;&nbsp; <em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg10">0</var> characters.</em></label>
    </div>
    
    </li>
    
    
    
    <li id="fo1li5" class=" ">
    <label class="desc" id="title5" for="Field5">
    Certification Serial Number
    </label>
    <div>
    <input id="Field5" name="Field5" type="text" class="field text large required" value="<?php echo $record->serial_number; ?>" maxlength="255" tabindex="3" onkeyup="handleInput(this); validateRange(5, 'character');" onchange="handleInput(this);">
    <!--label for="Field5">Must be at <var id="rangeMinMsg5">50</var> characters.&nbsp;&nbsp;&nbsp; --><em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg5">0</var> characters.</em></label>
    </div>
    </li>
    
    
    
     <li id="fo1li4" class=" ">
    <label class="desc" id="title4" for="version">
    Version Number
    </label>
   <span>
    <input id="version-1" name="version-1" type="text" class="field text version required" value="<?php echo substr($record->version_code,0,2); ?>" size="2" maxlength="2" tabindex="6" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version-1">NN</label>
    </span> 
    <span class="symbol">.</span>
    <span>
    <input id="version-2" name="version-2" type="text" class="field text version required" value="<?php echo substr($record->version_code,2,2); ?>" size="2" maxlength="2" tabindex="7" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version-2">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="version-3" name="version-3" type="text" class="field text version required" value="<?php echo substr($record->version_code,4,2); ?>" size="2" maxlength="2" tabindex="8" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version-3">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="version" name="version" type="text" class="field text version required" value="<?php echo substr($record->version_code,6,2); ?>" size="2" maxlength="2" tabindex="9" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version">NN</label>
    </span>
    </li>
    
    
    <li id="fo1li12" class="date      ">
    <label class="desc" id="title12" for="issueDate">
    Issue Date
    </label>
    <span>
    <input id="issueDate-1" name="issueDate-1" type="text" class="field text issueDate required" value="" size="2" maxlength="2" tabindex="10" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="issueDate-1">MM</label>
    </span> 
    <span class="symbol">/</span>
    <span>
    <input id="issueDate-2" name="issueDate-2" type="text" class="field text issueDate required" value="" size="2" maxlength="2" tabindex="11" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="issueDate-2">DD</label>
    </span>
    <span class="symbol">/</span>
    <span>
     <input id="issueDate" name="issueDate" type="text" class="field text issueDate required" value="" size="4" maxlength="4" tabindex="12" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="issueDate">YYYY</label>
    </span>
    <span id="cal12">
    <input type="hidden" id="pickIssueDate" name="pickIssueDate" value="<?php echo $record->issue_date; ?>" />
    <!--img id="pickIssueDatei" class="datepicker" src="images/icons/calendar.png" alt="Pick a date."-->
    </span>
    </li>
    
    
    <li id="fo1li11" class="date      ">
    <label class="desc" id="title11" for="expireDate">
    Expiration Date
    </label>
    <span>
    <input id="expireDate-1" name="expireDate-1" type="text" class="field text expireDate" value="" size="2" maxlength="2" tabindex="13" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate-1">MM</label>
    </span> 
    <span class="symbol">/</span>
    <span>
    <input id="expireDate-2" name="expireDate-2" type="text" class="field text expireDate" value="" size="2" maxlength="2" tabindex="14" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate-2">DD</label>
    </span>
    <span class="symbol">/</span>
    <span>
     <input id="expireDate" name="expireDate" type="text" class="field text expireDate" value="" size="4" maxlength="4" tabindex="15" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate">YYYY</label>
    </span>
    <span id="cal11">
    <input type="hidden" id="pickExpireDate" name="pickExpireDate" value="<?php echo $record->expire_date; ?>" />
    <!--img id="pickExpireDatei" class="datepicker" src="images/icons/calendar.png" alt="Pick a date."-->
    </span>
    </li>
    
     
    <li id="fo1li5" class=" ">
    <label class="desc" id="titlecertfile" for="certfile">
    Certificate
    </label>
    <div>
<?php if(!empty($record->filename)){ ?>
    <a href="<?php echo $record->filename; ?>" target="_blank">View Certificate</a><br/><br/>
<?php } ?>
    <input id="certfile" name="certfile" type="file" class="field file large" value="" tabindex="16">
    <label for="Field5">File format: PDF, IMG</label>
    
    </div>
    </li>
    
    
    
    <li class="buttons ">
    <div>
    <input id="saveForm" name="saveForm" class="btTxt submit" type="button" value="Submit" tabindex="91" onclick="doSubmitEvents();">
    
    </div>
    </li>
    
    <li class="hide">
    <input type="hidden" id="certuserrecord" name="certuserrecord" value="<?php echo $record->id; ?>">
    <input type="hidden" id="username" name="username" value="<?php echo $record->userid; ?>">
    <input type="hidden" id="edit" name="edit" value="1">
    </li>
    </ul>
    </form> 
    </div>


<?php
	
	}else{
?>
		 
    <!-- Admin: Add User Certification Record -->
    <div id="container" class="ltr">
    
    
    <form id="form1" name="form1" class="wufoo topLabel page1 record" autocomplete="off" enctype="multipart/form-data" method="post" action="index.php">
    
    
    <h2>Certification Verification: Add Record</h2>
    <div>
        providing verification for certifications that are issued through the ITA Portal.
        <br/><a href="index.php?manage=1">Manage Certificate Versions</a> | <a href="index.php?listmanage=1">Edit Certication Records</a> | <a href="index.php?certificate=1">Add Certificate</a>
    </div>
    <ul>
    
    
    
    
    <li id="fo1li8" class="      ">
    <label class="desc" id="title8" for="username">
    Student Name
    </label>
    <div>
    <select id="username" name="username" class="field select large required users" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="1"> 
    <?php echo getUsersList($param->selected); ?>
    </select>
    </div>
    </li>
    
    
    
    <li id="fo1li6" class="     ">
    <label class="desc" id="title6" for="email">
    Student Email
    </label>
    <div>
    <input id="email" name="email" type="email" spellcheck="false" class="field text large required" value="" maxlength="255" tabindex="2" onkeyup="handleInput(this);" onchange="handleInput(this);"> 
    </div>
    </li>
    
    
    
    <li id="fo1li8" class="      ">
    <label class="desc" id="title8" for="certificates">
    Certificate Title
    </label>
    <div>
    <select id="certificates" name="certificates" class="field select large required certs" onclick="handleInput(this);" onkeyup="handleInput(this);" tabindex="4"> 
    <?php echo getCertList(false); ?>
    </select>
    </div>
    </li>
    
    <li id="fo1li10" class="     ">
    <label class="desc" id="title10" for="Field10">
    Certification Description
    </label>
    
    <div>
    <textarea id="Field10" name="Field10" class="field textarea required medium" spellcheck="true" rows="10" cols="50" tabindex="5" onkeyup="handleInput(this); validateRange(10, 'character');" onchange="handleInput(this);"></textarea>
    
    <label for="Field10">Maximum Allowed: <var id="rangeMaxMsg10">200</var> characters.&nbsp;&nbsp;&nbsp; <em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg10">0</var> characters.</em></label>
    </div>
    
    </li>
    
    
    
    
    <li id="fo1li5" class=" ">
    <label class="desc" id="title5" for="Field5">
    Certification Serial Number
    </label>
    <div>
    <input id="Field5" name="Field5" type="text" class="field text large required" value="" maxlength="255" tabindex="3" onkeyup="handleInput(this); validateRange(5, 'character');" onchange="handleInput(this);">
    <!--label for="Field5">Must be at <var id="rangeMinMsg5">50</var> characters.&nbsp;&nbsp;&nbsp; --><em class="currently" style="display: inline; ">Currently Used: <var id="rangeUsedMsg5">0</var> characters.</em></label>
    </div>
    </li>
    
    
    
    
     <li id="fo1li4" class=" ">
    <label class="desc" id="title4" for="version">
    Version Number
    </label>
   <span>
    <input id="version-1" name="version-1" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="6" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version-1">NN</label>
    </span> 
    <span class="symbol">.</span>
    <span>
    <input id="version-2" name="version-2" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="7" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version-2">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="version-3" name="version-3" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="8" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version-3">NN</label>
    </span>
    <span class="symbol">.</span>
    <span>
     <input id="version" name="version" type="text" class="field text version required" value="" size="2" maxlength="2" tabindex="9" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="version">NN</label>
    </span>
    </li>
    
    
    <li id="fo1li12" class="date      ">
    <label class="desc" id="title12" for="issueDate">
    Issue Date
    </label>
    <span>
    <input id="issueDate-1" name="issueDate-1" type="text" class="field text issueDate required" value="" size="2" maxlength="2" tabindex="10" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="issueDate-1">MM</label>
    </span> 
    <span class="symbol">/</span>
    <span>
    <input id="issueDate-2" name="issueDate-2" type="text" class="field text issueDate required" value="" size="2" maxlength="2" tabindex="11" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="issueDate-2">DD</label>
    </span>
    <span class="symbol">/</span>
    <span>
     <input id="issueDate" name="issueDate" type="text" class="field text issueDate required" value="" size="4" maxlength="4" tabindex="12" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="issueDate">YYYY</label>
    </span>
    <span id="cal12">
    <input type="hidden" id="pickIssueDate" name="pickIssueDate" value="" />
    <!--img id="pickIssueDatei" class="datepicker" src="images/icons/calendar.png" alt="Pick a date."-->
    </span>
    </li>
    
    
    <li id="fo1li11" class="date      ">
    <label class="desc" id="title11" for="expireDate">
    Expiration Date
    </label>
    <span>
    <input id="expireDate-1" name="expireDate-1" type="text" class="field text expireDate" value="" size="2" maxlength="2" tabindex="13" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate-1">MM</label>
    </span> 
    <span class="symbol">/</span>
    <span>
    <input id="expireDate-2" name="expireDate-2" type="text" class="field text expireDate" value="" size="2" maxlength="2" tabindex="14" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate-2">DD</label>
    </span>
    <span class="symbol">/</span>
    <span>
     <input id="expireDate" name="expireDate" type="text" class="field text expireDate" value="" size="4" maxlength="4" tabindex="15" onkeyup="handleInput(this);" onchange="handleInput(this);">
    <label for="expireDate">YYYY</label>
    </span>
    <span id="cal11">
    <input type="hidden" id="pickExpireDate" name="pickExpireDate" value="" />
    <!--img id="pickExpireDatei" class="datepicker" src="images/icons/calendar.png" alt="Pick a date."-->
    </span>
    </li>
    
     
    <li id="fo1li5" class=" ">
    <label class="desc" id="titlecertfile" for="certfile">
    Certificate
    </label>
    <div>
    <input id="certfile" name="certfile" type="file" class="field file large required" value="" tabindex="16">
    <label for="Field5">File format: PDF, IMG</label>
    </div>
    </li>
    
    
    
    <li class="buttons ">
    <div>
    <input id="saveForm" name="saveForm" class="btTxt submit" type="button" value="Submit" tabindex="91" onclick="doSubmitEvents();">
    
    </div>
    </li>
    
    <li class="hide">
    <input type="hidden" id="add" name="add" value="1">
    </li>
    </ul>
    </form> 
    </div>
    
<?php
	}
}

//if(isloggedin()){
	print_footer('Certificate Verification');     // Please do not modify this line
//}

/*
 * <0 --> if date a< date b
 * 0 --> date a== date b
 * >0 --> date a > date b respectively.
 */
function compareDates($start_date,$end_date) {
  $start = strtotime($start_date);
  $end = strtotime($end_date);

  return $start-$end;
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
function parseVersionForDisplay($versioncode){

	//06000000

	$v = substr($versioncode,0,2)."."; 
	$v .= substr($versioncode,2,2).".";
	$v .= substr($versioncode,4,2).".";
	$v .= substr($versioncode,6,2);
	
	return $v;
}


?>
