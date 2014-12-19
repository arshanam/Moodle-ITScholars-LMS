//****************************************************************************************************
//Javascript: Scheduler.js 
//@author:   Vanessa Ramirez

//****************************************************************************************************



//Global Variables

var userTimezone;
var userAssignment;
var username;
var userfname;
var userlname;
var usercountry;
var usercity;
var userstate;
var userip;


var slotCount;
var firstCalendarDate;
var slotCountJson;
var selectedDate;

//Change this variable to set the number of days
//from today that the lab slots will be showing

var SlotPeriod = 30;




//*************************************************************************************************

// Request: loadAvailableTimeZoneIds

var loadAvailableTimeZoneIds_handleSuccess = function(o){
	hideAll();

	if(o.responseXML !== undefined){
	  var div = document.getElementById('step1');

		var root = o.responseXML.getElementsByTagName('data')[0];
		var container ='<h1>Step 1: Select your Timezone</h1>';	   
		container +='<label>Timezone: </label>';
		container +='<select id="timezones">';  
		

            var i=0;
            
	    for(i=0;i<root.getElementsByTagName('node').length;i++){
			
		var oTimezoneId = root.getElementsByTagName('node')[i].firstChild.nodeValue;
		container +='<option>'+ oTimezoneId +'</option>';
		
	    
	    } 
	     container += '</select>';	
	     container += '&nbsp;&nbsp;<input type="button" value="Next step" id="buttonns1" onclick="setUserDefaultTimeZoneId()"/>';

	
	     div.innerHTML = container ;
	     getUserDefaultTimeZoneId();	
	  
	}
	
	show('step1');
}

var loadAvailableTimeZoneIds_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
		
	}
}

var loadAvailableTimeZoneIds_callback =
{
  success:loadAvailableTimeZoneIds_handleSuccess,
  failure:loadAvailableTimeZoneIds_handleFailure
  
};



function loadAvailableTimeZoneIds(){
	var sUrl = 'webservice.php?action=getAvailableTimeZoneIds';

	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, loadAvailableTimeZoneIds_callback);
	
}
//***********************************************************************************************


// Request: getUserDefaultTimeZoneId

var getUserDefaultTimeZoneId_handleSuccess = function(o){
	
	if(o.responseText !== undefined){
	    //alert(o.responseText);	
	    var list = document.getElementById('timezones');
	    var options_array = list.childNodes;
    	    
	    var i=0;
	    for(i=0;i<options_array.length;i++){
		
				
			if(options_array[i].value==o.responseText || options_array[i].firstChild.nodeValue==o.responseText){
				//alert(options_array[i].value);	
				YAHOO.util.Dom.setAttribute(options_array[i],'selected','selected');
				list.selectedIndex = i;
				return;				
		}
		
	    
	    } 	
	
	}

}

var getUserDefaultTimeZoneId_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
}

var getUserDefaultTimeZoneId_callback =
{ cache:false,
  success:getUserDefaultTimeZoneId_handleSuccess,
  failure:getUserDefaultTimeZoneId_handleFailure
  
};


function getUserDefaultTimeZoneId(){


	getUserInfo();

	var sUrl = 'webservice.php?action=getUserDefaultTimeZoneId&username='+username+'&email='+useremail;
	//alert(sUrl);
	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, getUserDefaultTimeZoneId_callback);
	
}
//***********************************************************************************************


// Request: setUserDefaultTimeZoneId

var setUserDefaultTimeZoneId_handleSuccess = function(o){

	if(o.responseText !== undefined){
		//alert(o.responseText);
		loadLabs();
	}

}

var setUserDefaultTimeZoneId_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
}

var setUserDefaultTimeZoneId_callback =
{
  success:setUserDefaultTimeZoneId_handleSuccess,
  failure:setUserDefaultTimeZoneId_handleFailure
  
};

function setUserDefaultTimeZoneId(){

	var list = document.getElementById('timezones');
	var options_array = list.childNodes;
	userTimezone = options_array[list.selectedIndex].value;
	if(navigator.appName == 'Microsoft Internet Explorer'){
		userTimezone = options_array[list.selectedIndex].firstChild.nodeValue;
	}	
	
	var username = document.getElementById('username').value;
	var email = document.getElementById('email').value;
	
	
	
	var sUrl = 'webservice.php?action=setUserDefaultTimeZoneId&username='+username+'&email='+email+'&timezone='+encodePlus(userTimezone);
	//alert(sUrl);
	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, setUserDefaultTimeZoneId_callback);
	
}


