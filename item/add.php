<?php

use local_lor\form\type_form;
use local_lor\item\item;
use local_lor\page;

require_once(__DIR__ . '/../../../config.php');

$type = optional_param('type', false, PARAM_TEXT);

$page_url   = new moodle_url('/local/lor/item/add.php', ['type' => $type]);
$return_url = new moodle_url('/local/lor/index.php');

page::set_up(
    $page_url,
    get_string('add_title', 'local_lor'),
    get_string('add_heading', 'local_lor')
);

$PAGE->navbar->add(get_string('lor_page', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('add_title', 'local_lor'), $page_url);

$renderer = page::get_renderer();

if (empty($type)) {
    $form = new type_form();

    if ($form->is_cancelled()) {
        redirect($return_url);
    } else {
        echo $renderer->header();
        echo $renderer->heading(get_string('add_heading', 'local_lor'));
        $form->display();
    }
} else {

    $form = item::get_form($type);

    if ($form->is_cancelled()) {
        redirect($return_url);
    } else {

        if ($form_data = $form->get_data()) {
            if (item::create($form_data, $form)) {
                redirect($return_url, get_string('add_success', 'local_lor'));
            } else {
                print_error('add_error', 'local_lor', $return_url);
            }
        } else {
            echo $renderer->header();
            echo $renderer->heading(get_string('add_heading', 'local_lor'));
            $form->display();
        }
    }
}

echo $renderer->footer();
