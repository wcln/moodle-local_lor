<?php

use local_lor\item\item;
use local_lor\page;

require_once(__DIR__.'/../../../config.php');

$itemid = required_param('id', PARAM_INT);

$item = item::get($itemid);

$page_url   = new moodle_url('/local/lor/item/edit.php', ['id' => $itemid]);
$return_url = new moodle_url('/local/lor/index.php');

$context = context_system::instance();
page::set_up(
    $page_url,
    get_string('edit_title', 'local_lor'),
    get_string('edit_heading', 'local_lor', $item->name)
);

$PAGE->navbar->add(get_string('lor_page', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('edit_title', 'local_lor'), $page_url);

require_login();
require_capability('local/lor:manage', context_system::instance());

$renderer = page::get_renderer();

$form = item::get_form($item->type, $itemid);

if ($form->is_cancelled()) {
    redirect($return_url);
} else {
    if ($form_data = $form->get_data()) {
        if (item::update($itemid, $form_data, $form)) {
            redirect($return_url, get_string('edit_success', 'local_lor'));
        } else {
            print_error('edit_error', 'local_lor', $return_url);
        }
    } else {
        echo $renderer->header();

        // Prepare data to send to the form
        $item->description  = [
            'text'   => $item->description,
            'format' => FORMAT_HTML,
        ];
        $item->categories   = array_keys($item->categories);
        $item->contributors = array_keys($item->contributors);
        $item->grades       = array_keys($item->grades);
        $item->topics       = implode(',', array_column($item->topics, 'name'));

        // Load the preview image to show on the form
        $draftitemid = file_get_submitted_draft_itemid('image');
        file_prepare_draft_area($draftitemid, $context->id, 'local_lor', 'preview_image', $item->id);
        $item->image = $draftitemid;

        // Send data to the form, and display the form
        $form->set_data($item);
        $form->display();
    }
}

echo $renderer->footer();
