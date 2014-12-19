/**************************************************************
 * Current Report
 *************************************************************/

var courseLabels = [];
var creditTypeLabels = [];
var userLabels = [];

function initCurrentReport(userId){
	current_userId = "";
	$('.sorter p input[type="radio"][value="asc"]').select();

	if(userId==""){
		$("#currentReportTab .container .byStudentSelects").hide();
		getCourses("#currentReportTab", currentCourseSelect);
		getStudents("#currentReportTab", currentStudentSelect);
		
		$('#currentReportTab .container .radios input[type="radio"]').bind("click",function(){
			var view = $('#currentReportTab .container .radios input[type="radio"]:checked').val();
			if(view=="byCourse"){
				$("#currentReportTab .container .byStudentSelects").hide();
				$("#currentReportTab .container .byCourseSelects").show();
				
				getCurrentReport("");
			}else{
				$("#currentReportTab .container .byCourseSelects").hide();
				$("#currentReportTab .container .byStudentSelects").show();
				
        		var plot = $.plot($("#currentReportTab .chart"), [], options);
        		fixControlsPosition("#currentReportTab", plot.getPlotOffset());
			}	
		});
		
		getCurrentReport("");
	}else{
		current_userId = userId;
		getCourses("#currentReportTab", currentCourseSelect);
		getCurrentReportByUser(userId);
	}
}

function getCurrentReport(courseId){
	createLoadingInId("#currentReportTab .loadingId");
	$("#currentReportTab .byCourseSelects .breadcrumb").empty();
	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getCurrentReport',
			courseId:courseId
        },
        success: function(data){
        	// SMS & Jess: 
        	// if(data){
        	if(data.success){
        	//
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaUsed = [];
	        	var quotaAvailable = [];
	        	var quotaToExpire = [];
	        	var types = ["Total Available", "Total to expire", "Total used"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	var creditTypeSelect = '<select id="currentCreditSelect"><option value="all">All</option>';
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].creditType.id;
	        		names[i] = data[i].creditType.name;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaToExpire[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        		
	        		creditTypeSelect +='<option value="'+data[i].creditType.id+'">'+data[i].creditType.name+'</option>';
	        	}
	        	creditTypeSelect += '</select>';
	        	
	        	//Breadcrumbs
	        	$("#currentReportTab .byCourseSelects .breadcrumb").html(" >> Credit Type: "+ creditTypeSelect);
	        	$("#currentReportTab #currentCreditSelect").unbind("change");
	        	$("#currentReportTab #currentCreditSelect").bind("change",function(){
	        		var ctId = $(this).val();
	        		if(ctId == "all"){
	        			var courseId = $("#currentReportTab .container .byCourseSelects .course").val();
	        			if(courseId == "all")
	        				getCurrentReport("");
	        			else
	        				getCurrentReport(courseId);
	        		}else
	        			getCurrentReportByCreditType(ctId);
	        	});
	        	var data = [quotaAvailable,quotaToExpire,quotaUsed];
	        	
	        	renderReport("#currentReportTab",ids, names, data, types, colors, 10, currentPlotCLick);
	        	
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#currentReportTab .chart"), [], options);
        		fixControlsPosition("#currentReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#currentReportTab .loadingId");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);
        	removeLoadingInId("#currentReportTab .loadingId");
        }
	});	
}

function getCurrentReportByCreditType(creditTypeId){
	createLoadingInId("#currentReportTab .loadingId");
	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getCurrentReportByCreditType',
			creditTypeId:creditTypeId
        },
        success: function(data){
        	if(data!=null){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var usernames = [];
	        	var quotaUsed = [];
	        	var quotaAvailable = [];
	        	var quotaToExpire = [];
	        	var types = ["Available", "To expire", "Used"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].user.email;
	        		usernames[i] = data[i].user.username;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaToExpire[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        	}
	        	var data = [quotaAvailable,quotaToExpire,quotaUsed];
	
	        	renderReport("#currentReportTab",ids, usernames, data, types, colors, 20, currentPlotCLick);
	        	
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#currentReportTab .chart"), [], options);
        		fixControlsPosition("#currentReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#currentReportTab .loadingId");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);        
        	removeLoadingInId("#currentReportTab .loadingId");
        }
	});	
}

