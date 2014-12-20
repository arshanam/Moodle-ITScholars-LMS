<?php  /// Moodle Configuration File 

unset($CFG);

$CFG->dbtype    = 'mysql';
$CFG->dbhost    = 'ita-portal.cis.fiu.edu:3390';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'portal';
$CFG->dbpass    = 'k4se*prt4l';
$CFG->dbpersist =  false;
$CFG->prefix    = 'mdl_';

// original wwwroot
$CFG->wwwroot   = 'http://localhost/moodle';

// try this to open Moodle4Mac for intranet or internet
// $CFG->wwwroot = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/moodle19';

$CFG->dirroot   = '/Applications/MAMP/htdocs/moodle';
$CFG->dataroot  = '/Applications/MAMP/data/moodle';
$CFG->admin     = 'admin';

// $CFG->passwordsaltmain = 'some_very_long_secret!#A12345678901234567890!';
$CFG->directorypermissions = 00777;  // try 02777 on a server in Safe Mode

$CFG->unicodedb = true;  // Database is utf8

require_once("$CFG->dirroot/lib/setup.php");
// MAKE SURE WHEN YOU EDIT THIS FILE THAT THERE ARE NO SPACES, BLANK LINES,
// RETURNS, OR ANYTHING ELSE AFTER THE TWO CHARACTERS ON THE NEXT LINE.
?>
