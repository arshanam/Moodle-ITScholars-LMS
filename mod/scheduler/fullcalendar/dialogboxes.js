

    //Initialization

    $(document).ready(function() {

        //$("#create_appointment").click(function(){
        //  recurringEventDialogBox();
        //});

    });

    



//------------------ Dialog Boxes ------------------------------

function editDialogBox(event,view){
    
    var dialogContent = $("#edit-event-dialog").load('fullcalendar/edit_event.html',function() {
                                                                                             
        var dayformatter = "mm/dd/yyyy";    // mmmm d, yyyy
        var timeformatter = "h:MM TT";      // h:MM:ss TT                                                                               
            
        //$("#edit-event-dialog #startDate").blur();
        //$("#edit-event-dialog #startDate").datepicker();
        //$("#edit-event-dialog #endDate").datepicker();
        
        $("#edit-event-dialog #startDate").datepicker(
        // Commented by SMS: 8/7/2011
        // To address the problem when selecting a choice, it would not select it.
        /*
        {
            minDate: new Date(),
            onClose: function(dateText, inst) {
                $("#edit-event-dialog #endDate").datepicker( "option" ,'minDate', dateText );
            }
        }
        */
        );
        $("#edit-event-dialog #endDate").datepicker(
        // {minDate: event.start.format(dayformatter)}
        );
        
        
        $("#edit-event-dialog #start").ptTimeSelect(
        // Commented by SMS: 8/7/2011
        // To address the problem when selecting a choice, it would not select it.
        /*
        {onClose: function(){
                                                                       
            if(event.resourceType == "certificate"){
       
                var newstart = new Date(startDate.val() + " " + startField.val());
                event.start = newstart;
                correctCertificateEvent(event);
                $(endField).val(event.end.format(timeformatter));
            }
        
        }}
        */
        ); 
        
        $("#edit-event-dialog #end").ptTimeSelect(); 
        
        $("#edit-event-dialog #startDate").focus(function() {
            $("#ptTimeSelectCntr").hide();
        });
        $("#edit-event-dialog #start").focus(function() {
            $("#edit-event-dialog #endDate").datepicker('hide');
            $("#edit-event-dialog #startDate").datepicker('hide');
        });
        $("#edit-event-dialog #endDate").focus(function() {
            $("#ptTimeSelectCntr").hide();
        });
        $("#edit-event-dialog #end").focus(function() {
            $("#edit-event-dialog #startDate").datepicker('hide');
            $("#edit-event-dialog #endDate").datepicker('hide');
        });
            
        var customLabel;
        if(is_admin_user){
            customLabel = "Host";
        }else{
            customLabel = "Course";
        }
        
        if (jQuery.browser.msie) {
            document.getElementById("customddm").innerHTML = customLabel;
        }else{
            $("#edit-event-dialog #customddm").text(customLabel);
        }
        
        resetForm(this);
        
        var typeFieldOptions = "";
        var courseFieldOptions = "";
        //var timezoneFieldOptions = "";
        
        for(var i = 0; i<types.length; i++){
            typeFieldOptions +=  "<option>"+types[i]+"</option>";
        }
        
        for(var i = 0; i<courses.length; i++){
            courseFieldOptions +=  "<option>"+courses[i]+"</option>";
        }
        
        
        /*
        for(var i = 0; i<zones.length; i++){
            timezoneFieldOptions += "<option>"+zones[i]+"</option>";
        }
        */
        //if (jQuery.browser.msie) { alert("TEST: edit appointment form 1"); }
        
        var startDate = $(this).find("input[name='startDate']");
        var endDate = $(this).find("input[name='endDate']");
        var startField = $(this).find("input[name='start']");   //.val(event.start.format(timeformatter));
        var endField = $(this).find("input[name='end']");       //.val(event.end.format(timeformatter));
        var typeField = $(this).find("select[name='type']").html(typeFieldOptions);
        var courseField = $(this).find("select[name='course']").html(courseFieldOptions);
        //var timezoneField = $(this).find("select[name='timezone']").html(timezoneFieldOptions);
        
        var startNow = $(this).find("input[name='startNow']");
        
        //if (jQuery.browser.msie) { alert("TEST: edit appointment form 2"); }
        if(!event.start)
            startDate.val(event.start.format(dayformatter));
        endDate.val(event.end.format(dayformatter));
        if(event.start)
            startField.val(event.start.format(timeformatter));
        endField.val(event.end.format(timeformatter));
        
        typeField.val((event.resourceType).toUpperCase());
        courseField.val(event.course);
        
        // Warns if the appointment spans more than 24 hours
        /*
        var spanValue = "<span id='days_between' style='display:none'>" + days_between(event.start, event.end) + "</span>";
        if (jQuery.browser.msie) {
            var sParent = document.getElementById("endDate").parentNode;
            //alert(sParent.innerHTML);
            sParent.innerHTML += spanValue; 
        }else{
            $(endDate).append(spanValue);
        }*/
        
        
        // Enables and Disable the input fields based on the start date and startnow option, or if a certificate event
        // commented by SMS: 8/7/2011
        // No need for this in the JavaScript as the actions are fixed in the Web Service
        /*
        if(event.resourceType == "certificate"){
            
            //alert("certificate");
            
            $("input[name='endDate']").attr('disabled', 'disabled');
            $("input[name='end']").attr('disabled', 'disabled');
            
            if (jQuery.browser.msie) {
                    document.getElementById(this.id).checked = false;
                    document.getElementById(this.id).disabled = true;
            }else{
                $(startNow).attr('disabled', 'disabled');
                $(startNow).attr('checked','');
            }
        
        
        }else{
            */
            //alert("not certificate");
            
            var today = new Date();
            if(today > event.start){
                $("input[name='startDate']").attr('disabled', 'disabled');
                $("input[name='start']").attr('disabled', 'disabled');
                
                if (jQuery.browser.msie) {
                        document.getElementById(this.id).checked = true;
                        document.getElementById(this.id).disabled = true;
                }else{
                    $(startNow).attr('disabled', 'disabled');
                    $(startNow).attr('checked','checked');
                }
                
                startDate.val("");
                startField.val("");
                
            }else{
                /*
                $("#startDate, #endDate, #start, #end").change(function(){
                
                    var start = new Date($("input[name='startDate']").val() + " " + $("input[name='start']").val());
                    var end = new Date($("input[name='endDate']").val() + " " + $("input[name='end']").val());
                    
                    $("#days_between").html(days_between(start, end));
                                    
                });
                */
            
                $(startNow).change(function() {
                    
                    var isChecked = false;
                    
                    if (jQuery.browser.msie) {
                        isChecked = document.getElementById(this.id).checked;
                    }else{
                        isChecked = $(this).attr('checked');
                    }
                    
                    if(!isChecked){
                        $("input[name='startDate']").attr('disabled', '');
                        $("input[name='start']").attr('disabled', '');
                        
                        $("input[name='startDate']").val(event.start.format(dayformatter));
                        
                        $("#edit-event-dialog #endDate").datepicker( "option" ,'minDate', event.start.format(dayformatter));
                        
                    }else{
                        $("input[name='startDate']").attr('disabled', 'disabled');
                        $("input[name='start']").attr('disabled', 'disabled');
        
                        $("input[name='startDate']").val("");
                        $("input[name='start']").val("");
                        
                        $("#edit-event-dialog #endDate").datepicker( "option" ,'minDate', new Date());
                    }
                    
                    
                });
                
            }
        // }
        
        $(this).find("button").focus();
    
    });
    
    
    
    if(event.type == "scheduled"){
        //alert(event.type);
        
        $(dialogContent).dialog({
            autoOpen: false,
            width: 340,
            height: 350,
            modal: true,
            title: "Edit - " + event.title,
            close: function() {
                $("#edit-event-dialog #endDate").datepicker("destroy");
                $("#edit-event-dialog #startDate").datepicker("destroy");
                $(this).dialog("destroy");
                $(this).hide();
            },
            buttons: {
                "confirm new schedule" : function() {
                    var header = "Edit Appointment: Invalid Date";
                    var message = "";
                    var icon = "alert";
                    // Retrieve Form Objects
                    var startDate = $(dialogContent).find("input[name='startDate']");
                    var endDate = $(dialogContent).find("input[name='endDate']");
                    var startField = $(dialogContent).find("input[name='start']");
                    var endField = $(dialogContent).find("input[name='end']");
                    
                    var today = new Date();
                    var startNow = null;
                    
                    //event.title = typeField.val();
                    var start = new Date(startDate.val() + " " + startField.val());
                    var end = new Date(endDate.val() + " " + endField.val());
                    
                    if(today > event.start){
                        startNow = $(dialogContent).find("input[name='startNow']");
                        start = event.start;
                    }
                    
                    if(checkStartEndFields(startDate, startField, endDate, endField, startNow)){
                    
                        if(end>start){
                            $(this).dialog('close');
                            modifyAppointment(event, start, end, false, false);
                            //$(this).dialog("close");
                            
                        }else if(end.toString() == start.toString()){
                            // If dates are exactly the same alter
                            
                            /*
                            if(start.getTime() != event.start.getTime() || end.getTime() != event.end.getTime()){
                                
                                if(end.getTime() < start.getTime()){
                                    end.setDate(end.getDate()+1);  // changes month automatically
                                }else if(start.getTime() == end.getTime()){
                                    end.setHours(end.getHours()+1);
                                }else{
                                    
                                }
                            }
                            */
                            
                            message = "The appointment start time cannot be the same as the appointment end time.1";
                            noticeDialog(header, message, icon);
                            
                        }else{
                        
                            message = "The appointment end date cannot be before the appointment start date.1";
                            noticeDialog(header, message, icon);
                        }
                    }
                    
                },
                "cancel schedule" : function() {
                    $(this).dialog('close');
                    deleteDialogBox(event,dialogContent);
                },
                close : function() {
                    $(this).dialog("close");
                }
            }
        });

    }else{      // Available Events
    
        //alert(event.type);
    
        $(dialogContent).dialog({
            autoOpen: false,
            width: 340,
            height: 350,
            modal: true,
            title: "Edit - " + event.title,
            close: function() {
               $(this).dialog("destroy");
               $(this).hide();
            },
            buttons: {
                "confirm schedule" : function() {
                    var header = "Edit Appointment: Invalid Date";
                    var message = "";
                    var icon = "alert";
                    // Retrieve Form Objects
                    var startDate = $(dialogContent).find("input[name='startDate']");
                    var endDate = $(dialogContent).find("input[name='endDate']");
                    var startField = $(dialogContent).find("input[name='start']");
                    var endField = $(dialogContent).find("input[name='end']");
                    var startNow = $(dialogContent).find("input[name='startNow']");
                    
                    //event.title = typeField.val();
                    var start = new Date(startDate.val() + " " + startField.val());
                    var end = new Date(endDate.val() + " " + endField.val());
                    
                    if(checkStartEndFields(startDate, startField, endDate, endField, startNow)){
                        
                        var today = new Date();
                        
                        if(end>start || end>today){
                            $(this).dialog('close');
                            
                            var newevent = getCreateNewEventObj("#edit-event-dialog");
                        
                            //event.start = start;
                            //event.end = end;
                            //alert(printEvent(event));
                            
                            scheduleAppointment(newevent, view);
                            //modifyAppointment(event, start, end);
                            //$(this).dialog("close");
                            
                        }else if(end.toString() == start.toString()){
                        
                            message = "The appointment start time cannot be the same as the appointment end time.2";
                            noticeDialog(header, message, icon);
                            
                        }else{
                        
                            message = "The appointment end date cannot be before the appointment start date.2";
                            noticeDialog(header, message, icon);
                        }
                    }else{
                        //alert("You are here.");   
                    }
                    
                },
                close : function() {
                    $(this).dialog("close");
                }
            }
        });

    
    }

    $(dialogContent).dialog('open');
    
}

