<?php
// For Attempt.php Form
header('Content-Type: text/javascript'); 
$countdown_to = trim($_GET['countto']);

$diff = date("U",$countdown_to) -  date("U",time()) ;
//$diff = date("U",mktime(1,0,10,8,20,2009)) - date("U",mktime(1,0,0,8,20,2009));
//$stime =  date("U",mktime(1,0,0,8,20,2009));
//echo "alert('$diff');";
?>

// Here's where the Javascript starts
countdown = <?=$diff?>;

// Converting date difference from seconds to actual time
function convert_to_time(secs)
{
	secs = parseInt(secs);	
	hh = secs / 3600;	
	hh = parseInt(hh);	
	mmt = secs - (hh * 3600);	
	mm = mmt / 60;	
	mm = parseInt(mm);	
	ss = mmt - (mm * 60);	
		
	if (hh > 23)	
	{	
	   dd = hh / 24;	
	   dd = parseInt(dd);	
	   hh = hh - (dd * 24);	
	} else { dd = 0; }	
		
	if (ss < 10) { ss = "0"+ss; }	
	if (mm < 10) { mm = "0"+mm; }	
	if (hh < 10) { hh = "0"+hh; }	
	if (dd == 0) { return (hh+":"+mm+":"+ss); }	
	else {	
		if (dd > 1) { return (dd+" days "+hh+":"+mm+":"+ss); }
		else { return (dd+" day "+hh+":"+mm+":"+ss); }
	}	
}

// Our function that will do the actual countdown
function do_cd()
{
	if (countdown < 0)	
	{
        
        submitform();
	}	
	else	
	{
        if(countdown <= 300){
            document.getElementById('cd_end').innerHTML = convert_to_time(countdown);
            document.getElementById('cd').style.display='none';
            document.getElementById('cd_end').style.display='';



        }else{
            document.getElementById('cd').innerHTML = convert_to_time(countdown);
            document.getElementById('cd_end').style.display='none';
            document.getElementById('cd').style.display='';
        }
		
		setTimeout('do_cd()', 1000);
	}	
	countdown = countdown - 1;	
}

function submitform()
{
    form=document.getElementById('responseform');
    form.submit();
}

document.write("<div id='cd' style='position:fixed; top:2%; left:38%; z-index:2'></div>\n");
document.write("<div id='cd_end' style='display: none; position:fixed; top:2%; left:38%; z-index:2'></div>\n");

do_cd();

<? exit(); ?>
