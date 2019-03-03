<?php

use \local_lor\insert\type_form;
use \local_lor\insert\handler;

// Require standard config file and local library.
require_once(__DIR__ . '/../../config.php');
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

// Retrieve the renderer for the page.
$renderer = $PAGE->get_renderer('local_lor');

// Nav bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('nav_insert_form', 'local_lor'), new moodle_url('/local/lor/insert.php'));

// Ensure the user is logged in to proceed.
require_login();

// Check if the user has the ability to insert into the LOR.
if (local_lor_is_designer()) {

  // Will be used to store if a game link was received from the Game Creator plugin.
  $from_gamecreator = false;

  // Initialize the type form.
  $type_form = new type_form();

  // Check if there is a link from the Game Creator.
  if (isset($_POST['gamecreator'])) {
    $from_gamecreator = true;
  }

  if (($fromform = $type_form->get_data()) || $from_gamecreator) {

    // If a link from Game Creator was received, we know the type is 1 (game).
    if ($from_gamecreator) {
      $fromform->type = 1;
    }

    // Set the current insert type.
    handler::set_current_type($fromform->type);

    // Redirect to show the correct form depending on the type selected.
    if ($from_gamecreator) {
      redirect(new moodle_url("/local/lor/handleinsert.php", array('gamecreator' => $_POST['gamecreator'])));
    } else {
      redirect(new moodle_url("/local/lor/handleinsert.php"));
    }

  } else {

    // Output the header and the custom heading.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('heading', 'local_lor'));

    // If an inserted_id has been provided, show the success html and a link to the just inserted item.
    if (isset($_GET['inserted_id'])) {
      $insert_success = new \local_lor\output\insert_success($_GET['inserted_id']);
      echo $renderer->render($insert_success);

      // An item was just inserted, clear the current type.
      unset($SESSION->current_type);
    }

    // Display the form and output the footer.
    $type_form->display();
    echo $OUTPUT->footer();
  }


} else { // The user does not have permission to be here.

  // Output the page header.
  echo $OUTPUT->header();

  // Render HTML to tell the user they do not have permission to be here (no data required).
  echo $renderer->render_not_allowed(null);

  // Output the page footer.
  echo $OUTPUT->footer();
}