function getCurrentReportByUser(userId){
	createLoadingInId("#currentReportTab .loadingId");
	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getCurrentReportByUser',
			userId:userId
        },
        success: function(data){
        	if(data){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaUsed = [];
	        	var quotaAvailable = [];
	        	var quotaToExpire = [];
	        	var types = ["Available", "To expire", "Used"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].creditType.id;
	        		names[i] = data[i].creditType.name;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaToExpire[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        	}
	        	var data = [quotaAvailable,quotaToExpire,quotaUsed];
	        	
	        	renderReport("#currentReportTab",ids, names, data, types, colors, 11, currentPlotCLick);

        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#currentReportTab .chart"), [], options);
        		fixControlsPosition("#currentReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#currentReportTab .loadingId");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);       
        	removeLoadingInId("#currentReportTab .loadingId");
        }
	});	
}

function getCurrentReportByUserAndCourse(userId, courseId){
	createLoadingInId("#currentReportTab .loadingId");
	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getCurrentReportByUserAndCourse',
			userId:userId,
			courseId:courseId
        },
        success: function(data){
        	if(data){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaUsed = [];
	        	var quotaAvailable = [];
	        	var quotaToExpire = [];
	        	var types = ["Available", "To expire", "Used"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].creditType.id;
	        		names[i] = data[i].creditType.name;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaToExpire[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        	}
	        	var data = [quotaAvailable,quotaToExpire,quotaUsed];
	        	
	        	renderReport("#currentReportTab",ids, names, data, types, colors, 11, currentPlotCLick);
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#currentReportTab .chart"), [], options);
        		fixControlsPosition("#currentReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#currentReportTab .loadingId");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);   
        	removeLoadingInId("#currentReportTab .loadingId");
        }
	});	
}

function currentCourseSelect(containerId){
	if(current_userId == ""){	
		//Attach change event to the current report courses select box 
		$(containerId+" .container .byCourseSelects .course").unbind("change");
	    $(containerId+" .container .byCourseSelects .course").bind("change", function(){
	    	var courseId = $(this).val();
	    	if(courseId=="all"){
	    		getCurrentReport("");    		
	    	}else{
	    		getCurrentReport(courseId);
	    	}
	    });
	}else{
		//Attach change event to the current report courses select box 
		$(containerId+" .container .byCourseSelects .course").unbind("change");
	    $(containerId+" .container .byCourseSelects .course").bind("change", function(){
	    	var courseId = $(this).val();
	    	if(courseId=="all"){
	    		getCurrentReportByUser(current_userId);   		
	    	}else{
	    		getCurrentReportByUserAndCourse(current_userId, courseId);
	    	}
	    });
	}
}

function currentStudentSelect(containerId, userId){
    getCurrentReportByUser(userId);
    
    $(containerId+" .container .byStudentSelects .course").unbind("change");
    $(containerId+" .container .byStudentSelects .course").bind("change",function(){
    	var courseId = $(this).val();
    	if(courseId == "all")
    		getCurrentReportByUser(userId);
    	else
    		getCurrentReportByUserAndCourse(userId, courseId);
    });
}

function currentPlotCLick(containerId, level, ids){
	/* level = 
	 *   By Course
	 *   10 -> getCurrentReport(courseId OR null)
	 *   20 -> getCurrentReportByCreditType(creditTypeId)
	 *   By Student
	 *   11 -> getCurrentReportByStudent(userId) OR getCurrentReportByStudentAndCourse(userId, courseId) 
	 * */
	$(containerId+" .chart").unbind("plotclick");
	if(level == 10){ 
		$(containerId+" .chart").bind("plotclick", function (event, pos, item) {
			if(item){
				var ctID = ids[item.datapoint[0]];
				getCurrentReportByCreditType(ctID);
				$('#currentReportTab #currentCreditSelect').val(ctID);
			}
		});
	}
}


/**************************************************************
 * Historic Report
 *************************************************************/

function initHistoricReport(userId){
	hist_creditTypeId = null;
	hist_userId = "";
	content_creditTypeSelect = "";
	$('.sorter p input[type="radio"][value="asc"]').select();
	
	if(userId == ""){
		$("#histReportTab .container .byStudentSelects").hide();
		getCourses("#histReportTab", historicCourseSelect);
		getStudents("#histReportTab", historicStudentSelect);
		
		$('#histReportTab .container .radios input[type="radio"]').bind("click",function(){
			var view = $('#histReportTab .container .radios input[type="radio"]:checked').val();
			if(view=="byCourse"){
				$("#histReportTab .container .byStudentSelects").hide();
				$("#histReportTab .container .byCourseSelects").show();
				
				getHistoricReport("");
			}else{

				$("#histReportTab .container .byCourseSelects").hide();
				$("#histReportTab .container .byStudentSelects").show();
				
				
				var plot = $.plot($("#histReportTab .chart"), [], options);
        		fixControlsPosition("#histReportTab", plot.getPlotOffset());
        		
        		$("#histReportTab .container .byStudentSelects .student").val("none");
        		$("#histReportTab .container .byStudentSelects .course").html('<option value="all">All</option>');
        		$("#histReportTab .container .byStudentSelects .breadcrumb").empty();
        		
			}	
		});
		
		getHistoricReport("");
	}else{
		hist_userId = userId;
		getCourses("#histReportTab", historicCourseSelect);
		getHistoricReportByUser(userId, "");
	}
}