function createDialogBox(newDate,view, starttime){
    

    var createDialog =  $("#create-event-dialog").load('fullcalendar/create_event.html',function() {
        
        
        //$("#create-event-dialog #startDate").datepicker();
        //$("#create-event-dialog #endDate").datepicker();
        
        $("#create-event-dialog #startDate").datepicker(
        // Commented by SMS: 8/7/2011
        // To address the problem when selecting a choice, it would not select it.
        /*
        {
            minDate: new Date(),
            onClose: function(dateText, inst) {
                $("#create-event-dialog #endDate").datepicker( "option" ,'minDate', dateText);
            }
        }
        */
        );
        $("#create-event-dialog #endDate").datepicker(
        // Commented by SMS: 8/7/2011
        // To address the problem when selecting a choice, it would not select it.
        // {minDate: newDate}
        );
        
        
        
        $("#create-event-dialog #start").ptTimeSelect(); 
        $("#create-event-dialog #end").ptTimeSelect(); 
        
        $("#create-event-dialog #startDate").focus(function() {
            $("#ptTimeSelectCntr").hide();
        });
        $("#create-event-dialog #start").focus(function() {
            $("#create-event-dialog #endDate").datepicker('hide');
            $("#create-event-dialog #startDate").datepicker('hide');
        });
        $("#create-event-dialog #endDate").focus(function() {
            $("#ptTimeSelectCntr").hide();
        });
        $("#create-event-dialog #end").focus(function() {
            $("#create-event-dialog #startDate").datepicker('hide');
            $("#create-event-dialog #endDate").datepicker('hide');
        });
        $("#create-event-dialog #type").focus(function() {
            $("#create-event-dialog #startDate").datepicker('hide');
            $("#create-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
        });
        $("#create-event-dialog #course").focus(function() {
            $("#create-event-dialog #startDate").datepicker('hide');
            $("#create-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
        });
        
        var customLabel;
        if(is_admin_user){
            customLabel = "Host";
        }else{
            customLabel = "Course";
        }
        
        if (jQuery.browser.msie) {
            document.getElementById("customddm").innerHTML = customLabel;
        }else{
            $("#create-event-dialog #customddm").text(customLabel);
        }
        resetForm(createDialog);
        
        var dayformatter = "mm/dd/yyyy";    // mmmm d, yyyy 
        var timeformatter = "h:MM TT";      // h:MM:ss TT 
        var typeFieldOptions = "";
        var courseFieldOptions = "";
        //var timezoneFieldOptions = "";
        
        // Load types in the select box, and select current type
        for(var i = 0; i<types.length; i++){
            
            if(avail_resource_listing.length > 0){
                var type = types[i];
                var added = false;
                
                //alert('RESOURCE: '+type);
                for(var j = 0; j <avail_resource_listing.length; j++){
                    if(!added){
                        var newtype = type.replace(/ /g, "-").toLowerCase();
                        //alert('avail_resource_listing: \n'+avail_resource_listing[j]+' \n newtype: \n'+newtype);
                        
                        if(newtype == avail_resource_listing[j]){
                            //alert('MATCH: '+newtype);
                            typeFieldOptions +=  "<option>"+types[i]+"</option>";
                            added = true;
                        }
                    }
                }
            }else{
                typeFieldOptions +=  "<option>"+types[i]+"</option>";
            }
        }
        
        for(var i = 0; i<courses.length; i++){
            
            if(avail_course_listing.length >0){
                var course = courses[i];
                var added = false;
                
                //alert('COURSE: '+course);
                for(var j = 0; j <avail_course_listing.length; j++){
                    
                    if(!added){
                        var newcoursename = course.replace(/ /g, "-").toLowerCase();
                        newcoursename = newcoursename.replace(/\./g, "").toLowerCase();
                        //console.log('avail_course_listing: \n'+avail_course_listing[j]+' \n newcoursename: \n'+newcoursename);
                        
                        if(newcoursename == avail_course_listing[j]){
                            //alert('MATCH: '+newcoursename);
                            courseFieldOptions +=  "<option>"+courses[i]+"</option>";
                            added = true;
                        }
                    }
                }
            }else{
                courseFieldOptions +=  "<option>"+courses[i]+"</option>";
            }
        }
        
        /*
        for(var i = 0; i<zones.length; i++){
            timezoneFieldOptions += "<option>"+zones[i]+"</option>";
        }
        */
        var startDate = $(createDialog).find("input[name='startDate']");
        var endDate = $(createDialog).find("input[name='endDate']");
        var startField = (starttime) ? $(createDialog).find("input[name='start']").val(newDate.format(timeformatter)) : $(createDialog).find("input[name='start']");
        var endField = $(createDialog).find("input[name='end']");
        var typeField = $(createDialog).find("select[name='type']").html(typeFieldOptions);
        var courseField = $(createDialog).find("select[name='course']").html(courseFieldOptions);
        //var timezoneField = $(createDialog).find("select[name='timezone']").html(timezoneFieldOptions);
        var startNow = $(createDialog).find("input[name='startNow']");
    
        startDate.val(newDate.format(dayformatter));
        endDate.val(newDate.format(dayformatter));
        
    /*  
        var start = new Date(startDate.val());
        var today = new Date();
        if(start.format(dayformatter) == today.format(dayformatter)){
            $(startNow).attr('disabled','');
            //alert("IS: "+start);
        }else{
            $(startNow).attr('disabled','disabled');
            //alert("Not: "+start);
        }
    
        $(startDate).change(function() {
                                     
            var start = new Date($(this).val());
            var today = new Date();
            
            if(start.format(dayformatter) == today.format(dayformatter)){
                $(startNow).attr('disabled','');
                if(start.format(dayformatter) != ""){
                    $(endDate).val(start.format(dayformatter));
                }
            }else{
                $(startNow).attr('checked','');
                $(startNow).attr('disabled','disabled');
            }
        });
    */
    
        $(startNow).change(function() {
            var isChecked = $(startNow).attr('checked');
            var today = new Date();
            
            if(isChecked){
                $(startDate).attr('disabled','disabled');
                $(startField).attr('disabled','disabled');
                
                startDate.val("");
                startField.val("");
                
                $("#create-event-dialog #endDate").datepicker( "option" ,'minDate', new Date());
                
            }else{
                $(startDate).attr('disabled','');
                $(startField).attr('disabled','');
                
                startDate.val(newDate.format(dayformatter));
                startField.val(today.format(timeformatter));
                
                $("#create-event-dialog #endDate").datepicker( "option" ,'minDate', newDate.format(dayformatter));
            }   
        });
    
    });

    $(createDialog).dialog({
        autoOpen: false,
        width: 340,
        modal: true,
        title: "Create New Calendar Event",
        close: function() {
           $(createDialog).dialog("destroy");
           $(createDialog).hide();
           //('#calendar').fullCalendar("removeUnsavedEvents");
        },
        buttons: {
            "create" : function() {
                
                var startDate = $(createDialog).find("input[name='startDate']");
                var endDate = $(createDialog).find("input[name='endDate']");
                var startField = $(createDialog).find("input[name='start']");
                var endField = $(createDialog).find("input[name='end']");
                var startNow = $(createDialog).find("input[name='startNow']");
                
                if(checkStartEndFields(startDate, startField, endDate, endField, startNow)){
                
                    var newevent = getCreateNewEventObj("#create-event-dialog");
                    
                    $(createDialog).dialog("close");
                    //performAction(newevent, "create", view);
                    if(scheduleAppointment(newevent, view)){
                    
                    }
                    
                    /*
                    if(scheduleAppointment(newevent,view)){
                        $('#calendar').fullCalendar('newEvent',newevent);
                    }
                        */                                    
                    //$(createDialog).dialog("close");
                }
            //},
            /*
            'confirm': function(){
                
                var newevent = getCreateNewEventObj("#create-event-dialog");
                confirmEventEdit(newevent);
            */
            },
            close : function() {
                $(createDialog).dialog("close");
            }
        }
    });
    
    $(createDialog).dialog('open');

}


// Delete Event Dialog, takes event and initial dialog box to close on delete button click.
function deleteDialogBox(event, dialogBox, recurring){

    //alert('deleteDialogBox recurring: '+recurring);

    if(recurring){
        
        var deleteContent = $("<div id='delete-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This event will be permanently deleted and cannot be recovered. Are you sure?</p>');
                    
        $(deleteContent).dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            title: "Delete Recurring - " + event.title,
            modal: true,
            close: function() {
               $(deleteContent).dialog("destroy");
               $(deleteContent).hide();
            },
            buttons: {
                'cancel all occurrences': function() {
                    //$('#calendar').fullCalendar("removeEvents", event.id);
                    $(this).dialog('close');
                    deleteRecurringDialogBox(event,deleteContent,true);
                    //$(this).dialog('close');
                    if(dialogBox){
                        $(dialogBox).dialog("close");
                    }
                },
                'cancel this occurrence': function() {
                    //$('#calendar').fullCalendar("removeEvents", event.id);
                    $(this).dialog('close');
                    deleteRecurringDialogBox(event,deleteContent,false);
                    //$(this).dialog('close');
                    if(dialogBox){
                        $(dialogBox).dialog("close");
                    }
                },
                close: function() {
                    $(this).dialog('close');
                }
            }
        });
        
        $(deleteContent).dialog('open');
        
    }else{

        var deleteContent = $("<div id='delete-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This event will be permanently deleted and cannot be recovered. Are you sure?</p>');
                    
        $(deleteContent).dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            title: "Delete - " + event.title,
            modal: true,
            close: function() {
               $(deleteContent).dialog("destroy");
               $(deleteContent).hide();
            },
            buttons: {
                'delete schedule': function() {
                    //$('#calendar').fullCalendar("removeEvents", event.id);
                    $(this).dialog('close');
                    cancelAppointment(event,false);
                    //$(this).dialog('close');
                    if(dialogBox){
                        $(dialogBox).dialog("close");
                    }
                },
                close: function() {
                    $(this).dialog('close');
                }
            }
        });
        
        $(deleteContent).dialog('open');
                
    }

}

