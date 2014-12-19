function tz_init(){
	//Remove unused update button
	$("#page .navbar .navbutton").empty();
	
	//Use that place to put the timezone picker
	$.ajax({
		type: 'GET',
		url: 'fullcalendar/timezoneManager.php',
		dataType: 'json',
		data: {
			action: 'getTimeZones'
		},
		success: function(data){
			if(data){
				//Fill select with timezones 
				var tzSelect = '<select id="tz">';

				for (var i in data.timeZoneId){
					tzSelect += '<option val="'+data.timeZoneId[i]+'">'+data.timeZoneId[i]+'</option>';
				}

				tzSelect += '</select>';
				
				//Append select to page
				$("#page .navbar .navbutton").append(tzSelect);
				
				//Set the selected value to match the user's timezone
				//tz_getUserTimeZone();
				$("#tz").val($.trim(currentTimeZone));
				
				//Attach a change handler to the timezone
				$("#tz").bind("change",function(){
					//var tz = $(this).val();
					//tz_setUserTimeZone(tz);
					
					showProgressBar(true);
	
					setTimeout(function(){		// For Safari

						var timezone = $("#tz").val();
						if(SetUserDefaultTimeZone(timezone)){ 
							//alert("current_user_role:"+current_user_role+" currentUser:"+currentUser);
							//if(currentUser == $('#username').val()){
								//if(is_admin_user){
									//alert("#timezone-list");
									$("#ve_scheduler_form #timezone").val($.trim(timezone));
									$("#timezone-list").val($.trim(timezone));
									getConfiguration();
								//}
								
							//}
							loadAppointments(SchedStart, SchedEnd, false, currentUser);
						}else{
							showProgressBar(false);
							
							// timezone was not changed. Revert
							$("#tz").val($.trim(currentTimeZone));
							
							var header = "Set Default Time Zone";
							var message = "We were unable to set your new timezone.";
							var icon = "alert";
							noticeDialog(header, message, icon);
						}
					}, 2000);
					
				});
			}
		}
	});
}

function tz_getUserTimeZone(){
	$.ajax({
		type: 'GET',
		url: 'fullcalendar/timezoneManager.php',
		dataType: 'json',
		data: {
			action: 'getUserTimeZone',
			username: currentUser
		},
		success: function(data){
			$("#tz").val($.trim(data));
		}
	});	
}

function tz_setUserTimeZone(tz){
	
	$.ajax({
		type: 'GET',
		url: 'fullcalendar/timezoneManager.php',
		dataType: 'json',
		data: {
			action: 'setUserTimeZone',
			timezone: tz,
			username: currentUser
		},
		success: function(data){
			if(data){
				//reload calendar events	
				//alert('success');
			}
		}
	});	
}	