function getHistoricReport(courseId){
	createLoadingInId("#histReportTab .loadingId");
	$("#histReportTab .byCourseSelects .breadcrumb").empty();	
	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getHistoricReport',
			courseId:courseId
        },
        success: function(data){
        	// SMS & Jess
        	// if(data){
            if(data.success){
            //
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaAvailable = [];
	        	var quotaUsed = [];
	        	var quotaExpired = [];
	        	var types = ["Total available", "Total used", "Total expired"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	content_creditTypeSelect = "";
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].creditType.id;
	        		names[i] = data[i].creditType.name;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaExpired[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        		content_creditTypeSelect +='<option value="'+data[i].creditType.id+'">'+data[i].creditType.name+'</option>';
	        	}
	        	var data = [quotaAvailable, quotaUsed, quotaExpired];
	        	
	        	renderReport("#histReportTab",ids, names, data, types, colors, 11, historicPlotCLick);
	        	
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#histReportTab .chart"), [], options);
        		fixControlsPosition("#histReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#histReportTab .loadingId");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown); 
        	removeLoadingInId("#histReportTab .loadingId");
        }
	});	
}

function getHistoricReportPerPeriods(creditTypeId, userId){
	createLoadingInId("#histReportTab .loadingId");

	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getHistoricReportPerPeriods',
			creditTypeId: creditTypeId,
			userId: userId
        },
        success: function(data){
        	if(data){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaAvailable = [];
	        	var quotaUsed = [];
	        	var quotaExpired = [];
	        	var types = ["Available", "Used/Being Used/Reserved", "Expired/To Expire"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];

	        	var content_periodNoSelect = '<option value="all">All</option>';
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].periodNumber;
	        		names[i] = data[i].label;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaExpired[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        		content_periodNoSelect +='<option value="'+data[i].periodNumber+'">'+data[i].label+'</option>';
	        	}
	        	
	        	var data = [quotaAvailable, quotaUsed, quotaExpired];
	        	var level = 22;
	        	if(userId!=null)
	        		level = 31;
	        	
	        	//Breadcrumbs
	        	if(userId == null){
	        		$("#histReportTab .byCourseSelects .level1").html(" >> Credit Type (Periods): <select>"+content_creditTypeSelect+"</select>");
	        		$("#histReportTab .byCourseSelects .level1 select").val(creditTypeId);
	        		$("#histReportTab .byCourseSelects .level2").html(" >> Period: <select>"+content_periodNoSelect+"</select>");
	        		
		        	$("#histReportTab .byCourseSelects .level1 select").unbind("change");
		        	$("#histReportTab .byCourseSelects .level1 select").bind("change",function(){
		        		var ctId = $(this).val();
		        		getHistoricReportPerPeriods(ctId,null);
		        	});
		        	
		        	$("#histReportTab .byCourseSelects .level2 select").unbind("change");
		        	$("#histReportTab .byCourseSelects .level2 select").bind("change",function(){
		        		var pNum = $(this).val();
		        		var ctId = $("#histReportTab .byCourseSelects .level1 select").val();
		        		hist_creditTypeId = ctId;
		        		if(pNum=="all"){
		        			getHistoricReportPerPeriods(ctId,null);
		        		}else{
		        			getHistoricReportPerUsers(ctId, pNum);
		        		}
		        		
		        	});
	        	}else{
	        		$("#histReportTab .byCourseSelects .level2 select").val(userId);
	        		$("#histReportTab .byCourseSelects .level2 select").unbind("change");
	        		$("#histReportTab .byCourseSelects .level2 select").bind("change",function(){
		        		var uId = $(this).val();
		        		var ctId = $("#histReportTab .byCourseSelects .level1 select").val();
		        		hist_creditTypeId = ctId;
		        		if(uId == "all"){
		        			getHistoricReportPerUsers(ctId, null);
		        		}else
		        			getHistoricReportPerPeriods(ctId, uId);
		        	});
	        	}
	        	
	        	renderReport("#histReportTab",ids, names, data, types, colors, level, historicPlotCLick);
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#histReportTab .chart"), [], options);
        		fixControlsPosition("#histReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#histReportTab .loadingId");

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);   
        	removeLoadingInId("#histReportTab .loadingId");

        }
	});	
}