//function confirmDialogBox(event, actionName, view){
function confirmDialogBox(event, view){
    
    var today = new Date();
    var starttime = (today > event.start) ? "<b>NOW</b>" : event.start;
    
    var message = "Would you like to confirm the appointment: <br/>" +
                   "From: "+ starttime  + "<br/>" +
                   "To: "+event.end + "<br/><br/>" +
                   "Course: "+event.course;

    var confirmContent = $("<div id='confirm-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>');
                
    $(confirmContent).dialog({
        autoOpen: false,
        resizable: false,
        width: 360,
        title: "Confirm - " + event.title,
        modal: true,
        close: function() {
           $(this).dialog("destroy");
           $(this).hide();
        },
        buttons: {
            "confirm new schedule": function() {
                // Set new appoinment Call
                //performAction(event, actionName, view);
                
                // alter event start so if the start time is in the past, it will begin instantly.
                var today = new Date();
                if(today > event.start){
                    event.start = "";
                }
                
                $(this).dialog('close');
                if(confirmAppointment(event, view)){
                    
                }
                //$(this).dialog('close');

            },
            "edit new schedule": function(){
                $(this).dialog('close');
                editDialogBox(event, view);
                //$(this).dialog('close');
            },
            close: function() {
                $(this).dialog('close');
            }
        }
    });
    
    $(confirmContent).dialog('open');

}

function newDialogBox(newDate, view, starttime){

    var deleteContent = $("<div id='new-confirm' />").html('<p><span class="ui-icon ui-icon-document" style="float:left; margin:0 7px 20px 0;"></span>would you like to create a new event?</p>');
                
    $(deleteContent).dialog({
        autoOpen: false,
        resizable: false,
        width: 350,
        title: "New Calendar Event?",
        modal: true,
        close: function() {
           $(deleteContent).dialog("destroy");
           $(deleteContent).hide();
        },
        buttons: {
            'create event': function() {
                $(this).dialog('close');
                createDialogBox(newDate,view,starttime);
                //$(this).dialog('close');
            },
            'recurring event': function() {
                $(this).dialog('close');
                recurringEventDialogBox(newDate,view,starttime);
                //$(this).dialog('close');
            },
            close: function() {
                $(this).dialog('close');
            }
        }
    });
    
    $(deleteContent).dialog('open');

}

function noticeDialog(header, message, icon, returnObj){
    
    var noticeContent = $("<div id='calendar-notice' />").html('<p><span class="ui-icon ui-icon-'+icon+'" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>');
    
    $(noticeContent).dialog({
        modal: true,
        title: header,
        open: function(event, ui) {
            //alert(this.id);
            setTimeout("$('#"+this.id+"').dialog('close');",5000);
        },
        close: function() {
           $(noticeContent).dialog("destroy");
           $(noticeContent).hide();
        },
        buttons: {
            Ok: function() {
                $(this).dialog('close');
            }
        }
    });
    
    $(noticeContent).dialog('open');
    
    
    if(returnObj){
        return noticeContent;
    }
}
function noticeDialog2(header, message, icon, returnObj){
    
    var noticeContent = $("<div id='calendar-notice' />").html('<p><span class="ui-icon ui-icon-'+icon+'" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>');
    
    $(noticeContent).dialog({
        modal: true,
        title: header,
        close: function() {
           $(noticeContent).dialog("destroy");
           $(noticeContent).hide();
        },
        buttons: {
            Ok: function() {
                $(this).dialog('close');
            }
        }
    });
    
    $(noticeContent).dialog('open');
    
    if(returnObj){
        return noticeContent;
    }
}

//------------------------------------------------ End Dialog Boxes

//------------------ Progress Bar Dialog Boxes ------------------------------
// Source: http://jqueryui.com/demos/progressbar/
function progressDialogBox(loading){
    
    //alert('progressDialogBox: '+loading);
    
    //var top = $(window).height() / 2;
    //var left = $(window).width() / 2;

    /*
    ("<div></div>").addClass("ui-widget-overlay")).appendTo(document.body).css({width:this.width(),height:this.height()});
    */
    
    //var overlay = $("#progess-overlay");
    var progressContainer = $("#progressbarContainer");
    var progressbar = $("#progressbar");
    
    //$(progressContainer).center();


    //alert('height:'+$(window).height() + ' width:'+$(window).width());
  
    //alert(loading);
    if(loading){
        
        //$(overlay).addClass("ui-widget-overlay");
        
        $(progressContainer).show();
        $(progressbar).progressbar({value: 100});
        
        //$("#progess-overlay").css('height',$(window).height());
        //$("#progess-overlay").css('width',$(window).width());
        //$(progressContainer).center();
        
        $(progressContainer).each(function(){
            var container = $(window);
            var t = $(container).height();
            var l = $(container).width();
            
            var scrollTop = $(window).scrollTop();
            var scrollLeft = $(window).scrollLeft();
            
            //var top = -t / 2;
            //var left = -l / 2;
            
            var top = -50 + scrollTop;
            var left = -125 + scrollLeft;
            
            $(this).css('position', 'absolute').css({ 'margin-left': left + 'px', 'margin-top': top + 'px', 'left': '50%', 'top': '50%' });
            
            $(window).scroll(function () { 
                if($(progressContainer)){
                    var scrollTop = $(window).scrollTop();
                    var scrollLeft = $(window).scrollLeft();
                    var top = -50 + scrollTop;
                    var left = -125 + scrollLeft;
                    $(progressContainer).css('position', 'absolute').css({ 'margin-left': left + 'px', 'margin-top': top + 'px', 'left': '50%', 'top': '50%' });
                    //alert('scrollTop: '+scrollTop+' scrollLeft: '+scrollLeft);
                }
            });

            
        });
        
        
    }else{
        
        //$(overlay).removeClass("ui-widget-overlay");
        $(progressContainer).hide();
        $(progressbar).progressbar( "destroy" );
        
    }
}

//------------------------------------------------ End Progress Bar Dialog Boxes

//------------------ Confirm Dialog Boxes ------------------------------

function confirmModifyDialogBox(message, title, revertFunc, event, start, end){
    
    var confirmContent = $("<div id='custom-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>');
                
    $(confirmContent).dialog({
        autoOpen: false,
        resizable: false,
        width: 350,
        title: title,
        modal: true,
        close: function() {
           $(this).dialog("destroy");
           $(this).hide();
        },
        buttons: {
            "confirm": function(){
                //if(!modifyAppointment(event, start, end)){revertFunc();}
                $(this).dialog('close');
                modifyAppointment(event, start, end, false, true, revertFunc);
                //$(this).dialog('close');
            },
            close: function() {
                $(this).dialog('close');
                revertFunc();
            }
        }
    });
    
    $(confirmContent).dialog('open');

}

function confirmChangeDialogBox(message, title, affiliationId, dayDelta, minuteDelta, revertFunc){
    
    var confirmContent = $("<div id='custom-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'+message+'</p>');
                
    $(confirmContent).dialog({
        autoOpen: false,
        resizable: false,
        width: 360,
        title: title,
        modal: true,
        close: function() {
           $(this).dialog("destroy");
           $(this).hide();
        },
        buttons: {
            "confirm": function(){
                $(this).dialog('close');
                changeRecurringEvents(affiliationId, dayDelta, minuteDelta, revertFunc);
            },
            close: function() {
                $(this).dialog('close');
                revertFunc();
                
            }
        }
    });
    
    $(confirmContent).dialog('open');

}

//------------------------------------------------ End Confirm Dialog Boxes
//------------------ Recurring Dialog Boxes ------------------------------

function editRecurringDialogBox(event,view){
    
    var dialogContent = $("#edit-event-dialog").load('fullcalendar/edit_event.html',function() {
                                                                                             
        var dayformatter = "mm/dd/yyyy";    // mmmm d, yyyy
        var timeformatter = "h:MM TT";      // h:MM:ss TT 
        
        //$("#edit-event-dialog #startDate").datepicker();
        //$("#edit-event-dialog #endDate").datepicker();
        
        $("#edit-event-dialog #startDate").datepicker(
        // Commented by SMS: 8/7/2011
        // To address the problem when selecting a choice, it would not select it.
        /*
        {
            minDate: new Date(),
            onClose: function(dateText, inst) {
                $("#edit-event-dialog #endDate").datepicker( "option" ,'minDate', dateText);
            }
        }
        */
        );
        $("#edit-event-dialog #endDate").datepicker(
        // Commented by SMS: 8/7/2011
        // To address the problem when selecting a choice, it would not select it.
        // {minDate: event.start.format(dayformatter)}
        );
        
        $("#edit-event-dialog #start").ptTimeSelect(); 
        $("#edit-event-dialog #end").ptTimeSelect(); 
        
        var customLabel;
        if(is_admin_user){
            customLabel = "Host";
        }else{
            customLabel = "Course";
        }
        
        $("#edit-event-dialog #customddm").text(customLabel);
        
        resetForm(this);

        var typeFieldOptions = "";
        var courseFieldOptions = "";
        //var timezoneFieldOptions = "";
        
        
        for(var i = 0; i<types.length; i++) {
            typeFieldOptions +=  "<option>"+types[i]+"</option>";
        }
        for(var i = 0; i<courses.length; i++){
            courseFieldOptions +=  "<option>"+courses[i]+"</option>";
        }

        var startDate = $(this).find("input[name='startDate']");
        var endDate = $(this).find("input[name='endDate']");
        var startField = $(this).find("input[name='start']").val(event.start.format(timeformatter));
        var endField = $(this).find("input[name='end']").val(event.end.format(timeformatter));
        var typeField = $(this).find("select[name='type']").html(typeFieldOptions);
        var courseField = $(this).find("select[name='course']").html(courseFieldOptions);
        var startNow = $(this).find("input[name='startNow']");
        
        startDate.val(event.start.format(dayformatter));
        endDate.val(event.end.format(dayformatter));
        
        typeField.val((event.resourceType).toUpperCase());
        courseField.val(event.course);
        
        $(startNow).attr('disabled','disabled');
    });
    
    $(dialogContent).dialog({
        autoOpen: false,
        width: 350,
        modal: true,
        title: "Edit Recurring Appointment",
        close: function() {
           $(this).dialog("destroy");
           $(this).hide();
        },
        buttons: {
            "change all occurrences" : function() {
                
                var header = "Edit Recurring Appointment: Invalid Date";
                var message = "";
                var icon = "alert";
                // Retrieve Form Objects
                var startDate = $(dialogContent).find("input[name='startDate']");
                var endDate = $(dialogContent).find("input[name='endDate']");
                var startField = $(dialogContent).find("input[name='start']");
                var endField = $(dialogContent).find("input[name='end']");
                
                //event.title = typeField.val();
                var start = new Date(startDate.val() + " " + startField.val());
                var end = new Date(endDate.val() + " " + endField.val());
                
                
                // Calculate if the new date is being shifted forward or backwards.
                var startIndex = days_between(start,event.start);
                var endIndex = days_between(end,event.end);
                var delta = 1;
                
                if(startIndex > 0){
                    if(event.start > start){
                        startIndex = startIndex * (-1); 
                        delta = (-1);
                    }
                }
                if(endIndex > 0){
                    if(event.start > start){
                        endIndex = endIndex * (-1); 
                    }
                }
                
                
                if(checkStartEndFields(startDate, startField, endDate, endField)){
                    if(end>start){
                        
                        //modifyRecurringAppointment(event, start, end, true);
                        $(this).dialog('close');
                        modifyRecurringAppointment(event, start, startIndex, end, endIndex, delta, true);
                        //$(this).dialog("close");
                        
                    }else if(end.toString() == start.toString()){
                        
                        message = "The appointment start time cannot be the same as the appointment end time.";
                        noticeDialog(header, message, icon);
                        
                    }else{
                    
                        message = "The appointment end date cannot be before the appointment start date.";
                        noticeDialog(header, message, icon);
                    }
                }
                
                
            },
            "change this occurrence" : function() {
                
                var header = "Edit Recurring Appointment: Invalid Date";
                var message = "";
                var icon = "alert";
                // Retrieve Form Objects
                var startDate = $(dialogContent).find("input[name='startDate']");
                var endDate = $(dialogContent).find("input[name='endDate']");
                var startField = $(dialogContent).find("input[name='start']");
                var endField = $(dialogContent).find("input[name='end']");
                
                var today = new Date();
                var startNow = null;
                
                //event.title = typeField.val();
                var start = new Date(startDate.val() + " " + startField.val());
                var end = new Date(endDate.val() + " " + endField.val());
                
                if(today > event.start){
                    startNow = $(dialogContent).find("input[name='startNow']");
                    start = event.start;
                }
                
                var delta = null;
                
                if(checkStartEndFields(startDate, startField, endDate, endField, startNow)){
                    if(end>start){
                        
                        //modifyRecurringAppointment(event, start, end, false);
                        $(this).dialog('close');
                        modifyRecurringAppointment(event, start, 0, end, 0, delta, false);
                        //$(this).dialog("close");
                        
                    }else if(end.toString() == start.toString()){
                        
                        message = "The appointment start time cannot be the same as the appointment end time.";
                        noticeDialog(header, message, icon);
                        
                    }else{
                    
                        message = "The appointment end date cannot be before the appointment start date.";
                        noticeDialog(header, message, icon);
                    }
                }
            },
            close : function() {
                $(this).dialog("close");
            },
            "cancel all occurrences" : function() {
                deleteRecurringDialogBox(event, this, true);
            },
            "cancel this occurrence" : function() {
                deleteRecurringDialogBox(event, this, false);
            }
        }
    });

    $(dialogContent).dialog('open');
    
}


function deleteRecurringDialogBox(event, dialogBox, all){

    var requestType;

    if(all){
    
        var deleteContent = $("<div id='delete-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These events will be permanently deleted and cannot be recovered. Are you sure?</p>');
                    
        $(deleteContent).dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            title: "Delete Occurrences",
            modal: true,
            close: function() {
               $(deleteContent).dialog("destroy");
               $(deleteContent).hide();
            },
            buttons: {
                'delete all occurrences': function() {
                    //$('#calendar').fullCalendar("removeEvents", event.id);
                    if(is_mentor_user && event.type == "scheduled"){
                        requestType = "User";
                    }else{
                        requestType = getRequestType(currentUser);
                    }
                    $(this).dialog('close');
                    cancelAllAppointments(event.id, requestType); // id is actually the affiliationId, vice versa
                    //$(this).dialog('close');
                    if(dialogBox){
                        $(dialogBox).dialog("close");
                    }
                },
                close: function() {
                    $(this).dialog('close');
                }
            }
        });
        
        $(deleteContent).dialog('open');
        
    }else{
        
        var deleteContent = $("<div id='delete-confirm' />").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This event will be permanently deleted and cannot be recovered. Are you sure?</p>');
                    
        $(deleteContent).dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            title: "Delete - " + event.title,
            modal: true,
            close: function() {
               $(deleteContent).dialog("destroy");
               $(deleteContent).hide();
            },
            buttons: {
                'delete this occurrence': function() {
                    // Switch back the affiliation and id
                    var newAffil = event.affiliation;
                    var newId = event.id;
                    event.affiliation = newId;
                    event.id = newAffil;
                    event.recurring = false;
                    
                    $(this).dialog('close');
                    cancelAppointment(event,false);
                    //$(this).dialog('close');
                    if(dialogBox){
                        $(dialogBox).dialog("close");
                    }
                },
                close: function() {
                    $(this).dialog('close');
                }
            }
        });
        
        $(deleteContent).dialog('open');
    }

}

