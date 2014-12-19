/*------------------ Recurring Events ------------------------------

recur_range : start(string), type(string), occurences(int), endby(string)

*/

var dateformatter = "yyyy-mm-dd'T'HH:MM:ss";

//--------------------------- Daily --------------------------------
// recur_pattern : type(string), occurences(int) - every_day
// recur_pattern : type(string) - every_weekday
function dailyRecurrEvents(recur_startTime, recur_endTime, recur_course, recur_type, recur_pattern, recur_range){
	
	var recur_events = [];
	var recur_date = [];
	var recur_dateEndDate;
	var recur_occurs;
	
	recur_occurs = parseInt(recur_pattern.occurences);	// PATTERN occurrences
	
	var increment = (recur_pattern.type == "every_day") ? recur_occurs : 7;

	// Not worrking for overnight appointments
	var startDate = new Date(recur_range.start + " " + recur_startTime);
	var endDate = new Date(recur_range.start + " " + recur_endTime);
	
	// When the starttime is after the end time, it is necessary to make sure the endtime is afterwards
	if(startDate > endDate){
		endDate.setDate(endDate.getDate()+1);
	}

	if(recur_range.type == "end_after"){
		recur_occurs = parseInt(recur_range.occurences);	// RANGE occurrences
		
		for(var i = 0; i<recur_occurs; i++){
					
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			var cnt = increment * i;
			
			newSDate.setDate(newSDate.getDate() + cnt);
			newEDate.setDate(newEDate.getDate() + cnt);
			
			recur_date.push({
				start: newSDate,
				end: newEDate
			});
		}
		
	}else if(recur_range.type == "end_by"){
		recur_dateEndDate = new Date(recur_range.endby + " " + recur_endTime);
		
		var searching = true;
		
		var i = 0;
		
		while(searching){
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			var cnt = increment * i;
			
			newSDate.setDate(newSDate.getDate() + cnt);
			newEDate.setDate(newEDate.getDate() + cnt);
			
			if(newSDate < recur_dateEndDate){
				
				recur_date.push({
					start: newSDate,
					end: newEDate
				});
			}else{
				searching = false;	
			}
			i++;
		}
	}
	
	for(var i=0; i<recur_date.length; i++){
		
		recur_events.push({
	
			title: recur_type,
			resourceType: recur_type,
			start: recur_date[i].start,
			editable: false,
			end: recur_date[i].end,
			className:  "div"+recur_type.toString().replace(" ", "-").toLowerCase()+" scheduled",
			course: recur_course,
			type: "scheduled",
			actions: null
	
		});
	}
	requestRecurringAppointments(recur_events);
	
}

