
<!--

<script>

d= new Date();

var phptime = <?php echo time(); ?>;
var phpoffset = <?php echo date("Z"); ?>;
var offset = d.getTimezoneOffset();

document.write(phpoffset+"<br/>");
document.write(offset+"<br/>");

var time = phptime - phpoffset - (offset*60);

document.write("<br/>"+time+"<br/>");

nd = new Date(phptime*1000);
document.write(nd.toString());


</script>
-->
<!--[if !IE]><![IGNORE[--><![IGNORE[]]>

<script type="text/javascript">

function displaytime(){

var localtimezone = serverdate.toTimeString().substr(8);
if(globaltimezone != localtimezone){
    window.location.reload();
}

serverdate.setSeconds(serverdate.getSeconds()+1);

var datestring=serverdate.toString();

var timezone=datestring.match(/[(][ a-zA-Z]+[)]/);
var date=datestring.match(/[a-zA-Z]{3} [a-zA-Z]{3} [0-9]{1,2} [0-9]{4}/);
var time=datestring.match(/[0-9]{2}:[0-9]{2}:[0-9]{2}/);

//document.getElementById("servertime").innerHTML=time+" "+timezone;
//document.getElementById("serverdate").innerHTML=date;
document.getElementById("servertime").innerHTML=serverdate.toTimeString();
document.getElementById("serverdate").innerHTML=serverdate.toDateString();

}
// Formats the server date to display with the users browsers timezone
function formatMoodleDate(thistime){
    var thisdate=new Date(thistime*1000);

    var datestring=thisdate.toString();

    var timezone=datestring.match(/[(][ a-zA-Z]+[)]/);

    var time=datestring.match(/[0-9]{2}:[0-9]{2}:[0-9]{2}/);

    //return time+" "+timezone;
    return thisdate.toTimeString();
}
function formatMoodleDay(thistime){
    var thisdate=new Date(thistime*1000);

    var datestring=thisdate.toString();

    var date=datestring.match(/[a-zA-Z]{3} [a-zA-Z]{3} [0-9]{1,2} [0-9]{4}/);

    //return date;
    return thisdate.toDateString();
}

</script>
<!--<![endif]-->

<!--[if IE]>

<script type="text/javascript">

function displaytime(){

var localtimezone = serverdate.toTimeString().substr(8);
if(globaltimezone != localtimezone){
    window.location.reload();
}

serverdate.setSeconds(serverdate.getSeconds()+1);
//var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
//var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
//document.getElementById("servertime").innerHTML=datestring+" "+timestring

var datestring=serverdate.toString();

var timezone=datestring.match(/ [A-Z]+ /);
var date=datestring.match(/[a-zA-Z]{3} [a-zA-Z]{3} [0-9]{1,2}/);
var time=datestring.match(/[0-9]{2}:[0-9]{2}:[0-9]{2}/);
var year=datestring.match(/ [0-9]{4}/);
//document.write(date+"<br/>"+time+" "+timezone);

//document.getElementById("servertime").innerHTML=time+" "+timezone;
//document.getElementById("serverdate").innerHTML=date+" "+year;
document.getElementById("servertime").innerHTML=serverdate.toTimeString();
document.getElementById("serverdate").innerHTML=serverdate.toDateString();

}


// Formats the server date to display with the users browsers timezone
function formatMoodleDate(thistime){
    var thisdate=new Date(thistime*1000);

    var datestring=thisdate.toString();
    var timezone=datestring.match(/ [A-Z]+ /);
    var time=datestring.match(/[0-9]{2}:[0-9]{2}:[0-9]{2}/);
    //document.write(formatMoodleDatedate+"<br/>"+time+" "+timezone);
    //return time+" "+timezone;
    return thisdate.toTimeString();
}
function formatMoodleDay(thistime){
    var thisdate=new Date(thistime*1000);

    var datestring=thisdate.toString();
    var date=datestring.match(/[a-zA-Z]{3} [a-zA-Z]{3} [0-9]{1,2}/);
    var year=datestring.match(/ [0-9]{4}/);
    //return date+" "+year;
    return thisdate.toDateString();

}

</script>

<![endif]-->

<script type="text/javascript">

// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

//var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' //SSI method of getting server date
//var currenttime = '<? print date("F d, Y H:i:s", time())?>' //PHP method of getting server date
var currenttime = <?php echo time(); ?>

///////////Stop editting here/////////////////////////////////
//var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime*1000)

var globaltimezone = serverdate.toTimeString().substr(8);

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}



window.onload=function(){
setInterval("displaytime()", 1000)
}



</script>

<!-- <p><b>Current Server Time:</b> <span id="servertime"></span></p> -->

<font size='1' color='gray'>&nbsp;&nbsp; Current Time:&nbsp;&nbsp;</font><br/>
<font size='3' face='Arial'>&nbsp;&nbsp; <b><span id="servertime"></span></b></font><br/><br/>
<font size='3' face='Arial'>&nbsp;&nbsp; <span id="serverdate"></span></font>
