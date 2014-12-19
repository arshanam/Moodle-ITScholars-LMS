function loadUserAppointments(start, end, init, username) {
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	
	// Show loading message
	if(init){
		$('#calendar').fullCalendar('loading', view, true);
	}
	//$("#progressbar").progressbar( "value" , 100 );
	
	getCourses();
	getResourcesAvailable(username);
	
	
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
			requestType: 'User'
		},

		success: function(appointments) {
			// Setup - show progress bar during load
			eventLoader = 0;

			
		
			
			//alert('appointments: '+appointments.length + '  allEvents: '+allEvents.length);
			allEvents = [];
			
			if(appointments != null){
				//alert('appointments != null');
				//alert('debug: #4');
				for (var j=0; j<appointments.length; j++){

/*
					if(eventLoader < appointments.length){
						eventLoader++;
						var value = (eventLoader/appointments.length) * 100;
						$("#progressbar").progressbar( "value" , value );
						$("#debug").html(value);

					}
	*/
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
					filteredEvents = filterEvents(allEvents, filters);  
					//$('#calendar').fullCalendar('refetchEvents');
					$('#calendar').fullCalendar('addEventSource', filteredEvents); 
			
				}
				
				resetCheckboxFilters(filters);
				
			}else{
				
				$('#calendar').fullCalendar('removeEventSource',filteredEvents);
				//filteredEvents = [];
				//$('#calendar').fullCalendar('addEventSource', filteredEvents); 
			
				// if no appointments stop loading gif
				$('#calendar').fullCalendar('loading', view, false);	
				
			}
			
			if(filteredEvents.length == 0){
				$('#calendar').fullCalendar('loading', view, false);	
			}
			
			//Check if the user is on the calendar tab - for loading bar
			if(selectedTab > 0){
				$('#calendar').fullCalendar('loading', view, false);	
			}
			
			// check if filter options are selected - for loading bar
			$("#courses").each(function() {
				if(!this.checked){
					$('#calendar').fullCalendar('loading', view, false);	
				}
			});
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Error Loading Appointments";
			var message = "";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			$('#calendar').fullCalendar('loading', view, false);
		}
	});
	
	//$('#calendar').fullCalendar('loading', view, false);
}

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

			$('#calendar').fullCalendar('loading', view, false);
		}

	});

}

function scheduleUserAppointment(event, view){
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	$('#calendar').fullCalendar('loading', view, true);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		data: {
			action: 'scheduleAppointments',
			id: '',
			requestingUser:  $('#username').val(),
			username: username,
			start: event.start.format(dateformatter),
			end: event.end.format(dateformatter),
			resourceType: event.resourceType,
			course: event.course,
			affiliationId: '',
			availabilityStatus: '',
			requestType: 'User'
		},
		success: function(data){
			
			var message = "";
			
			if(data){
				var appointments = generateAppointments(data);
				var scheduled = 0;
				var unsheduled = 0;
				var scheduledMsg = "";
				var unscheduledMsg = "";
			
				for(a in appointments){
					
					if(appointments[a].id == ""){
						unsheduled++;
						unscheduledMsg = unsheduled + " appointment(s) could <b>NOT</b> be scheduled.";	
					}else{
						scheduled++;
						scheduledMsg = scheduled + " appointment(s) was scheduled.";
					}
				}
				
				//message = appointments.length + " appointment(s) was scheduled.";			
				message = scheduledMsg + unscheduledMsg;
				
				if(scheduled>0){
					loadAppointments(view.visStart, view.visEnd, false, currentUser);
				}else{
					$('#calendar').fullCalendar('loading', view, false);	
				}
				
				success = true;
			
			}else{
				message = "We were unable to schedule you for this appointment.<br/><br/> ** please try again later.";
			}
			
			//alert("here");
			noticeDialog("Schedule Appointment", message, "alert");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Schedule Appointment";
			var message = "We were unable to schedule you for this appointment.<br/><br/> ** please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			$('#calendar').fullCalendar('loading', view, false);
		}
	});
	
	return success;

}

function confirmUserAppointment(event, view){
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	$('#calendar').fullCalendar('loading', view, true);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'json',
		data: {
			action: 'scheduleAppointments',
			id: '',
			requestingUser:  $('#username').val(),
			username: username,
			start: event.start.format(dateformatter),
			end: event.end.format(dateformatter),
			resourceType: event.resourceType,
			course: event.course,
			affiliationId: '',
			availabilityStatus: '',
			requestType: 'User'
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
					message = " This appointment could not be confirmed.";
					$('#calendar').fullCalendar('loading', view, false);
				}
			
			}else{
				message = "We were unable to confirm this appointment for you.<br/><br/> ** please try again later.";
				$('#calendar').fullCalendar('loading', view, false);
			}
			
			noticeDialog("Confirm Appointment", message, "alert");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Confirm Appointment";
			var message = "We were unable to confirm this appointment for you.<br/><br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			$('#calendar').fullCalendar('loading', view, false);
		}
	});
	
	return success;
}

function cancelAllUserAppointments(affiliationId){
	
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	$('#calendar').fullCalendar('loading', view, true);
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		data: {
			action: 'cancelAllAppointments',
			requestingUser:  $('#username').val(),
			username: username,
			affiliationId: affiliationId,
			requestType: 'User'
		},
		success: function(data){
			
			var success, reason, successInt;
			
			$(data).find('reason').each(function() {
				reason = $(this).text();					  
			});
			$(data).find('success').each(function() {
				success = $(this).text();								  
			});
			
			successInt = parseInt(success);
			if(successInt){
				success = true;
				//$('#calendar').fullCalendar("removeEvents", event.id);
				loadAppointments(view.visStart, view.visEnd, false, currentUser);
				//$('#calendar').fullCalendar('refetchEvents');
			}else{
				success = false;
				$('#calendar').fullCalendar('loading', view, false);
			}
			
			noticeDialog("Delete Recurring Appointments", reason, "alert");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Delete Appointment";
			var message = "We were unable to remove this appointment.<br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);

			$('#calendar').fullCalendar('loading', view, false);
		}
	});
	
	return success;
}


