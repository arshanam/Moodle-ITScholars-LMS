/*
 * ct_table is a global DataTable for Credit Types
 */
var ct_table;
var ct_open_validation_forms = new Array();

function ct_init(){
	$("#add-creditType").button();
	$("#add-creditType").click(function() {
    	ct_openForm("#addCreditTypeForm", true, null);
    	$("#add-creditType").button("disable");
    });

    ct_loadTable();
}
/*
 * Load Credit Types table
 */
function ct_loadTable()
{
    $("#creditsTableContainer").html("");

    createLoadingDivAfter("#creditsTableContainer", "Loading Credit Types table");

    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/creditTypeManager.php',
        dataType: 'json',
        data: {
            action: 'getCreditTypes'
        },
        success: function(data){
			
			// added: jam - 08.09.2011
			if(data){
        		if(!$.isArray(data))
	        		data = [data];
				
				removeLoadingDivAfter("#creditsTableContainer");
	
				$('#creditsTableContainer').html( '<table cellpadding="0" cellspacing="0" border="0" class="display" id="creditsTable"></table>' );
	
				ct_table = $("#creditsTable").dataTable({
					"aaData": data,
					"aoColumns": [
					{  "bVisible": false },
					{ "sTitle": "Name" },
					{ "sTitle": "Resource" },
					{ "sTitle": "Course" },
					{ "sTitle": "Policy" },
					{ "sTitle": "Active" , "fnRender": function (oObj) { return oObj.aData[5]=="true" ? "Yes" : "No"; } },
					{ "sTitle": "Assignable" , "fnRender": function (oObj) { return oObj.aData[6]=="true" ? "Yes" : "No"; } }
					],
					"bJQueryUI": true,
					"bAutoWidth": false,
					"sPaginationType": "full_numbers"
				});
	
				$("#creditsTable").removeAttr("style");
	
				//Attach event handler to each row in the table
				$('#creditsTable tbody tr td').live('click', ct_rowClickHandler);
			
			}
			
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	removeLoadingDivAfter("#creditsTableContainer");
            displayError("#creditsTableContainer",errorThrown);
        }
    });
}

/*
 * Handle the click event on a row in the Credit Type table
 */
function ct_rowClickHandler(){
	var nTr = this.parentNode;
	var aData = null; 
	try{
		aData = ct_table.fnGetData( nTr );
	}catch(err){
		return;
	}
	var open = false;

	try{
		if($(nTr).next().children().first().hasClass("ui-state-highlight"))
			open=true;
	}catch(err){}

	var divId = "#creditDetails"+aData[0];

	if (open){
		/* This row is already open - close it */
		ct_table.fnClose( nTr );
		$(nTr).css("color","");
		ct_removeValidationForm(divId);
	}else{
		/* Open this row */
		ct_openDetailsRow( nTr );
	}
}

/*
 * Open Credit Type details row. Bind events for Modify, Delete, Submit and Cancel buttons
 */
function ct_openDetailsRow( nTr ){
	ct_table.fnOpen( nTr, ct_formatDetailsRow(nTr), 'ui-state-highlight' );
	var aData = ct_table.fnGetData( nTr );
	$("#modifyCreditType"+aData[0]).button();
	$("#deleteCreditType"+aData[0]).button();

	var divId = "#creditDetails"+aData[0];

	$("#modifyCreditType"+aData[0]).click(function(){
		$(nTr).css("color","#c5dbec");
		$(divId).empty();
		ct_openForm(divId, false, nTr, aData[0]);
	});

	$("#deleteCreditType"+aData[0]).click(function(){
		$(divId).empty();
		ct_delete(divId, nTr, aData[0]);

	});
}

/*
 * Return html for a Credit Type details row
 */
function ct_formatDetailsRow ( nTr )
{
	var aData = ct_table.fnGetData( nTr );
	var sOut = '';

	sOut += '<div id="creditDetails'+aData[0]+'" style="text-align:right">';
	sOut += '	<button id="modifyCreditType'+aData[0]+'">Modify</button>';
	sOut += '	<button id="deleteCreditType'+aData[0]+'">Delete</button>';
	sOut += '</div>';

	return sOut;
}

