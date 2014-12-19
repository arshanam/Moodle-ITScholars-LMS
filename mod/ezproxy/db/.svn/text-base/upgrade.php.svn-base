<?php  //$Id: upgrade.php,v 1.4 2009/07/15 18:23:19 adelamarre Exp $

// This file keeps track of upgrades to
// the ezproxy module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_ezproxy_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

/// And upgrade begins here. For each one, you'll need one
/// block of code similar to the next one. Please, delete
/// this comment lines once this file start handling proper
/// upgrade code.

    if ($result && $oldversion < 2009042403) {
    /// Rebuild the course cache of every course which uses one of these modules in it to get
    /// the new link.
        if ($courseids = get_records_menu('ezproxy', '', '', 'course ASC', 'id, course')) {
        /// Just get the unique course ID values.
            $courseids = array_unique(array_values($courseids));

            if (!empty($courseids)) {
                require_once($CFG->dirroot . '/course/lib.php');

                foreach ($courseids as $courseid) {
                    rebuild_course_cache($courseid);  // Does not return a bool
                }
            }
        }
    }

    if ($result && $oldversion < 2009042404) {
        $table = new XMLDBTable('ezproxy');
        $field = new XMLDBField('serverurl');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, '', 'name');

        $result = change_field_type($table, $field);
    }
    return $result;
}

?>