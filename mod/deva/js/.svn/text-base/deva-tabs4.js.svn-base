
$(document).ready(function() {
	
	reloadDevaFront();
    
});


function DoViewIFrame(tid) {
	
	$('.tabContentIFrame').hide();
	$(tid).show();

}

function reloadDevaFront() {
	
    // var role = $("#role").val();
    // if(role=="admin"){
    	
	// First: Get the information for the current schedule for this ve, if any, 
    // and populate the information for the user to work with the ve.
	var devaWasDisplayed = getCurDevaInsInfo(); 
	
	// Second: If there is no current schedule for this ve, get the 
	// information for the schedule of this ve for the next 30 minutes, if any
	// and ask the user whether he/she wants to start early.
	/*
	$.ajax({
		type: 'POST',
		url: 'php/getAppointment.php',
		dataType: 'json',
		async: false,
		data: {
			requestingUser: $('#username').val(),
			username: $('#username').val(),
			course: $('#course').val(),
			resourceType: $('#resourcetype').val()
		},
		success: function(vms) {

			if(vms != null) {

				if (vms.success) {
					
				}
			}
		}
	});
	*/
	// alert("before editDialogBox!");
	// editDialogBox(event,view);
	// editDialogBox();
	// alert("after editDialogBox!");

	// Third: If the user confirmed to start early, modify the appointment and 
	// go to the First step. 
	
	// Fourth: If there is no appointment for the next 30 minutes for this ve
	// then ask user if he/she wants to schedule one by asking the length of the
	// appointment in hours and minutes, starting from now.
	if (!devaWasDisplayed) {
		createInstantAppointmentDialogBox(
			$('#username').val(), 
			$('#course').val(), 
			$('#resourcetype').val());
	}
	
	// Fifth: If the user confirmed, then schedule an appointment accordingly 
	// and then go to the First step.
}

