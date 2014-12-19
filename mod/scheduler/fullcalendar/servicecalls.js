//filename: servicecalls.js

var isDebug = false;
var debugSTR = "";

function getRequestType(user){
	var requestType;
	
	if(is_admin_user){
		requestType = "Host";
	}else if(is_mentor_user){
		requestType = "Mentor";
	}else{
		requestType = "User";
	}
	
	return requestType;
}

function showDebug(){
	$("#debug1").html(debugSTR);	
}

function clearDebug(){
	debugSTR = "";
}

function debugging(debugMsg){

	if(isDebug){
		var clocked = new Date();
		debugSTR += clocked + " - " + debugMsg + " <br/>";
	}

}

function loadAppointments(start, end, init, username) {
	//alert('loadAppointments');
	debugging("loadAppointments: start");
	
	isloading_events = true;
	
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var requestType;
	var retry = 2;
	
	view = $('#calendar').fullCalendar('getView');
	//alert("IE Test: view: "+view.name);
	start = view.visStart;
	end = view.visEnd;
	
	//alert("start: "+start + " end: "+end);
	
	// Show loading message
	if(init){
		showProgressBar(true);
	}
	//$("#progressbar").progressbar( "value" , 100 );
	
	getHostsCourses();
	getResourcesAvailable(username);
	//types = ["MENTORING","CLASSES","VIRTUAL LAB"];
	
	requestType = getRequestType(currentUser);
	
	//debugging("loadAppointments: ajax called 'getAppointments'");
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		async: false,
		timeout: 4000,
		data: {
			action:'getAppointments',
			requestingUser:  $('#username').val(),
			username: username,
			start: start.format(dateformatter),
			end: end.format(dateformatter),
			requestType: requestType
		},

		success: function(appointments) {
			debugging("loadAppointments: success 'getAppointments'");
			//debugging("loadAppointments: parse appointments - start");
			
			// Setup - show progress bar during load
			eventLoader = 0;

			//alert('appointments: '+appointments.length + '  allEvents: '+allEvents.length);
			allEvents = [];		// resets the arrays, to prevent duplicates
			tags = [];	
			
			//var appointments = generateAppointments(data);
			
			if(appointments != null){
				//alert('appointments != null');
				//alert('debug: #4');
				
				if($.isArray(appointments)){
					for (var j=0; j<appointments.length; j++){
	
	/*
						if(eventLoader < appointments.length){
							eventLoader++;
							var value = (eventLoader/appointments.length) * 100;
							$("#progressbar").progressbar( "value" , value );
							$("#debug").html(value);
	
						}
		*/
		
						parseAppointment(appointments[j]);
		
		/*
						// Save EventActions in JSON Object -> Array
						var currId;
						var isRecurring = false;
						var isEditable = (appointments[j].availabilityStatus.toLowerCase() == "scheduled") ? true : false;
						//var appointmentId = parseInt($(this).find('appointmentId').text());
						var appointmentId = appointments[j].id;
						var affiliation = appointments[j].affiliationId;
					
						if(appointmentId){
							currId = appointmentId;
						}else{
							_id++;
							currId = _id;
						}
						//alert('debug: #5');
						// tracking the appointment affiliation
						
						if(affiliation){
							
							
							var found = false;
							
							for(var i = 0; i<tags.length; i++){
								if(tags[i].tagId == affiliation){
									var list = new Array(tags[i].tagList);
						
									//alert('recurring');
									isRecurring = true;
									tags[i].tagList = list.push(appointmentId);
									tags[i].recurring = isRecurring;
									found = true;
									//alert('out');
								}
							}
							
							if(!found){
								//alert('new');
								var list = [];
								tags.push({
									tagId: appointments[j].affiliationId,
									tagList: list.push(appointments[j].id),
									recurring: false
								});
							}
							//alert(tags.length);
						
						}
						
						//alert('debug: #6');
						// Setup the Actions property with name and params
						var eventActions = [];
						eventActions = getActionList(appointments[j].action);
						
						var className = appointments[j].resourceType.replace(/ /g, "-").toLowerCase()+"-"+appointments[j].course.replace(/ /g,"-").toLowerCase();
						className = className.replace(/\./g,"");
						
						//alert(eventActions.length);
						allEvents.push({
			
							id: currId,
							affiliation: affiliation,
							recurring: isRecurring,
							title: appointments[j].resourceType.toLowerCase(),
							resourceType: appointments[j].resourceType.toLowerCase(),
							start: appointments[j].start,
							editable: false,
							end: appointments[j].end,
							className:  "div"+ className +" "+ appointments[j].availabilityStatus.toLowerCase(),
							course: appointments[j].course,
							type: appointments[j].availabilityStatus.toLowerCase(),
							actions: eventActions
			
						});
						*/
					
					}
				}else{
				
					parseAppointment(appointments);
				
				}
				// change the id of the event to the affiliation id - to group them in the calendar
				//  -- will need to switch the id back when editing.
				for(var i = 0; i<tags.length; i++){
					
					if(tags[i].recurring){
						
						for (var j=0; j<allEvents.length; j++){
							if(tags[i].tagId == allEvents[j].affiliation){
								//alert(tags[i].tagId + " - "+ allEvents[j].affiliation);
								
								var newAffil = allEvents[j].affiliation;
								var newId = allEvents[j].id;
								allEvents[j].affiliation = newId;
								allEvents[j].id = newAffil;
								allEvents[j].recurring = true;
							}
						}
					}
				}
					
				if(init){	// for the initial events loading.
	
					//alert("Initial loading");
					//$('#calendar').fullCalendar('removeEventSource',filteredEvents);
					filteredEvents = filterEvents(allEvents, filters);  
					//alert('filteredEvents: '+filteredEvents.length);
					$('#calendar').fullCalendar('addEventSource', filteredEvents); 
					
				}else{
					//alert('not inital');
					$('#calendar').fullCalendar('removeEventSource',filteredEvents);
					renderedEvents = 0;
					filteredEvents = filterEvents(allEvents, filters);  
					//$('#calendar').fullCalendar('refetchEvents');
					$('#calendar').fullCalendar('addEventSource', filteredEvents); 
					
				}
				
				resetCheckboxFilters(filters);
				
		
				
			}else{
				
				$('#calendar').fullCalendar('removeEventSource',filteredEvents);
				renderedEvents = 0;
				//filteredEvents = [];
				//$('#calendar').fullCalendar('addEventSource', filteredEvents); 
			
				// if no appointments stop loading gif
				showProgressBar(false);	
				
			}
			
			//alert("Before: getCalendarCSS");
			//setAppointmentCSS(username);
			//alert("After: getCalendarCSS");
			
			if(filteredEvents.length == 0){
				showProgressBar(false);	
			}
			
			//Check if the user is on the calendar tab - for loading bar
			if(selectedTab > 0){
				showProgressBar(false);	
			}
			
			// check if filter options are selected - for loading bar
			$("#courses").each(function() {
				if(!this.checked){
					showProgressBar(false);	
				}
			});
			
			//debugging("loadAppointments: parse appointments - end");
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Error Loading Appointments";
			var message = "";
			var icon = "alert";
			
			//Removes Appointments from calendar on error
			$('#calendar').fullCalendar('removeEventSource',filteredEvents);
			renderedEvents = 0;
			
			//if(retry > 0){
			//	loadAppointments(start, end, init, username);	
			//}else{
				message = textStatus + " : " +errorThrown;
				message = "Your available appointments could not be loaded.<br/><br/> ** please try again later.";
				noticeDialog(header, message, icon);
				showProgressBar(false);
			//}
			
			
	
		}
	});
	
	//showProgressBar(false);
	debugging("loadAppointments: end");
	
	setAppointmentCSS(username);
	
	isloading_events = false;
}

