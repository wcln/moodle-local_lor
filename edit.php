<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

require_once('edit_form.php');


// Get the item ID from the URL.
$id = null;
$item = null;
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $item = local_lor_get_content_from_id($id);
} else if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $item = local_lor_get_content_from_id($id);
} else {
  // TODO error.
}

// Set up the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("WCLN: LOR");
$PAGE->set_heading("WCLN Learning Material");
$PAGE->set_url(new moodle_url('/local/lor/edit.php', array('id' => $id)));


// Retrieve the renderer for the page.
$renderer = $PAGE->get_renderer('local_lor');

// Nav bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('nav_edit_form', 'local_lor'), new moodle_url("/local/lor/edit.php?id=$id"));

// Ensure user is logged in.
require_login();

// Fetch the current system context.
$systemcontext = context_system::instance();

// Ensure user has permission to be here.
if (has_capability('local/lor:edit', $systemcontext)) {

  // Initialize the form.
  $edit_form = new edit_form(null, array(
    'id' => $id,
    'type' => $item->type,
    'title' => $item->title,
    'topics' => local_lor_get_keywords_string_for_item($item->id),
    'categories' => local_lor_get_categories_for_item($item->id),
    'grades' => local_lor_get_grades_for_item($item->id),
    'contributors' => local_lor_get_contributors_string_for_item($item->id)
  ));

  // temp
  $delete = false;

  if ($data = $edit_form->get_data()) {

    // Ouput the header and custom heading.
    echo $OUTPUT->header();
    $a = new stdClass();
    $a->title = $item->title;
    echo $OUTPUT->heading(get_string('heading_edit', 'local_lor', $a));

    // Check if the delete button was clicked.
    if (isset($data->deletebutton)) {

      // Delete the item.
      local_lor_delete_item($id);

      // Render success template.
      $delete_success = new \local_lor\output\delete_success($item->title);
      echo $renderer->render($delete_success);

    } else { // The save button was clicked.

      // Update the item.
      local_lor_update_item($id, $data->title, $data->topics, $data->categories, $data->grades, $data->contributors);

      // Render success template.
      $update_success = new \local_lor\output\update_success($id);
      echo $renderer->render($update_success);

      // Display the form.
      $edit_form->display();

    }

  } else if ($edit_form->is_cancelled()) {

    // Cancel button clicked, redirect to the main LOR page.
    redirect(new moodle_url('/local/lor/index.php'));

  } else {

    // Ouput the header and custom heading.
    echo $OUTPUT->header();
    $a = new stdClass();
    $a->title = $item->title;
    echo $OUTPUT->heading(get_string('heading_edit', 'local_lor', $a));

    // Our first time here, or errors occurred, display the form.
    $edit_form->display();
  }

  // Ouput the footer.
  echo $OUTPUT->footer();

} else {

  // Output the page header.
  echo $OUTPUT->header();

  // Render HTML to tell the user they do not have permission to be here (no data required).
  echo $renderer->render_not_allowed(null);

  // Output the page footer.
  echo $OUTPUT->footer();

}

?>
