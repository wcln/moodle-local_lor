<?php

use local_lor\item\item;
use local_lor\output\item_view;
use local_lor\page;

require_once(__DIR__ . '/../../../config.php');

$itemid = required_param('id', PARAM_INT);

$item = item::get($itemid);

$page_url   = new moodle_url('/local/lor/item/view.php', ['id' => $itemid]);
$return_url = new moodle_url('/local/lor/index.php');

page::set_up($page_url, $item->name, $item->name);

$PAGE->navbar->add(get_string('lor_page', 'local_lor'), $return_url);
$PAGE->navbar->add(get_string('view_title', 'local_lor'), $page_url);

$renderer = page::get_renderer();

echo $renderer->header();

// Render the item view template
$item_view = new item_view($itemid);
echo $renderer->render($item_view);

echo $renderer->footer();
