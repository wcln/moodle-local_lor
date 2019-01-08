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
$type = null;

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

if (isset($_GET['page'])) {
  $page = (int) $_GET['page'] - 1;
}

if (isset($_GET['type'])) {
  $type = (int) $_GET['type'];
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

// Fetch the current system context.
$systemcontext = context_system::instance();

// Retrieve the renderer for the page.
$renderer = $PAGE->get_renderer('local_lor');

// Configuring the Nav bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));

// Ouput the header.
echo $OUTPUT->header();

// Check if the user is a teacher anywhere on the site.
$roleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher']);
$isteacheranywhere = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $roleid]);

// Check if the user has the ability to insert into the LOR.
if (has_capability('local/lor:insert', $systemcontext) || $isteacheranywhere) {

  // Ouput the template to show a link to the insert page.
  $insert_link = new \local_lor\output\insert_link();
  echo $renderer->render($insert_link);
}

// Initialize and display the search form.
$search_form = new search_form(null, array('type' => $type));
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
  $items = local_lor_get_content($type, null, null, "new", null);
}

// Send all of the items, the current page, and the number of items to be displayed per page.
$countent_output = new \local_lor\output\content($items, $page, ITEMS_PER_PAGE);

// Ouput the template.
echo $renderer->render($countent_output);

// Output the page footer.
echo $OUTPUT->footer();
?>
