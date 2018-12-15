<?php

use \local_lor\insert\handler;

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');

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
$PAGE->requires->css('/local/lor/style/styles.css', true); // Require custom CSS stylesheet to style form.


// nav bar
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('nav_insert_form', 'local_lor'), new moodle_url('/local/lor/insert.php'));

require_login();

// Check if there is custom data from the game creator to send to a form.
$custom_data = null;
if (isset($_GET['gamecreator'])) {
  $custom_data = array('link' => $_GET['gamecreator']);
}



// Try to load and display the current form.
try {
  // Try to load current form.
  $current_form = handler::get_current_form();
  if ($current_form !== false) {
    if ($fromform = $current_form->get_data()) {
      // Insert the new LOR item.
      $inserted_id = handler::insert_item($fromform, $current_form);
      // Redirect to type_form.
      redirect(new moodle_url('/local/lor/insert.php', array('inserted_id' => $inserted_id)));
    } else if ($current_form->is_cancelled()) {
      // Go back to type_form.
      redirect(new moodle_url('/local/lor/insert.php'));
    } else {

      // Update the nav bar using the type name as found in the database.
      $PAGE->navbar->add(handler::get_navbar_string());

      // Output the page header.
      echo $OUTPUT->header();
      echo $OUTPUT->heading(get_string('heading', 'local_lor'));

      // Display the current form.
      $current_form->display();
    }
  } else {
    // Error loading form. No form with matching ID.

    // Output the page header.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('heading', 'local_lor'));

    echo get_string('error_missing_form', 'local_lor');

  }

} catch (Exception $e) {

  // Output the page header.
  echo $OUTPUT->header();
  echo $OUTPUT->heading(get_string('heading', 'local_lor'));

  // Output exception.
  echo get_string('error_unexpected', 'local_lor') . $e;

}


// project form
// $project_form = new project_form();
//
// if ($fromform = $game_form->get_data()) {
//
//   $pid = local_lor_add_game($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $fromform->link, $fromform->width, $fromform->height, $game_form);
//   redirect(new moodle_url('/local/lor/insert.php', array('pid' => $pid)));
//
// } else if ($fromform = $project_form->get_data()) {
//
//   $pid = local_lor_add_project($fromform->title, $fromform->categories, $fromform->topics, $fromform->contributors, $fromform->grades, $project_form);
//   redirect(new moodle_url('/local/lor/insert.php', array('pid' => $pid)));
//
// } else {

  // update nav bar
  // if ($SESSION->current_type == "game" || $from_gamecreator) { // game
  //   $PAGE->navbar->add(get_string('add_game', 'local_lor'));
  // } else if ($SESSION->current_type == "project") { // project
  //   $PAGE->navbar->add(get_string('add_project', 'local_lor'));
  // } else { // video
  //   $PAGE->navbar->add(get_string('add_video', 'local_lor'));
  // }






  echo $OUTPUT->footer();
