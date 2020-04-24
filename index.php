<?php

use local_lor\output\search_page;
use local_lor\page;

require_once(__DIR__ . '/../../config.php');

$page_url = new moodle_url('/local/lor/index.php');
page::set_up(
    $page_url,
    get_string('search_title', 'local_lor'),
    get_string('search_heading', 'local_lor')
);

$renderer = page::get_renderer();

echo $renderer->header();
echo $renderer->heading(get_string('search_heading', 'local_lor'));

$search_page = new search_page();
echo $renderer->render($search_page);

echo $renderer->footer();