function getHistoricReportPerUsers(creditTypeId, periodNumber){
	createLoadingInId("#histReportTab .loadingId");

	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getHistoricReportPerUsers',
			creditTypeId: creditTypeId,
			periodNumber: periodNumber
        },
        success: function(data){
        	if(data){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaAvailable = [];
	        	var quotaUsed = [];
	        	var quotaExpired = [];
	        	var types = ["Total available", "Total used", "Total expired"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	var content_userSelect = '<option value="all">All</option>';
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].user.id;
	        		names[i] = data[i].user.username;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaExpired[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        		content_userSelect +='<option value="'+data[i].user.id+'">'+data[i].user.username+'</option>';

	        	}
	        	var data = [quotaAvailable, quotaUsed, quotaExpired];
	        	var level = 21;
	        	if(periodNumber!=null)
	        		level = 32;
	        	
	        	//Breadcrumbs
	        	if(periodNumber == null){
	        		$("#histReportTab .byCourseSelects .level1").html(" >> Credit Type (Users): <select>"+content_creditTypeSelect+"</select>");
	        		$("#histReportTab .byCourseSelects .level1 select").val(creditTypeId);
	        		$("#histReportTab .byCourseSelects .level2").html(" >> User: <select>"+content_userSelect+"</select>");
	        		
		        	$("#histReportTab .byCourseSelects .level1 select").unbind("change");
		        	$("#histReportTab .byCourseSelects .level1 select").bind("change",function(){
		        		var ctId = $(this).val();
		        		hist_creditTypeId = ctId;
		        		getHistoricReportPerUsers(ctId,null);
		        	});
		        	
	        		$("#histReportTab .byCourseSelects .level2 select").unbind("change");
	        		$("#histReportTab .byCourseSelects .level2 select").bind("change",function(){
		        		var uId = $(this).val();
		        		var ctId = $("#histReportTab .byCourseSelects .level1 select").val();
		        		hist_creditTypeId = ctId;
		        		if(uId == "all"){
		        			getHistoricReportPerUsers(ctId, null);
		        		}else
		        			getHistoricReportPerPeriods(ctId, uId);
		        	});
	        	}else{
	        		$("#histReportTab .byCourseSelects .level2 select").val(periodNumber);
		        	$("#histReportTab .byCourseSelects .level2 select").unbind("change");
		        	$("#histReportTab .byCourseSelects .level2 select").bind("change",function(){
		        		var pNum = $(this).val();
		        		var ctId = $("#histReportTab .byCourseSelects .level1 select").val();
		        		hist_creditTypeId = ctId;
		        		if(pNum=="all"){
		        			getHistoricReportPerPeriods(ctId,null);
		        		}else{
		        			getHistoricReportPerUsers(ctId, pNum);
		        		}
		        		
		        	});
	        	}
	        	
	        	renderReport("#histReportTab",ids, names, data, types, colors, level, historicPlotCLick);
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#histReportTab .chart"), [], options);
        		fixControlsPosition("#histReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#histReportTab .loadingId");

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);    
        	removeLoadingInId("#histReportTab .loadingId");

        }
	});	
}

function getHistoricReportByUser(userId, courseId){
	createLoadingInId("#histReportTab .loadingId");

	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getHistoricReportByUser',
			userId:userId,
			courseId:courseId
        },
        success: function(data){
        	if(data){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaAvailable = [];
	        	var quotaUsed = [];
	        	var quotaExpired = [];
	        	var types = ["Available", "Used", "Expired"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	var creditTypesSelect = '<option value="all">All</option>';
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].creditType.id;
	        		names[i] = data[i].creditType.name;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaExpired[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        		
	        		creditTypesSelect += '<option value="'+ data[i].creditType.id +'">'+ data[i].creditType.name +'</option>';
	        	}
	        	var data = [quotaAvailable, quotaUsed, quotaExpired];
	        	
	        	//Breadcrumbs
	        	$("#histReportTab .byStudentSelects .breadcrumb").html(" >> Credit Type: <select>"+creditTypesSelect+"</select>");
	        	$("#histReportTab .byStudentSelects .breadcrumb select").unbind("change");
	        	$("#histReportTab .byStudentSelects .breadcrumb select").bind("change", function(){
	        		var ctId = $(this).val();
	        		if(ctId == "all"){
	        			var courseId = $("#histReportTab .byStudentSelects .course").val(); 
	        			if(courseId == "all"){
	        				getHistoricReportByUser(userId, "");
	        			}else{
	        				getHistoricReportByUser(userId, courseId);
	        			}
	        		}else{
	        			getHistoricReportByUserAndCreditType(userId, ctId);
	        		}
	        	});
	        	
	        	renderReport("#histReportTab",ids, names, data, types, colors, 10, historicPlotCLick);
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#histReportTab .chart"), [], options);
        		fixControlsPosition("#histReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#histReportTab .loadingId");

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);  
        	removeLoadingInId("#histReportTab .loadingId");

        }
	});	
}

