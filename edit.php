<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

require_once('edit_form.php');

// Set up the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("WCLN: LOR");
$PAGE->set_heading("WCLN Learning Material");
$PAGE->set_url(new moodle_url('/local/lor/edit.php'));

// Retrieve the renderer for the page.
$renderer = $PAGE->get_renderer('local_lor');

// Get the item ID from the URL.
$id = null;
$item = null;
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $item = local_lor_get_content_from_id($id);

} else {
  // TODO Error no ID is set to edit.
}

// Nav bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('nav_edit_form', 'local_lor'), new moodle_url("/local/lor/edit.php?id=$id"));

// Ouput the header and custom heading.
echo $OUTPUT->header();
$a = new stdClass();
$a->title = $item->title;
echo $OUTPUT->heading(get_string('heading_edit', 'local_lor', $a));

// Ensure user is logged in.
require_login();

// Fetch the current system context.
$systemcontext = context_system::instance();

// Ensure user has permission to be here.
if (has_capability('local/lor:edit', $systemcontext)) {

  // Initialize the form.
  $edit_form = new edit_form();

  if ($data = $edit_form->get_data()) {

  } else {
    $edit_form->display();
  }


} else {
  die();
}

// Ouput the footer.
echo $OUTPUT->footer();
