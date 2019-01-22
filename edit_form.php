<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

class edit_form extends moodleform {

	protected function definition() {
		global $CFG;

		$type = $this->_customdata['type'];

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

		// Title text.
		if ($type == 2) {
			// If the type being edited is a project, show a different title string.
			$titlestring = 'inquiry';
		} else {
			$titlestring = 'title';
		}
		$mform->addElement('textarea', 'title', get_string($titlestring, 'local_lor'), 'wrap="virtual" rows="2" cols="50"');
    $mform->setType('title', PARAM_TEXT);
    $mform->addRule('title', get_string('required'), 'required', null);
		if (isset($this->_customdata['title'])) {
				$mform->setDefault('title', $this->_customdata['title']);
		}


		// Topics text.
		$mform->addElement('text', 'topics', get_string('topics', 'local_lor'));
		$mform->setType('topics', PARAM_TEXT);
		$mform->addRule('topics', get_string('required'), 'required', null);
		if (isset($this->_customdata['topics'])) {
			$mform->setDefault('topics', $this->_customdata['topics']);
		}

		// Category checkboxes.
		$category_item = array();
		foreach ($categories_arr as $id => $category_name) {
			$category_item[] = &$mform->createElement('advcheckbox', $id, '', $category_name, array('name' => $id, 'group' => 1), $id);
		}
		$mform->addGroup($category_item, 'categories', get_string('categories', 'local_lor'));
		$mform->addRule('categories', get_string('required'), 'required', null);
		if (isset($this->_customdata['categories'])) {
			foreach ($categories_arr as $id => $category_name) {
				foreach ($this->_customdata['categories'] as $category) {
					if ($id == $category->id) {
						$mform->setDefault("categories[$id]", true);
					}
				}
			}
		}

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
		if (isset($this->_customdata['grades'])) {
			foreach ($grades_arr as $grade) {
				foreach ($this->_customdata['grades'] as $existinggrade) {
					if ($grade == $existinggrade->grade) {
						$mform->setDefault("grades[$grade]", true);
					}
				}
			}
		}

		// Contributors.
		$mform->addElement('text', 'contributors', get_string('contributors', 'local_lor'));
		$mform->setType('contributors', PARAM_TEXT);
		if (isset($this->_customdata['contributors'])) {
			$mform->setDefault('contributors', $this->_customdata['contributors']);
		}

		if (isset($this->_customdata['id'])) {
			$mform->addElement('hidden', 'id', $this->_customdata['id']);
			$mform->setType('id', PARAM_INT);
		}

		// Buttons.
		$buttonarray=array();
		$buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
		$buttonarray[] = $mform->createElement('reset', 'resetbutton', get_string('revert'));
		$buttonarray[] = $mform->createElement('submit', 'deletebutton', get_string('delete'));
		$buttonarray[] = $mform->createElement('cancel');
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

	}

	public function validation($data, $files) {
		global $CFG;

		$errors = parent::validation($data, $files);

		// Check that at least one checkbox is checked.
		 if(sizeof(array_filter($data['categories'])) === 0) {
			 $errors['categories'] = get_string('error_categories', 'local_lor');
		 }

		 // Check length of title.
		 if (strlen($data['title']) >= 200) {
			 $errors['title'] = get_string('error_title_length', 'local_lor');
		 }

		return $errors;
	}
}
