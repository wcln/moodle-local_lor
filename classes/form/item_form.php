<?php

namespace local_lor\form;

use coding_exception;
use dml_exception;
use local_lor\item\item;
use local_lor\item\property\category;
use local_lor\item\property\contributor;
use local_lor\item\property\grade;
use local_lor\type\type;
use moodleform;

require_once($CFG->libdir.'/formslib.php');

defined('MOODLE_INTERNAL') || die;

class item_form extends moodleform
{

    const NAME_MAX_LENGTH = 200;
    const DESCRIPTION_MAX_LENGTH = 500;

    /**
     * Define the item form
     *
     * This form will be shown for all item types
     */
    protected function definition()
    {
        global $USER;

        $mform = $this->_form;

        $type_class = type::get_class($this->_customdata['type']);

        $mform->addElement('hidden', 'id', isset($this->_customdata['id']) ? $this->_customdata['id'] : 0);
        $mform->setType('id', PARAM_INT);

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
            get_string('item_description', 'local_lor'),
            ['maxfiles' => 0, 'maxbytes' => 0, 'enable_filemanagement' => false]
        );
        $mform->addHelpButton('description', 'item_description', 'local_lor');

        // Preview image
        if ($type_class::get_image_url() === false) {
            $mform->addElement(
                'filemanager',
                'image',
                get_string('item_image', 'local_lor'),
                null,
                ['maxfiles' => 1, 'accepted_types' => ['.jpg', '.png', '.jpeg']]
            );
            $mform->addHelpButton('image', 'item_image', 'local_lor');
            $mform->addRule('image', get_string('required'), 'required');
        }

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
        $mform->addElement(
            'header',
            'type_header',
            get_string(
                'type_header',
                'local_lor',
                $type_class::get_name()
            )
        );
        $type_class::add_to_form($mform, isset($this->_customdata['id']) ? $this->_customdata['id'] : 0);

        $this->add_action_buttons();
    }

    /**
     * Validate the form
     *
     * @param  array  $data
     * @param  array  $files
     *
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    function validation($data, $files)
    {
        global $DB;

        $errors = [];

        // Validate that the 'name' field is unique
        if ($DB->record_exists_select(item::TABLE, 'name LIKE :name AND id != :id',
            ['name' => $data['name'], 'id' => $data['id']])
        ) {
            $errors['name'] = get_string('error:name_exists', 'local_lor');
        }

        // Validate the length of the 'name' field
        if (strlen($data['name']) >= self::NAME_MAX_LENGTH) {
            $errors['name'] = get_string('error:name_length', 'local_lor');
        }

        // Validate the length of the 'description' field
        if (strlen($data['description']['text']) >= self::DESCRIPTION_MAX_LENGTH) {
            $errors['description'] = get_string('error:description_length', 'local_lor');
        }

        // Ensure the title does not include '/'
        if (strpos($data['name'], '/')) {
            $errors['name'] = get_string('error:name_no_symbol', 'local_lor', '/');
        }

        return $errors;
    }
}