function scheduleAppointment(event, view){
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	var requestType;
	
	var startDate;
	
	showProgressBar(true);
	//alert(event.start);
	if(event.start != ""){
		startDate = event.start.format(dateformatter);
	}else{
		startDate = "";
	}
	
	requestType = getRequestType(currentUser);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		async: false,
		timeout: 4000,
		data: {
			action: 'scheduleAppointments',
			id: '',
			requestingUser:  $('#username').val(),
			username: username,
			start: startDate,
			// Modified by SMS: 8/7/2011
			// To make sure that certification test takes two hours.
			end: (event.end == null) ? "" : event.end.format(dateformatter),
			// end: event.end.format(dateformatter),
			resourceType: event.resourceType,
			course: event.course,
			affiliationId: '',
			availabilityStatus: '',
			requestType: requestType
		},
		success: function(data){
			
			var message = "";
			
			if(data){
				var appointments = generateAppointments(data);
				//var emailappointments = [];
				var scheduled = 0;
				var unsheduled = 0;
				var scheduledMsg = "";
				var unscheduledMsg = "";
			
				for(a in appointments){
					
					if(appointments[a].id == ""){
						unsheduled++;
						unscheduledMsg = unsheduled + " appointment(s) could <b>NOT</b> be scheduled. <br/>"+event.availabilityStatus;	
					}else{
						scheduled++;
						scheduledMsg = scheduled + " appointment(s) was scheduled.";
						
						//emailappointments.push(appointments[a]);
					}
				}
				
				//message = appointments.length + " appointment(s) was scheduled.";			
				message = scheduledMsg + unscheduledMsg;
				
				if(scheduled>0){
					loadAppointments(view.visStart, view.visEnd, false, currentUser);
				}else{
					showProgressBar(false);	
				}
				
				success = true;
			
			}else{
				message = "We were unable to schedule you for this appointment.<br/><br/> ** please try again later.";
			}
			
			//alert("here");
			noticeDialog("Schedule Appointment", message, "alert");
			
			//if(scheduled)
			//	emailUserAppointmentInfo('confirm', event.type, event.course, emailappointments, null);
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Schedule Appointment";
			var message = "We were unable to schedule you for this appointment.<br/><br/> ** please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			showProgressBar(false);
		}
	});
	
	return success;

}

