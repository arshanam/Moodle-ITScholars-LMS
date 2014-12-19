<?php  // $Id: index.php,v 1.201.2.10 2009/04/25 21:18:24 stronk7 Exp $
       // index.php - the front page.

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////


    if (!file_exists('./config.php')) {
        header('Location: install.php');
        die;
    }

    require_once('config.php');
    require_once($CFG->dirroot .'/course/lib.php');
    require_once($CFG->dirroot .'/lib/blocklib.php');

    // get values from form for actions on this page
    $param = new stdClass();

    // Parameters: create a URL that displays the last (largest) quiz id and name. (eg. 2 Test Exam)
    ?>
<html>
<head>
<style type="text/css">
a:focus, a:active
{
color:green;
}
</style>

<script type="text/javascript">
function getfocus()
{
document.getElementById('myAnchor').focus();
}

function losefocus()
{
document.getElementById('myAnchor').blur();
}
</script>
</head>

<body>


<input type="button" onclick="getfocus()" value="Get focus">
<input type="button" onclick="losefocus()" value="Lose focus">
<br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/>
<a id="myAnchor" href="http://www.w3schools.com">Visit W3Schools.com</a>
<br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/><br /><br/>
</body>

</html>
  <script type="text/javascript">

getfocus();


</script>

