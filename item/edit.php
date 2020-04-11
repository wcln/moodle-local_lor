<?php

use local_lor\item\item;
use local_lor\page;

require_once(__DIR__ . '/../../../config.php');

$itemid = required_param('id', PARAM_INT);

$item       = item::get($itemid);
$type_class = item::get_type_class($item->type);

$page_url   = new moodle_url('/local/lor/item/edit.php', ['id' => $itemid]);
$return_url = new moodle_url('/local/lor/item/index.php');

page::set_up(
    $page_url,
    get_string('edit_title', 'local_lor'),
    get_string('edit_heading', 'local_lor', $item->name)
);

$PAGE->navbar->add(get_string('lor_page', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('edit_title', 'local_lor'), $page_url);

$renderer = page::get_renderer();

echo $renderer->header();

$form = item::get_form($item->type, $itemid);

if ($form->is_cancelled()) {
    redirect($return_url);
} else if ($form_data = $form->get_data()) {
    if (item::update($itemid, $form_data)) {
        redirect($return_url, get_string('edit_success', 'local_lor'));
    } else {
        print_error('edit_error', 'local_lor', $return_url);
    }
} else {
    $form->display();
}

echo $renderer->footer();