function confirmAppointment(event, view){
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	var requestType;
	
	showProgressBar(true);
	
	if(event.start != ""){
		startDate = event.start.format(dateformatter);
	}else{
		startDate = "";
	}
	
	requestType = getRequestType(currentUser);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		async: false,
		timeout: 4000,
		data: {
			action: 'scheduleAppointments',
			id: '',
			requestingUser:  $('#username').val(),
			username: username,
			start: startDate,
			end: event.end.format(dateformatter),
			resourceType: event.resourceType,
			course: event.course,
			affiliationId: '',
			availabilityStatus: '',
			requestType: requestType
		},
		success: function(appointment){
			
			var message = "";
			
			if(appointment){
				if(appointment.id != ""){
					//var appointments = generateAppointments(data);
					message = "The appointment for <b>" + appointment.course + " - "+ appointment.resourceType + "</b> was confirmed.";
				
					//var eventActions = getActionList(appointment.action);
					
					event.id = appointment.id;
					event.title = appointment.resourceType.toLowerCase();
					event.resourceType = appointment.resourceType.toLowerCase();
					event.start = appointment.start;
					event.editable = false;
					event.end = appointment.end;
					event.className =  "div"+appointment.resourceType.replace(/ /g, "-").toLowerCase()+"-"+appointment.course.replace(/ /g,"-").toLowerCase() +" "+ appointment.availabilityStatus.toLowerCase();
					event.course = appointment.course;
					event.type = appointment.availabilityStatus.toLowerCase();
					//event.actions = eventActions;
					event.type = "scheduled";
					event.affiliationId = appointment.affiliationId;
					event.availabilityStatus = appointment.availabilityStatus;
					event.actions = getActionList(appointment.action);
						
					//$('#calendar').fullCalendar('newEvent', event);
					
					//var message = "A new appointment was scheduled.";
					
					var divid = '#'+view.name+"-event-"+event.id;
					$(divid).removeClass('available');
					$(divid).addClass('scheduled');
					
					//alert(appointment.id);
					//$('#calendar').fullCalendar('updateEvent', event);
					loadAppointments(view.visStart, view.visEnd, false, currentUser);
				
					success = true;
				}else{
					message = " This appointment could <b>NOT</b> be confirmed. <br/>"+appointment.availabilityStatus;
					showProgressBar(false);
				}
			
			}else{
				message = "We were unable to confirm this appointment for you.<br/><br/> ** please try again later.";
				showProgressBar(false);
			}
			
			noticeDialog("Confirm Appointment", message, "alert");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Confirm Appointment";
			var message = "We were unable to confirm this appointment for you.<br/><br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			showProgressBar(false);
		}
	});
	
	return success;
}

