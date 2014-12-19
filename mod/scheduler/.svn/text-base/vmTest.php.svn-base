<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of scheduler
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/scheduler
 */

/// (Replace scheduler with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once(dirname(__FILE__).'/lib.php');


?>
<link rel='stylesheet' type='text/css' href='fullcalendar/css/custom-theme/jquery-ui-1.8.1.custom2.css' />
<script type='text/javascript' src='fullcalendar/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery-ui-1.8.1.custom.min.js'></script>

<script type='text/javascript' src='fullcalendar/jquery.loader.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.countDown.js'></script>



<style type="text/css">

#toolbar {
	padding: 15px 6px;
	/*
		padding: 10px 4px;
	*/
}
#timetools {
	padding: 15px 6px;
}

</style>


<script type="text/javascript">
$(document).ready(function() {

	var currentDate = new Date();
	var appointmentDate = new Date("May 13, 2011 18:00:00");
	

	// Set up Countdown
	$('#counter').countDown({
		//startNumber: appointmentDate.getTime(),
		startNumber: currentDate,
		endNumber: appointmentDate,
		returnDate: true,
		callBack: function(me) {
			$(me).text('All done! This is where you give the reward!').css('color','#090');
		}
	});



	$("#vmControls li.getState").click(function(){
		vmInstanceCmd('getState');
	});
	
/*
	
	$("#vmControls li.powerOff").click(function(){
		vmInstanceCmd('powerOff');
	});
	$("#vmControls li.shutdown").click(function(){
		vmInstanceCmd('shutdown');
	});
	$("#vmControls li.pause").click(function(){
		vmInstanceCmd('suspend');
	});
	$("#vmControls li.powerOn").click(function(){
		vmInstanceCmd('powerOn');
	});
	$("#vmControls li.restart").click(function(){
		vmInstanceCmd('restart');
	});
	
	*/

	$("button.powerOff").button({
		icons: {
			primary: "ui-icon-power",
		},
		text: true,
		disabled: true
	}).click(function(){
		vmInstanceCmd('powerOff');
		vmInstanceCmd('getState');
	});

	$("button.shutdown").button({
		icons: {
			primary: "ui-icon-stop",
		},
		text: true,
		disabled: true
	}).click(function(){
		vmInstanceCmd('shutdown');
		vmInstanceCmd('getState');
	});
	
	$("button.pause").button({
		icons: {
			primary: "ui-icon-pause",
		},
		text: true,
		disabled: true
	}).click(function(){
		vmInstanceCmd('suspend');
		vmInstanceCmd('getState');
	});
	
	$("button.powerOn").button({
		icons: {
			primary: "ui-icon-power",
		},
		text: true,
		disabled: true
	}).click(function(){
		vmInstanceCmd('powerOn');
		vmInstanceCmd('getState');
	});
	
	$("button.restart").button({
		icons: {
			primary: "ui-icon-refresh",
		},
		text: true,
		disabled: true
	}).click(function(){
		vmInstanceCmd('restart');
		vmInstanceCmd('getState');
	});
	
	$("button.refresh").button({
		icons: {
			primary: "ui-icon-trash",
		},
		text: true,
		disabled: true
	}).click(function(){
		//vmInstanceCmd('refresh');
		//vmInstanceCmd('getState');
		confirmRefresh();
	});

	//markCurrentInstanceState("off");
	//markCurrentInstanceState("suspended");
	//markCurrentInstanceState("on");
	//markCurrentInstanceState("error");
	
	
	// Buttons for the Time Tools
	$("button.addtime").button({
		icons: {
			primary: "ui-icon-plus",
		},
		text: true,
		disabled: true
	}).click(function(){
		
	});

	$("button.minustime").button({
		icons: {
			primary: "ui-icon-minus",
		},
		text: true,
		disabled: true
	}).click(function(){
		
	});

	$("button.cancel").button({
		icons: {
			primary: "ui-icon-eject",
		},
		text: true,
		disabled: true
	}).click(function(){
		
	});


});
	
	//http://jquery-ui.googlecode.com/svn/tags/1.6rc5/tests/static/icons.html
	
