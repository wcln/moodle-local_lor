<?php

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

$PAGE->requires->js_call_amd('local_lor/app-lazy', 'init', [
    'contextid' => context_system::instance()->id,
]);

echo <<<HTML
<div id="local-lor-app">
  <router-view></router-view>
</div>
HTML;

echo $renderer->footer();
