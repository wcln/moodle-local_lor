<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');
require_once('insert_form.php');
require_once('type_form.php');

// set up the page
$title = get_string('pluginname', 'local_lor');
$pagetitle = $title;
$url = new moodle_url("/local/lor/insert.php");
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');


require_login();

$type_form = new type_form();
$type = null;
if ($fromform = $type_form->get_data()) {
    $type = $fromform->type + 1;
}

$insert_form = new insert_form(null, $type);


if ($fromform = $type_form->get_data()) {

  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  // show insert form
  $insert_form->display();

  echo $OUTPUT->footer();

} else if ($fromform = $insert_form->get_data()) {

  $pid = -1;
  // check type
  if ($fromform->type == 1) { // game

    $pid = local_lor_add_game($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $_POST['link'], $_POST['image'], $fromform->platform);

  } else if ($fromform->type == 2) { // project

    $pid = local_lor_add_project($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $addproject_form);

  }

  redirect(new moodle_url($url, array('pid' => $pid)));
} else {
  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  if (isset($_GET['pid'])) {
    $success_output = $PAGE->get_renderer('local_lor');
    $renderable = new \local_lor\output\success_html($_GET['pid']);
    echo $success_output->render($renderable);
  }

  $type_form->display();


  echo $OUTPUT->footer();
}