function getHistoricReportByUserAndCreditType(userId, creditTypeId){
	createLoadingInId("#histReportTab .loadingId");

	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
		data: {
			action: 'getHistoricReportByUserAndCreditType',
			userId:userId,
			creditTypeId:creditTypeId
        },
        success: function(data){
        	if(data){
	        	if(!$.isArray(data))
	        		data = [data];
	        	var ids = [];
	        	var names = [];
	        	var quotaAvailable = [];
	        	var quotaUsed = [];
	        	var quotaExpired = [];
	        	var types = ["Available", "Used/Being Used/Reserved", "Expired/To Expire"];
	        	var colors = ["#afd8f8", "#edc240", "#cb4b4b"];
	        	
	        	for(var i=0 ; i<data.length ; i++){
	        		ids[i] = data[i].periodNumber;
	        		names[i] = data[i].label;
	        		quotaAvailable[i] = [i, data[i].quotaAvailable];
	        		quotaExpired[i] = [i, data[i].quotaExpired];
	        		quotaUsed[i] = [i, data[i].quotaUsed];
	        	}
	        	var data = [quotaAvailable, quotaUsed, quotaExpired];
	        	
	        	//Breadcrumbs
	        	$("#histReportTab .byStudentSelects .breadcrumb select").val(creditTypeId);
	        	
	        	renderReport("#histReportTab",ids, names, data, types, colors, 20, historicPlotCLick);
        	}else{
        		//Say it is empty somehow
        		var plot = $.plot($("#histReportTab .chart"), [], options);
        		fixControlsPosition("#histReportTab", plot.getPlotOffset());
        	}
        	removeLoadingInId("#histReportTab .loadingId");

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	//alert(errorThrown);   
        	removeLoadingInId("#histReportTab .loadingId");

        }
	});	
}

function historicCourseSelect(containerId){
	if(hist_userId == ""){	
		//Attach change event to the current report courses select box 
		$(containerId+" .container .byCourseSelects .course").unbind("change");
	    $(containerId+" .container .byCourseSelects .course").bind("change", function(){
	    	var courseId = $(this).val();
	    	if(courseId=="all"){
	    		getHistoricReport("");    		
	    	}else{
	    		getHistoricReport(courseId);
	    	}
	    });
	}else{
		//Attach change event to the current report courses select box 
		$(containerId+" .container .byCourseSelects .course").unbind("change");
	    $(containerId+" .container .byCourseSelects .course").bind("change", function(){
	    	var courseId = $(this).val();
	    	if(courseId=="all"){
	    		getHistoricReportByUser(hist_userId, "");    		
	    	}else{
	    		getHistoricReportByUser(hist_userId, courseId);
	    	}
	    });
	}
}

function historicStudentSelect(containerId, userId){
	getHistoricReportByUser(userId, "");
	
	hist_userId = userId;
    
    $(containerId+" .container .byStudentSelects .course").unbind("change");
    $(containerId+" .container .byStudentSelects .course").bind("change",function(){
    	var courseId = $(this).val();
    	if(courseId == "all")
    		getHistoricReportByUser(hist_userId, "");
    	else
    		getHistoricReportByUser(hist_userId, courseId);
    });
}