function cancelAllAppointments(affiliationId, requestType){
	
	//alert("cancelAllAppointments");
	
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	var requestType;
	
	showProgressBar(true);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		async: false,
		timeout: 4000,
		data: {
			action: 'cancelAllAppointments',
			requestingUser:  $('#username').val(),
			username: username,
			affiliationId: affiliationId,
			requestType: requestType
		},
		success: function(data){
			
			var successText, reason, successInt;
			
			$(data).find('reason').each(function() {
				reason = $(this).text();					  
			});
			$(data).find('success').each(function() {
				successText = $(this).text();								  
			});
			
			successInt = parseInt(successText);
			if(successInt > 0){
				success = true;
				//$('#calendar').fullCalendar("removeEvents", event.id);
				loadAppointments(view.visStart, view.visEnd, false, currentUser);
				//$('#calendar').fullCalendar('refetchEvents');
			}else{
				success = false;
				showProgressBar(false);
			}
			
			noticeDialog("Delete Recurring Appointments", reason, "alert");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Delete Appointment";
			var message = "We were unable to remove this appointment.<br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);

			showProgressBar(false);
		}
	});
	
	return success;
}


// createNew: flag to create a new event
function cancelAppointment(event, createNew){
	
	//alert("cancelAppointment");
	
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	var requestType;
	
	if(!createNew){
		showProgressBar(true);
	}
	
	if(is_mentor_user && event.type == "scheduled"){
		requestType = "User";
	}else{
		requestType = getRequestType(currentUser);
	}
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		async: false,
		timeout: 4000,
		data: {
			action: 'cancelAppointment',
			requestingUser:  $('#username').val(),
			username: username,
			id: event.id,
			requestType: requestType
		},
		success: function(data){
			
			var successText, reason, successInt;
			
			$(data).find('reason').each(function() {
				reason = $(this).text();					  
			});
			$(data).find('success').each(function() {
				successText = $(this).text();								  
			});
			
			successInt = parseInt(successText);
			
			if(successInt > 0){
				success = true;
				//$('#calendar').fullCalendar("removeEvents", event.id);
				//loadAppointments(view.visStart, view.visEnd, false, username);
				//$('#calendar').fullCalendar('refetchEvents');
				
				if(createNew){
					//alert(printEvent(event));
					scheduleAppointment(event, view);
				}
			}else{
				success = false;
				if(!createNew){
					showProgressBar(false);	
				}
			}
			
			if(!createNew){
				loadAppointments(view.visStart, view.visEnd, false, username);
				noticeDialog("Delete Appointment", reason, "alert");
				//var newevent = event;
				//var emailappointments = [];
				//emailappointments.push(event);
				//emailUserAppointmentInfo('cancel', event.type, event.course, newevent, null);	
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Delete Appointment";
			var message = "We were unable to remove this appointment.<br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			if(!createNew){
				noticeDialog(header, message, icon);

				showProgressBar(false);
			}
		}
	});
	
	return success;

}

