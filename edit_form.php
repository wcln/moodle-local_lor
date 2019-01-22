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

		// Output type specific form elements.
		if (isset($this->_customdata['type'])) {

			$a = new stdClass();
			$a->name = local_lor_get_type_name_from_id($this->_customdata['type']);
			$mform->addElement('header', 'typeheader', get_string('type_header', 'local_lor', $a));

			switch ($this->_customdata['type']) {
				// Game.
				case 1:

					// Link to game.
					$mform->addElement('text', 'link', get_string('link', 'local_lor'));
					$mform->addRule('link', get_string('required'), 'required', null);
					$mform->setType('link', PARAM_NOTAGS);
					if (isset($this->_customdata['link'])) {
								$mform->setDefault('link', $this->_customdata['link']);
					}

					// Preview image.
					$mform->addElement('html', "<p>The current preview image is: </p><img width='200px' height='150px' src='".$this->_customdata['image']."'/><p>Upload a new preview image to replace the existing one. You may have to refresh the page (CTRL-F5) for the image to change.</p>");
					$mform->addELement('filepicker', 'image', get_string('image', 'local_lor'), null, array('maxbytes' => 1000000, 'accepted_types' => array('.png')));
					$mform->addHelpButton('image', 'image', 'local_lor');

					// Paragraph.
					$mform->addElement('html', '<p>'.get_string('iframe_size_paragraph', 'local_lor').'</p>');

					// Width.
					$mform->addElement('text', 'width', get_string('width', 'local_lor'));
					$mform->setType('width', PARAM_INT);
					$mform->addRule('width', get_string('required'), 'required', null);
					if (isset($this->_customdata['width'])) {
						$mform->setDefault('width', $this->_customdata['width']);
					}

					// Height.
					$mform->addElement('text', 'height', get_string('height', 'local_lor'));
					$mform->setType('height', PARAM_INT);
					$mform->addRule('height', get_string('required'), 'required', null);
					if (isset($this->_customdata['height'])) {
						$mform->setDefault('height', $this->_customdata['height']);
					}

					break;
				// Project.
				case 2:

					$mform->addElement('html', '<p>Existing documents:</p><ul>'
						. '<li><a target="_blank" href="'.preg_replace('/.pdf$/', '.docx', $this->_customdata['link']).'">Word document</a></li>'
						. '<li><a target="_blank" href="'.$this->_customdata['link'].'">PDF</a></li>'
						. '<li><a target="_blank" href="'.preg_replace('/.pdf$/', '.png', $this->_customdata['link']).'">Preview Image</a></li>'
						. '</ul>'
						. '<p>To replace any of the above documents, upload a file below.</p>'
					);

					// Word Document.
			    $mform->addElement('filepicker', 'word', get_string('word', 'local_lor'), null, array('maxbytes'=>10000000, 'accepted_types'=>array('.doc', '.docx')));

					// PDF.
			    $mform->addElement('filepicker', 'pdf', get_string('pdf', 'local_lor'), null, array('maxbytes'=>10000000, 'accepted_types'=>array('.pdf')));

					// Image file.
			    $mform->addElement('filepicker', 'icon', get_string('icon', 'local_lor'), null, array('maxbytes'=>10000000, 'accepted_types'=>array('.png')));

					break;
				// Video.
				case 3:

					// Video ID.
					$mform->addElement('text', 'video_id', get_string('video_id', 'local_lor'));
					$mform->addRule('video_id', get_string('required'), 'required', null);
					$mform->setType('video_id', PARAM_TEXT);
					$mform->addHelpButton('video_id', 'video_id', 'local_lor');
					if (isset($this->_customdata['video_id'])) {
						$mform->setDefault('video_id', $this->_customdata['video_id']);
					}

					break;
				// Lesson.
				case 5:

					// Book ID.
					$mform->addElement('text', 'book_id', get_string('book_id', 'local_lor'));
					$mform->addRule('book_id', get_string('required'), 'required', null);
					$mform->setType('book_id', PARAM_INT);
					if (isset($this->_customdata['book_id'])) {
						$mform->setDefault('book_id', $this->_customdata['book_id']);
					}

					// Preview image.
					$mform->addElement('html', "<p>The current preview image is: </p><img width='200px' height='150px' src='".$this->_customdata['image']."'/><p>Upload a new preview image to replace the existing one.</p>");
					$mform->addELement('filepicker', 'image', get_string('image', 'local_lor'), null, array('maxbytes' => 1000000, 'accepted_types' => array('.png')));
					$mform->addHelpButton('image', 'image', 'local_lor');

					break;
				// Learning Guide.
				case 6:

					$a->id = $this->_customdata['id'];
					$mform->addElement('html', '<p>'.get_string('edit_server', 'local_lor', $a).'</p>');

					break;
			}
		}

		// Close header.
		$mform->closeHeaderBefore('buttonar');

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
