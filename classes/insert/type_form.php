<?php

namespace local_lor\insert;

use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');
require_once(__DIR__ . '/../../locallib.php');

class type_form extends moodleform {

    protected function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('html', '<p>' . get_string('subheading', 'local_lor') . '</p>');

        $types_arr = array();
        $types     = local_lor_get_types();
        foreach ($types as $type) {
            if ($type->id != 2) {
                $types_arr[$type->id] = $type->name;
            } else {
                $types_arr = array($type->id => $type->name) + $types_arr; // ensure Project is first option
            }
        }

        $mform->addElement('select', 'type', get_string('type', 'local_lor'), $types_arr);

        // next button
        $this->add_action_buttons(false, get_string('next', 'local_lor'));
    }

    public function validation($data, $files) {
        global $CFG;
        global $DB;
        $errors = parent::validation($data, $files);

        return $errors;
    }
}

?>