function modifyAppointment(event, newStart, newEnd, multiple, hasRevert, revertFunc){
	//alert("modifyUserAppointment");
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	var requestType;
	
	if(!multiple){
		showProgressBar(true);
	}
	
	requestType = getRequestType(currentUser);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		async: false,
		timeout: 4000,
		data: {
			action: 'modifyAppointment',
			requestingUser:  $('#username').val(),
			username: username,
			id: event.id,
			start: newStart.format(dateformatter),
			end: newEnd.format(dateformatter),
			requestType: requestType
		},
		success: function(data){
			
			var successText, reason, successInt;
			
			$(data).find('reason').each(function() {
				reason = $(this).text();					  
			});
			$(data).find('success').each(function() {
				successText = $(this).text();								  
			});
			
			successInt = parseInt(successText);
			//alert("success: "+success+" successInt: "+successInt);
			if(successInt > 0){
				success = true;
				//event.start = newStart.format(dateformatter);
				//event.end = newEnd.format(dateformatter);
				//$('#calendar').fullCalendar('updateEvent', event);
				if(!multiple){	// prevents multiple calls
					loadAppointments(view.visStart, view.visEnd, false, currentUser);
				}
		
			}else{
				success = false;
				if(!multiple){
					showProgressBar(false);
				}
				// Moves appointment back if a drag or resize
				if(hasRevert){
					revertFunc();
				}
			}
			
			if(!multiple){
				noticeDialog("Confirm Appointment", reason, "alert");
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Modify Appointment";
			var message = "We were unable to modify this appointment for you.<br/><br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			if(!multiple){
				noticeDialog(header, message, icon);
				showProgressBar(false);
			}
			
			if(hasRevert){
				revertFunc();
			}
		}
	});
	
	return success;

}


///// --------------------------------------------------------- End - Buffer Functions 

function emailUserAppointmentInfo(emailtype, resource, course, newappointments, oldappointments) {

	//alert ("emailUserAppointmentInfo");
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		async: false,
		data: {
			action:'emailUserAppointmentInfo',
			emailtype:  emailtype,
			resource: resource,
			course: course,
			appointments: JSON.stringify(newappointments),
			oldappointments: JSON.stringify(oldappointments),
			username: $('#username').val()
		},
		success: function(data) {
			if(data.success){
				var header = data.title;
				var message = data.message;
				var icon = "alert";
				noticeDialog(header, message, icon);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Error Sending Email";
			var message = "An error occurred trying to send you a confirmation email for your appointment request.";
			var icon = "alert";
			noticeDialog(header, message, icon);

		}

	});

}
///// --------------------------------------------------------- End - EMAIL USER
function getCourses(username) {

	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		async: false,
		data: {
			action:'getCourses',
			requestingUser:  $('#username').val(),
			username: username
		},
		success: function(data) {
	
			//$("#coursesList").val(courses.split(",",coursesLimit));
			
			courses = [];
			// hardcoded mentor 'all courses'
			
			if(is_mentor_user){
				courses.push("All Courses");	
			}
			
			$(data).find('courses').each(function() {
			
				var nodes  = this.getElementsByTagName('node');
				
				if(nodes.length!=0)
				{
					for(var n =0; n<nodes.length; n++)
					{
						courses.push(nodes[n].childNodes[0].nodeValue);
					}
					
				}
				/*else
				{
					courses.push($(this).text());
				}
				*/
				
		   });
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Error Loading Courses";
			var message = "";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);

			showProgressBar(false);
		}

	});

}

function getHosts(username) {
	
	//debugging("getHosts: start");
	
	//debugging("getHosts: ajax called 'getHostList'");

	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		async: false,
		data: {
			action: 'getHostList',
			requestingUser:  $('#username').val(),
			username: username
		},
		success: function(data) {
		
			//debugging("getHosts: success 'getHostList'");
			//debugging("getHosts: parse hosts - start");
			
			courses = [];
			
			if(data){		
				if(data.length > 0){
					
					for(h in data){
						courses.push(data[h].name);
					}	
				}else{
					courses.push(data.name);
				}
			}
			
			//debugging("getHosts: parse hosts - end");
				
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Error Loading Hosts";
			var message = "";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);

			showProgressBar(false);
		}

	});
	//debugging("getHosts: end");

}

