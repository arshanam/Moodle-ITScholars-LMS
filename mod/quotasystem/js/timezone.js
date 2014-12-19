function tz_init(){
	//Remove unused update button
	$("#page .navbar .navbutton").empty();
	
	//Use that place to put the timezone picker
	$.ajax({
		type: 'GET',
		url: '../quotasystem/server/timezoneManager.php',
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
				tz_getUserTimeZone();
				
				//Attach a change handler to the timezone
				$("#tz").bind("change",function(){
					var tz = $(this).val();
					tz_setUserTimeZone(tz);
				});
			}
		}
	});
}

function tz_getUserTimeZone(){
	$.ajax({
		type: 'GET',
		url: '../quotasystem/server/timezoneManager.php',
		dataType: 'json',
		data: {
			action: 'getUserTimeZone'
		},
		success: function(data){
			$("#tz").val(data);
		}
	});	
}

function tz_setUserTimeZone(tz){
	
	$.ajax({
		type: 'GET',
		url: '../quotasystem/server/timezoneManager.php',
		dataType: 'json',
		data: {
			action: 'setUserTimeZone',
			timezone: tz
		},
		success: function(data){
			if(data.role=="admin"){
				pol_init();
		        initCurrentReport("");
		        initHistoricReport("");
			}else{
				initCurrentReport(data.userid);
		        initHistoricReport(data.userid);
			}
		}
	});	
}	
