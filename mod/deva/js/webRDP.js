
$(document).ready(function() {
	
	parent.selectTab($('#tab').val());
	getVMInsInfo();
    
});

function getVMInsInfo() {
	
	var vmWasDisplayed = false;
	
	// Clean wrapper div to build it
	$("#wrapper").html("");
	
	$.ajax({
		type: 'POST',
		url: 'php/virtuallabs-wscalls.php',
		dataType: 'json',
		async: false,
		data: {
			action: 'getVMInsInfo',
			id: $('#vmInsId').val(),
		},
		success: function(vm) {

			if(vm != null) {
				if (vm.success) {
					if (vm.vmInfo.status == "RUNNING") {
						vmWasDisplayed = true;
						var div = "";
						div += '<!-- Loads the applet and utilizes 100% of browser window width and height.  Width and height could be hard coded to specific values -->';
						div += '<applet name="rdp" code="com.webinflection.webrdp.MainApplet" archive="./webRDP.jar" width="100%" height="'+$("#bottomFrameHeightPercentage").val()+'%">';

						div += '<!-- Hostname or IP Address of Terminal Server -->';
						div += '<!-- This is a required parameter -->';
						div += '<param name="host" value="'+vm.vmInfo.accessAddress+'">';

						div += '<!-- Port that the Terminal Server -->';
						div += '<!-- This is a required parameter -->';
						div += '<param name="port" value="'+vm.vmInfo.accessPort+'">';

						div += '<!-- Username to authenticate to terminal server with -->';
						div += '<!-- Optional SSO Parameter -->';
						div += '<param name="username" value="'+vm.vmInfo.username+'">';

						div += '<!-- Password to authenticate to terminal server with -->';
						div += '<!-- Optional SSO Parameter -->';
						div += '<param name="password" value="'+vm.vmInfo.password+'">';

						div += '<!-- AD Domain name to authenticate to terminal server with -->';
						div += '<!-- Optional SSO Parameter -->';
						div += '<param name="domain" value="'+vm.vmInfo.domain+'">';

						div += '<!-- Application to start. This value should be url encoded.  In this example we are launching c:\windows\system32\notepad.exe-->';
						div += '<!-- param name="program" value="'+vm.vmInfo.appName+'" -->';

						div += '<!-- Working directory for Application.  This value should be url encoded.  In this example the working directory will be set to c:\windows\system32\ -->';
						div += '<!-- param name="directory" value="'+vm.vmInfo.appDir+'" -->';

						div += '<!-- In this example I used a site ( http://meyerweb.com/eric/tools/dencoder/ ) to encode the above values -->';

						div += '<!-- This specifies a javascript method to be called after the user logs out of the RDP session -->';
						div += '<param name="onlogout" value="javascript:rdpOnLogout();">';

						div += '</applet>';

						$("#wrapper").append(div);
					} else {
						alert(vm.vmInfo.name + " is not ready yet. Plesae try again in 30 seconds. Sorry for the inconvenience!");
					}
				
				} else {
					// alert(vms.reason);
				}
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert("The server may be down! Please contact sadjadi@cs.fiu.edu");
		}
	});
	
	return vmWasDisplayed;
}

