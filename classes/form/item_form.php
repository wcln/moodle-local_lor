<?php

namespace local_lor\form;

use local_lor\item\property\category;
use local_lor\item\property\contributor;
use local_lor\item\property\grade;
use local_lor\type\type;
use moodleform;

require_once($CFG->libdir . '/formslib.php');

defined('MOODLE_INTERNAL') || die;

class item_form extends moodleform
{

    /**
     * Define the item form
     *
     * This form will be shown for all item types
     */
    protected function definition()
    {
        global $USER;

        $mform = $this->_form;

        $mform->addElement(
            'header',
            'general',
            get_string('general_settings', 'local_lor')
        );

        // Name
        $mform->addElement(
            'text',
            'name',
            get_string('item_name', 'local_lor')
        );
        $mform->setType('name', PARAM_TEXT);
        $mform->addHelpButton('name', 'item_name', 'local_lor');
        $mform->addRule('name', get_string('required'), 'required');

        // Description
        $mform->addElement(
            'editor',
            'description',
            get_string('item_description', 'local_lor')
        );
        $mform->addHelpButton('description', 'item_description', 'local_lor');

        // Preview image
        $mform->addElement(
            'filemanager',
            'image',
            get_string('item_image', 'local_lor'),
            null,
            ['maxfiles' => 1, 'accepted_types' => ['.jpg', '.png', '.jpeg']]
        );
        $mform->addHelpButton('image', 'item_image', 'local_lor');
        $mform->addRule('image', get_string('required'), 'required');

        // Topics
        $mform->addElement(
            'text',
            'topics',
            get_string('item_topics', 'local_lor')
        );
        $mform->setType('topics', PARAM_TEXT);
        $mform->addHelpButton('topics', 'item_topics', 'local_lor');
        $mform->addRule('topics', get_string('required'), 'required');

        // Categories
        $mform->addElement(
            'autocomplete',
            'categories',
            get_string('item_categories', 'local_lor'),
            category::get_all_menu(),
            ['multiple' => true]
        );
        $mform->addHelpButton('categories', 'item_categories', 'local_lor');
        $mform->addRule('categories', get_string('required'), 'required');

        // Grades
        $mform->addElement(
            'autocomplete',
            'grades',
            get_string('item_grades', 'local_lor'),
            grade::get_all_menu(),
            ['multiple' => true]
        );
        $mform->addHelpButton('grades', 'item_grades', 'local_lor');

        // Contributors
        $mform->addElement(
            'autocomplete',
            'contributors',
            get_string('item_contributors', 'local_lor'),
            contributor::get_form_options(),
            ['multiple' => true]
        );
        $mform->addHelpButton('contributors', 'item_contributors', 'local_lor');
        $mform->addRule('contributors', get_string('required'), 'required');
        $mform->setDefault('contributors', $USER->id);

        // Add type specific elements to the form
        $mform->addElement('hidden', 'type', $this->_customdata['type']);
        $mform->setType('type', PARAM_TEXT);
        $type_class = type::get_class($this->_customdata['type']);
        $mform->addElement(
            'header',
            'type_header',
            get_string(
                'type_header',
                'local_lor',
                $type_class::get_name()
            )
        );
        $type_class::add_to_form($mform);

        $this->add_action_buttons();
    }
}