//***********************************************************************************************
// Request: loadLabs

var loadLabs_handleSuccess = function(o){
var button = document.getElementById("buttonns1")
YAHOO.util.Dom.setAttribute(button,'disabled','false');
	if(o.responseXML !== undefined){

	  	hide('step1');
		
		var div = document.getElementById('step2');
		var root = o.responseXML.getElementsByTagName('Root')[0];
		var container ='<h1>Step 2: Select a Lab to show available dates</h1>';	   
		container +='<label>Lab: </label>';
		container +='<select id="labs">';  
	

            var i=0;
            
	    for(i=0;i<root.getElementsByTagName('course').length;i++){
			
		var oLab = root.getElementsByTagName('course')[i];
		container +='<option value='+oLab.childNodes[0].firstChild.nodeValue+'>'+ oLab.childNodes[1].firstChild.nodeValue +'</option>';
		
	    
	    } 
	     container += '</select>&nbsp;&nbsp;';	
	     container += '<input type="button" value="Next step" id="buttonns2" onclick="setUserAssignment()"/>';	
	     container += '&nbsp;&nbsp;<input type="button" value="Back" onclick="back(\'step1\',\'step2\')"/>';
	     div.innerHTML = container ;
	     show('step2');
		
	      
	}
	

}

var loadLabs_handleFailure = function(o){


	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
	
}

        

var loadLabs_callback =
{
  success:loadLabs_handleSuccess,
  failure:loadLabs_handleFailure
  
};



function loadLabs(){
	var buttton = document.getElementById("buttonns1").disabled=true;
	var sUrl = 'webservice.php?action=getLabs';

	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, loadLabs_callback);
	
}
//***********************************************************************************************
function setUserAssignment(){
	var buttton = document.getElementById("buttonns2").disabled=true;
	var list = document.getElementById('labs');
	var options_array = list.childNodes;
	userAssignment = options_array[list.selectedIndex].value;
	getSlotCount();
}
//***********************************************************************************************
function showSlotCount(year, month){

	hide('step2');
	var div = document.getElementById('step3');
	var container ='<h1>Step 3: Pick a day</h1>';
	container += '<input type="button" value="Back" onclick="back(\'step2\',\'step3\')"/>';	  
	container += '<div id="calendar" class="calendar"></div>';
	div.innerHTML = container;
	createCalendar();
	show('step3');

}

//************************************************************************************************

function createCalendar(){
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			editable: false,
			eventClick : function(event) {
			   if(event.count>0)
			   {
				getAvailableSlots(event.year, event.month, event.day);
			   }else{
				alert('There are no available slots on the selected date.');
			   }	
			},
			events: slotCountJson	
			
		});
		$('#calendar').fullCalendar('render');
				
}

//************************************************************************************************
// Request: getSlotCount

var getSlotCount_handleSuccess = function(o){
	var buttton = document.getElementById("buttonns2").disabled=false;
	if(o.responseXML !== undefined){

		var root = o.responseXML.getElementsByTagName('Root')[0];
		var slotCountStartDate = root.getElementsByTagName('dayStr')[0].firstChild.nodeValue;
		var counts = root.getElementsByTagName('count');
		
		
		
		var jsonArray = new Array();
		var i =0;		
		for(i=0;i<counts.length;i++)
		{
			var daystr = root.getElementsByTagName('dayStr')[i].firstChild.nodeValue;
			var day=  daystr.substring(3,5);	
			var month = daystr.substring(0,2);
			var year = daystr.substring(6,10);
			var jsonObj={   
					title: counts[i].firstChild.nodeValue+' slot(s) available',
					start:new Date(year,month-1,day),
					allDay:true,
					month:month-1,
					day:day,
					count:counts[i].firstChild.nodeValue,
					year:year
			};
			jsonArray[i]=jsonObj;
			
			
			
		}
		
		slotCountJson = jsonArray;
		showSlotCount(year,month);		
		
	
	}
}

var getSlotCount_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
}

var getSlotCount_callback =
{
  success:getSlotCount_handleSuccess,
  failure:getSlotCount_handleFailure
  
};


function getSlotCount(){
	
	var currentTime = new Date();
	var start = toDateFormat(currentTime);
	var endDate = currentTime;
	endDate.setDate(endDate.getDate()+SlotPeriod);	
	var end = toDateFormatEnd(endDate);
	
	
	

	var sUrl = 'webservice.php?action=getSlotCount&from='+start+'&to='+end+'&timezone='+encodePlus(userTimezone)+'&assignment='+userAssignment;

	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, getSlotCount_callback);
	
}



