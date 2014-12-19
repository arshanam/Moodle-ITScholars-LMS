
$(document).ready(function() {
    reloadDevaFront();
    // Ajax activity indicator bound
    // to ajax start/stop document events
    $(document).ajaxStart(function() {
    	// Setup the ajax indicator
    	$(".container").each(function(index) {
    		if($(this).is(":visible")){
    			var id = $(this).attr("id");
    			if(id)
    			{

    				$("#"+id+" .tableTop").append('<div id="ajaxBusy"><p><img src="css/images/ajax-loader.gif">Processing</p></div>');
    				$("#"+id+" .tableTop #ajaxBusy").css({
    					display : "none"
    				});
    				$("#"+id+" .tableTop").show();
    				$("#"+id+" .tableTop #ajaxBusy").show();
    			}
    		}
    	});



    }).ajaxStop(function() {
    	$(".tableTop #ajaxBusy").remove();

    });

    
});

var BROWSER = {IE:0, CHROME:1, SAFARI:2, MOZILLA:3, OPERA:4}; 

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

function reloadDevaFront()
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

	var width = myWidth;
	var height = myHeight - 145;
	
    var role = $("#role").val();

    // $("#placeorderdiv").hide();


    // Clean tabs div to build it

    $("#tabs").html("");
    
    $("#tabs").append("<ul></ul>");
    
    // if(role=="admin"){

        $.ajax({
    		type: 'POST',
    		url: 'php/tabs.php',
    		dataType: 'json',
    		async: false,
    		data: {
    			requestingUser: $('#username').val(),
    			username: $('#username').val(),
    			course: $('#course').val(),
    			resourceType: $('#course').val()
    		},
    		success: function(vms) {
    			
    			if(vms != null) {

    				var div = "";
 /*   				
    		    	// Network Diagram Tab
    		        $("#tabs ul").append('<li><a href="#devaTab"><span>Network Diagram</span></a></li>');
    		        div  = '<div id="devaTab">';
    		        div += '  <div style="width:720px; margin-left:auto; margin-right:auto;">';
    		        div += '    <img id="fiuNetworkDiagram" src="fiu-network-diagram.gif" usemap="#Image-Map-1" border="0" width="720" height="540" alt="" class="style1" />';
    		        div += '    <map name="Image-Map-1">';
    		        div += '      <area id="area_1" shape="rect" coords="522,68,645,183" href="http://kaseya2.cis.fiu.edu/" alt="Kaseya Server" target="_blank" onmouseover="xstooltip_show(\'tooltip_1\', \'fiuNetworkDiagram\', 522, 68);" onmouseout="xstooltip_hide(\'tooltip_1\');" />';
    		        div += '      <area id="area_2" shape="rect" coords="228,338,312,427" href="#tab0" alt="'+vms.vmInfo[0].vmName+'" onmouseover="xstooltip_show(\'tooltip_2\', \'fiuNetworkDiagram\', 228, 338);" onmouseout="xstooltip_hide(\'tooltip_2\');" />';
    		        div += '      <area id="area_3" shape="rect" coords="15,321,78,403"   href="#tab1" alt="'+vms.vmInfo[1].vmName+'" onmouseover="xstooltip_show(\'tooltip_3\', \'fiuNetworkDiagram\', 15 , 321);" onmouseout="xstooltip_hide(\'tooltip_3\');" />';
    		        div += '      <area id="area_4" shape="rect" coords="464,322,524,403" href="#tab2" alt="'+vms.vmInfo[2].vmName+'" onmouseover="xstooltip_show(\'tooltip_4\', \'fiuNetworkDiagram\', 464, 322);" onmouseout="xstooltip_hide(\'tooltip_4\');" />';
    		        div += '      <area id="area_5" shape="rect" coords="646,322,706,403" href="#tab3" alt="'+vms.vmInfo[3].vmName+'" onmouseover="xstooltip_show(\'tooltip_5\', \'fiuNetworkDiagram\', 646, 322);" onmouseout="xstooltip_hide(\'tooltip_5\');" />';
    		        div += '      <area id="area_6" shape="rect" coords="645,409,705,490" href="#tab4" alt="'+vms.vmInfo[4].vmName+'" onmouseover="xstooltip_show(\'tooltip_6\', \'fiuNetworkDiagram\', 645, 409);" onmouseout="xstooltip_hide(\'tooltip_6\');" />';
    		        div += '    </map>';
    		        div += '    <!-- Image map text links - Start - If you do not wish to have text links under your image map, you can move or delete this DIV -->';
    		        div += '    <div style="text-align:center; font-size:12px; font-family:verdana; margin-left:auto; margin-right:auto; width:720px;">';
    		        div += '        <a id="link_1" style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://kaseya2.cis.fiu.edu/" target=_blank title="Kaseya Server" onmouseover="xstooltip_show(\'tooltip_1\', \'fiuNetworkDiagram\', 522, 68);" onmouseout="xstooltip_hide(\'tooltip_1\');">Kaseya Server</a>';
    		        div += '      | <a id="link_2" style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="#tab0" title="'+vms.vmInfo[0].vmName+'" onmouseover="xstooltip_show(\'tooltip_2\', \'fiuNetworkDiagram\', 228, 338);" onmouseout="xstooltip_hide(\'tooltip_2\');">'+vms.vmInfo[0].vmName+'</a>';
    		        div += '      | <a id="link_3" style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="#tab1" title="'+vms.vmInfo[1].vmName+'" onmouseover="xstooltip_show(\'tooltip_3\', \'fiuNetworkDiagram\', 15 , 321);" onmouseout="xstooltip_hide(\'tooltip_3\');">'+vms.vmInfo[1].vmName+'</a>';
    		        div += '      | <a id="link_4" style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="#tab2" title="'+vms.vmInfo[2].vmName+'" onmouseover="xstooltip_show(\'tooltip_4\', \'fiuNetworkDiagram\', 464, 322);" onmouseout="xstooltip_hide(\'tooltip_4\');">'+vms.vmInfo[2].vmName+'</a>';
    		        div += '      | <a id="link_5" style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="#tab3" title="'+vms.vmInfo[3].vmName+'" onmouseover="xstooltip_show(\'tooltip_5\', \'fiuNetworkDiagram\', 646, 322);" onmouseout="xstooltip_hide(\'tooltip_5\');">'+vms.vmInfo[3].vmName+'</a>';
    		        div += '      | <a id="link_6" style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="#tab4" title="'+vms.vmInfo[4].vmName+'" onmouseover="xstooltip_show(\'tooltip_6\', \'fiuNetworkDiagram\', 645, 409);" onmouseout="xstooltip_hide(\'tooltip_6\');">'+vms.vmInfo[4].vmName+'</a>';
    		        div += '    </div>';
    		        div += '    <!-- Image map text links - End - -->';
    		        div += '  </div>'; 
		        	div += '  <div id="tooltip_1" class="xstooltip">'; 
					div += '    Machine Name:<i> Kaseya Server</i><br/>';
					div += '    Connection Protocol:<i> RDP</i><br/>';
					div += '    Host Name:<i> kaseya2.cis.fiu.edu</i><br/>';
					div += '    Host Port:<i> 80</i><br/>';
					div += '    Username:<i> '+vms.vmInfo[0].username+'</i><br/>';
					div += '    Password:<i> '+'********'+'</i><br/>';
					div += '    Domain:<i> </i>';
					div += '  </div>'; 
					for (var i=0; i<vms.vmInfo.length; i++) {
						var vmNum = i+2;
    		        	div += '  <div id="tooltip_'+vmNum+'" class="xstooltip">'; 
						div += '    Machine Name:<i> '+vms.vmInfo[i].vmName+'</i><br/>';
						div += '    Connection Protocol:<i> RDP</i><br/>';
						div += '    Host Name:<i> '+vms.vmInfo[i].vmHostName+'</i><br/>';
						div += '    Host Port:<i> '+vms.vmInfo[i].vmHostPort+'</i><br/>';
						div += '    Username:<i> '+vms.vmInfo[i].username+'</i><br/>';
						div += '    Password:<i> '+'********'+'</i><br/>';
						div += '    Domain:<i> '+vms.vmInfo[i].domain+'</i><br/>';
						div += '  </div>'; 					
    		        }
					div += '</div>'; 
    		        $("#tabs").append(div);

    		        // Connection Information Tab
					$("#tabs ul").append('<li><a href="#infoTab"><span>Connection Information</span></a></li>');
    		        div  = '<div id="infoTab">';
    		        div += '  <table bordercellpadding="0" cellspacing="0" border="0" class="display" id="devaTable">';
					div += '  <thead>';
    		        div += '    <tr>';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        #';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Machine Name';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Connection Protocol';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Host Name';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Host Port';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Username';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Password';
					div += '      </th> ';
					div += '      <th class="ui-state-default"><span class="css_right ui-icon ui-icon-carat-2-n-s"></span>';
					div += '        Domain';
					div += '      </th> ';
					div += '    </tr>';
					div += '  </thead>';
					div += '  <tbody>';
					div += '    <tr id="row_1" class="odd">';
					div += '      <td class="center">1</td> ';
					div += '      <td class="center">Kaseya Server</td> ';
					div += '      <td class="center">http</td>';
					div += '      <td class="center"><a href="http://kaseya2.cis.fiu.edu/" target="_blank">kaseya2.cis.fiu.edu</a></td>';
					div += '      <td class="center">80</td>';
					div += '      <td class="center">'+vms.vmInfo[0].username+'</td>';
					div += '      <td class="center">'+'********'+'</td>';
					div += '      <td class="center"></td>';
					div += '    </tr>';
					for (var i=0; i<vms.vmInfo.length; i++) {
						var rowClass = "odd";
						if (i%2 == 0)
							rowClass = "even";
						var row = i + 2;
						div += '    <tr id="row_'+row+'" class="'+rowClass+'">';
						div += '      <td class="center">'+row+'</td> ';
						div += '      <td class="center">'+vms.vmInfo[i].vmName+'</td>';
						div += '      <td class="center">RDP</td>';
						div += '      <td class="center">'+vms.vmInfo[i].vmHostName+'</td>';
						div += '      <td class="center">'+vms.vmInfo[i].vmHostPort+'</td>';
						div += '      <td class="center">'+vms.vmInfo[i].username+'</td>';
						// div += '      <td>'+vms.vmInfo[i].password+'</td>';
						div += '      <td class="center">'+'********'+'</td>';
						div += '      <td class="center">'+vms.vmInfo[i].domain+'</td>';
						div += '    </tr>';	
					}
					div += '  </tbody>';
					div += '  </table>';
    		        div += '</div>';
    		        $("#tabs").append(div);
*/
    		        
					for (var i=0; i<1; i++) {
//						for (var i=0; i<vms.vmInfo.length; i++) {
						/*
    					if (browserType == BROWSER.IE) {
    						
        					$("#tabs ul").append('<li><a href="#tab'+i+'"><span>'+vms.vmInfo[i].vmName+'</span></a></li>');
        					div = '<div id="tab'+i+'">';
        					div += '<div style="width:100%; min-height:600px;">';
        					
    						div += '       <OBJECT language="javascript" ID="MsRdpClient"';
    						div += '       		CLASSID="CLSID:9059f30f-4eb1-4bd2-9fdc-36f43a218f4a"';
    						div += '       		CODEBASE="msrdp.cab#version=5,1,2600,2180"';
    						div += '       </OBJECT>';

        					div+= '</div>';
        					div+='</div>';
        					$("#tabs").append(div);

        					MsRdpClient.server = +vms.vmInfo[i].vmHostName;
        					MsRdpClient.AdvancedSettings2.RDPPort = +vms.vmInfo[i].vmHostPort;
        					MsRdpClient.UserName = +vms.vmInfo[i].username;
        					MsRdpClient.Domain = +vms.vmInfo[i].domain;
        					MsRdpClient.AdvancedSettings.ClearTextPassword = +vms.vmInfo[i].password;
        					MsRdpClient.FullScreen = false;
        					MsRdpClient.DesktopWidth = width;
        					MsRdpClient.DesktopHeight = height;
        					MsRdpClient.Width = width + 120;
        					MsRdpClient.Height = height + 90;
        					MsRdpClient.Connect();
        					
    					} else {
    						
    						*/
					
        					$("#tabs ul").append('<li><a href="#tab'+i+'"><span>'+vms.vmInfo[i].vmName+'</span></a></li>');
        					div  = '<div id="tab'+i+'">';
        					div += '  <div style="width:100%; min-height:600px;">';
        					// div += '    Computer Name: '+vms.vmInfo[i].vmName;
        					
        					div += '       <!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
        					div += '       <applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="'+height+'">';

        					div += '       <!-- Hostname or IP Address of Terminal Server -->';
        					div += '       <!-- This is a required parameter -->';
        					div += '       <param name="host" value="'+vms.vmInfo[i].vmHostName+'">';

        					div += '       <!-- Port that the Terminal Server -->';
        					div += '       <!-- This is a required parameter -->';
        					div += '       <param name="port" value="'+vms.vmInfo[i].vmHostPort+'">';

        					div += '        <!-- Username to authenticate to terminal server with -->';
        					div += '        <!-- Optional SSO Parameter -->';
        					div += '       <param name="username" value="'+vms.vmInfo[i].username+'">';

        					div += '        <!-- Password to authenticate to terminal server with -->';
        					div += '        <!-- Optional SSO Parameter -->';
        					div += '       <param name="password" value="'+vms.vmInfo[i].password+'">';

        					div += '        <!-- AD Domain name to authenticate to terminal server with -->';
        					div += '        <!-- Optional SSO Parameter -->';
        					div += '        <param name="domain" value="'+vms.vmInfo[i].domain+'">';

        					div += '        <!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
        					div += '        <!-- param name="program" value="'+vms.vmInfo[i].appName+'" -->';

        					div += '        <!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
        					div += '        <!-- param name="directory" value="'+vms.vmInfo[i].appDir+'" -->';

        					div += '        <!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

        					div += '        <!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
        					div += '        <param name="onlogout" value="javascript:rdpOnLogout();">';

        					div += '        </applet>';

        					div += '  </div>';
        					div += '</div>';
        					$("#tabs").append(div);

    					// }
    				}
    		        
					var $tabs = $('#tabs').tabs(); // first tab selected

    		        $('#area_2').click(function() { // bind click event to link
    		            $tabs.tabs('select', 2); // switch to third tab
    		            return false;
    		        });
    		        $('#area_3').click(function() { // bind click event to link
    		            $tabs.tabs('select', 3); // switch to fourth tab
    		            return false;
    		        });
    		        $('#area_4').click(function() { // bind click event to link
    		            $tabs.tabs('select', 4); // switch to fifth tab
    		            return false;
    		        });
    		        $('#area_5').click(function() { // bind click event to link
    		            $tabs.tabs('select', 5); // switch to sixth tab
    		            return false;
    		        });
    		        $('#area_6').click(function() { // bind click event to link
    		            $tabs.tabs('select', 6); // switch to seventh tab
    		            return false;
    		        });
    		        
    		        $('#link_2').click(function() { // bind click event to link
    		            $tabs.tabs('select', 2); // switch to third tab
    		            return false;
    		        });
    		        $('#link_3').click(function() { // bind click event to link
    		            $tabs.tabs('select', 3); // switch to fourth tab
    		            return false;
    		        });
    		        $('#link_4').click(function() { // bind click event to link
    		            $tabs.tabs('select', 4); // switch to fifth tab
    		            return false;
    		        });
    		        $('#link_5').click(function() { // bind click event to link
    		            $tabs.tabs('select', 5); // switch to sixth tab
    		            return false;
    		        });
    		        $('#link_6').click(function() { // bind click event to link
    		            $tabs.tabs('select', 6); // switch to seventh tab
    		            return false;
    		        });
    		        
    		        $('#row_1').click(function() { // bind click event to link
    		        	window.open("http://kaseya2.cis.fiu.edu/", "blank");
        		        return false;
    		        });
    		        $('#row_2').click(function() { // bind click event to link
    		            $tabs.tabs('select', 2); // switch to third tab
    		            return false;
    		        });
    		        $('#row_3').click(function() { // bind click event to link
    		            $tabs.tabs('select', 3); // switch to fourth tab
    		            return false;
    		        });
    		        $('#row_4').click(function() { // bind click event to link
    		            $tabs.tabs('select', 4); // switch to fifth tab
    		            return false;
    		        });
    		        $('#row_5').click(function() { // bind click event to link
    		            $tabs.tabs('select', 5); // switch to sixth tab
    		            return false;
    		        });
    		        $('#row_6').click(function() { // bind click event to link
    		            $tabs.tabs('select', 6); // switch to seventh tab
    		            return false;
    		        });
    			}
    		},
    		error: function(XMLHttpRequest, textStatus, errorThrown) {
 
    	        $("#tabs ul").append('<li><a href="#errorTab"><span>Error Tab</span></a></li>');
    	        div = '<div id="errorTab" >';
    	        div += '<div style="width:100%; min-height:600px;">';
    	        div +=  
    				'<br/>XMLHttpRequest:' + XMLHttpRequest +
    				'<br/>textStatus:' + textStatus +
    				'<br/>errorThrown:' + errorThrown ;
    	        
    	        div += '</div>';
    	        div += '</div>';
    	        $("#tabs").append(div);
    	        
    		}
    	});
    	/*
        // dc
        $("#tabs ul").append('<li><a href="#dcTab"><span>Domain Controller (dc)</span></a></li>');
        div = '<div id="dcTab">';
        div += '<div style="width:100%; min-height:600px;">';

        div += '       <!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
        div += '       <applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="600">';

        div += '       <!-- Hostname or IP Address of Terminal Server -->';
        div += '       <!-- This is a required parameter -->';
        div += '       <param name="host" value="serval.cis.fiu.edu">';

        div += '       <!-- Port that the Terminal Server -->';
        div += '       <!-- This is a required parameter -->';
        div += '       <param name="port" value="10000">';

        div += '        <!-- Username to authenticate to terminal server with -->';
        div += '        <!-- Optional SSO Parameter -->';
        div += '       <param name="username" value="Administrator">';

        div += '        <!-- Password to authenticate to terminal server with -->';
        div += '        <!-- Optional SSO Parameter -->';
        div += '       <param name="password" value="k4se*prt4l">';

        div += '        <!-- AD Domain name to authenticate to terminal server with -->';
        div += '        <!-- Optional SSO Parameter -->';
        div += '        <param name="domain" value="FIU">';

        div += '        <!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
        div += '        <!-- param name="program" value="c%3A%5Cwindows%5Csystem32%5Cnotepad.exe" -->';

        div += '        <!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
        div += '        <!-- param name="directory" value="c%3A%5Cwindows%5Csystem32%5C" -->';

        div += '        <!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

        div += '        <!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
        div += '        <param name="onlogout" value="javascript:rdpOnLogout();">';

        div += '        </applet>';
        div+= '</div>';
        div+='</div>';
        $("#tabs").append(div);

        // xp-1
        $("#tabs ul").append('<li><a href="#xp1Tab"><span>Workstation 1 (ws1)</span></a></li>');
        div = '<div id="xp1Tab" >';
        div += '<div style="width:100%; min-height:600px;">';
        div+= '       <!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
        div += '       <applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="600">';

        div+= '       <!-- Hostname or IP Address of Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="host" value="serval.cis.fiu.edu">';

        div+= '       <!-- Port that the Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="port" value="10001">';

        div+= '        <!-- Username to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="username" value="Administrator">';

        div+= '        <!-- Password to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="password" value="k4se*prt4l">';

        div+= '        <!-- AD Domain name to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '        <param name="domain" value="FIU">';

        div+= '        <!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
        div+= '        <!-- param name="program" value="c%3A%5Cwindows%5Csystem32%5Cnotepad.exe" -->';

        div+= '        <!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
        div+= '        <!-- param name="directory" value="c%3A%5Cwindows%5Csystem32%5C" -->';

        div+= '        <!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

        div+= '        <!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
        div+= '        <param name="onlogout" value="javascript:rdpOnLogout();">';

        div+= '        </applet>';
        div += '</div>';
        div+='</div>';
        $("#tabs").append(div);

        // xp-2
        $("#tabs ul").append('<li><a href="#xp2Tab"><span>Guest 1 (guest1)</span></a></li>');
        div = '<div id="xp2Tab" >';
        div += '<div style="width:100%; min-height:600px;">';
        div+= '       <!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
        div += '       <applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="600">';

        div+= '       <!-- Hostname or IP Address of Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="host" value="serval.cis.fiu.edu">';

        div+= '       <!-- Port that the Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="port" value="10002">';

        div+= '        <!-- Username to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="username" value="Administrator">';

        div+= '        <!-- Password to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="password" value="k4se*prt4l">';

        div+= '        <!-- AD Domain name to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '        <param name="domain" value="FIU">';

        div+= '        <!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
        div+= '        <!-- param name="program" value="c%3A%5Cwindows%5Csystem32%5Cnotepad.exe" -->';

        div+= '        <!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
        div+= '        <!-- param name="directory" value="c%3A%5Cwindows%5Csystem32%5C" -->';

        div+= '        <!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

        div+= '        <!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
        div+= '        <param name="onlogout" value="javascript:rdpOnLogout();">';

        div+= '        </applet>';
        div += '</div>';
        div+='</div>';
        $("#tabs").append(div);

        // xp-3
        $("#tabs ul").append('<li><a href="#xp3Tab"><span>PC 1 (pc1)</span></a></li>');
        div = '<div id="xp3Tab" >';
        div += '<div style="width:100%; min-height:600px;">';
        div+= '       <!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
        div += '       <applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="600">';

        div+= '       <!-- Hostname or IP Address of Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="host" value="serval.cis.fiu.edu">';

        div+= '       <!-- Port that the Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="port" value="10003">';

        div+= '        <!-- Username to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="username" value="Administrator">';

        div+= '        <!-- Password to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="password" value="k4se*prt4l">';

        div+= '        <!-- AD Domain name to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '        <param name="domain" value="FIU">';

        div+= '        <!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
        div+= '        <!-- param name="program" value="c%3A%5Cwindows%5Csystem32%5Cnotepad.exe" -->';

        div+= '        <!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
        div+= '        <!-- param name="directory" value="c%3A%5Cwindows%5Csystem32%5C" -->';

        div+= '        <!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

        div+= '        <!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
        div+= '        <param name="onlogout" value="javascript:rdpOnLogout();">';

        div+= '        </applet>';
        div += '</div>';
        div+='</div>';
        $("#tabs").append(div);

        // xp-4
        $("#tabs ul").append('<li><a href="#xp4Tab"><span>Laptop 1 (laptop1)</span></a></li>');
        div = '<div id="xp4Tab" >';
        div += '<div style="width:100%; min-height:600px;">';
        div+= '       <!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
        div += '       <applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="600">';

        div+= '       <!-- Hostname or IP Address of Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="host" value="serval.cis.fiu.edu">';

        div+= '       <!-- Port that the Terminal Server -->';
        div+= '       <!-- This is a required parameter -->';
        div+= '       <param name="port" value="10004">';

        div+= '        <!-- Username to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="username" value="Administrator">';

        div+= '        <!-- Password to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '       <param name="password" value="k4se*prt4l">';

        div+= '        <!-- AD Domain name to authenticate to terminal server with -->';
        div+= '        <!-- Optional SSO Parameter -->';
        div+= '        <param name="domain" value="FIU">';

        div+= '        <!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
        div+= '        <!-- param name="program" value="c%3A%5Cwindows%5Csystem32%5Cnotepad.exe" -->';

        div+= '        <!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
        div+= '        <!-- param name="directory" value="c%3A%5Cwindows%5Csystem32%5C" -->';

        div+= '        <!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

        div+= '        <!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
        div+= '        <param name="onlogout" value="javascript:rdpOnLogout();">';

        div+= '        </applet>';
        div += '</div>';
        div+='</div>';
        $("#tabs").append(div);

        */
    // }else{

    // }

    //Show tabs
    $('#tabs').tabs();
    
    
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

