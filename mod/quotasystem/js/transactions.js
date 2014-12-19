/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function reloadTransactions()
{
    $("#transactionsTableContainer").html("");

    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/transactions.php',
        dataType: 'json',
        data: { action: 'getTransactions' },
        success: function(data){

            $('#transactionsTableContainer').html( '<table cellpadding="0" cellspacing="0" border="0" class="display" id="transactionsTable"></table>' );

            $('#transactionsTable').dataTable( {
                "aaData": data,
                "aoColumns": [
                { "sTitle": "Id" },
                { "sTitle": "User" },
                { "sTitle": "Date" },
                { "sTitle": "Quota" },
                { "sTitle": "Type" },
                { "sTitle": "Status" },
                { "sTitle": "Credit Type" }
                ],
        		"bJQueryUI": true,
        		"sPaginationType": "full_numbers"
            } );
            
            $("#transactionsTable").removeAttr("style");

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        }
    });

}


function reloadTransactionsByUser(username)
{

    $("#transactionsTableContainer").html("");

    $.ajax({
        type: 'POST',
        url: '../quotasystem/server/transactions.php',
        dataType: 'json',
        data: {
            action: 'getTransactionsByUser',
            user:username
        },
        success: function(data){
            $('#transactionsTableContainer').html( '<table cellpadding="0" cellspacing="0" border="0" class="display" id="transactionsTable"></table>' );

            $('#transactionsTable').dataTable( {
                "aaData": data,
                "aoColumns": [
                { "sTitle": "Id" },
                { "sTitle": "Date" },
                { "sTitle": "Quota"},
                { "sTitle": "Type"},
                { "sTitle": "Status"},
                { "sTitle": "Credit Type"}
                ],
	    		"bJQueryUI": true,
	    		"sPaginationType": "full_numbers"
	        } );

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(errorThrown);
        }
    });

}