function cs_isValidForm(containerId){
	for(var i=0; i < ct_open_validation_forms.length ; i++){
		if(ct_open_validation_forms[i].container_id == containerId){
			return LiveValidation.massValidate(ct_open_validation_forms[i].form_fields);
		}
	}
	//alert("No forms in the ct_open_validation_forms array");
	return false;
}

/*
 * Open Add Credit Type form on top of the Credit Types Table
 * containerId - id for the div that will contain the form
 * add - boolean - true=add, false=edit
 */
function ct_openForm(containerId, add, nTr, creditTypeId)
{
	$(containerId).empty();
	$(containerId).hide();

    $(containerId).load("creditTypeForm.html", function(){

        $(containerId+" .submit").button();
        $(containerId+" .cancel").button();

        ct_loadResourcesCoursesPolicies(containerId, add, nTr, creditTypeId);

        ct_addFormValidation(containerId);
    });
}

function ct_addFormValidation(containerId){
	var formFields = new Array();

	var id = containerId.substring(1)+"_nameCreditType";
	$(containerId+" .nameCreditType").attr("id",id);
	var nameValidator = new LiveValidation(id,{ wait: 500 });
	nameValidator.add( Validate.Presence );
	nameValidator.add( Validate.Length, { maximum: 45 });
	formFields.push(nameValidator);

	var liveValidationForm = { container_id: containerId, form_fields: formFields };
	ct_open_validation_forms.push(liveValidationForm);
}

function ct_removeValidationForm(containerId){

	var tempArray = new Array();
	for(var i=0; i < ct_open_validation_forms.length ; i++){
		if(ct_open_validation_forms[i].container_id != containerId){
			tempArray.push(ct_open_validation_forms[i]);
		}
	}

	ct_open_validation_forms = tempArray;
}
/*
 *
 */
function ct_fillOutForm(containerId, nTr, id){

	createLoadingDivAfter(containerId, "Loading Credit Type data");
	  $.ajax({
	        type: 'POST',
	        url: '../quotasystem/server/creditTypeManager.php',
	        dataType: 'json',
	        data: {
	            action: 'getCreditType',
	            id:id
	        },
	        success: function(data){
	        	removeLoadingDivAfter(containerId);

	        	var creditType =  data.creditType;
	        	$(containerId+" .nameCreditType").val(creditType.name);
	        	$(containerId+" .resourceCreditType").val(creditType.resource);
	        	$(containerId+" .policyCreditType").val(creditType.policyId);
	        	$(containerId+" .courseCreditType").val(creditType.courseId);
	        	$(containerId+" .activeCreditType").attr('checked', creditType.active);
	        	$(containerId+" .assignableCreditType").attr('checked', creditType.assignable);

	        	$(containerId).slideDown(400);
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){
	        	removeLoadingDivAfter(containerId);
	            displayError(containerId, errorThrown, function(){
	            	$(nTr).css("color","");
	                ct_table.fnClose( nTr );
	            });
	        }
	    });

}


/*
 * Send a request to add a Credit Type in the Quota System
 */
function ct_add(containerId, name, resource, policyId,policyName, courseId,courseName, active, assignable)
{
	createLoadingDivAfter(containerId, "Creating Credit Type");

    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/creditTypeManager.php',
        dataType: 'json',
        data: {
            action: 'addCreditType',
            name:name,
            resource:resource,
            active:active,
            policyId:policyId,
            courseId:courseId,
            assignable:assignable
        },
        success: function(data){
        	removeLoadingDivAfter(containerId);
        	ct_table.fnAddData( [
        			data.id,
        			name,
        			resource,
        			courseName,
        			policyName,
        			active ? "true" : "false",
        			assignable ? "true" : "false"] );

        	displayMessage(containerId,"Credit type ["+name+"] successfully added");
        },

        error: function(XMLHttpRequest, textStatus, errorThrown){
        	removeLoadingDivAfter(containerId);
        	displayError(containerId,errorThrown);
        }
    });

}