//--------------------------- Weekly -------------------------------
// recur_pattern: weeks(int), weekdays(array int) 
function weeklyRecurrEvents(recur_startTime, recur_endTime, recur_course, recur_type, recur_pattern, recur_range){

	var recur_events = [];
	var recur_date = [];
	var recur_dateEndDate;
	var recur_occurs;
	
	var increment = parseInt(recur_pattern.weeks) * 7;

	// Not working for overnight appointments
	var startDate = new Date(recur_range.start + " " + recur_startTime);
	var endDate = new Date(recur_range.start + " " + recur_endTime);
	
	// When the starttime is after the end time, it is necessary to make sure the endtime is afterwards
	if(startDate > endDate){
		endDate.setDate(endDate.getDate()+1);
	}
	
	if(recur_range.type == "end_after"){
		recur_occurs = parseInt(recur_range.occurences);	// RANGE occurrences
		
		for(var i=0; i<recur_occurs; i++){
		
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			//var cnt = increment * i;
			var cnt = increment * i;
			var startDay = newSDate.getDay();
			
			newSDate.setDate(newSDate.getDate() + cnt);
			newEDate.setDate(newEDate.getDate() + cnt);
			
			for (var d = newSDate.getDay(); d < 7; d++){
				var weekdays = recur_pattern.weekdays;
				for(w in weekdays){
					if(weekdays[w] == d){
						//alert(newSDate);
						recur_date.push({
							start: new Date(newSDate.toString()),
							end: new Date(newEDate.toString())
						});
					}
				}
				newSDate.setDate(newSDate.getDate() + 1);
				newEDate.setDate(newEDate.getDate() + 1);
			}
			
			if(startDay > 0){
				//alert(startDay);
				for (var d = 0; d < startDay; d++){
					var weekdays = recur_pattern.weekdays;
					for(w in weekdays){
						if(weekdays[w] == d){
							recur_date.push({
								start: new Date(newSDate.toString()),
								end: new Date(newEDate.toString())
							});
						}
					}
					newSDate.setDate(newSDate.getDate() + 1);
					newEDate.setDate(newEDate.getDate() + 1);
				}
			}
		
		
		}
			
		
		
	}else if(recur_range.type == "end_by"){
		recur_dateEndDate = new Date(recur_range.endby + " " + recur_endTime);
		
		var searching = true;
		
		var i = 0;
		
		while(searching){
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			var cnt = increment * i;
			var startDay = newSDate.getDay();
			
			newSDate.setDate(newSDate.getDate() + cnt);
			newEDate.setDate(newEDate.getDate() + cnt);
			
			
					
			for (var d = newSDate.getDay(); d < 7; d++){
				var weekdays = recur_pattern.weekdays;
				for(w in weekdays){
					if(weekdays[w] == d){
						if(newSDate < recur_dateEndDate){
							recur_date.push({
								start: new Date(newSDate.toString()),
								end: new Date(newEDate.toString())
							});
						}else{
							searching = false;	
						}
					}
				}
				newSDate.setDate(newSDate.getDate() + 1);
				newEDate.setDate(newEDate.getDate() + 1);
			}
			
			if(startDay > 0){
				//alert(startDay);
				for (var d = 0; d < startDay; d++){
					var weekdays = recur_pattern.weekdays;
					for(w in weekdays){
						if(weekdays[w] == d){
							if(newSDate < recur_dateEndDate){
								recur_date.push({
									start: new Date(newSDate.toString()),
									end: new Date(newEDate.toString())
								});
							}else{
								searching = false;	
							}
						}
					}
					newSDate.setDate(newSDate.getDate() + 1);
					newEDate.setDate(newEDate.getDate() + 1);
				}
			}
			
			i++;
		}
	}
	
	for(var i=0; i<recur_date.length; i++){
		
		recur_events.push({
	
			title: recur_type,
			resourceType: recur_type,
			start: recur_date[i].start,
			editable: false,
			end: recur_date[i].end,
			className:  "div"+recur_type.toString().replace(" ", "-").toLowerCase()+" scheduled",
			course: recur_course,
			type: "scheduled",
			actions: null
	
		});
	}
	requestRecurringAppointments(recur_events);

}


	
//--------------------------- Monthly ------------------------------
// recur_pattern: type(string), days(int), months(int) - monthly_numday
// recur_pattern: type(string), nth(string), weekdays(string), months(int) - monthly_weekday
function monthlyRecurrEvents(recur_startTime, recur_endTime, recur_course, recur_type, recur_pattern, recur_range){
	
	var recur_events = [];
	var recur_date = [];
	var recur_dateEndDate;
	var recur_months;
	var recur_day;

	// Not worrking for overnight appointments
	var startDate = new Date(recur_range.start + " " + recur_startTime);
	var endDate = new Date(recur_range.start + " " + recur_endTime);
	
	// When the starttime is after the end time, it is necessary to make sure the endtime is afterwards
	if(startDate > endDate){
		endDate.setDate(endDate.getDate()+1);
	}

	if(recur_range.type == "end_after"){
		recur_occurs = parseInt(recur_range.occurences);	// RANGE occurrences
		var extra = 0;
		
		for(var i = 0; i<recur_occurs; i++){
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			
			
			if(recur_pattern.type == "monthly_numday"){
	
				recur_months = parseInt(recur_pattern.months);
				recur_day = parseInt(recur_pattern.days);
			
				if(recur_day <= daysInMonth(startDate.getMonth(), startDate.getFullYear())){
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}else{
					recur_day = daysInMonth(startDate.getMonth(), startDate.getFullYear());
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}
				
				var cnt = recur_months * i;				
				
				newSDate.setMonth(newSDate.getMonth() + cnt + extra);
				newEDate.setMonth(newEDate.getMonth() + cnt + extra);
			
				if(newSDate > startDate){
					recur_date.push({
						start: newSDate,
						end: newEDate
					});
				}else{
					extra = 1;
					newSDate.setMonth(newSDate.getMonth() + extra);
					newEDate.setMonth(newEDate.getMonth() + extra);
					recur_date.push({
						start: newSDate,
						end: newEDate
					});
				}				
			
			}else if(recur_pattern.type == "monthly_weekday"){
				
				
				var newRecDate = new Date(startDate.toString());
				var totalDays = daysInMonth(newRecDate.getMonth(), newRecDate.getFullYear());
				recur_months = parseInt(recur_pattern.months);
				
				var nth = getNth(recur_pattern.nth);
				var weekday = getWeekdayNumber(recur_pattern.weekdays.toLowerCase());
				var searching = true;
				var j = 0;
				var cnt = recur_months * i;
					
				newRecDate.setMonth(newRecDate.getMonth() + cnt);
				
				newRecDate.setDate(1);
				
				// number of weekday instance
				if(nth > weekdayInMonth(recur_months, newRecDate.getFullYear(), weekday)){
					nth = weekdayInMonth(recur_months, newRecDate.getFullYear(), weekday);
				}
				
				
				while(searching){
					recur_day = newRecDate.getDate();
					
					if(newRecDate.getDay() == weekday){
						j++;
					}
					if(j == nth){
						searching = false;
						
						newSDate.setDate(newRecDate.getDate());
						newEDate.setDate(newRecDate.getDate());
						newSDate.setMonth(newRecDate.getMonth());
						newEDate.setMonth(newRecDate.getMonth());
						
						if(newSDate > startDate){
							recur_date.push({
								start: newSDate,
								end: newEDate
							});
						}else{
							searching = true;
							newRecDate.setMonth(newRecDate.getMonth() + 1);
							newRecDate.setDate(1);
							j = 0;
						}
							
					}else{
						newRecDate.setDate(newRecDate.getDate() + 1);
					}
				}	
			}
		}
		
	}else if(recur_range.type == "end_by"){
		
		recur_dateEndDate = new Date(recur_range.endby + " " + recur_endTime);
		var searching = true;
		var extra = 0;
		var i = 0;
		
		while(searching){
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			var cnt = recur_months * i;
			
			if(recur_pattern.type == "monthly_numday"){
				
				recur_months = parseInt(recur_pattern.months);
				recur_day = parseInt(recur_pattern.days);
			
				if(recur_day <= daysInMonth(startDate.getMonth(), startDate.getFullYear())){
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}else{
					recur_day = daysInMonth(startDate.getMonth(), startDate.getFullYear());
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}
				
				var cnt = recur_months * i;				
				
				newSDate.setMonth(newSDate.getMonth() + cnt + extra);
				newEDate.setMonth(newEDate.getMonth() + cnt + extra);
			
				if(newSDate > startDate){
					
					if(newSDate < recur_dateEndDate){
						recur_date.push({
							start: newSDate,
							end: newEDate
						});
					}else{
						searching = false;	
					}
					
				}else{
					extra = 1;
					newSDate.setMonth(newSDate.getMonth() + extra);
					newEDate.setMonth(newEDate.getMonth() + extra);
					if(newSDate < recur_dateEndDate){
						recur_date.push({
							start: newSDate,
							end: newEDate
						});
					}else{
						searching = false;	
					}
				}
				
				
			}else if(recur_pattern.type == "monthly_weekday"){
				
				
				var newRecDate = new Date(startDate.toString());
				var totalDays = daysInMonth(newRecDate.getMonth(), newRecDate.getFullYear());
				recur_months = parseInt(recur_pattern.months);
				
				var nth = getNth(recur_pattern.nth);
				var weekday = getWeekdayNumber(recur_pattern.weekdays.toLowerCase());
				var isSearching = true;
				var j = 0;
				var cnt = recur_months * i;
					
				newRecDate.setMonth(newRecDate.getMonth() + cnt);
				
				newRecDate.setDate(1);
				
				// number of weekday instance
				if(nth > weekdayInMonth(recur_months, newRecDate.getFullYear(), weekday)){
					nth = weekdayInMonth(recur_months, newRecDate.getFullYear(), weekday);
				}
				
				while(isSearching){
					recur_day = newRecDate.getDate();
					
					if(newRecDate.getDay() == weekday){
						j++;
					}
					if(j == nth){
						isSearching = false;
						
						newSDate.setDate(newRecDate.getDate());
						newEDate.setDate(newRecDate.getDate());
						newSDate.setMonth(newRecDate.getMonth());
						newEDate.setMonth(newRecDate.getMonth());
						
						if(newSDate > startDate){
							if(newSDate < recur_dateEndDate){
								recur_date.push({
									start: newSDate,
									end: newEDate
								});
							}else{
								searching = false;	
								isSearching = false;
							}
						}else{
							isSearching = true;
							newRecDate.setMonth(newRecDate.getMonth() + 1);
							newRecDate.setDate(1);
							j = 0;
						}
							
					}else{
						newRecDate.setDate(newRecDate.getDate() + 1);
					}
				}
			
			}
			i++;
		}
	}
	
	for(var i=0; i<recur_date.length; i++){
		
		recur_events.push({
	
			title: recur_type,
			resourceType: recur_type,
			start: recur_date[i].start,
			editable: false,
			end: recur_date[i].end,
			className:  "div"+recur_type.toString().replace(" ", "-").toLowerCase()+" scheduled",
			course: recur_course,
			type: "scheduled",
			actions: null
	
		});
	}
	requestRecurringAppointments(recur_events);
}