function historicPlotCLick(containerId, level, ids){
	/* level = 
	 *   By Course
	 *   11 -> getHistoricReport(courseId OR null)
	 *   21 -> getHistoricReportPerUsers(creditTypeId, null), 22 -> getHistoricReportPerPeriods(creditTypeId, null)
	 *   31 -> getHistoricReportPerPeriods(creditTypeId, userId), 32 -> getHistoricReportPerUsers(creditTypeId, periodNumber)
	 *   By Student
	 *   10 -> getHistoricReportByUser(userId, courseId OR null)
	 *   20 -> getHistoricReportByUserAndCreditType(userId, creditTypeId) 
	 * */
	$(containerId+" .chart").unbind("plotclick");
	var previousPoint = null;

	if(level == 11){
		$(containerId+" .chart").bind("plotclick", function (event, pos, item) {
			if(item){
				var ctID = ids[item.datapoint[0]];
				if (previousPoint != item.datapoint) {
					previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                   
                    showPlotSelectionBox(item.pageX, item.pageY);
                    $("#periodsButton").unbind("click");
					$("#periodsButton").bind("click", function(){
						getHistoricReportPerPeriods(ctID, null);
						hist_creditTypeId = ctID;
						$("#tooltip").remove();
		                previousPoint = null;
					});
					$("#usersButton").unbind("click");
					$("#usersButton").bind("click", function(){
						getHistoricReportPerUsers(ctID, null);
						hist_creditTypeId = ctID;
						$("#tooltip").remove();
		                previousPoint = null;
					});
				}
			}else {
				$("#tooltip").remove();
                previousPoint = null;
			}
		});
	}else if(level == 21){
		$(containerId+" .chart").bind("plotclick", function (event, pos, item) {
			if(item){
				var userId = ids[item.datapoint[0]];
				getHistoricReportPerPeriods(hist_creditTypeId, userId);
			}
		});
	}else if(level == 22){
		$(containerId+" .chart").bind("plotclick", function (event, pos, item) {
			if(item){
				var periodNumber = ids[item.datapoint[0]];
				getHistoricReportPerUsers(hist_creditTypeId, periodNumber);
			}
		});
	}else if(level == 31 || level == 32){
		//Do nothing, it is the third level By Course
	}else if(level == 10){
		$(containerId+" .chart").bind("plotclick", function (event, pos, item) {
			if(item){
				var ctID = ids[item.datapoint[0]];
				getHistoricReportByUserAndCreditType(hist_userId, ctID);
			}
		});
	}else if(level == 20){
		//Do nothing, it is the second level By User
	}
}

function showPlotSelectionBox(x, y) {

	var div = '';
	div += '<div id="tooltip">';
	div += '  <p><button id="periodsButton">Periods</button></p>';
	div += '  <p><button id="usersButton">Users</button></p>';
	div += '</div>';
	
    $(div).css( {
        top: y + 5,
        left: x - 65
    }).appendTo("body").fadeIn(200);
    
    $("#tooltip p button").button();
}

/**************************************************************
 * Plot (flot) Functions
 *************************************************************/
var options = {
	grid: {
		hoverable: true, 
		clickable: true
	},
    series: {
        stack: 0,
        lines: { show: false, steps: false },
        bars: { show: true, barWidth: 0.6 }
    },
    xaxis: {
    	min: 0,
    	max: 0,
    	ticks: [],
    	panRange: []
    },
//	    yaxis: {
//	    	min: 0,
//	    	max: 0,
//	    	panRange: []
//	    },
    pan: {
        interactive: false
    },
    colors: []
};