function vmInstanceCmd(command) {

	$.ajax({
		type: 'POST',
		url: 'fullcalendar/vmControls.php',
		dataType: 'text',
		async: false,
		data: {
			action: command,
			instanceId:  '',
			vmName: '',
			requestingUser:  $('#username').val()
		},
		success: function(data) {
			if(command == "getState"){
				//alert(data);
				markCurrentInstanceState(data);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			vmInstanceCmd('getState');
			var header = "Instance Command Error";
			var message = "Command could not be completed.";
			var icon = "alert";
			noticeDialog(header, message, icon);
			
		}

	});

}

/*
//getter
var disabled = $( ".selector" ).button( "option", "disabled" );
//setter
$( ".selector" ).button( "option", "disabled", true );
*/
function markCurrentInstanceState(state){

	if(state == "off"){
	
		$("button.powerOff").button("option", "disabled", true);
		$("button.powerOn").button("option", "disabled", false);
		$("button.pause").button("option", "disabled", true);
		$("button.shutdown").button("option", "disabled", true);
		$("button.restart").button("option", "disabled", true);
		$("button.refresh").button("option", "disabled", false);
	
	}else if(state == "suspended"){
	
		$("button.powerOff").button("option", "disabled", true);
		$("button.powerOn").button("option", "disabled", false);
		$("button.pause").button("option", "disabled", true);
		$("button.shutdown").button("option", "disabled", true);
		$("button.restart").button("option", "disabled", true);
		$("button.refresh").button("option", "disabled", false);
	
	}else if(state == "on"){
		
		$("button.powerOff").button("option", "disabled", false);
		$("button.powerOn").button("option", "disabled", true);
		$("button.pause").button("option", "disabled", false);
		$("button.shutdown").button("option", "disabled", false);
		$("button.restart").button("option", "disabled", false);
		$("button.refresh").button("option", "disabled", false);
		
	}else{
	
		$("button.powerOff").button("option", "disabled", true);
		$("button.powerOn").button("option", "disabled", true);
		$("button.pause").button("option", "disabled", true);
		$("button.shutdown").button("option", "disabled", true);
		$("button.restart").button("option", "disabled", true);
		$("button.refresh").button("option", "disabled", true);
	}
	

}

function noticeDialog(header, message, icon, returnObj){
	
	var noticeContent = $("<div id='calendar-notice' />").html('<p><span class="ui-icon ui-icon-'+icon+'" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>');
	
	$(noticeContent).dialog({
		modal: true,
		title: header,
		close: function() {
		   $(noticeContent).dialog("destroy");
		   $(noticeContent).hide();
		},
		buttons: {
			Ok: function() {
				$(this).dialog('close');
			}
		}
	});
	
	$(noticeContent).dialog('open');
	
	if(returnObj){
		return noticeContent;
	}
}


function confirmRefresh(){

	var refreshNoticeContent = $("<div id='new-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>WARNING! <br/>All your work on this virtual machine will be lost.<br/><br/> Do you want to continue?</p>');
	var success = false;
				
	$(refreshNoticeContent).dialog({
		autoOpen: false,
		resizable: false,
		width: 350,
		title: "Refresh Virtual Machine",
		modal: true,
		close: function() {
		   $(refreshNoticeContent).dialog("destroy");
		   $(refreshNoticeContent).hide();
		},
		buttons: {
			Ok: function() {
				$(this).dialog('close');
				vmInstanceCmd('refresh');
				vmInstanceCmd('getState');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});
	
	$(refreshNoticeContent).dialog('open');

}

</script>




<ul id="vmControls">
<li class="getState">Get State</li>
<li class="powerOff">Power Off</li>
<li class="shutdown">Shutdown</li>
<li class="pause">Suspend</li>
<li class="powerOn">Power On</li>
<li class="restart">Restart</li>
</ul>

<br/><br/>


<span id="timetools" class="ui-widget-header ui-corner-all">
	<label id="counter"></label>
    <button class="addtime">Add</button>
    <button class="minustime">Minus</button>
    <button class="cancel">Cancel</button>

</span>

<br/><br/>

<span id="toolbar" class="ui-widget-header ui-corner-all">

    <button class="powerOff">Power Off</button>
    <button class="powerOn">Power On</button>
    <button class="shutdown">Shutdown</button>
    <button class="restart">Reset</button>
    <button class="pause">Suspend</button>
    <button class="refresh">Refresh</button>

</span>

<?php

?>
