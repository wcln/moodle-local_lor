<?php

namespace local_lor\form;

use local_lor\item\item;

require_once($CFG->libdir . '/formslib.php');

defined('MOODLE_INTERNAL') || die;

class item_form extends \moodleform {

    /**
     * Define the item form
     *
     * This form will be shown for all item types
     */
    protected function definition() {

        $mform = $this->_form;

        $mform->addElement('header', 'general', 'General');
        $mform->addElement('text', 'test1', 'FROM BASE FORM');

        // Add type specific elements to the form
        $type_class = item::get_type_class($this->_customdata['type']);
        $mform->addElement('header', 'type_header', get_string('type_header', 'local_lor', $type_class::get_name()));
        $type_class::add_to_form($mform);

        $this->add_action_buttons();
    }
}
