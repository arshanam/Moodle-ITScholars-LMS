<?php  // colormanager.php

/**
 * this page helps manage the color required for the fullcalendar events *
 * @author  Jessica Merrigan <jmerr003@fiu.edu>
 * @version $Id: colormanager.php, 1
 * @package mod/scheduler
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir .'/ddllib.php');

//require_login($course, true, $cm);

?>
<!--
<link rel='stylesheet' type='text/css' href='fullcalendar/css/custom-theme/jquery-ui-1.8.1.custom.css' />
<link rel="stylesheet" media="screen" type="text/css" href="fullcalendar/colorpicker/css/colorpicker_custom.css" />
<link rel="stylesheet" media="screen" type="text/css" href="fullcalendar/colorpicker/css/layout2.css" />
-->
<!-- http://www.eyecon.ro/colorpicker/ -->

<!--
<script type='text/javascript' src='fullcalendar/jquery/jquery-1.4.2.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery-ui-1.8.1.custom.min.js'></script>
<script type='text/javascript' src='fullcalendar/jquery/jquery.blockUI.js'></script>


<script type="text/javascript" src="fullcalendar/colorpicker/js/colorpicker.js"></script>
<script type="text/javascript" src="fullcalendar/colorpicker/js/eye.js"></script>
<script type="text/javascript" src="fullcalendar/colorpicker/js/utils.js"></script>

<script type="text/javascript" src="colormanager.js"></script>
-->

<style type='text/css'>
	.colorpicker { z-index: 1005; }
    .colorcode-available { width: 12px; height: 12px; color: #FFFFFF; opacity:0.3; filter:alpha(opacity=30); }
    .colorcode-scheduled { width: 12px; height: 12px; color: #FFFFFF; }
	.enabled { color:#339900; }
	.disabled { color:#CC0000; font-weight: bold; } 

</style>


<script language="javascript">

$(document).ready(function() {
//$(function(){
	//grab all a tags
	
	$('#addNewColor').click(function(){

		insertColorDialogBox();
		
	});
	
	loadColorOptions();
	
});


 
 
 
</script>

<?php


	$table = new XMLDBTable('scheduler_colormap');
	
    if (!table_exists($table)) {
        echo "<br/>Creating Table ...<br/>";
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
		$table->addFieldInfo('colorcode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('enabled', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null); 
		$table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));
        // Create table for scheduler_colormap
        create_table($table);
		
		if(table_exists($table)){
		
			$eventsColors = array('A00000','0066CC','00CC66','FF9933','990099','cc9900','ff3399','999999','663300','666600','008080','FF7F7F');
			
			$cnt = 0;
			foreach ($eventsColors as $color){
				$cnt++;
				//insert_record($table, $record, false);
				insert_record('scheduler_colormap',array(
					'colorcode'=>$color,
					'enabled'=>1
				), false);
				
			}
			
			echo "<br/>".$cnt." entries were inserted into the database.";
				
		}	
    }
	
?>







<br />

<div class="wrapper">



<table id="scheduler-colormap" border="0" cellpadding="5" cellspacing="5">
<tbody>
	<tr><td colspan="5"></td><td><input type="button" id="addNewColor" name="addNewColor" value="Add New Color" /></td></tr>
	<tr>
    	<th>Order</th><th>Color Code</th><th></th><th>Status</th><th></th><th></th></tr>
    </tr>
<?php

if(table_exists($table)){
	$order = 0;
	$records = get_records('scheduler_colormap');
		
	foreach($records as $record){
		$order++;
		
		$status = "disabled";
		if($record->enabled){
			$status = "enabled";
		}
		echo "<tr>";
		echo "<td>".$order."</td>";
		echo "<td id='code-".$record->id."'>".$record->colorcode."</td>";
		echo "<td><div id='scheduled-".$record->id."' class='colorcode-scheduled' style='background-color: #".$record->colorcode."'>S</div>";
		echo "<div id='available-".$record->id."' class='colorcode-available' style='background-color: #".$record->colorcode."'>A</div></td>";
		echo "<td><a id='status-".$record->id."' class='colorStatus ".$status."'>".$status."</a></td>";
		echo "<td><a id='edit-".$record->id."' class='colorEdit'>edit</a></td>";
		echo "<td><a id='delete-".$record->id."' class='colorDelete'>delete</a></td>";
		echo "</tr>";
	}
}
?>
    
</tbody>
</table>
    
</div>

