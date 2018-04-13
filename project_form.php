<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once(__DIR__ . '/locallib.php');

class project_form extends moodleform {

	protected function definition() {
		global $CFG;
    global $SESSION;

		// getting categories for later
    $categories = local_lor_get_categories();
    $categories_arr = [];
    foreach ($categories as $category) {
			if ($category->name !== "Other") {
				$categories_arr[$category->id] = $category->name;
			} else {
				$addlast = $category;
			}
    }
		$categories_arr[$addlast->id] = $addlast->name; // ensure other is at the end

		$mform = $this->_form;

		// header
		$mform->addElement('header', 'about', get_string('about', 'local_lor'));

		// description textarea
    $mform->addElement('textarea', 'title', get_string('inquiry', 'local_lor'), 'wrap="virtual" rows="2" cols="50"');
    $mform->setType('title', PARAM_TEXT);
    $mform->addRule('title', get_string('required'), 'required', null);

		// topics text
		$mform->addElement('text', 'topics', get_string('topics', 'local_lor'));
		$mform->setType('topics', PARAM_TEXT);
		$mform->addRule('topics', get_string('required'), 'required', null);

		// category checkboxes
		$category_item = array();
		foreach ($categories_arr as $id => $category_name) {
			$category_item[] = &$mform->createElement('advcheckbox', $id, '', $category_name, array('name' => $id, 'group' => 1), $id);
		}
		$mform->addGroup($category_item, 'categories', get_string('categories', 'local_lor'));

		// grade checkboxes (1 to 12)
    $grades_arr = [];
		for ($i = 1; $i <= 12; $i++) {
			$grades_arr[] = $i;
		}
		$grade_item = array();
		foreach ($grades_arr as $grade) {
			$grade_item[] = &$mform->createElement('advcheckbox', $grade, '', $grade, array('name' => $grade, 'group' => 2), $grade);
		}
		$mform->addGroup($grade_item, 'grades', get_string('grade', 'local_lor'));

		// contributors
		$mform->addElement('text', 'contributors', get_string('contributors', 'local_lor'));
		$mform->setType('contributors', PARAM_TEXT);

		// header
		$mform->addElement('header', 'files', get_string('files', 'local_lor'));


    // word doc file
    $mform->addElement('filepicker', 'word', get_string('word', 'local_lor'), null, array('maxbytes'=>10000000, 'accepted_types'=>array('.doc', '.docx')));
    $mform->addRule('word', get_string('required'), 'required', null);

		// pdf file
    $mform->addElement('filepicker', 'pdf', get_string('pdf', 'local_lor'), null, array('maxbytes'=>10000000, 'accepted_types'=>array('.pdf')));
    $mform->addRule('pdf', get_string('required'), 'required', null);

		// image file
    $mform->addElement('filepicker', 'icon', get_string('icon', 'local_lor'), null, array('maxbytes'=>10000000, 'accepted_types'=>array('.png', '.jpg')));
    $mform->addRule('icon', get_string('required'), 'required', null);



		// submit button
		$this->add_action_buttons(false, get_string('submit', 'local_lor'));
	}

	public function validation($data, $files) {
		global $CFG;
		global $DB;
		$errors = parent::validation($data, $files);

    // check that all files have same ID
		$sql = 'SELECT id, filename, filesize FROM {files} WHERE itemid=? OR itemid=? OR itemid=?';
		$records = $DB->get_records_sql($sql, array($data['word'], $data['pdf'], $data['icon']));

		foreach ($records as $r1) {
			foreach ($records as $r2) {
				if ($r1->filesize > 0 && $r2->filesize > 0 && explode(".", $r1->filename, 2)[0] != explode(".", $r2->filename, 2)[0]) {
					$errors['word'] = $errors['pdf'] = $errors['icon'] = get_string('error_filenames', 'local_lor');
					break 2;
				} else {
					// check that file doesnt already exist on server
					if ($r1->filesize > 0 && file_exists($CFG->dirroot . '/LOR/projects/' . $r1->filename)) {
						$errors['word'] = $errors['pdf'] = $errors['icon'] = get_string('error_file_exists', 'local_lor');
						break 2;
					}
				}

			}
		}


    // check that ID doesnt exist in database
		// if (!isset($errors['word'])) { // only check if all files have same name
		// 	$filename = explode(".", array_pop($records)->filename, 2)[0];
    //
		// 	$sql = 'SELECT id FROM {content} WHERE id=?';
		// 	$records2 = $DB->get_records_sql($sql, array($filename));
    //
		// 	if (sizeof($records2) !== 0) {
		// 		$errors['word'] = $errors['pdf'] = $errors['icon'] = get_string('error_filename_exists', 'local_lor');
		// 	}
    //
		// }

		// check that at least one checkbox is checked
		 if(sizeof(array_filter($data['categories'])) === 0) {
			 $errors['categories'] = get_string('error_categories', 'local_lor');
		 }


		return array(); // temp, need to do validation again
	}
}