function renderReport(containerId, ids, names, originalData, types, colors, level, plotClickFunc){
	originalData.reverse();
	types.reverse();
	colors.reverse();
	var percentData = toPercentage(originalData);
	
	var labels = [];
	for(var i=0 ; i<names.length ; i++ )
		labels[i] = [i+0.3,names[i]];
	
	options.xaxis.min = -2;
	options.xaxis.max = 24.8;
//	options.xaxis.panRange = [-2, names.length+2];
//	options.xaxis.ticks = labels;
	
//	options.yaxis.min = 0.1;
//	options.yaxis.max = 3000;
//	options.yaxis.panRange = [0.1, 3000];
	
	$(containerId+" .sortable").empty();
	$(containerId+" .sortBy").empty();
	var list = "";
	var sortBy = '<option selected="selected" value="label">Label</option>';
	for(var i=types.length -1 ; i >= 0 ; i--){
		list += '<li style="border-left: 25px solid '+colors[i]+'">'+types[i]+'</li>';
		sortBy += '<option value="'+types[i]+'">'+types[i]+'</option>';
	}
	
	$(containerId+" .sortable").append(list);
	$(containerId+" .sortBy").append(sortBy);
	
	var plot = null;
	if($(containerId+' .checkPercentage input[type="checkbox"]').is(":checked")){
		data = "percent";
		plot = sortHandler(containerId, options, percentData, originalData, labels, ids, colors);
	}else{
		data = "original";
		plot = sortHandler(containerId, options, originalData, percentData, labels, ids, colors);
	}
	
	//Fix positioning of control elements
	fixControlsPosition(containerId, plot.getPlotOffset());
	
	plotClickFunc(containerId, level, ids);
		
    var previousHoverPoint = null;
    $(containerId + " .chart").unbind("plothover");
    $(containerId + " .chart").bind("plothover", function (event, pos, item) {
        if (item) {
            if (previousHoverPoint != item.datapoint) {
            	previousHoverPoint = item.datapoint;
                
                $("#tooltipData").remove();
                var x = item.datapoint[0].toFixed(2);
                
                //Build tooltip content
                var tooltip_content = "";
                for(var i=types.length-1 ; i>=0 ; i--){
                	if(data=="original")                	
                		tooltip_content += "<p>"+types[i]+": "+originalData[i][x/1][1]+"</p>";
                	else
                		tooltip_content += "<p>"+types[i]+": "+percentData[i][x/1][1].toFixed(2)+"%</p>";
                }
                	
                showTooltip(item.pageX, item.pageY, tooltip_content);
                
            }
        }
        else {
            $("#tooltipData").remove();
            previousHoverPoint = null;            
        }
    });


    
	
	// and add panning buttons
	$(containerId+" .panLeft").unbind("click");
	$(containerId+" .panLeft").bind("click",function(){
		plot.pan({ left: -500 });
	});
	
	$(containerId+" .panRight").unbind("click");
	$(containerId+" .panRight").bind("click", function(){
		plot.pan({ left: 500 });
	});
	
	$(containerId+" .sortable").sortable();
	$(containerId+" .sortable").disableSelection();
	$(containerId+" .sortable").unbind("sortstop");
	$(containerId+" .sortable").bind( "sortstop", function(event, ui) {
		
		var order = [];
		$(containerId+" .sortable li").each(function(index){
			order[index] = $(this).text();
		});

		order.reverse();
		var sortedData = [];
		var sortedPercentData = [];
		var sortedColors = [];

		for (i=0;i<order.length;i++){
			for(j=0; j<types.length; j++){
				if(types[j] == order[i]){
					sortedData[i] = originalData[j];
					sortedPercentData[i] = percentData[j];
					sortedColors[i] = colors[j];
				}
			}
		}
		types = order;
		originalData = sortedData;
		percentData = sortedPercentData;
		colors = sortedColors;
		
		options.colors = colors;
		options.xaxis.ticks = labels;
		plot = $.plot($(containerId+" .chart"), data=="original" ? originalData : percentData , options);
	});
	
	$(containerId+' .sorter p input[type="radio"], '+containerId+' .sorter .sortBy').unbind("change");
	$(containerId+' .sorter p input[type="radio"], '+containerId+' .sorter .sortBy').bind("change",function(){
		if(data=="original")
			plot = sortHandler(containerId, options, originalData, percentData, labels, ids, colors);
		else
			plot = sortHandler(containerId, options, percentData, originalData, labels, ids, colors);
	});
	
	$(containerId+'').unbind("change");
	$(containerId+' .checkPercentage input[type="checkbox"]').bind("click", function(){
		if($(this).is(":checked")){
			data = "percent";
			plot = sortHandler(containerId, options, percentData, originalData, labels, ids, colors);
		}else{
			data = "original";
			plot = sortHandler(containerId, options, originalData, percentData, labels, ids, colors);
		}
		fixControlsPosition(containerId, plot.getPlotOffset());
	});
}

function showTooltip(x, y, contents) {
    $('<div id="tooltipData" class="chartTooltip">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80
    }).appendTo("body").show();
}


function sortHandler(containerId, options, data1, data2, labels, ids, colors){
	var ascDesc = $(containerId+' .sorter p input[type="radio"]:checked').val();
	var sortBy = $(containerId+' .sorter .sortBy').val();
	
	var posToSort=-1;
	var tot=-1;
	$(containerId+" .sortable li").each(function(index){
		tot++;
		if(sortBy == $(this).text())
			posToSort = index;
	});
	posToSort = posToSort != -1 ? tot-posToSort : posToSort;
	
	
	sortData(data1, data2, labels, ids, posToSort, ascDesc);
	options.colors = colors;
	options.xaxis.ticks = labels;
	return $.plot($(containerId+" .chart"), data1, options);
}

function fixControlsPosition(containerId, offset){
	var width = $(containerId+" .chart").css("width");
	var height = $(containerId+" .chart").css("height");
	
	$(containerId+" .panLeft").css({
		right: parseInt(width)-offset.left+2,
		top: offset.top-2,
		height: parseInt(height)-offset.bottom-4
	});
	$(containerId+" .panRight").css({
		top: offset.top-2,
		height: parseInt(height)-offset.bottom-4
	});
	$(containerId+" .ascDescDiv").css("top",parseInt(height)+offset.top-131);
}

/**************************************************************
 * Auxiliary Functions
 *************************************************************/

//Get all courses from moodle
function getCourses(containerId, changeHandler){
	$.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
        data: {
            action: 'getAllCourses'
        },
        success: function(data){
            //Fill out select box for courses
            var courses = data.courses;
            var content_courses = '<option value="all">All</option>';
            for (var i in courses )
                content_courses+="<option value='"+courses[i].id+"'>"+courses[i].shortname+"</option>";
            
            $(containerId+" .container .byCourseSelects .course").empty();
            $(containerId+" .container .byCourseSelects .course").append(content_courses);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	alert(errorThrown);    
        }
    });
    
	changeHandler(containerId);
}