//--------------------------- Yearly -------------------------------
//recur_pattern: type, years(int), month(int), day(int)  - yearly_on
//recur_pattern: type, years(int), nth(string), weekdays(string), month(int) - yearly_on_the
function yearlyRecurrEvents(recur_startTime, recur_endTime, recur_course, recur_type, recur_pattern, recur_range){
	
	var recur_events = [];
	var recur_date = [];
	var recur_dateEndDate;
	var recur_years;
	var recur_month;
	var recur_day;

	// Not worrking for overnight appointments
	var startDate = new Date(recur_range.start + " " + recur_startTime);
	var endDate = new Date(recur_range.start + " " + recur_endTime);
	
	// When the starttime is after the end time, it is necessary to make sure the endtime is afterwards
	if(startDate > endDate){
		endDate.setDate(endDate.getDate()+1);
	}
	
	recur_years = recur_pattern.years;
	recur_month = recur_pattern.month;
	
	if(recur_range.type == "end_after"){
		recur_occurs = parseInt(recur_range.occurences);
		var extra = 0;
		
		for(var i = 0; i<recur_occurs; i++){
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			
		
			if(recur_pattern.type == "yearly_on"){
				//alert(recur_years);
				recur_day = parseInt(recur_pattern.day);
			
				if(recur_day <= daysInMonth(recur_month, startDate.getFullYear())){
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}else{
					recur_day = daysInMonth(recur_month, startDate.getFullYear());
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}
				
				newSDate.setMonth(recur_month - 1);
				newEDate.setMonth(recur_month - 1);
				
				var cnt = recur_years * i;				
				
				newSDate.setFullYear(newSDate.getFullYear() + cnt + extra);
				newEDate.setFullYear(newEDate.getFullYear() + cnt + extra);
			
				if(newSDate > startDate){
					recur_date.push({
						start: newSDate,
						end: newEDate
					});
				}else{
					extra = 1;
					newSDate.setFullYear(newSDate.getFullYear() + extra);
					newEDate.setFullYear(newEDate.getFullYear() + extra);
					recur_date.push({
						start: newSDate,
						end: newEDate
					});
				}	
				
			}else if(recur_pattern.type == "yearly_on_the"){
				
				var newRecDate = new Date(startDate.toString());
				var totalDays = daysInMonth(recur_month, newRecDate.getFullYear());
				
				var nth = getNth(recur_pattern.nth);
				var weekday = getWeekdayNumber(recur_pattern.weekdays.toLowerCase());
				var searching = true;
				var j = 0;
				var cnt = recur_years * i;
				
				
				newRecDate.setFullYear(newRecDate.getFullYear() + cnt + extra);
				newRecDate.setMonth(recur_month - 1);
				newRecDate.setDate(1);
				
				// number of weekday instance
				if(nth > weekdayInMonth(recur_month, newRecDate.getFullYear(), weekday)){
					nth = weekdayInMonth(recur_month, newRecDate.getFullYear(), weekday);
				}
				
				newSDate.setMonth(newRecDate.getMonth());
				newEDate.setMonth(newRecDate.getMonth());
				
				while(searching){
					recur_day = newRecDate.getDate();
					
					if(newRecDate.getDay() == weekday){
						j++;
					}
					if(j == nth){
						searching = false;
						
						newSDate.setDate(newRecDate.getDate());
						newEDate.setDate(newRecDate.getDate());
						newSDate.setFullYear(newRecDate.getFullYear());
						newEDate.setFullYear(newRecDate.getFullYear());
						
						if(newSDate > startDate){
							recur_date.push({
								start: newSDate,
								end: newEDate
							});
						}else{
							extra = 1;
							searching = true;
							newRecDate.setFullYear(newRecDate.getFullYear() + 1);
							newRecDate.setDate(1);
							j = 0;
						}
							
					}else{
						newRecDate.setDate(newRecDate.getDate() + 1);
					}
				}
				
				
			}
		}
		
	}else if(recur_range.type == "end_by"){	
		
		recur_dateEndDate = new Date(recur_range.endby + " " + recur_endTime);
		var searching = true;
		var extra = 0;
		var i = 0;
		
		while(searching){
			
			var newSDate = new Date(startDate.toString());
			var newEDate = new Date(endDate.toString());
			var cnt = recur_months * i;
			
			if(recur_pattern.type == "yearly_on"){
				recur_day = parseInt(recur_pattern.day);
			
				if(recur_day <= daysInMonth(recur_month, startDate.getFullYear())){
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}else{
					recur_day = daysInMonth(recur_month, startDate.getFullYear());
					newSDate.setDate(recur_day);
					newEDate.setDate(recur_day);
				}
				
				newSDate.setMonth(recur_month - 1);
				newEDate.setMonth(recur_month - 1);
				
				var cnt = recur_years * i;				
				
				newSDate.setFullYear(newSDate.getFullYear() + cnt + extra);
				newEDate.setFullYear(newEDate.getFullYear() + cnt + extra);
			
				if(newSDate > startDate){
					if(newSDate < recur_dateEndDate){
						recur_date.push({
							start: newSDate,
							end: newEDate
						});
					}else{
						searching = false;	
					}
				}else{
					extra = 1;
					newSDate.setFullYear(newSDate.getFullYear() + extra);
					newEDate.setFullYear(newEDate.getFullYear() + extra);
					if(newSDate < recur_dateEndDate){
						recur_date.push({
							start: newSDate,
							end: newEDate
						});
					}else{
						searching = false;	
					}
				}
				
			}else if(recur_pattern.type == "yearly_on_the"){
				
				var newRecDate = new Date(startDate.toString());
				var totalDays = daysInMonth(recur_month, newRecDate.getFullYear());
				
				var nth = getNth(recur_pattern.nth);
				var weekday = getWeekdayNumber(recur_pattern.weekdays.toLowerCase());
				var isSearching = true;
				var j = 0;
				var cnt = recur_years * i;
				
				
				newRecDate.setFullYear(newRecDate.getFullYear() + cnt + extra);
				newRecDate.setMonth(recur_month - 1);
				newRecDate.setDate(1);
				
				// number of weekday instance
				if(nth > weekdayInMonth(recur_month, newRecDate.getFullYear(), weekday)){
					nth = weekdayInMonth(recur_month, newRecDate.getFullYear(), weekday);
				}
				
				newSDate.setMonth(newRecDate.getMonth());
				newEDate.setMonth(newRecDate.getMonth());
				
				while(isSearching){
					recur_day = newRecDate.getDate();
					
					if(newRecDate.getDay() == weekday){
						j++;
					}
					if(j == nth){
						searching = false;
						
						newSDate.setDate(newRecDate.getDate());
						newEDate.setDate(newRecDate.getDate());
						newSDate.setFullYear(newRecDate.getFullYear());
						newEDate.setFullYear(newRecDate.getFullYear());
						
						if(newSDate > startDate){
							if(newSDate < recur_dateEndDate){
								recur_date.push({
									start: newSDate,
									end: newEDate
								});
							}else{
								searching = false;	
								isSearching = false;
							}
						}else{
							extra = 1;
							isSearching = true;
							newRecDate.setFullYear(newRecDate.getFullYear() + 1);
							newRecDate.setDate(1);
							j = 0;
						}
							
					}else{
						newRecDate.setDate(newRecDate.getDate() + 1);
					}
				}
	
			
			}
			i++;
		}
	}
	
	
	for(var i=0; i<recur_date.length; i++){
		
		recur_events.push({
	
			title: recur_type,
			resourceType: recur_type,
			start: recur_date[i].start,
			editable: false,
			end: recur_date[i].end,
			className:  "div"+recur_type.toString().replace(" ", "-").toLowerCase()+" scheduled",
			course: recur_course,
			type: "scheduled",
			actions: null
	
		});
	}
	requestRecurringAppointments(recur_events);
}

