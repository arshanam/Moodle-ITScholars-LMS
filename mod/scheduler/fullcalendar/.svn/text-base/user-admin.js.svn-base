function loadHostAppointments(start, end, init, username) {
	var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";	
	
	// Show loading message
	if(init){
		$('#calendar').fullCalendar('loading', view, true);
	}
	
	getHosts();
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
			requestType: 'Host'
		},

		success: function(appointments) {
			// Setup - show progress bar during load
			eventLoader = 0;

			allEvents = [];
			
			if(appointments != null){
				
				for (var j=0; j<appointments.length; j++){

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
					// tracking the appointment affiliation
					
					if(affiliation){
						
						var found = false;
						
						for(var i = 0; i<tags.length; i++){
							if(tags[i].tagId == affiliation){
								var list = new Array(tags[i].tagList);
								isRecurring = true;
								tags[i].tagList = list.push(appointmentId);
								tags[i].recurring = isRecurring;
								found = true;
							}
						}
						
						if(!found){
							var list = [];
							tags.push({
								tagId: appointments[j].affiliationId,
								tagList: list.push(appointments[j].id),
								recurring: false
							});
						}
					}
					
					// Setup the Actions property with name and params
					var eventActions = [];
					eventActions = getActionList(appointments[j].action);
					
					var className = appointments[j].resourceType.replace(/ /g, "-").toLowerCase()+"-"+appointments[j].course.replace(/ /g,"-").toLowerCase();
					className = className.replace(/\./g,"");
					
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
	
					filteredEvents = filterEvents(allEvents, filters);  
					$('#calendar').fullCalendar('addEventSource', filteredEvents); 
					//alert('inital');
					
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
	
	
}

function getHosts(username) {

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
		
			courses = [];
			
			if(data){
				
				for(h in data){
					courses.push(data[h].name);
				}	
			}
			
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var header = "Error Loading Hosts";
			var message = "";
			var icon = "alert";
			message = textStatus + " : " +errorThrown;
			noticeDialog(header, message, icon);

			$('#calendar').fullCalendar('loading', view, false);
		}

	});

}


function scheduleHostAppointment(event, view){
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
			requestType: 'Host'
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
				$('#calendar').fullCalendar('loading', view, false);
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

function confirmHostAppointment(event, view){
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
			requestType: 'Host'
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

function cancelAllHostAppointments(affiliationId){
	
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
			requestType: 'Host'
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
function cancelHostAppointment(event, createNew){
	
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
			requestType: 'Host'
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

function modifyHostAppointment(event, newStart, newEnd, multiple){
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
			requestType: 'Host'
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