/*
 * Send a request to modify a Credit Type in the Quota System
 */
function ct_modify(containerId, nTr, id, name, resource, policyId,policyName, courseId,courseName, active, assignable)
{
	createLoadingDivAfter(containerId, "Modifying Credit Type");

    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/creditTypeManager.php',
        dataType: 'json',
        data: {
            action: 'modifyCreditType',
            id:id,
            name:name,
            resource:resource,
            active:active,
            policyId:policyId,
            courseId:courseId,
            assignable:assignable
        },
        success: function(data){
        	removeLoadingDivAfter(containerId);
        	if(!data.success){
        		displayError(containerId,data.message, function(){
                	$(nTr).css("color","");
                    ct_table.fnClose( nTr );
                });
        	}else{
	            displayMessage(containerId,"Credit Type ["+name+"] successfully modified", function(){
	            	$(nTr).css("color","");
	                ct_table.fnClose( nTr );
	            });
	            
	        	ct_table.fnUpdate( [id,
	        	                    name,
	        	                    resource,
	        	                    courseName,
	        	                    policyName,
	        	                    active ? "true" : "false",
	        	                    assignable ? "true" : "false"],
	        	                    nTr,
	        	                    false,
	        	                    false);
        	}
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	removeLoadingDivAfter(containerId);
        	displayError(containerId,errorThrown, function(){
            	$(nTr).css("color","");
                ct_table.fnClose( nTr );
            });
        }
    });

}

/*
 * Send a request to delete a Credit Type in the Quota System
 */
function ct_delete(divId, nTr, id)
{
	createLoadingDivAfter(divId, "Deleting Credit Type");
    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/creditTypeManager.php',
        dataType: 'text',
        data: {
            action: 'deleteCreditType',
            id:id
        },
        success: function(data){
        	removeLoadingDivAfter(divId);
        	
        	if(data.success){
                displayMessage(divId,"Credit Type successfully deleted", function(){
                	ct_table.fnClose( nTr );
            		ct_table.fnDeleteRow(nTr);
	            });  
        	}else{
            	displayError(divId,data.message, function(){
            		$(nTr).css("color","");
                	ct_table.fnClose( nTr );
                });
        		
        	}


        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	removeLoadingDivAfter(divId);
        	displayError(divId,errorThrown, function(){
            	$(nTr).css("color","");
                ct_table.fnClose( nTr );
            });
        }
    });

}

/*
 * GetCreditTypeInfo
 *
 */
function ct_getCreditType(id){
	   $.ajax({
	        type: 'POST',
	        url: '../quotasystem/server/creditTypeManager.php',
	        dataType: 'json',
	        data: {
	            action: 'getCreditType',
	            id:id
	        },
	        success: function(data){
	        	return data.creditType;

	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){
	        	displayError("creditsTab div",errorThrown);
	        }
	    });

}


/*
 * Fill the select boxes (Course, Resource, Policy) in the Credit Types form
 */
