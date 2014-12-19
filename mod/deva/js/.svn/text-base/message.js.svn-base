function displayError(parent, message)
{
    $("#"+parent+" .messageContainer").empty();
    $("#"+parent+" .messageContainer").removeClass('ui-state-highlight ui-corner-all');
    $("#"+parent+" .messageContainer").addClass('ui-state-error ui-corner-all');
	$("#"+parent+" .messageContainer").html('<p><span class="ui-icon ui-icon-alert"></span><strong>Error:</strong> '+message+'</p>');
	$("#"+parent+" .messageContainer").addClass("clear");
	$("#"+parent+" .messageContainer").click(function(){
		$("#"+parent+" .messageContainer").slideUp('slow');
	});
	
	$("#"+parent+" .messageContainer").slideDown('slow', function(){
		setTimeout(function(){ $("#"+parent+" .messageContainer").slideUp('slow');}, 5000);
		
	});

}

function displayMessage(parent,message)
{
	$("#"+parent+" .messageContainer").die();
    $("#"+parent+" .messageContainer").empty();
    $("#"+parent+" .messageContainer").removeClass('ui-state-error ui-corner-all');
	$("#"+parent+" .messageContainer").addClass('ui-state-highlight ui-corner-all');
	$("#"+parent+" .messageContainer").html('<p><span class="ui-icon ui-icon-check"></span><strong>'+message+'<strong></p>');
	$("#"+parent+" .messageContainer").addClass("clear");
	$("#"+parent+" .messageContainer").live('click',function(){
		$("#"+parent+" .messageContainer").slideUp('slow');
	});
	
	$("#"+parent+" .messageContainer").slideDown('slow', function(){	
		setTimeout(function(){ $("#"+parent+" .messageContainer").slideUp('slow');}, 5000);
		
	});

}