function requestRecurringAppointments(newEvents){
	//printEvents(newEvents);
	scheduleRecurringAppointment(newEvents);
}

function printEvents(events){
	var message = "";
	
	for(var i=0; i<events.length;i++){
		message += events[i].title+":"+events[i].start.toString() + " - " + events[i].end.toString() + "\n";
	}
	alert(message);
}

function getWeekdayNumber(name){
	var day;
	if(name == "sunday"){
		day = 0;
	}else if(name == "monday"){
		day = 1;
	}else if(name == "tuesday"){
		day = 2;
	}else if(name == "wednesday"){
		day = 3;
	}else if(name == "thursday"){
		day = 4;
	}else if(name == "friday"){
		day = 5;
	}else if(name == "saturday"){
		day = 6;
	}else{
		day = (-1);
	}
	return day;
}

function getNth(name){
	var week;
	if(name == "first"){
		week = 1;
	}else if(name == "second"){
		week = 2;
	}else if(name == "third"){
		week = 3;
	}else if(name == "fourth"){
		week = 4;
	}else if(name == "last"){
		week = 5;
	}else{
		week = (-1);
	}
	return week;
}

function weekdayInMonth(month, year, weekday){
	var totalDays = daysInMonth(month, year);
	var newDate = new Date(year, month-1, 1, 0, 0, 0, 0);
	var weeks = 0;
	
	for(var i = 0; i < totalDays; i++){
		if(newDate.getDay() == weekday){
			weeks++;
		}
		newDate.setDate(i + 1);
	}
	
	return weeks;
}

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}