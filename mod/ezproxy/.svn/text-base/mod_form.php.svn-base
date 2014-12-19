<?php // $Id: mod_form.php,v 1.6 2009/08/04 14:27:13 adelamarre Exp $
require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once ($CFG->dirroot.'/mod/ezproxy/lib.php');

class mod_ezproxy_mod_form extends moodleform_mod {

    function definition() {

        $mform    =& $this->_form;

        $mform->addElement('text', 'name', get_string('linkname', 'ezproxy'), 'maxlength="100" size="30"');
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', get_string('missinglinkname', 'ezproxy'), 'required', null, 'client');

        $mform->addElement('text', 'serverurl', get_string('serverurl', 'ezproxy'), 'maxlength="1000" size="30"');
        $mform->setType('serverurl', PARAM_TEXT);
        $mform->addRule('serverurl', get_string('missingserverurl', 'ezproxy'), 'required', null, 'client');

        $features = array('groups'=>false, 'groupings'=>true, 'groupmembersonly'=>true,
                          'outcomes'=>false, 'gradecat'=>false, 'idnumber'=>false);

        $this->standard_coursemodule_elements($features);

//-------------------------------------------------------------------------------
// buttons
        $this->add_action_buttons(true, false, null);

    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Disabled this feature because client wants to be able to paste
        // URLs with spaces and only have them trimed just before the page
        // is redirected.
//        if(!ezproxy_has_protocol($data['serverurl'])) {
//            $errors['serverurl'] = get_string('missingprotocol', 'ezproxy');        	
//        }
        
        return $errors;
    }

}
?>