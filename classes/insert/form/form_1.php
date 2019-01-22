<?php

namespace local_lor\insert\form;
use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once(__DIR__ . '/../../../locallib.php');

class form_1 extends moodleform {

	protected function definition() {
		global $CFG;
		global $USER;

		// Fetching categories for later.
    $categories = local_lor_get_categories();
    $categories_arr = [];
    foreach ($categories as $category) {
			if ($category->name !== "Other") {
				$categories_arr[$category->id] = $category->name;
			} else {
				$addlast = $category;
			}
    }
		$categories_arr[$addlast->id] = $addlast->name; // Ensure 'other' is at the end.

		$mform = $this->_form;

		// Header.
		$mform->addElement('header', 'about', get_string('about', 'local_lor'));

		// Description textarea.
    $mform->addElement('textarea', 'title', get_string('title', 'local_lor'), 'wrap="virtual" rows="2" cols="50"');
    $mform->setType('title', PARAM_TEXT);
    $mform->addRule('title', get_string('required'), 'required', null);

		// Topics text.
		$mform->addElement('text', 'topics', get_string('topics', 'local_lor'));
		$mform->setType('topics', PARAM_TEXT);
		$mform->addRule('topics', get_string('required'), 'required', null);

		// Category checkboxes.
		$category_item = array();
		foreach ($categories_arr as $id => $category_name) {
			$category_item[] = &$mform->createElement('advcheckbox', $id, '', $category_name, array('name' => $id, 'group' => 1), $id);
		}
		$mform->addGroup($category_item, 'categories', get_string('categories', 'local_lor'));
		$mform->addRule('categories', get_string('required'), 'required', null);

		// Grade checkboxes (1 to 12).
    $grades_arr = [];
		for ($i = 1; $i <= 12; $i++) {
			$grades_arr[] = $i;
		}
		$grade_item = array();
		foreach ($grades_arr as $grade) {
			$grade_item[] = &$mform->createElement('advcheckbox', $grade, '', $grade, array('name' => $grade, 'group' => 2), $grade);
		}
		$mform->addGroup($grade_item, 'grades', get_string('grade', 'local_lor'));

		// Contributors.
		$mform->addElement('text', 'contributors', get_string('contributors', 'local_lor'));
		$mform->setType('contributors', PARAM_TEXT);
		$mform->setDefault('contributors', fullname($USER));

		// Header.
		$mform->addElement('header', 'files', get_string('files', 'local_lor'));

    // Game link.
    $mform->addElement('text', 'link', get_string('link', 'local_lor'));
		if (!is_null($this->_customdata['link'])) {
					$mform->setDefault('link', $this->_customdata['link']); // pre-populate link field if link given from gamecreator
		}
    $mform->addRule('link', get_string('required'), 'required', null);
		$mform->setType('link', PARAM_NOTAGS);

		// Preview image.
		$mform->addELement('filepicker', 'image', get_string('image', 'local_lor'), null, array('maxbytes' => 1000000, 'accepted_types' => array('.png')));
		$mform->addRule('image', get_string('required'), 'required', null);

		// Header.
		$mform->addElement('header', 'iframe_size', get_string('iframe_size', 'local_lor'));
		$mform->setExpanded('iframe_size');

		// Paragraph.
		$mform->addElement('html', '<p>'.get_string('iframe_size_paragraph', 'local_lor').'</p>');

		// Width.
		$mform->addElement('text', 'width', get_string('width', 'local_lor'));
		$mform->setType('width', PARAM_INT);
		$mform->addRule('width', get_string('required'), 'required', null);

		// Height.
		$mform->addElement('text', 'height', get_string('height', 'local_lor'));
		$mform->setType('height', PARAM_INT);
		$mform->addRule('height', get_string('required'), 'required', null);

		// Submit and cancel buttons.
		$this->add_action_buttons(true, get_string('submit', 'local_lor'));

	}

	public function validation($data, $files) {
		global $CFG;

		$errors = parent::validation($data, $files);

		// Check that at least one checkbox is checked.
		 if(sizeof(array_filter($data['categories'])) === 0) {
			 $errors['categories'] = get_string('error_categories', 'local_lor');
		 }

		 // Check length of title.
		 if (strlen($data['title']) >= 150) {
			 $errors['title'] = get_string('error_title_length', 'local_lor');
		 }


		return $errors;
	}
}