function getCurDevaInsInfo() {
	
	var bottomFrameHeightPercentage = getBottomFrameHeightPercentage();
	var devaWasDisplayed = false;
	
	// Clean devaTabs div to build it
	$("#devaTabs").html("");

	$.ajax({
		type: 'POST',
		url: 'php/virtuallabs-wscalls.php',
		dataType: 'json',
		async: false,
		data: {
			action: 'getDevaInsInfo',
			requestingUser: $('#username').val(),
			username: $('#username').val(),
			course: $('#course').val(),
			resourceType: $('#resourcetype').val()
		},
		success: function(vms) {

			if(vms != null) {

				if (vms.success) {
					devaWasDisplayed = true;
					var div = "";
					var kaseyaServer = "http://kaseya2.cis.fiu.edu/";

					// Network Diagram Tab
					/*
					$("#devaTabs").append('<a id="devaGraph" class="devaTabs ui-corner-all" ' + 
							'href="devaGraph.php?'+
							'kaseyaServer='+kaseyaServer+
							'&vmName0='+vms.vmInfo[0].name+
							'&vmName1='+vms.vmInfo[1].name+
							'&vmName2='+vms.vmInfo[2].name+
							'&vmName3='+vms.vmInfo[3].name+
							'&vmName4='+vms.vmInfo[4].name+
							'&hostName='+vms.vmInfo[0].accessAddress+
							'&hostPort0='+vms.vmInfo[0].accessPort+
							'&hostPort1='+vms.vmInfo[1].accessPort+
							'&hostPort2='+vms.vmInfo[2].accessPort+
							'&hostPort3='+vms.vmInfo[3].accessPort+
							'&hostPort4='+vms.vmInfo[4].accessPort+
							'&username='+vms.vmInfo[0].username+
							'&password='+vms.vmInfo[0].password+
							'&domain='+vms.vmInfo[0].domain+
							'&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+
							'" '+ 
							'target="mainscreen">'+
					'<span>Network Diagram</span></a>&nbsp;');
					*/
					/*
					div = '<a id="devaGraph" class="devaTabs ui-corner-all" ' + 
						'href="devaGraph2.php?'+
						'kaseyaServer='+kaseyaServer+
						'&vmName0='+vms.vmInfo[0].name+
						'&vmName1='+vms.vmInfo[1].name+
						'&vmName2='+vms.vmInfo[2].name+
						'&vmName3='+vms.vmInfo[3].name+
						'&vmName4='+vms.vmInfo[4].name+
						'&hostName='+vms.vmInfo[0].accessAddress+
						'&hostPort0='+vms.vmInfo[0].accessPort+
						'&hostPort1='+vms.vmInfo[1].accessPort+
						'&hostPort2='+vms.vmInfo[2].accessPort+
						'&hostPort3='+vms.vmInfo[3].accessPort+
						'&hostPort4='+vms.vmInfo[4].accessPort+
						'&username='+vms.vmInfo[0].username+
						'&password=********'+ // +vms.vmInfo[0].password+
						'&domain='+vms.vmInfo[0].domain;
					for (var i=0; i<vms.vmInfo.length; i++) {
						div += '&tab'+i+'=tab'+i;
						div += '&vmInsId'+i+'='+vms.vmInfo[i].id;
					}
					div += '&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+
						'" '+ 
						'target="mainscreen">'+
						'<span>Network Diagram</span></a>&nbsp;';
					$("#devaTabs").append(div);
					*/
					$("#devaTabs").append('<span id="devaGraph"  class="devaTabs ui-corner-all">'+
							'<span onclick="DoViewIFrame(\'#devaGraphContentIFrame\')>Network Diagram</span></span>&nbsp;');

					// Connection Information Tab
					// $("#devaTabs").append('<a id="devaInfo" class="devaTabs ui-corner-all" href="devaInfo.php?kaseyaServer='+kaseyaServer+'&vmName0='+vms.vmInfo[0].name+'&vmName1='+vms.vmInfo[1].name+'&vmName2='+vms.vmInfo[2].name+'&vmName3='+vms.vmInfo[3].name+'&vmName4='+vms.vmInfo[4].name+'&hostName='+vms.vmInfo[0].accessAddress+'&hostPort0='+vms.vmInfo[0].accessPort+'&hostPort1='+vms.vmInfo[1].accessPort+'&hostPort2='+vms.vmInfo[2].accessPort+'&hostPort3='+vms.vmInfo[3].accessPort+'&hostPort4='+vms.vmInfo[4].accessPort+'&username='+vms.vmInfo[0].username+'&password='+vms.vmInfo[0].password+'&domain='+vms.vmInfo[0].domain+'&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+'" target="mainscreen"><span>Connection Info</span></a>&nbsp;');

					/*
					div = '<a id="devaInfo" class="devaTabs ui-corner-all" ' + 
							'href="devaInfo2.php?'+
							'kaseyaServer='+kaseyaServer+
							'&vmName0='+vms.vmInfo[0].name+
							'&vmName1='+vms.vmInfo[1].name+
							'&vmName2='+vms.vmInfo[2].name+
							'&vmName3='+vms.vmInfo[3].name+
							'&vmName4='+vms.vmInfo[4].name+
							'&hostName='+vms.vmInfo[0].accessAddress+
							'&hostPort0='+vms.vmInfo[0].accessPort+
							'&hostPort1='+vms.vmInfo[1].accessPort+
							'&hostPort2='+vms.vmInfo[2].accessPort+
							'&hostPort3='+vms.vmInfo[3].accessPort+
							'&hostPort4='+vms.vmInfo[4].accessPort+
							'&username='+vms.vmInfo[0].username+
							'&password=********'+ // +vms.vmInfo[0].password+
							'&domain='+vms.vmInfo[0].domain;
						for (var i=0; i<vms.vmInfo.length; i++) {
							div += '&tab'+i+'=tab'+i;
							div += '&vmInsId'+i+'='+vms.vmInfo[i].id;
						}
						div += '&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+
							'" '+ 
							'target="mainscreen">'+
							'<span>Connection Info</span></a>&nbsp;';
						$("#devaTabs").append(div);
						*/
					$("#devaTabs").append('<span id="devaInfo"  class="devaTabs ui-corner-all">'+
						'<span onclick="DoViewIFrame(\'#devaInfoContentIFrame\')>Connection Info</span></span>&nbsp;');

					for (var i=0; i<vms.vmInfo.length; i++) {
						// Deva Tabs
						// $("#devaTabs").append('<a id="tab'+i+'" class="devaTabs ui-corner-all" href="webRDP.php?tab=tab'+i+'&hostName='+vms.vmInfo[i].accessAddress+'&hostPort='+vms.vmInfo[i].accessPort+'&username='+vms.vmInfo[i].username+'&password='+vms.vmInfo[i].password+'&domain='+vms.vmInfo[i].domain+'&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+'" target="mainscreen"><span>'+vms.vmInfo[i].name+'</span></a>&nbsp;');
						$("#devaTabs").append('<span id="tab'+i+'" class="devaTabs ui-corner-all">'+
								'<span onclick="DoViewIFrame(\'#tab'+i+'ContentIFrame'+'\')>'+vms.vmInfo[i].name+'</span></span>&nbsp;');
					}
					
					// deva graph iframe
					var url = 'devaGraph2.php?'+
						'kaseyaServer='+kaseyaServer+
						'&vmName0='+vms.vmInfo[0].name+
						'&vmName1='+vms.vmInfo[1].name+
						'&vmName2='+vms.vmInfo[2].name+
						'&vmName3='+vms.vmInfo[3].name+
						'&vmName4='+vms.vmInfo[4].name+
						'&hostName='+vms.vmInfo[0].accessAddress+
						'&hostPort0='+vms.vmInfo[0].accessPort+
						'&hostPort1='+vms.vmInfo[1].accessPort+
						'&hostPort2='+vms.vmInfo[2].accessPort+
						'&hostPort3='+vms.vmInfo[3].accessPort+
						'&hostPort4='+vms.vmInfo[4].accessPort+
						'&username='+vms.vmInfo[0].username+
						'&password=********'+ // +vms.vmInfo[0].password+
						'&domain='+vms.vmInfo[0].domain;
					for (var i=0; i<vms.vmInfo.length; i++) {
						url += '&tab'+i+'=tab'+i;
						url += '&vmInsId'+i+'='+vms.vmInfo[i].id;
					}
					url += '&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+
						'" ';
					alert("url: " + url);
					div  = "";
					div += '<div id="devaGraphContentIFrame" class="tabContentIFrame" align="center">'; // style="display: none;">';
					div += '<IFRAME name="devaGraphContent" id="devaGraphContentid"'; 
					div += '	SRC='+url;
					div += '	WIDTH=100% HEIGHT="'+tabContentHeight()+'"';
					div += '	frameborder="0"';
					div += '	scrolling="no">';
					div += '	Your browser doesn\'t understand IFRAME. Please click'; 
					div += '	<A target=_blank HREF='+url;
					div += '	here</A> to load the page in a separate window.';
					div += '</IFRAME>';
					div += '</div>'
					$("#devaTabContent").append(div);
				
					// deva info iframe
					url = 'devaInfo2.php?'+
						'kaseyaServer='+kaseyaServer+
						'&vmName0='+vms.vmInfo[0].name+
						'&vmName1='+vms.vmInfo[1].name+
						'&vmName2='+vms.vmInfo[2].name+
						'&vmName3='+vms.vmInfo[3].name+
						'&vmName4='+vms.vmInfo[4].name+
						'&hostName='+vms.vmInfo[0].accessAddress+
						'&hostPort0='+vms.vmInfo[0].accessPort+
						'&hostPort1='+vms.vmInfo[1].accessPort+
						'&hostPort2='+vms.vmInfo[2].accessPort+
						'&hostPort3='+vms.vmInfo[3].accessPort+
						'&hostPort4='+vms.vmInfo[4].accessPort+
						'&username='+vms.vmInfo[0].username+
						'&password=********'+ // +vms.vmInfo[0].password+
						'&domain='+vms.vmInfo[0].domain;
					for (var i=0; i<vms.vmInfo.length; i++) {
						div += '&tab'+i+'=tab'+i;
						div += '&vmInsId'+i+'='+vms.vmInfo[i].id;
					}
					div += '&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+
						'" ';
					alert("url: " + url);
					div  = "";
					div += '<div id="devaInfoContentIFrame" class="tabContentIFrame" align="center"'; // style="display: none;">';
					div += '<IFRAME name="devaInfoContent" id="devaInfoContentid"'; 
					div += '	SRC='+url;
					div += '	WIDTH=100% HEIGHT="'+tabContentHeight()+'"';
					div += '	frameborder="0"';
					div += '	scrolling="no">';
					div += '	Your browser doesn\'t understand IFRAME. Please click'; 
					div += '	<A target=_blank HREF='+url;
					div += '	here</A> to load the page in a separate window.';
					div += '</IFRAME>';
					div += '</div>'
					$("#devaTabContent").append(div);
				
					// deva iframes
					for (var i=0; i<vms.vmInfo.length; i++) {
						
						url = 'webRDP2.php?'+
							'tab=tab'+i+
							'&vmInsId='+vms.vmInfo[i].id+
							'&bottomFrameHeightPercentage='+bottomFrameHeightPercentage+
							'"';
						alert("url: " + url);
						div  = "";
						div += '<div id="tab'+i+'ContentIFrame" class="tabContentIFrame" align="center"'; // style="display: none;">';
						div += '<IFRAME name="tab'+i+'Content" id="tab'+i+'Contentid"'; 
						div += '	SRC='+url;
						div += '	WIDTH=100% HEIGHT="'+tabContentHeight()+'"';
						div += '	frameborder="0"';
						div += '	scrolling="no">';
						div += '	Your browser doesn\'t understand IFRAME. Please click'; 
						div += '	<A target=_blank HREF='+url;
						div += '	here</A> to load the page in a separate window.';
						div += '</IFRAME>';
						div += '</div>'
						$("#devaTabContent").append(div);
					
					}

					// This is just a comment!
					$("#tabs-wrapper").css({"border": "4px solid #DFEFFC"})
				
				} else {
					// alert(vms.reason);
				}
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert("The server may be down! Please contact sadjadi@cs.fiu.edu");
		}
	});
	
	return devaWasDisplayed;
}

function updateTips(t) {
    tips
    .text(t)
    .addClass('ui-state-highlight');
    setTimeout(function() {
        tips.removeClass('ui-state-highlight', 1500);
    }, 500);
}

function checkLength(o,n,min,max) {

    if ( o.val().length > max || o.val().length < min ) {
        o.addClass('ui-state-error');
        updateTips("Length of " + n + " must be between "+min+" and "+max+".");
        return false;
    } else {
        return true;
    }

}

function checkRegexp(o,regexp,n) {

    if ( !( regexp.test( o.val() ) ) ) {
        o.addClass('ui-state-error');
        updateTips(n);
        return false;
    } else {
        return true;
    }

}

function xstooltip_findPosX(obj) 
{
  var curleft = 0;
  if (obj.offsetParent) 
  {
    while (obj.offsetParent) 
        {
            curleft += obj.offsetLeft
            obj = obj.offsetParent;
        }
    }
    else if (obj.x)
        curleft += obj.x;
    return curleft;
}

function xstooltip_findPosY(obj) 
{
    var curtop = 0;
    if (obj.offsetParent) 
    {
        while (obj.offsetParent) 
        {
            curtop += obj.offsetTop
            obj = obj.offsetParent;
        }
    }
    else if (obj.y)
        curtop += obj.y;
    return curtop;
}

function xstooltip_show(tooltipId, parentId, posX, posY)
{
    it = document.getElementById(tooltipId);
    
    if ((it.style.top == '' || it.style.top == 0) 
        && (it.style.left == '' || it.style.left == 0))
    {
        // need to fixate default size (MSIE problem)
        it.style.width = it.offsetWidth + 'px';
        it.style.height = it.offsetHeight + 'px';
        
        img = document.getElementById(parentId); 
    
        // if tooltip is too wide, shift left to be within parent 
        if (posX + it.offsetWidth > img.offsetWidth) posX = img.offsetWidth - it.offsetWidth;
        if (posX < 0 ) posX = 0; 
        
        x = xstooltip_findPosX(img) + posX;
        y = xstooltip_findPosY(img) + posY;
        
        it.style.top = y + 'px';
        it.style.left = x + 'px';
    }
    
    it.style.visibility = 'visible'; 
}

function xstooltip_hide(id)
{
    it = document.getElementById(id); 
    it.style.visibility = 'hidden'; 
}

function resizeIframe(frameId, newHeight)
{
	// alert("Hi!");
  document.getElementById(frameId).style.height = parseInt(newHeight) + 10 + 'px';
}

function findPosX(obj)
{
  var curleft = 0;
  if(obj.offsetParent)
      while(1) 
      {
        curleft += obj.offsetLeft;
        if(!obj.offsetParent)
          break;
        obj = obj.offsetParent;
      }
  else if(obj.x)
      curleft += obj.x;
  return curleft;
}

function findPosY(obj)
{
  var curtop = 0;
  if(obj.offsetParent)
      while(1)
      {
        curtop += obj.offsetTop;
        if(!obj.offsetParent)
          break;
        obj = obj.offsetParent;
      }
  else if(obj.y)
      curtop += obj.y;
  return curtop;
}

function tabContentHeight()
{
	var tabConHeight = 0;
	var tabContent = document.getElementById('devaTabContent');
	tabConHeight = getHeight() - findPosY(tabContent) - 10;
	// alert(tabConHeight);
	return tabConHeight;
}

var BROWSER = {IE:0, CHROME:1, SAFARI:2, MOZILLA:3, OPERA:4}; 

function getBottomFrameHeightPercentage() {

	var bottomFrameHeightPercentage = 100;
	
	var browserType = getBrowserType();
	
	switch (browserType) {
		case BROWSER.SAFARI:
			bottomFrameHeightPercentage = 97;
			break;
		
		case BROWSER.IE:
		case BROWSER.CHROME:
		case BROWSER.MOZILLA:
		case BROWSER.OPERA:
			bottomFrameHeightPercentage = 100;
			break;

		default: 
			bottomFrameHeightPercentage = 100;
	}
		
	return bottomFrameHeightPercentage;
}

function getBrowserType() {

	// Is this a version of IE?
	if($.browser.msie){
		return BROWSER.IE;
	}

	// Is this a version of Chrome?
	if($.browser.chrome){
		return BROWSER.CHROME;
	}

	// Is this a version of Safari?
	if($.browser.safari){
		return BROWSER.SAFARI;
	}

	// Is this a version of Mozilla?
	if($.browser.mozilla){
		return BROWSER.MOZILLA
	}

	// Is this a version of Opera?
	if($.browser.opera){
		return BROWSER.OPERA;
	}
	
}

function getHeight()
{
	var browserType = getBrowserType();
	// alert(browserType);
	
	var myWidth = 0, myHeight = 0;
	if( typeof( window.innerWidth ) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		//IE 6+ in 'standards compliant mode'
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		//IE 4 compatible
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}
// 	window.alert( 'Width = ' + myWidth );
//	window.alert( 'Height = ' + myHeight );

	// alert("myHeight: " + myHeight);
	return myHeight;
}

function selectTab(tabId, height)
{
	// alert("selectTab - Parent: " + "#"+tabId);
	// alert("selectTab - Parent - height: " + height);
	var actualHeight = tabContentHeight(); 
	if (height > 0) 
		actualHeight = height;		
	// alert("selectTab - Parent - actualHeight: " + actualHeight);
	resizeIframe(tabId, actualHeight);		
	$(".devaTabs").css({"color":"#2E6E9E", "background-color":"#86B3D5", "font-weight": "normal", "padding": "2px 5px 2px 5px"});
	$("#"+tabId).css({"color":"#E17009", "background-color":"#DFEFFC", "font-weight": "bold", "padding": "2px 5px 8px 5px"});
}
