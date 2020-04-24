<?php

use local_lor\item\item;
use local_lor\page;

require_once(__DIR__ . '/../../../config.php');

$type = required_param('type', PARAM_TEXT);

$type_class = item::get_type_class($type);

$page_url   = new moodle_url('/local/lor/item/add.php', ['type' => $type]);
$return_url = new moodle_url('/local/lor/index.php');

page::set_up(
    $page_url,
    get_string('add_title', 'local_lor'),
    get_string('add_heading', 'local_lor', $type_class::get_name())
);

$PAGE->navbar->add(get_string('lor_page', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('add_title', 'local_lor'), $page_url);

$renderer = page::get_renderer();

echo $renderer->header();

$form = item::get_form($type);

if ($form->is_cancelled()) {
    redirect($return_url);
} else {
    if ($form_data = $form->get_data()) {
        if (item::create($form_data)) {
            redirect($return_url, get_string('add_success', 'local_lor'));
        } else {
            print_error('add_error', 'local_lor', $return_url);
        }
    } else {
        $form->display();
    }
}

echo $renderer->footer();
