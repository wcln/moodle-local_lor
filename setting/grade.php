<?php

use core\output\notification;
use local_lor\form\grade_form;
use local_lor\item\property\grade;
use local_lor\page;

require_once('../../../config.php');

$id      = required_param('id', PARAM_INT); // grade ID
$delete  = optional_param('delete', false, PARAM_BOOL); // Delete the grade?
$confirm = optional_param('confirm', false, PARAM_BOOL); // Delete confirmation

$grade = false;
if (! empty($id)) {
    $grade = grade::get($id);
}

$page_url   = new moodle_url('/local/lor/setting/grade.php', compact($id, $delete, $confirm));
$return_url = new moodle_url('/admin/settings.php', ['section' => 'local_lor']);

page::set_up(
    $page_url,
    get_string('manage_grade', 'local_lor'),
    get_string('manage_grade', 'local_lor')
);
$renderer = page::get_renderer();

$PAGE->navbar->add(get_string('plugin_settings', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('manage_grade', 'local_lor'), $page_url);

if (! empty($delete) && ($grade !== false)) {
    if (! confirm_sesskey()) {
        print_error('error_sesskey', 'local_lor');
    }

    if (! $confirm) {
        echo $renderer->header();

        $options = ['id' => $id, 'sesskey' => $USER->sesskey, 'delete' => 1, 'confirm' => 1];

        echo $renderer->confirm(get_string('grade_delete_confirm', 'local_lor', format_string($grade->name)),
            new moodle_url("/local/lor/setting/grade.php", $options),
            new moodle_url($return_url));

        echo $renderer->footer();
        die;
    } else {
        grade::delete($id);
        redirect($return_url, get_string('grade_delete_success', 'local_lor'), notification::NOTIFY_SUCCESS);
    }
}

$form = new grade_form(null, ['id' => $id]);

if ($form->is_cancelled()) {
    redirect($return_url);
} else if ($form_data = $form->get_data()) {
    if ($grade === false) {
        grade::create($form_data);
    } else {
        grade::update($id, $form_data);
    }

    redirect($return_url, get_string('grade_save_success', 'local_lor'));
} else if ($grade !== false) {
    $form->set_data($grade);
}

echo $renderer->header();
$form->display();
echo $renderer->footer();
