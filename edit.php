<?php

// Prevent caching on this page.
header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Expires: 0");

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
}

// If an ID was not provided, or an item with that ID does not exist, output an error message on a blank page.
if (is_null($item) || !$item) {
  echo get_string('error_no_item', 'local_lor');
  die();
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

// Ensure user has permission to be here.
if (local_lor_is_designer()) {

  // Check if we are restoring an item.
  if (isset($_GET['undo'])) {
    // Restore the item.
    local_lor_undo_delete($id);
  }

  // Initialize the form.
  $edit_form = new edit_form(null, array(
    'id' => $id,
    'type' => $item->type,
    'title' => $item->title,
    'topics' => local_lor_get_topics_string_for_item($item->id),
    'categories' => local_lor_get_categories_for_item($item->id),
    'grades' => local_lor_get_grades_for_item($item->id),
    'contributors' => local_lor_get_contributors_string_for_item($item->id),
    'image' => $item->image,
    'link' => $item->link,
    'width' => $item->width,
    'height' => $item->height,
    'video_id' => local_lor_get_video_id_from_content_id($item->id),
    'book_id' => local_lor_get_book_id_from_content_id($item->id)
  ));

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
      $delete_success = new \local_lor\output\delete_success($id, $item->title);
      echo $renderer->render($delete_success);

    } else { // The save button was clicked.

      // Update the item.
      local_lor_update_item(

        // General settings.
        $id,
        $item->type,
        $data->title,
        $data->topics,
        $data->categories,
        $data->grades,
        $data->contributors,
        isset($data->link) ? $data->link : $item->link,

        // Game specific settings.
        isset($data->width) ? $data->width : null,
        isset($data->height) ? $data->height : null,

        // Video specific settings.
        isset($data->video_id) ? $data->video_id : null,

        // Lesson specific settings.
        isset($data->book_id) ? $data->book_id : null,

        // Form to handle files.
        $edit_form
      );

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