//------------------------------------------------ End Recurring Dialog Boxes

//------------------ Utilities ------------------------------

function isValidDate(dateStr) {
    //var str = "07/11/1984";

    var success = false;
    var datere = /[0-9]{2}\/[0-9]{2}\/[0-9][0-9]{3}/;
    
    var result = dateStr.match(datere);
    var newDate;
    
    if(result!=null){
        newDate = new Date(result[0]);
    }
    
    if(newDate != null && !isNaN(newDate.getTime())){
        success = true;
    }
    return success;
}

function isValidTime(dateStr, timeStr) {
    //var str = "07/11/1984";

    var success = false;
    var datere = /[0-9]{2}\/[0-9]{2}\/[0-9][0-9]{3}/;
    var timere = /[0-9]{1,2}(:[0-9]{2})\s(pm|am)/i;
    
    var result = dateStr.match(datere);
    var time = timeStr.match(timere);
    var newDate = null;
    
    if(result!=null){
        if(time!=null){
            newDate = new Date(result[0] + " " + time[0]);
            //newDate = new Date("hi"); // Invalide date
            
        }
    }
    
    if(newDate != null && !isNaN(newDate.getTime())){
        success = true;
    }
    return success;
}

function checkStartEndFields(startDate, startField, endDate, endField, startNow){
    
    var validStartDate = false;
    var validStartTime = false;
    var validEndDate = false;
    var validEndTime = false;
    
    var isChecked = $(startNow).attr('checked');
    
    startDate.removeClass('error');
    startField.removeClass('error');
    endDate.removeClass('error');
    endField.removeClass('error');

    if(isValidDate(endDate.val())){
        validEndDate = true;
    }else{
        endDate.addClass('error');  
    }
    
    if(isValidTime(endDate.val(), endField.val())){
        validEndTime = true;
    }else{
        endField.addClass('error'); 
    }
    
    
    //if(validStartDate && validEndDate && validEndTime){
        //if the startNow checkbox is check, ignore
        if(!isChecked){
            
            if(isValidDate(startDate.val())){
                validStartDate = true;
            }else{
                startDate.addClass('error');    
            }
                    
            if(isValidTime(startDate.val(), startField.val())){
                validStartTime = true;
            }else{
                startField.addClass('error');   
            }
        }else{
            if(validEndDate && validEndTime){
                startDate.val("");
                startField.val("");
                validStartDate = true;
                validStartTime = true;
            }
            
        }
    //}
    
    //alert('validStartDate: '+validStartDate+' validStartTime: '+validStartTime+' validEndDate: '+validEndDate+' validEndTime: '+validEndTime);
    return (validStartDate && validStartTime && validEndDate && validEndTime) ? true : false; 

}
//------------------------------------------------ End Utilities

