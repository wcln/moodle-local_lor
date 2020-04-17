<?php

use core\output\notification;
use local_lor\item\property\category;
use local_lor\page;

require_once('../../../config.php');

$id      = required_param('id', PARAM_INT); // Category ID
$delete  = optional_param('delete', false, PARAM_BOOL); // Delete the category?
$confirm = optional_param('confirm', false, PARAM_BOOL); // Delete confirmation

$category = false;
if (! empty($id)) {
    $category = category::get($id);
}

$page_url   = new moodle_url('/local/lor/setting/category.php', compact($id, $delete, $confirm));
$return_url = new moodle_url('/admin/settings.php', ['section' => 'local_lor']);

page::set_up(
    $page_url,
    get_string('manage_category', 'local_lor'),
    get_string('manage_category', 'local_lor')
);
$renderer = page::get_renderer();

$PAGE->navbar->add(get_string('plugin_settings', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('manage_category', 'local_lor'), $page_url);

if (! empty($delete) && ($category !== false)) {
    if (! confirm_sesskey()) {
        print_error('error_sesskey', 'local_lor');
    }

    if (! $confirm) {
        echo $renderer->header();

        $options = ['id' => $id, 'sesskey' => $USER->sesskey, 'delete' => 1, 'confirm' => 1];

        echo $renderer->confirm(get_string('category_delete_confirm', 'local_lor', format_string($category->name)),
            new moodle_url("/local/lor/setting/category.php", $options),
            new moodle_url($return_url));

        echo $renderer->footer();
        die;
    } else {
        category::delete($id);
        redirect($return_url, get_string('category_delete_success', 'local_lor'), notification::NOTIFY_SUCCESS);
    }
}

$form = new \local_lor\form\category_form(null, ['id' => $id]);

if ($form->is_cancelled()) {
    redirect($return_url);
} else if ($form_data = $form->get_data()) {
    if ($category === false) {
        category::create($form_data);
    } else {
        category::update($id, $form_data);
    }

    redirect($return_url, get_string('category_save_success', 'local_lor'));
} else if ($category !== false) {
    $form->set_data($category);
}

echo $renderer->header();
$form->display();
echo $renderer->footer();
