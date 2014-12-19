<?php  // $Id: view.php,v 1.11 2009/08/04 14:27:13 adelamarre Exp $

    require_once("../../config.php");
    require_once($CFG->dirroot.'/mod/ezproxy/lib.php');
    global $USER;

    $id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
    $l = optional_param('l',0,PARAM_INT);     // Label ID

    if ($id) {
        if (! $cm = get_coursemodule_from_id('ezproxy', $id)) {
            error("Course Module ID was incorrect");
        }

        if (! $course = get_record("course", "id", $cm->course)) {
            error("Course is misconfigured");
        }

        if (! $ezproxy = get_record("ezproxy", "id", $cm->instance)) {
            error("Course module is incorrect");
        }

    } else {
        if (! $ezproxy = get_record("ezproxy", "id", $l)) {
            error("Course module is incorrect");
        }
        if (! $course = get_record("course", "id", $ezproxy->course)) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("ezproxy", $ezproxy->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

    require_login($course->id);

    $serverurl = trim($ezproxy->serverurl);
    $proxyurl = trim($CFG->ezproxy_serverurl);

/// The proxy URL *must* have a protocol prefix otherwise this doesn't work.
    if (!ezproxy_has_protocol($proxyurl)) {
        $proxyurl = 'http://' . $proxyurl;
    }

    if (!ezproxy_has_protocol($serverurl)) {
        error("Invalid URL formation");
    }

    $serverurl = trim($serverurl, '/');

    $url = $proxyurl . '/login?url=' . $serverurl;

    redirect($url);

?>