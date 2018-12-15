<?php

use \local_lor\insert\type_form;
use \local_lor\insert\handler;

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');

// Set up the page.
$title = get_string('pluginname', 'local_lor');
$pagetitle = $title;
$url = new moodle_url("/local/lor/insert.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

// Nav bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('nav_insert_form', 'local_lor'), new moodle_url('/local/lor/insert.php'));

require_login();

$from_gamecreator = false;

$type_form = new type_form();

if (isset($_POST['gamecreator'])) { // check if there is a link from gamecreator
  $from_gamecreator = true;
}

if (($fromform = $type_form->get_data()) || $from_gamecreator) {

  if ($from_gamecreator) {
    $fromform->type = 1;
  }

  handler::set_current_type($fromform->type);

  if ($from_gamecreator) {
    redirect(new moodle_url("/local/lor/handleinsert.php", array('gamecreator' => $_POST['gamecreator'])));
  } else {
    redirect(new moodle_url("/local/lor/handleinsert.php"));
  }

} else {

  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  if (isset($_GET['inserted_id'])) {
    $success_output = $PAGE->get_renderer('local_lor');
    $renderable = new \local_lor\output\success_html($_GET['inserted_id']);
    echo $success_output->render($renderable);
    unset($SESSION->current_type);
  }

  $type_form->display();
  echo $OUTPUT->footer();
}
