<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');
require_once('game_form.php');
require_once('project_form.php');
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


// nav bar
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('insert', 'local_lor'), new moodle_url('/local/lor/insert.php'));


require_login();

$from_gamecreator = false;

$type_form = new type_form();

if (isset($_POST['gamecreator'])) { // check if there is a link from gamecreator
  $from_gamecreator = true;
}



if (($fromform = $type_form->get_data()) || $from_gamecreator) {

  // update nav bar
  if ($fromform->type == 1 || $from_gamecreator) { // game
    $PAGE->navbar->add(get_string('add_game', 'local_lor'));
  } else if ($fromform->type == 2) { // project
    $PAGE->navbar->add(get_string('add_project', 'local_lor'));
  } else { // video
    $PAGE->navbar->add(get_string('add_video', 'local_lor'));
  }

  // show insert form
  if ($fromform->type == 1 || $from_gamecreator) { // game (check if linked to from gamecreator)
    $SESSION->current_type = "game";
  } else if ($fromform->type == 2) { // project
    $SESSION->current_type = "project";
  } else { // video or animation
    unset($SESSION->current_type);
  }

  redirect(new moodle_url("/local/lor/handleinsert.php", array('gamecreator' => $from_gamecreator)));

} else {

  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  if (isset($_GET['pid'])) {
    $success_output = $PAGE->get_renderer('local_lor');
    $renderable = new \local_lor\output\success_html($_GET['pid']);
    echo $success_output->render($renderable);
    unset($SESSION->current_type);
  }

  $type_form->display();
  echo $OUTPUT->footer();
}
