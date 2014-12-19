<?php
    require_once("../config.php");

$username=$_REQUEST['username'];
$password=$_REQUEST['password'];

//where are we posting to?
$url = $CFG->wwwroot .'/login/index.php';


//what post fields?
$fields = array(
   'username'=>$username,
   'password'=>$password,
   'testcookies'=>1
);

//build the urlencoded data
$postvars='';
$sep='';
foreach($fields as $key=>$value) 
{ 
   $postvars.= $sep.urlencode($key).'='.urlencode($value); 
   $sep='&'; 
}

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_COOKIESESSION,true);
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);

// echo '<br>ch: ' . $ch;

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
?>