function scheduleRecurringAppointment(events){
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	requestType = getRequestType(currentUser);
	
	
	showProgressBar(true);
	
	// build recurring appointment array
	var recurrApps = [];
	
	for(var i=0; i<events.length;i++){
			
		recurrApps.push({
			
			id: '',
			userName: username,
			start: events[i].start.format(dateformatter),
			end: events[i].end.format(dateformatter),
			resourceType: events[i].resourceType,
			course: events[i].course,
			affiliationId: '',
			availabilityStatus: ''
			
		});
	
	}
	//alert('scheduleRecurringAppointments');
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		async: false,
		timeout: 4000,
		data: {
			action: 'scheduleRecurringAppointments',
			username: username,
			requestingUser:  $('#username').val(),
			appointments: recurrApps,
			requestType: requestType
		},
		success: function(data){
				
			var emailappointments = [];
			var type, course;
			var appointments = generateAppointments(data);
			var countedAppointments = 0;
			
			for(var i=0; i<appointments.length;i++){
				//alert("id: "+appointments[i].id);
				if(appointments[i].id != ""){
					countedAppointments++;
					emailappointments.push(appointments[i]);
					course = appointments[i].course;
					type = appointments[i].type;
				}
			}
			
			
			var message = countedAppointments + " appointment(s) was scheduled.";
			
			loadAppointments(view.visStart, view.visEnd, false, currentUser);
			
			success = true;
			
			//alert("here");
			noticeDialog("Schedule Appointment", message, "alert");
			
	//		if(countedAppointments)
		//		emailUserAppointmentInfo('confirm', type, course, emailappointments, null);
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Schedule Appointment";
			var message = "We were unable to schedule you for this appointment.<br/><br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			showProgressBar(false);
		}
	});
	
	return success;

}


function modifyRecurringAppointment(event, start, startDateShift, end, endDateShift, delta, all){
	
	var successInt = 0;

	
	if(all){
	
		showProgressBar(true);
	
		for(var i = 0; i<tags.length; i++){
			
			if(tags[i].recurring && tags[i].tagId == event.id){
				
				//alert("delta: "+delta);
				if(delta < 0){
					for (var j=0; j<allEvents.length; j++){
						if(tags[i].tagId == allEvents[j].id){
							
							var newAffil = allEvents[j].id;
							var newId = allEvents[j].affiliation;
							
							allEvents[j].affiliation = newAffil;
							allEvents[j].id = newId;
							//allEvents[j].recurring = false;
							
							// get new date from base event date
							var newStart = allEvents[j].start;
							var newEnd = allEvents[j].end;
							
							newStart.setDate(newStart.getDate() + startDateShift);
							newStart.setHours(start.getHours());
							newStart.setMinutes(start.getMinutes());
							newStart.setSeconds(0);
							
							newEnd.setDate(newEnd.getDate() + endDateShift);
							newEnd.setHours(end.getHours());
							newEnd.setMinutes(end.getMinutes());
							newEnd.setSeconds(0);
							
							//alert("newStart: "+newStart + "\n newEnd: "+newEnd);
							
							if(modifyAppointment(allEvents[j], newStart, newEnd, all, false)){
								successInt++;		
							}
						}
					}
				}else{
					
					var len = allEvents.length - 1;
					for (var j=len; j>=0; j--){
					//for (var j=0; j<allEvents.length; j++){
						
						if(tags[i].tagId == allEvents[j].id){
							
							var newAffil = allEvents[j].id;
							var newId = allEvents[j].affiliation;
							
							allEvents[j].affiliation = newAffil;
							allEvents[j].id = newId;
							//allEvents[j].recurring = false;
							
							// get new date from base event date
							var newStart = allEvents[j].start;
							var newEnd = allEvents[j].end;
							
							newStart.setDate(newStart.getDate() + startDateShift);
							newStart.setHours(start.getHours());
							newStart.setMinutes(start.getMinutes());
							newStart.setSeconds(0);
							
							newEnd.setDate(newEnd.getDate() + endDateShift);
							newEnd.setHours(end.getHours());
							newEnd.setMinutes(end.getMinutes());
							newEnd.setSeconds(0);
							
							//alert("newStart: "+newStart + "\n newEnd: "+newEnd);
							
							if(modifyAppointment(allEvents[j], newStart, newEnd, all, false)){
								successInt++;		
							}
						}
					}	
				}
			}
		}
		
		
		//alert("complete");
		if(successInt > 0){
			loadAppointments(view.visStart, view.visEnd, false, currentUser);	
		}else{
			showProgressBar(false);	
		}
		//alert("show message");
		var message =  successInt + " appointment(s) have been modified.";
		noticeDialog("Modify Recurring Appointments", message, "alert");

	}else{
		
		// Changes the recurring event to a regular event, 
		// by deleting event and requesting a new one to obtain a new affiliation id.
		
		var newAffil = event.affiliation;
		var newId = event.id;
		event.affiliation = newId;
		event.id = newAffil;
		event.recurring = false;
		
		event.start = start;
		event.end = end;
		cancelAppointment(event,true);
		
	}
}