//Get all the students from moodle
function getStudents(containerId, changeHandler){
    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/reportManager.php',
        dataType: 'json',
        data: {
            action: 'getAllUsers'
        },
        success: function(data){
            //Fill out select box for courses
            var users = data.users;
            var content_users = "";
            for (var i in users )
                content_users+="<option value='"+users[i].id+"'>"+users[i].lastname+", "+users[i].firstname+"</option>";

            $(containerId+" .container .byStudentSelects .student").append(content_users);
            
            $(containerId+" .container .byStudentSelects .student").unbind("change");
            $(containerId+" .container .byStudentSelects .student").bind("change",function(){
            	var userId = $(this).val();
            	if(userId!="none"){
	            	$.ajax({
	                    type: 'POST',
	                    url: '../quotasystem/server/reportManager.php',
	                    dataType: 'json',
	                    data: {
	                        action: 'getCoursesByUser',
	                        userId: userId
	                    },
	                    success: function(data){
	                        //Fill out select box for courses
	                        var courses = data.courses;
	                        var content_courses = '<option value="all">All</option>';
	                        for (var i in courses )
	                            content_courses+="<option value='"+courses[i].id+"'>"+courses[i].shortname+"</option>";
	                        
	                        $(containerId+" .container .byStudentSelects .course").empty();
	                        $(containerId+" .container .byStudentSelects .course").append(content_courses);
	                        
	                        changeHandler(containerId, userId);
	                        
	                    },
	                    error: function(XMLHttpRequest, textStatus, errorThrown){
	                    	alert(errorThrown);    
	                    }
	                });
            	}else{
            		$(containerId+" .container .byStudentSelects .student").val("none");
            	}
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	alert(errorThrown);    
        }
    });
}

function toPercentage(dataArrays)
{
	var rv = [];
	var numArrays = dataArrays.length;
	var numElements = dataArrays[0].length;
	
	for(var i=0 ; i<numArrays ; i++)
		rv.push([]);
	
	for(var i=0 ; i<numElements ; i++){
		var tot = 0;
		for(var j=0 ; j<numArrays ; j++)
			tot += dataArrays[j][i][1];
		for(var j=0 ; j<numArrays ; j++){
			rv[j].push([]);
			var val = (dataArrays[j][i][1]/tot);
			val = isNaN(val) ? 0 : val;
			rv[j][i] = [dataArrays[j][i][0], val*100];
		}
	}
	return rv;
}

//Sort dataArrays, labels, ids depending on the sortBy param, and ascending or descending by ascDesc
function sortData(dataArray1, dataArray2, labels, ids, sortBy, ascDesc)
{
	var numArrays = dataArray1.length;
	var N = dataArray1[0].length;

	// bubble sort - not very efficient but ok for short lists
	for(var j=N-1; j > 0; j--) {
		for(var i=0; i < j; i++) {
			if(ascDesc == "desc"){
				if(sortBy==-1){
					if(labels[i][1] < labels[i+1][1])
						swap(dataArray1, dataArray2, numArrays,labels,ids,i);
				}else if(dataArray1[sortBy][i][1] < dataArray1[sortBy][i+1][1]){
					swap(dataArray1, dataArray2, numArrays,labels,ids,i);
				}
			}else{
				if(sortBy==-1){
					if(labels[i][1] > labels[i+1][1])
						swap(dataArray1, dataArray2, numArrays,labels,ids,i);
				}else if(dataArray1[sortBy][i][1] > dataArray1[sortBy][i+1][1]){
					swap(dataArray1, dataArray2, numArrays,labels,ids,i);
				}
			}
			
		}
	}
}

function swap(dataArray1, dataArray2, numArrays, labels, ids, i){
	for(var k=0 ; k<numArrays ; k++){
		var temp1 = dataArray1[k][i][1];
		dataArray1[k][i][1] = dataArray1[k][i+1][1];
		dataArray1[k][i+1][1] = temp1;
		
		var temp2 = dataArray2[k][i][1];
		dataArray2[k][i][1] = dataArray2[k][i+1][1];
		dataArray2[k][i+1][1] = temp2;
	}
	var temp = labels[i][1];
	labels[i][1] = labels[i+1][1];
	labels[i+1][1] = temp;
	var tempI = ids[i];
	ids[i] = ids[i+1];
	ids[i+1] = tempI;
}

