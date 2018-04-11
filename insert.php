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

$type_form = new type_form();
$game_form = new game_form();
$project_form = new project_form();


if ($fromform = $type_form->get_data()) {

  // update nav bar
  if ($fromform->type == 1) { // game
    $PAGE->navbar->add(get_string('add_game', 'local_lor'));
  } else if ($fromform->type == 2) { // project
    $PAGE->navbar->add(get_string('add_project', 'local_lor'));
  } else { // video
    $PAGE->navbar->add(get_string('add_video', 'local_lor'));
  }

  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  ?><link rel="stylesheet" href="styles.css"><?php

  // show insert form
  if ($fromform->type == 1) { // game
    $PAGE->navbar->add(get_string('add_game', 'local_lor'));
    $game_form->display();
  } else if ($fromform->type == 2) { // project
    $PAGE->navbar->add(get_string('add_project', 'local_lor'));
    $project_form->display();
  } else { // video
    $PAGE->navbar->add(get_string('add_video', 'local_lor'));
    echo "Not available yet.";
  }

  echo $OUTPUT->footer();

} else if ($fromform = $game_form->get_data()) {

  $pid = local_lor_add_game($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $_POST['link'], $_POST['image']);
  redirect(new moodle_url($url, array('pid' => $pid)));

} else if ($fromform = $project_form->get_data()) {

  $pid = local_lor_add_project($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $project_form);
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