//------------------ Recurring Events ------------------------------

function recurringEventDialogBox(newDate,view,starttime){
        
    var dialogContent = $('#edit-recur-event-dialog').load('fullcalendar/edit_recur_event2.html', function() {
        
        //appointment time
        $("#edit-recur-event-dialog #startDate").datepicker({
            minDate: new Date()
            /*,
            onClose: function(dateText, inst) {
                // Commented by SMS: 8/7/2011
                // To address the problem when selecting a choice, it would not select it.
                // $("#edit-recur-event-dialog #endDate").datepicker( "option" ,'minDate', dateText );
            }
            */
        });
        $("#edit-recur-event-dialog #endDate").datepicker({minDate: newDate});
        
        
        
        $('#edit-recur-event-dialog #recur_start').ptTimeSelect(); 
        $('#edit-recur-event-dialog #recur_end').ptTimeSelect();
            
        var customLabel;
        if(is_admin_user){
            customLabel = "Host";
        }else{
            customLabel = "Course";
        }
        
        $("#edit-recur-event-dialog #customddm").text(customLabel);
        
        /*
        $("#edit-recur-event-dialog #startDate").focus(function() {
            $("#ptTimeSelectCntr").hide();
        });
        $("#edit-recur-event-dialog #recur_start").focus(function() {
            $("#create-event-dialog #endDate").datepicker('hide');
            $("#create-event-dialog #startDate").datepicker('hide');
        });
        $("#edit-recur-event-dialog #endDate").focus(function() {
            $("#ptTimeSelectCntr").hide();
        });
        
        $("#edit-recur-event-dialog #recur_end").focus(function() {
            $("#create-event-dialog #startDate").datepicker('hide');
            $("#create-event-dialog #endDate").datepicker('hide');
        });
        $("#edit-recur-event-dialog #end_after").focus(function() {
            $("#create-event-dialog #startDate").datepicker('hide');
            $("#create-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
        });
        */
        /*  
        $("input").focus(function() {
            $("#edit-recur-event-dialog #startDate").datepicker('hide');
            $("#edit-recur-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
            //alert('$("input").focus(function()');
        });
        */  
        $("#edit-recur-event-dialog #recur_type").focus(function() {
            $("#edit-recur-event-dialog #startDate").datepicker('hide');
            $("#edit-recur-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
        });
        $("#edit-recur-event-dialog #recur_course").focus(function() {
            $("#edit-recur-event-dialog #startDate").datepicker('hide');
            $("#edit-recur-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
        });
        /*
        $("#edit-recur-event-dialog #recur_timezone").focus(function() {
            $("#create-event-dialog #startDate").datepicker('hide');
            $("#create-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
        });
        */
        /*  
        $("fieldset").focus(function() {
            $("#edit-recur-event-dialog #startDate").datepicker('hide');
            $("#edit-recur-event-dialog #endDate").datepicker('hide');
            $("#ptTimeSelectCntr").hide();
            //alert('$("fieldset").focus(function() {');
        });
        */
        //recurrence pattern
        $("#daily").attr('checked', true);
        $("#daily_container").show();
        $("#weekly_container").hide();
        $("#monthly_container").hide(); 
        $("#yearly_container").hide();
        
        
        $("#daily").click(function () { 
            recurType = "daily";
            $("#ocurrences_label").html(recurType +' ocurrences');
            $("#daily_container").show("slow");
            $("#weekly_container").hide();
            $("#monthly_container").hide(); 
            $("#yearly_container").hide();
        
        });
        $("#weekly").click(function () { 
            recurType = "weekly";
            $("#ocurrences_label").html(recurType +' ocurrences');
            $("#weekly_container").show("slow");
            $("#daily_container").hide();
            $("#monthly_container").hide(); 
            $("#yearly_container").hide();
        });
        $("#monthly").click(function () { 
            recurType = "monthly";
            $("#ocurrences_label").html(recurType +' ocurrences');
            $("#monthly_container").show("slow");
            $("#daily_container").hide();
            $("#weekly_container").hide();
            $("#yearly_container").hide();
        });
        $("#yearly").click(function () { 
            recurType = "yearly";
            $("#ocurrences_label").html(recurType +' ocurrences');
            $("#yearly_container").show("slow");
            $("#daily_container").hide();
            $("#weekly_container").hide();
            $("#monthly_container").hide(); 
        });
        
        // Range of Occurance Radio Buttons
        $("#end_after").click(function(){
            $("#ocurrences").focus();
        });
        $("#ocurrences").focus(function(){
            $("#end_after").attr('checked','checked');
        });
        $("#end_by").click(function(){
            $("#endDate").focus();
        });
        $("#endDate").focus(function(){
        
            $("#end_by").attr('checked','checked');
            
        });
    
        var dayformatter = "mm/dd/yyyy";    // mmmm d, yyyy
        var timeformatter = "h:MM TT";      // h:MM:ss TT 
        var typeFieldOptions = "";
        var courseFieldOptions = "";
        //var timezoneFieldOptions = "";
        
        // Load types in the select box, and select current type
        /*for(var i = 0; i<types.length; i++){
            typeFieldOptions +=  "<option>"+types[i]+"</option>";
        }
        for(var i = 0; i<courses.length; i++){
            courseFieldOptions +=  "<option>"+courses[i]+"</option>";
        }*/
        
        for(var i = 0; i<types.length; i++){
            
            if(avail_resource_listing.length > 0){
                var type = types[i];
                var added = false;
                
                //alert('RESOURCE: '+type);
                for(var j = 0; j <avail_resource_listing.length; j++){
                    if(!added){
                        var newtype = type.replace(/ /g, "-").toLowerCase();
                        //alert('avail_resource_listing: \n'+avail_resource_listing[j]+' \n newtype: \n'+newtype);
                        
                        if(newtype == avail_resource_listing[j]){
                            //alert('MATCH: '+newtype);
                            // Modified by SMS: 8/7/2011
                            // To make sure that certificate exams cannot be scheduled on a recurring basis.
                            // alert("1 " + types[i]);
                            if (types[i] != "CERTIFICATE") {
                                // alert("2 " + types[i]);
                                typeFieldOptions +=  "<option>"+types[i]+"</option>";
                            }
                            // typeFieldOptions +=  "<option>"+types[i]+"</option>";
                            added = true;
                        }
                    }
                }
            }else{
                // Modified by SMS: 8/7/2011
                // To make sure that certificate exams cannot be scheduled on a recurring basis.
                // alert("3 " + types[i]);
                if (types[i] != "CERTIFICATE") {
                    // alert("4 " + types[i]);
                    typeFieldOptions +=  "<option>"+types[i]+"</option>";
                }               
                // typeFieldOptions +=  "<option>"+types[i]+"</option>";
            }
        }
        
        for(var i = 0; i<courses.length; i++){
            
            if(avail_course_listing.length >0){
                var course = courses[i];
                var added = false;
                
                //alert('COURSE: '+course);
                for(var j = 0; j <avail_course_listing.length; j++){
                    
                    if(!added){
                        var newcoursename = course.replace(/ /g, "-").toLowerCase();
                        newcoursename = newcoursename.replace(/\./g, "").toLowerCase();
                        //console.log('avail_course_listing: \n'+avail_course_listing[j]+' \n newcoursename: \n'+newcoursename);
                        
                        if(newcoursename == avail_course_listing[j]){
                            //alert('MATCH: '+newcoursename);
                            courseFieldOptions +=  "<option>"+courses[i]+"</option>";
                            added = true;
                        }
                    }
                }
            }else{
                courseFieldOptions +=  "<option>"+courses[i]+"</option>";
            }
        }
        
        
        /*
        for(var i = 0; i<zones.length; i++){
            timezoneFieldOptions += "<option>"+zones[i]+"</option>";
        }*/
        
        var startDate = $(this).find("input[name='startDate']").val(newDate.format(dayformatter));
        var endDate = $(this).find("input[name='endDate']");
        var startField = (starttime) ? $(this).find("input[name='recur_start']").val(newDate.format(timeformatter)) : $(this).find("input[name='recur_start']");
        var endField = $(this).find("input[name='recur_end']");
        
        var typeField = $(this).find("select[name='recur_type']");
        var courseField = $(this).find("select[name='recur_course']");
        //var timezoneField = $(this).find("select[name='recur_timezone']").html(timezoneFieldOptions);
        
        $(typeField).html(typeFieldOptions);
        $(courseField).html(courseFieldOptions);
        /*
        if(is_mentor_user){
            var parent = $(typeField).parent();
            parent.hide();
                
        }*/
        
        // Debugging
        //var startField = $("input[name='recur_start']").val("1:00 PM");
        //var endField = $("input[name='recur_end']").val("3:00 PM");
        
        //Default Values
        $("input[name='daily_num']").val("1");
        $("input[name='weekly_weeks']").val("1");
        $("input[name='monthly_day']").val("1");
        $("input[name='monthly_months']").val("1");
        $("input[name='monthly_months_nth']").val("1");
        $("input[name='yearly_years']").val("1");
        
        $("#edit-recur-event-dialog #ocurrences_label").html('daily ocurrences');
        /*
        alert("ocurrences_label- html");
        $("#edit-recur-event-dialog #ocurrences_label").text('daily ocurrences');
        alert("ocurrences_label- text");
        $("#edit-recur-event-dialog #ocurrences_label").val('daily ocurrences');
        alert("ocurrences_label- val");
        */
    
    });

    $(dialogContent).dialog({
        autoOpen: false,
        height: 500,
        width: 500,
        modal: true,
        buttons: {
            'create recurring event': function() {
                //$("#recur-event-dialog").submit();
                
                if(!generateRecurringEvent(this)){
                    $(this).dialog('close');
                }
                
            },
            close: function() {
                $(this).dialog('close');
    
            }
        },
        close: function() {
            //allFields.val('').removeClass('ui-state-error');
        }
    });
    
    $(dialogContent).dialog('open');
}
    


function generateRecurringEvent(form){
    
    var errorClass = "ui-state-error";
    
    var recurType = $(form).find("input[name='recurrence_choice']:checked").val();
    
    var startTime = $(form).find("input[name='recur_start']");
    var endTime = $(form).find("input[name='recur_end']");
    var course = $(form).find("select[name='recur_course']");
    var resourceType = $(form).find("select[name='recur_type']");
    var time_container = $(form).find("#recur_time");
    
    var end_date_choice = $(form).find("input[name='end_date_choice']:checked");
    var startDate = $(form).find("input[name='startDate']");
    var endDate = $(form).find("input[name='endDate']");
    var ocurrences = $(form).find("input[name='ocurrences']");
    
    var range_table = $(form).find("#range_end_date_choice");
    var range_container = $(form).find("#recur_range");
    
    // Event Vars
    var recur_start, recur_end, recur_course, recur_type;
    var recur_range = {};
    var recur_pattern = {};
    
    var errors = false;

    
    // Appointment Time
    time_container.removeClass(errorClass);
    
    if(startTime.val() || endTime.val()){
        
        if(startTime.val()){
            recur_start = startTime.val();
        }else{
            startTime.addClass(errorClass);
            errors = true;
        }
        
        if(endTime.val()){
            recur_end = endTime.val();
        }else{
            endTime.addClass(errorClass);
            errors = true;
        }
        
    }else{
        time_container.addClass(errorClass);
        errors = true;
    }
    
    recur_type = resourceType.val();
    recur_course = course.val();
    
    // Range of ocurrence
    range_container.removeClass(errorClass);
    range_table.removeClass(errorClass);
    ocurrences.removeClass(errorClass);
    startDate.removeClass(errorClass);
    endDate.removeClass(errorClass);
    
    
    if(end_date_choice.val() || startDate.val()){
    
        if(startDate.val()){
            //HERE
            recur_range.start = startDate.val();
        
        }else{
            startDate.addClass(errorClass);
            errors = true;
        }
    
        if(end_date_choice.val() == "end_after"){
            
            var times = parseInt(ocurrences.val());
            if(times){
                if(times>0){
                    //HERE  
                    recur_range.type = "end_after";
                    recur_range.occurences = times;
                    
                }else{
                    ocurrences.addClass(errorClass);
                    errors = true;
                }
            }else{
                ocurrences.addClass(errorClass);
                errors = true;
            }
            
        }else if(end_date_choice.val() == "end_by"){
            
            if(endDate.val()){
                //HERE
                recur_range.type = "end_by";
                recur_range.endby = endDate.val();
            }else{
                endDate.addClass(errorClass);
                errors = true;
            }
            
        }else{
            range_table.addClass(errorClass);
            errors = true;
        }
    }else{
        range_container.addClass(errorClass);
        errors = true;
    }
                    
    // Recurrence pattern
    if(recurType == "daily"){
    
        // Daily
        var daily_choice = $("input[name=daily_choice]:checked");
        var daily_days = $(form).find("input[name='daily_num']");
        var container = $(form).find("#daily_container");
        
        container.removeClass(errorClass);
        daily_days.removeClass(errorClass);
        
        if(daily_choice.val() == "every_day"){
            var days = parseInt(daily_days.val());
            
            if(days){
                if(days>0){
                    // Here
                    recur_pattern.type = "every_day";
                    recur_pattern.occurences = days;
                }else{
                    daily_days.addClass(errorClass);
                    errors = true;
                }
            }else{
                daily_days.addClass(errorClass);
                errors = true;
            }
            
        }else if(daily_choice.val() == "every_weekday"){
            
            recur_pattern.type = "every_weekday";
        
        }else{
            container.addClass(errorClass);
            errors = true;
        }
        
        if(!errors){
            
            dailyRecurrEvents(recur_start, recur_end, recur_course, recur_type, recur_pattern, recur_range);
        }
        
    }else if(recurType == "weekly"){
        
        // Weekly
        var weekly_weekday = $("input[name=weekly_weekday]:checked")//;
        var num_weeks = $(form).find("input[name='weekly_weeks']");
        var container = $(form).find("#weekly_container");
        var weekly_table = $(form).find("#weekly_weekday_table");
        
        container.removeClass(errorClass);
        weekly_table.removeClass(errorClass);
        num_weeks.removeClass(errorClass);
        
        var weeks = parseInt(num_weeks.val());
        
        if(weekly_weekday.val() || weeks){
            
            if(weeks>0){
                recur_pattern.weeks = weeks;
            }else{
                num_weeks.addClass(errorClass);
                errors = true;
            }
        
            if(weekly_weekday.val()){
                var weekdays = [];
                weekly_weekday.each(function(){
                                             
                    weekdays.push(getWeekdayNumber($(this).val()));
                    
                });
                recur_pattern.weekdays = weekdays;
            }else{
                weekly_table.addClass(errorClass);
                errors = true;
            }
            
        }else{
            num_weeks.addClass(errorClass);
            container.addClass(errorClass);
            errors = true;
        }
        
        if(!errors){
            weeklyRecurrEvents(recur_start, recur_end, recur_course, recur_type, recur_pattern, recur_range);
        }
        
    }else if(recurType == "monthly"){
        
        // Monthly
        var monthly_choice = $("input[name=monthly_choice]:checked");
        var monthly_days = $(form).find("input[name='monthly_day']");
        var monthly_months;
        //var monthly_num_months = $(form).find("input[name='monthly_num_months']");
        var container = $(form).find("#monthly_container");
        
        container.removeClass(errorClass);
        monthly_days.removeClass(errorClass);
        //monthly_months.removeClass(errorClass);
        //monthly_num_months.removeClass(errorClass);
        
        // recur_pattern: type(string), days(int), months(int)
        
        if(monthly_choice.val() == "monthly_numday"){
            monthly_months = $(form).find("input[name='monthly_months']");
            monthly_months.removeClass(errorClass);
            recur_pattern.type = "monthly_numday";
            
            var days = parseInt(monthly_days.val());
            var months = parseInt(monthly_months.val());
            
            if(days || months){
                if(days){
                    if(days>0){
                        // HERE
                        recur_pattern.days = days;
                    }else{
                        monthly_days.addClass(errorClass);
                        errors = true;
                    }
                }else{
                    monthly_days.addClass(errorClass);
                    errors = true;
                }
                
                if(months){
                    if(months>0){
                        // HERE
                        recur_pattern.months = months;
                    }else{
                        monthly_months.addClass(errorClass);
                        errors = true;
                    }
                }else{
                    monthly_months.addClass(errorClass);
                    errors = true;
                }
            }else{
                container.addClass(errorClass);
                errors = true;
            }
            
        }else if(monthly_choice.val() == "monthly_weekday"){
            
            monthly_months = $(form).find("input[name='monthly_months_nth']");
            monthly_months.removeClass(errorClass);
            recur_pattern.type = "monthly_weekday";
            
            var nth = $(form).find("select[name='monthly_week_num']").val();
            var weekday = $(form).find("select[name='monthly_on_weekdays']").val();
            var months = parseInt(monthly_months.val());
            // recur_pattern: nth
            if(months){
                if(months>0){
                    // HERE
                    recur_pattern.nth = nth.toLowerCase();
                    recur_pattern.weekdays = weekday.toLowerCase(); 
                    recur_pattern.months = months;
                }else{
                    monthly_months.addClass(errorClass);
                    errors = true;
                }
            }else{
                monthly_months.addClass(errorClass);
                errors = true;
            }
            
        }else{
            container.addClass(errorClass);
            errors = true;
        }
        
        if(!errors){
            monthlyRecurrEvents(recur_start, recur_end, recur_course, recur_type, recur_pattern, recur_range);
        }
        
    }else if(recurType == "yearly"){
        
        // Yearly
        var yearly_choice = $("input[name=yearly_choice]:checked");
        var every_years = $(form).find("input[name='yearly_years']");
        var container = $(form).find("#yearly_container");
        
        container.removeClass(errorClass);
        every_years.removeClass(errorClass);
        
        var years = parseInt(every_years.val());
        if(years){
            if(years>0){
                // HERE
                recur_pattern.years = years;
            }else{
                every_years.addClass(errorClass);
                errors = true;
            }
        }else{
            every_years.addClass(errorClass);
            errors = true;
        }
        
        if(yearly_choice.val() == "yearly_on"){
            
            recur_pattern.type = "yearly_on";
            
            var yearly_on_month = $(form).find("select[name='yearly_on_month']");
            var yearly_on_day = $(form).find("select[name='yearly_on_day']");
            
            recur_pattern.month = parseInt(yearly_on_month.val());
            recur_pattern.day = parseInt(yearly_on_day.val());
            
            
        }else if(yearly_choice.val() == "yearly_on_the"){
            
            recur_pattern.type = "yearly_on_the";
            
            var yearly_on_the_week = $(form).find("select[name='yearly_on_the_week']");
            var yearly_on_the_weekdays = $(form).find("select[name='yearly_on_the_weekdays']");
            var yearly_on_the_month = $(form).find("select[name='yearly_on_the_month']");
            
            recur_pattern.nth = yearly_on_the_week.val();
            recur_pattern.weekdays = yearly_on_the_weekdays.val(); 
            recur_pattern.month = parseInt(yearly_on_the_month.val());
            
            
        }else{
            container.addClass(errorClass); 
            errors = true;
        }
        
        if(!errors){
            yearlyRecurrEvents(recur_start, recur_end, recur_course, recur_type, recur_pattern, recur_range);
        }
    
    }
    return errors;
}

//------------------------------------------------ End Recurring Events



function addHostDialogBox(){
    

    var addHostDialog =  $("#add-host-dialog").load('fullcalendar/add_host.html',function() {

         //$("#add-host-form").validate();
    
         $("#add-host-form").submit(function() {
                 //$("#add-host-form").validate();
                addHost();
                $(addHostDialog).dialog("close");
                return false;
            });
        
    }); 
        
    



    $(addHostDialog).dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        title: "Add new host",
        close: function() {
           $(addHostDialog).dialog("destroy");
           $(addHostDialog).hide();

        },
        buttons: {
            close : function() {
                $(addHostDialog).dialog("close");
            }
        }
    });
    
    $(addHostDialog).dialog('open');

}