function ct_loadResourcesCoursesPolicies(containerId, add, nTr, creditTypeId)
{
    var content_resources='';
    var content_courses='';
    var content_policies='';

    createLoadingDivAfter(containerId, "Loading Credit Type form");

    $(containerId).hide();
    // load resources and courses
    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/creditTypeManager.php',
        dataType: 'json',
        data: {
            action: 'getResources'
        },
        success: function(data){

            //Fill out select boxes for resources and courses
            var resources = data.resources;
            var courses = data.courses;

            for (var i in resources )
                content_resources+="<option value='"+resources[i]+"'>"+resources[i]+"</option>";

            for (var i in courses )
                content_courses+="<option value='"+courses[i].id+"'>"+courses[i].shortname+"</option>";

            $(containerId+" .resourceCreditType").empty();
            $(containerId+" .courseCreditType").empty();
            $(containerId+" .resourceCreditType").append(content_resources);
            $(containerId+" .courseCreditType").append(content_courses);
            // load policies
            $.ajax({
                type: 'POST',
                url: '../quotasystem/server/policyManager.php',
                dataType: 'json',
                data: {
                    action: 'getAssignablePolicies'
                },
                success: function(data){

                	removeLoadingDivAfter(containerId);

                    for (var p in data )
                        content_policies+="<option value='"+data[p].id+"'>"+data[p].name+" : "+data[p].type+"</option>";

                    $(containerId+" .policyCreditType").empty();
                    $(containerId+" .policyCreditType").append(content_policies);

                    //Bind events for add
                    if(add)
                    {
                    	$(containerId).addClass("ui-state-highlight");

            	        $(containerId+" .submit").click(function(){

            	        	if(cs_isValidForm(containerId)){

            	                $(containerId).slideUp(400,function(){
                	                ct_add(containerId,
                	                		$(containerId+" .nameCreditType").val(),
                	                		$(containerId+" .resourceCreditType").val(),
                	                		$(containerId+" .policyCreditType").val(),
                	                		$(containerId+" .policyCreditType option[value="+$(containerId+" .policyCreditType").val()+"]").text(),
                	                		$(containerId+" .courseCreditType").val(),
                	                		$(containerId+" .courseCreditType option[value="+$(containerId+" .courseCreditType").val()+"]").text(),
                	                		$(containerId+" .activeCreditType").is(':checked'),
                	                		$(containerId+" .assignableCreditType").is(':checked'));

            	            		$(containerId).empty();
            	            		$("#add-creditType").button("enable");
                	                ct_removeValidationForm(containerId);
            	            	});
            	            }
            	        	else{
            	        		//alert("The Create Credit Type form contained some errors.\nPlease confirm all required fields have been correctly field before saving.");
            	        	}
            	        });

            	        $(containerId+" .cancel").click(function(){

            	        	$(containerId).slideUp(400,function(){
            	        		$(containerId).empty();
            	        		$("#add-creditType").button("enable");
            	        		ct_removeValidationForm(containerId);
            	        	});

            	        });

            	        $(containerId).slideDown(400);
                    }
                    //Bind events for edit
                    else
                    {
                		  ct_fillOutForm(containerId, nTr,  creditTypeId);

                		  $(containerId+" .submit").click(function(){
              	        	var bValid = true;

              	        	if(cs_isValidForm(containerId)){

            	                $(containerId).slideUp(400,function(){
                  	                ct_modify(containerId, nTr, creditTypeId,
                  	                		$(containerId+" .nameCreditType").val(),
                  	                		$(containerId+" .resourceCreditType").val(),
                  	                		$(containerId+" .policyCreditType").val(),
                  	                		$(containerId+" .policyCreditType option[value="+$(containerId+" .policyCreditType").val()+"]").text(),
                  	                		$(containerId+" .courseCreditType").val(),
                  	                		$(containerId+" .courseCreditType option[value="+$(containerId+" .courseCreditType").val()+"]").text(),
                  	                		$(containerId+" .activeCreditType").is(':checked'),
                  	                		$(containerId+" .assignableCreditType").is(':checked')
                  	                );

            	            		$(containerId).empty();
            		                ct_removeValidationForm(containerId);
            	            	});


              	            }
              	        	else{
              	        		//alert("The Modify Credit Type form contained some errors.\nPlease confirm all required fields have been correctly field before saving.");
              	        	}
            	        });

            	        $(containerId+" .cancel").click(function(){

            	        	$(containerId).slideUp(400,function(){
                        		$(containerId).empty();
                        		$(nTr).css("color","");
            	                ct_table.fnClose( nTr );
            	                ct_removeValidationForm(containerId);
            	        	});
            	        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                	displayError(containerId,errorThrown);
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
        	displayError(containerId,errorThrown);
        }
    });



}

/*
 * check if resource is inside resourceArr
 */
function ct_resourceExists(resourceArr, resource)
{
    for(var i in resourceArr)
    {
        if(resourceArr[i].resource == resource)
            return true;
    }

    return false;
}


