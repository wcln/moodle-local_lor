<?php

namespace local_lor\form;

defined('MOODLE_INTERNAL') || die;

class item_form extends \moodleform {

    /**
     * Define the item form
     *
     * This form will be shown for all item types
     */
    protected function definition() {
        // TODO: Implement definition() method.
        $mform = $this->_form;
        $this->add_action_buttons();
    }
}
