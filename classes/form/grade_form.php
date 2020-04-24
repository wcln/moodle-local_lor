<?php

namespace local_lor\form;

use moodleform;

require_once($CFG->libdir . '/formslib.php');

defined('MOODLE_INTERNAL') || die;

class grade_form extends moodleform
{

    /**
     * Define the grade form
     */
    protected function definition()
    {
        $mform = $this->_form;

        $mform->addElement('header', 'general', get_string('general'));

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->setType('id', PARAM_INT);

        $mform->addElement(
            'text',
            'name',
            get_string('grade_name', 'local_lor')
        );
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required');

        $this->add_action_buttons();
    }
}
