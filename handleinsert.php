<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');
require_once('game_form.php');
require_once('project_form.php');
require_once('type_form.php');

// set up the page
$title = get_string('pluginname', 'local_lor');
$pagetitle = $title;
$url = new moodle_url("/local/lor/handleinsert.php");
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

// game form
if (isset($_GET['gamecreator'])) { // check if there is a link from gamecreator
  $from_gamecreator = true;
  $game_form = new game_form(null, array('link' => $_GET['gamecreator'])); // send custom data to game form to pre-populate link field
} else {
  $game_form = new game_form();
}

// project form
$project_form = new project_form();

if ($fromform = $game_form->get_data()) {

  $pid = local_lor_add_game($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $fromform->link, $game_form);
  redirect(new moodle_url('/local/lor/insert.php', array('pid' => $pid)));

} else if ($fromform = $project_form->get_data()) {

  $pid = local_lor_add_project($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $project_form);
  redirect(new moodle_url('/local/lor/insert.php', array('pid' => $pid)));

} else {

  // update nav bar
  if ($SESSION->current_type == "game" || $from_gamecreator) { // game
    $PAGE->navbar->add(get_string('add_game', 'local_lor'));
  } else if ($SESSION->current_type == "project") { // project
    $PAGE->navbar->add(get_string('add_project', 'local_lor'));
  } else { // video
    $PAGE->navbar->add(get_string('add_video', 'local_lor'));
  }

  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  ?><link rel="stylesheet" href="styles.css"><?php

  // show insert form
  if ($SESSION->current_type == "game" || $from_gamecreator) { // game (check if linked to from gamecreator)
    $PAGE->navbar->add(get_string('add_game', 'local_lor'));
    $game_form->display();
  } else if ($SESSION->current_type == "project") { // project
    $PAGE->navbar->add(get_string('add_project', 'local_lor'));
    $project_form->display();
  } else { // video or animation
    $PAGE->navbar->add(get_string('add_video', 'local_lor'));
    echo "Not available yet.";
  }

  echo $OUTPUT->footer();
}