function changeRecurringEventsOld(affiliationId, dayDelta, minuteDelta, revertFunc){

	var multiple = true;
	var successInt = 0;
	var debug = "";
	var delta = dayDelta + minuteDelta;
	
	//alert("dayDelta: "+dayDelta+"minuteDelta: "+minuteDelta);
	
	showProgressBar(true);

	for(var i = 0; i<tags.length; i++){
		//alert('tagId: '+tags[i].tagId + 'affil: '+affiliationId);
		if(tags[i].recurring && tags[i].tagId == affiliationId){
			//alert("delta: "+delta);
			if(delta < 0){
				
				for (var j=0; j<allEvents.length; j++){
					if(tags[i].tagId == allEvents[j].id){
						
						var newAffil = allEvents[j].id;
						var newId = allEvents[j].affiliation;
						
						allEvents[j].affiliation = newAffil;
						allEvents[j].id = newId;
						//allEvents[j].recurring = false;
						
						// get new date from base event date
						var newStart = allEvents[j].start;
						var newEnd = allEvents[j].end;
						
						debug += "start: "+ newStart + "\n end: "+ newEnd+"\n\n";
						if(modifyAppointment(allEvents[j], newStart, newEnd, multiple, false)){
							successInt++;
						}
					}
				}
			}else if(delta > 0){
				
				var len = allEvents.length - 1;
				for (var j=len; j>=0; j--){
					if(tags[i].tagId == allEvents[j].id){
						
						var newAffil = allEvents[j].id;
						var newId = allEvents[j].affiliation;
						
						allEvents[j].affiliation = newAffil;
						allEvents[j].id = newId;
						//allEvents[j].recurring = false;
						
						// get new date from base event date
						var newStart = allEvents[j].start;
						var newEnd = allEvents[j].end;
						
						debug += "start: "+ newStart + "\n end: "+ newEnd+"\n\n";
						
						if(modifyAppointment(allEvents[j], newStart, newEnd, multiple, false)){
							successInt++;
						}
					}
				}
			}
		}
	}
	//alert(debug);
	var message =  successInt + " appointment(s) have been modified.";
	noticeDialog("Modify Recurring Appointments", message, "alert");
	
	if(successInt > 0){
		loadAppointments(view.visStart, view.visEnd, false, currentUser);	
	}else{
		revertFunc();
		showProgressBar(false);	
	}
	
}


function changeRecurringEvents(affiliationId, dayDelta, minuteDelta, revertFunc){

	var multiple = true;
	var successInt = 0;
	var debug = "";
	var delta = dayDelta + minuteDelta;
	var isReverse = false;
	var newAppointments = [];
	
	//alert("dayDelta: "+dayDelta+"minuteDelta: "+minuteDelta);
	
	showProgressBar(true);

	if(delta < 0){
		isReverse = false;
	}else if(delta > 0){
		isReverse = true;
	}

	for(var i = 0; i<tags.length; i++){
		//alert('tagId: '+tags[i].tagId + 'affil: '+affiliationId);
		if(tags[i].recurring && tags[i].tagId == affiliationId){
	
				
			for (var j=0; j<allEvents.length; j++){
				if(tags[i].tagId == allEvents[j].id){
					
					var newAffil = allEvents[j].id;
					var newId = allEvents[j].affiliation;
					
					allEvents[j].affiliation = newAffil;
					allEvents[j].id = newId;
					
					newAppointments.push(allEvents[j]);
					
				}
			}
			
		}
	}
	
	newAppointments = orderRecuringAppointment(newAppointments,isReverse); // orderd the appointments forward|reverse
	
	for (var j=0; j<newAppointments.length; j++){
		
		var newStart = newAppointments[j].start;
		var newEnd = newAppointments[j].end;
		
		//debug += "start: "+ newStart + "\n end: "+ newEnd+"\n\n";
		if(modifyAppointment(newAppointments[j], newStart, newEnd, multiple, false)){
			successInt++;
		}
	}
	
	
	//alert(debug);
	var message =  successInt + " appointment(s) have been modified.";
	noticeDialog("Modify Recurring Appointments", message, "alert");
	
	if(successInt > 0){
		loadAppointments(view.visStart, view.visEnd, false, currentUser);	
	}else{
		revertFunc();
		showProgressBar(false);	
	}
	
}