// createNew: flag to create a new event
function cancelUserAppointment(event, createNew){
	
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	if(!createNew){
		$('#calendar').fullCalendar('loading', view, true);
	}
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		data: {
			action: 'cancelAppointment',
			requestingUser:  $('#username').val(),
			username: username,
			id: event.id,
			requestType: 'User'
		},
		success: function(data){
			
			var success, reason, successInt;
			
			$(data).find('reason').each(function() {
				reason = $(this).text();					  
			});
			$(data).find('success').each(function() {
				success = $(this).text();								  
			});
			
			successInt = parseInt(success);
			
			if(successInt){
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
					$('#calendar').fullCalendar('loading', view, false);	
				}
			}
			
			if(!createNew){
				loadAppointments(view.visStart, view.visEnd, false, username);
				noticeDialog("Delete Appointment", reason, "alert");
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Delete Appointment";
			var message = "We were unable to remove this appointment.<br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			if(!createNew){
				noticeDialog(header, message, icon);

				$('#calendar').fullCalendar('loading', view, false);
			}
		}
	});
	
	return success;

}

function modifyUserAppointment(event, newStart, newEnd, multiple){
	//alert("modifyUserAppointment");
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	if(!multiple){
		$('#calendar').fullCalendar('loading', view, true);
	}
	
	$.ajax({
		type: 'POST',
		url: 'fullcalendar/calendar.php',
		dataType: 'xml',
		data: {
			action: 'modifyAppointment',
			requestingUser:  $('#username').val(),
			username: username,
			id: event.id,
			start: newStart.format(dateformatter),
			end: newEnd.format(dateformatter),
			requestType: 'User'
		},
		success: function(data){
			
			var success, reason, successInt;
			
			$(data).find('reason').each(function() {
				reason = $(this).text();					  
			});
			$(data).find('success').each(function() {
				success = $(this).text();								  
			});
			
			successInt = parseInt(success);
			if(successInt){
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
					$('#calendar').fullCalendar('loading', view, false);
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
				$('#calendar').fullCalendar('loading', view, false);
			}
		}
	});
	
	return success;

}


///// --------------------------------------------------------- No Buffer Functions (down)

function scheduleRecurringAppointment(events){
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	var username = currentUser;
	var success = false;
	
	if(isValidAminUser(currentUser)){
		requestType = "Host";
	}else{
		requestType = "User";
	}
	
	
	$('#calendar').fullCalendar('loading', view, true);
	
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
		data: {
			action: 'scheduleRecurringAppointments',
			username: username,
			requestingUser:  $('#username').val(),
			appointments: recurrApps,
			requestType: requestType
		},
		success: function(data){
				
			
			var appointments = generateAppointments(data);
			var message = appointments.length + " appointment(s) was scheduled.";
			
			loadAppointments(view.visStart, view.visEnd, false, currentUser);
			
			success = true;
			
			//alert("here");
			noticeDialog("Schedule Appointment", message, "alert");
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Schedule Appointment";
			var message = "We were unable to schedule you for this appointment.<br/><br/> **please try again later.";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);
			
			$('#calendar').fullCalendar('loading', view, false);
		}
	});
	
	return success;

}


function modifyRecurringAppointment(event, start, end, all){
	
	var successInt = 0;
	
	
	if(all){
	
		$('#calendar').fullCalendar('loading', view, true);
	
		for(var i = 0; i<tags.length; i++){
			
			if(tags[i].recurring && tags[i].tagId == event.id){

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
						
						newStart.setHours(start.getHours());
						newStart.setMinutes(start.getMinutes());
						newStart.setSeconds(0);
						
						newEnd.setHours(end.getHours());
						newEnd.setMinutes(end.getMinutes());
						newEnd.setSeconds(0);
						
						successInt++;
						modifyAppointment(allEvents[j], newStart, newEnd, all);
					}
				}
			}
		}
		
		var message =  successInt + " appointment(s) have been modified.";
		noticeDialog("Modify Recurring Appointments", message, "alert");
		
		if(successInt){
			loadAppointments(view.visStart, view.visEnd, false, currentUser);	
		}else{
			$('#calendar').fullCalendar('loading', view, false);	
		}

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

function changeRecurringEvents(affiliationId, dayDelta, minuteDelta, revertFunc){

	var multiple = true;
	var successInt = 0;
	//var debug = "";
	
	$('#calendar').fullCalendar('loading', view, true);

	for(var i = 0; i<tags.length; i++){
		//alert('tagId: '+tags[i].tagId + 'affil: '+affiliationId);
		if(tags[i].recurring && tags[i].tagId == affiliationId){

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
					
					//debug += "start: "+ newStart + "\n end: "+ newEnd+"\n\n";
					successInt++;
					modifyAppointment(allEvents[j], newStart, newEnd, multiple);
				}
			}
		}
	}
	//alert(debug);
	var message =  successInt + " appointment(s) have been modified.";
	noticeDialog("Modify Recurring Appointments", message, "alert");
	
	if(successInt){
		loadAppointments(view.visStart, view.visEnd, false, currentUser);	
	}else{
		$('#calendar').fullCalendar('loading', view, false);	
	}
	
}
