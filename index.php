<?php

// Standard config file and local library.
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

// Search form.
require_once('search_form.php');

// Settings
define("ITEMS_PER_PAGE", 25);

// Check the URL for search arguments.
$id = null;
$page = 0;

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

if (isset($_GET['page'])) {
  $page = (int) $_GET['page'] - 1;
}

// Setting up the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("WCLN: LOR");
$PAGE->set_heading("WCLN Learning Material");
$PAGE->set_url(new moodle_url('/local/lor/index.php'));

// Require Javascript and CSS files.
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url("https://bclearningnetwork.com/lib/bootstrap/bootstrap.min.js"));
$PAGE->requires->js(new moodle_url("js/navigation.js"));
$PAGE->requires->js(new moodle_url("js/modal_handler.js"));

// Configuring the Nav bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));

// Ouput the header.
echo $OUTPUT->header();

// Initialize and display the search form.
$search_form = new search_form();
$search_form->display();

// Check if search form was submitted.
if ($search_data = $search_form->get_data()) {

  // Remove grade and category items if checkbox is not set.
  foreach (array_keys($search_data->categories, "", true) as $key) {
    unset($search_data->categories[$key]);
  }
  if (count($search_data->categories) === 0) {
    $search_data->categories = null;
  }
  foreach (array_keys($search_data->grades, "", true) as $key) {
    unset($search_data->grades[$key]);
  }
  if (count($search_data->grades) === 0) {
    $search_data->grades = null;
  }

  // Search for specific content.
  $items = local_lor_get_content($search_data->type, $search_data->categories, $search_data->grades, $search_data->sort_by, $search_data->keywords);

} else { // Search form was not submitted.

  // Get all content.
  $items = local_lor_get_content(null, null, null, null, null);
}

// Retrieve the renderer for the page.
$content_output = $PAGE->get_renderer('local_lor');

// Send all of the items, the current page, and the number of items to be displayed per page.
$renderable = new \local_lor\output\content($items, $page, ITEMS_PER_PAGE);

// Ouput the template.
echo $content_output->render($renderable);

// Output the page footer.
echo $OUTPUT->footer();
?>
