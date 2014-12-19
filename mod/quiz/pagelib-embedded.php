<?php // $Id: pagelib.php,v 1.14.4.1 2007/11/02 16:19:58 tjhunt Exp $

require_once($CFG->libdir.'/pagelib.php');
require_once($CFG->dirroot.'/course/lib.php'); // needed for some blocks

define('PAGE_QUIZ_VIEW_EMBEDDED',   'mod-quiz-view-embedded');

page_map_class(PAGE_QUIZ_VIEW_EMBEDDED, 'page_quiz_embedded');

$DEFINEDPAGES = array(PAGE_QUIZ_VIEW_EMBEDDED);

/**
 * Class that models the behavior of an embedded quiz
 *
 * @author Jon Papaioannou
 * @package pages
 */

class page_quiz_embedded extends page_generic_activity {

    function init_quick($data) {
        if(empty($data->pageid)) {
            error('Cannot quickly initialize page: empty course id');
        }
        $this->activityname = 'quiz';
        parent::init_quick($data);
    }
  
    function get_type() {
        return PAGE_QUIZ_VIEW_EMBEDDED;
    }
}

?>