function editHostDialogBox(id){
    

    var addHostDialog =  $("#add-host-dialog").load('fullcalendar/add_host.html',function() {

         //$("#add-host-form").validate();
    
         $("#add-host-form").submit(function() {
                 //$("#add-host-form").validate();
                setHost(id);
                $(addHostDialog).dialog("close");
                return false;
            });
            
        $.ajax({
            type: 'POST',
            url: 'fullcalendar/calendar.php',
            dataType: 'json',
            data: {
                action: 'getHost',
                requestingUser:  $('#username').val(),
                id:id
            },
            success: function(data){
                
                $("#hname").val(data.host.name);
                $("#husername").val(data.host.username);
                $("#hpassword").val(data.host.password);
                $("#hsshport").val(data.host.sshPort);
                $("#hnumcap").val(data.host.veNumCap);
                $("#hfreeport").val(data.host.veFirstFreePort);
                $("#hport").val(data.host.vePortNum);
                
    
                if(data.host.active==true)
                    $("#hactive").attr('checked', true);
    
       
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                var header = "Manage Hosts";
                var message = "We were unable get host.";
                var icon = "alert";
                message = textStatus + " : " +errorThrown;
                noticeDialog(header, message, icon);
                
            }
        });
        
    }); 
        
    



    $(addHostDialog).dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        title: "Add new host",
        close: function() {
           $(addHostDialog).dialog("destroy");
           $(addHostDialog).hide();

        },
        buttons: {
            close : function() {
                $(addHostDialog).dialog("close");
            }
        }
    });
    
    $(addHostDialog).dialog('open');

}