//------------------------------------------------ End - main servicecalls

//------------------ Utilities ------------------------------

function parseAppointment(appointment){

	var currId;
	var isRecurring = false;
	var isEditable = (appointment.availabilityStatus.toLowerCase() == "scheduled") ? true : false;
	//var appointmentId = parseInt($(this).find('appointmentId').text());
	var appointmentId = appointment.id;
	var affiliation = appointment.affiliationId;

	if(appointmentId){
		currId = appointmentId;
	}else{
		_id++;
		currId = _id;
	}
	//alert('debug: #5');
	// tracking the appointment affiliation
	
	if(affiliation){
		
		var found = false;
		
		for(var i = 0; i<tags.length; i++){
			if(tags[i].tagId == affiliation){
				var list = new Array(tags[i].tagList);
	
				//alert('recurring');
				isRecurring = true;
				tags[i].tagList = list.push(appointmentId);
				tags[i].recurring = isRecurring;
				found = true;
				//alert('out');
			}
		}
		
		if(!found){
			//alert('new');
			var list = [];
			tags.push({
				tagId: appointment.affiliationId,
				tagList: list.push(appointment.id),
				recurring: false
			});
		}
		//alert(tags.length);
	
	}
	
	//alert('debug: #6');
	// Setup the Actions property with name and params
	var eventActions = [];
	eventActions = getActionList(appointment.action);
	
	var className = appointment.resourceType.replace(/ /g, "-").toLowerCase()+"-"+appointment.course.replace(/ /g,"-").toLowerCase();
	className = className.replace(/\./g,"");
	
	// Adjust time to be on the same day - for Available appointments
	if(appointment.availabilityStatus.toLowerCase() == "available"){
		var newEnd = $.fullCalendar.parseISO8601(appointment.end);
		newEnd.setSeconds(newEnd.getSeconds() - 1);
		appointment.end = newEnd;
	}
	
	var title = "";
	switch (appointment.resourceType.toLowerCase()) {
		case "virtual lab": title = "vlab"; break;
		case "certificate": title = "cert"; break;
		case "mentoring"  : title = "ment"; break;
	}
	
	//alert(eventActions.length);
	allEvents.push({

		id: currId,
		affiliation: affiliation,
		recurring: isRecurring,
		title: title,
		resourceType: appointment.resourceType.toLowerCase(),
		start: appointment.start,
		editable: false,
		end: appointment.end, 
		className:  "div"+ className +" "+ appointment.availabilityStatus.toLowerCase(),
		course: appointment.course,
		type: appointment.availabilityStatus.toLowerCase(),
		actions: eventActions
		// Added by SMS: 8/8/2011
		,
		username: (see_calendar_username) ? appointment.userName : ""
		//
	});

}

function orderRecuringAppointment(list,isReverse){
	
	var ordered = null;
	var sort_by = function(field, reverse){
	
	   reverse = (reverse) ? -1 : 1;
	
	   return function(a,b){
	
		   a = a[field];
		   b = b[field];
	
		   if (a<b) return reverse * -1;
		   if (a>b) return reverse * 1;
		   return 0;
	
	   }
	}
	
	if($.isArray(list)){
		ordered = list.sort(sort_by('start', isReverse));
	}
	
	return ordered;
}

//------------------------------------------------ End - Utilities