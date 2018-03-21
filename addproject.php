<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');
require_once('addproject_form.php');

// set up the page
$title = get_string('pluginname', 'local_projectspage');
$pagetitle = $title;
$url = new moodle_url("/local/projectspage/addproject.php");
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');



require_login();

$addproject_form = new addproject_form();

if ($fromform = $addproject_form->get_data()) {

  $pid = local_projectspage_add_project($fromform->description, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $addproject_form);
  redirect(new moodle_url($url, array('pid' => $pid)));


}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('heading', 'local_projectspage'));

if (isset($_GET['pid'])) {
  $success_output = $PAGE->get_renderer('local_projectspage');
  $renderable = new \local_projectspage\output\success_html($_GET['pid']);
  echo $success_output->render($renderable);
}

$addproject_form->display();

echo $OUTPUT->footer();