//************************************************************************************************
// Request: getAvailableSlots

var getAvailableSlots_handleSuccess = function(o){

	if(o.responseXML !== undefined){

		hide('step3');

		var div = document.getElementById('step4');
		var root = o.responseXML.getElementsByTagName('Root')[0];
		var container ='<h1>Step 4: Select a slot</h1>';	   
		container +='<label>Available Slots: </label>';
		container +='<select id="slots">';  
		

            var i=0;
	     	
            var times = root.getElementsByTagName('start');
	     for(i=0;i<root.getElementsByTagName('slotStr').length;i++){
		var oSlot = root.getElementsByTagName('slotStr')[i];	
		if(oSlot.firstChild.nodeValue.substring(0,10)==selectedDate)		
		{	
			
			container +='<option value='+times[i].firstChild.nodeValue+'>'+ oSlot.firstChild.nodeValue +'</option>';
		}
	    
	    } 
	     container += '</select>&nbsp;&nbsp;';	
	     container += '<input type="button" value="Schedule Lab"  id="buttonns3" onclick="allocateExam()"/>';
	     container += '&nbsp;&nbsp;<input type="button" value="Back" onclick="back(\'step3\',\'step4\')"/>';		
	     div.innerHTML = container ;
	     show('step4');	
	}
}

var getAvailableSlots_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
}
       

var getAvailableSlots_callback =
{
  success:getAvailableSlots_handleSuccess,
  failure:getAvailableSlots_handleFailure
  
};
function getAvailableSlots(y,m,d){
	
	m=checkTime(m+1);
	selectedDate = m +'/'+d+'/'+y;
	//alert(selectedDate);
	
	var currentTime = new Date();
	var start = toDateFormat(currentTime);
	var endDate = currentTime;
	endDate.setDate(endDate.getDate()+SlotPeriod+1);	
	var end = toDateFormatStart(endDate);
	
	

	var sUrl = 'webservice.php?action=getAvailableSlots&from='+start+'&to='+end+'&timezone='+encodePlus(userTimezone)+'&assignment='+userAssignment;

	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, getAvailableSlots_callback);
}

//************************************************************************************************
// Request: allocateExam

var allocateExam_handleSuccess = function(o){
	var buttton = document.getElementById("buttonns3").disabled=false;
	if(o.responseXML !== undefined){
		var root = o.responseXML.getElementsByTagName('Root')[0];
		var res = root.getElementsByTagName('response')[0];
		var mes = res.getElementsByTagName('message')[0].firstChild.nodeValue;
		var success = res.getElementsByTagName('success')[0].firstChild.nodeValue;
		var examurl = res.getElementsByTagName('examurl')[0].firstChild.nodeValue;

			
		hide('step4');

		var div = document.getElementById('step5');
		
		var container ='<h3>'+mes+'</h3>';
		 
		div.innerHTML = container ;
		
	     show('step5');	
	}
}

var allocateExam_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
}
      

var allocateExam_callback =
{
  success:allocateExam_handleSuccess,
  failure:allocateExam_handleFailure
  
};


function allocateExam(){
	var buttton = document.getElementById("buttonns3").disabled=true;
	var list = document.getElementById('slots');
	var options_array = list.childNodes;
	var timeslot = options_array[list.selectedIndex].value;
	
	var sUrl = 'webservice.php?action=allocateExam&examtime='+timeslot+
		   '&timezone='+encodePlus(userTimezone)+'&assignment='+userAssignment+
	           '&username='+username+'&firstname='+userfname+'&lastname='+userlname+
		   '&country='+usercountry+'&state='+userstate+'&city='+usercity+
		   '&ip='+userip+'&email='+useremail; 			

	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, allocateExam_callback);
	
}

//************************************************************************************************
// Request: cancelLab

var cancelLab_handleSuccess = function(o){

	if(o.responseText !== undefined){
	
		showTable();
	}
}

var cancelLab_handleFailure = function(o){

	if(o.responseText !== undefined){
		div.innerHTML = "<ul><li>Transaction id: " + o.tId + "</li>";
		div.innerHTML += "<li>HTTP status: " + o.status + "</li>";
		div.innerHTML += "<li>Status code message: " + o.statusText + "</li></ul>";
	}
}
        

var cancelLab_callback =
{
  success:cancelLab_handleSuccess,
  failure:cancelLab_handleFailure
  
};


