<?php
session_start();
include "global.php";
if(isset($_POST['submit'])){
//get stored password from database
$sql= "SELECT id,name FROM users WHERE pass='".MD5($_POST['pw'])."'";
$res = mysql_query($sql);
if($res){
$row= mysql_fetch_assoc($res);
$name= $row['name'];
$_SESSION['name'] =$name ;
$_SESSION['id'] =$row['id'] ;
header("location:editor.php");
}else{
echo "Your details did not match";
exit;
}
}
/**IF your password is stored in a txtfile
if(isset($_POST['submit'])){
//get pass from file
if(file_exists('passfile.txt')){
//open up the file
if($file_pointer = fopen('passfile.txt','r')){
$pass=fread($file_pointer,15);
//echo $pass;
fclose($file_pointer);
//compare the hashed password against the pass from the form
if(md5($_POST['pw'])==$pass){
header("location:editor.php");
}
}else{
echo "An error occurred while reading the file";
}
}else{
echo "Could not carry out the file operation because file does not exists.";
}//fileexists
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-size: 36px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<form action="login.php" method="post">
<table width="100%"  border="1">
  <tr>
    <td colspan="2"><span class="style1">My Diary Login </span></td>
  </tr>
  <tr>
    <td width="14%"> </td>
    <td width="86%"> </td>
  </tr>
  <tr>
    <td>Login:</td>
    <td><input type="password" name="pw"></td>
  </tr>
  <tr>
    <td> </td>
    <td><input type="submit" name="submit" value="submit"></td>
  </tr>
</table>
</form>
</body>
</html>
