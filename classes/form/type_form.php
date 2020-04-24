<?php

namespace local_lor\form;

use local_lor\type\type;
use moodleform;

require_once($CFG->libdir . '/formslib.php');

defined('MOODLE_INTERNAL') || die;

class type_form extends moodleform
{

    /**
     * Define the type form
     *
     * This form will allow users to select which type of item they would like to add
     */
    protected function definition()
    {
        $mform = $this->_form;

        $mform->addElement('html', \html_writer::tag('p', get_string('type_form_help', 'local_lor')));

        $mform->addElement(
            'select',
            'type',
            get_string('item_type', 'local_lor'),
            type::get_all_types()
        );
        $mform->setType('type', PARAM_TEXT);
        $mform->addRule('type', get_string('required'), 'required');

        $this->add_action_buttons(true, get_string('next'));
    }
}