function cancelLab(id){
		
	var sUrl = 'webservice.php?action=cancelLab&username='+username+'&email='+useremail+'&id='+id; 			

	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, cancelLab_callback);
	
}

//************************************************************************************************
function showTable() {
	
	hideAll();
	var formatUrl = function(elCell, oRecord, oColumn, sData) {
		elCell.innerHTML = "<a href='#' onclick='cancelLab("+sData+")' >Cancel Lab</a>";
	};

        var myColumnDefs = [
		{key:"coursename", label:"Name", sortable: true},
		{key:"slotStr", label:"Time", sortable: true},
		{key:"id", label:"Cancel",formatter:formatUrl}
        ];

        var myDataSource = new YAHOO.util.DataSource("webservice.php?");
        myDataSource.connMethodPost = true;
        myDataSource.responseType = YAHOO.util.DataSource.TYPE_XML;
        myDataSource.responseSchema = {
            resultNode: "ExamAttempt",
            fields: ["coursename","slotStr","id"]
        };

        var myDataTable = new YAHOO.widget.DataTable("labList", myColumnDefs,
                myDataSource, {initialRequest:"action=getScheduledExams&username="+username+"&email="+useremail, caption: "My Scheduled Labs"});
	show('labList');

}
//************************************************************************************************
// AUXILIARY FUNCTIONS
//************************************************************************************************

function checkTime(i)
{
if (i<10)
  {
  i="0" + i;
  }
return i;
}


function toDateFormat( dateobj){

	var month = dateobj.getMonth() +1;
	var day = dateobj.getDate();
	var year = dateobj.getFullYear();
	
	
	var h=dateobj.getHours();
	var m=dateobj.getMinutes();
	var s=dateobj.getSeconds();
	// add a zero in front of numbers<10
	h=checkTime(h);
	m=checkTime(m);
	s=checkTime(s);
	
	var date = ''+year;
	date += '-';

	if(month<10) date +='0'+month;
	else date += month;
	date += '-';
	
	if(day<10) date +='0'+day;
	else date +=day;

	date +='T'+h+':'+m+':'+s;
	
	return date;

}

function toDateFormatStart( dateobj){

	var month = dateobj.getMonth() +1;
	var day = dateobj.getDate();
	var year = dateobj.getFullYear();
	
	
	var h=dateobj.getHours();
	var m=dateobj.getMinutes();
	var s=dateobj.getSeconds();
	// add a zero in front of numbers<10
	h=checkTime(h);
	m=checkTime(m);
	s=checkTime(s);
	
	var date = ''+year;
	date += '-';

	if(month<10) date +='0'+month;
	else date += month;
	date += '-';
	
	if(day<10) date +='0'+day;
	else date +=day;

	date +='T00:00:00';
	
	return date;

}

function toDateFormatEnd( dateobj){

	var month = dateobj.getMonth() +1;
	var day = dateobj.getDate();
	var year = dateobj.getFullYear();
	
	
	var h=dateobj.getHours();
	var m=dateobj.getMinutes();
	var s=dateobj.getSeconds();
	// add a zero in front of numbers<10
	h=checkTime(h);
	m=checkTime(m);
	s=checkTime(s);
	
	var date = ''+year;
	date += '-';

	if(month<10) date +='0'+month;
	else date += month;
	date += '-';
	
	if(day<10) date +='0'+day;
	else date +=day;

	date +='T23:59:59';
	
	return date;

}

function show(showobjid){

	var divShow = document.getElementById(showobjid);
	divShow.style.display = 'block';
	divShow.style.visibility = 'visible';


}

function hide(hideobjid){

	var divHide = document.getElementById(hideobjid);
	divHide.style.display = 'none';
	divHide.style.visibility = 'hidden';


}

function hideAll(){
	hide('step1');
	hide('step2');
	hide('step3');
	hide('step4');
	hide('step5');
	hide('labList');
}

function back(to, from){
	hide(from);
	show(to);
	
}

function getUserInfo(){
	username = encodePlus(document.getElementById('username').value);
	useremail = encodePlus(document.getElementById('email').value);
	userfname = encodePlus(document.getElementById('firstname').value);
	userlname= encodePlus(document.getElementById('lastname').value);	
	userip= encodePlus(document.getElementById('lastip').value);
	usercountry= encodePlus(document.getElementById('country').value);
	usercity=encodePlus( document.getElementById('city').value);
	userstate= encodePlus(document.getElementById('state').value);

}

function encodePlus(str){
	return str.replace("+", '%2B');

}



