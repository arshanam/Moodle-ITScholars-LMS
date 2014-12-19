//filename: externalObjects.js

//------------------ External Panel Objects ------------------------------ 

//-------------------------initialize the external events---------------------------

	
function initialExternalPanel(){
	
	var panel = '<h3>Event Templates<br><small>(Drag &amp; Drop to month view)</small></h3>' +
				//'<div id="draggableEvents">' +
				'<div class="external-event" data-id="holiday" data-start="10a" data-end="12p">Lab</div>'+
				'<div class="external-event" data-id="meeting" data-start="1p" data-end="2p" data-title="Meeting">Meeting</div>' +
				'<div class="external-event" data-id="classes" data-start="10a" data-end="12p">Class</span></div>' +
				//'</div>' +
				'<p><input type="checkbox" id="drop-remove"> <label for="drop-remove">remove after drop</label></p>' +
  				'<button id="addExternalEventButton" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Create New</span></button><br/><br/>' +
				'<button id="addExternalCalButton" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text">Personal Calendar</span></button><br/><br/>';
  
	
	$(panel).appendTo("#sidePanel");
	
	
	$('#sidePanel div.external-event').each(function() {
		//alert("each");
		// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		// it doesn't need to have a start or end
		var eventObject = {
			title:			$.trim($(this).text()),
			className:      'gcal-event'
		};
		
		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);
		
		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});
		
	});
	
	
}

