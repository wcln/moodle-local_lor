<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/locallib.php');

// Search form.
require_once('search_form.php');


// Initialize search variables.
$id = null;
$search_categories = null;
$search_type = null;
$search_grades = null;
$search_keywords = null;
$order_by = "new";
$page = 0;

// Settings
define("ITEMS_PER_PAGE", 25);

// Check the URL for search arguments.
if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

if (isset($_GET['categories'])) {
  if ($_GET['categories'] !== "-1") {
    $search_categories = $_GET['categories'];
  }
}

if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
}

if (isset($_GET['type'])) {
  $search_type = $_GET['type'];
}

if (isset($_GET['grades'])) {
  $search_grades = $_GET['grades'];
}

if (isset($_GET['keywords'])) {
  $search_keywords = $_GET['keywords'];
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
$PAGE->requires->css(new moodle_url("lib/multiple-select/multiple-select.css"));
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
  $content = local_lor_get_content($search_data->type, $search_data->categories, $search_data->grades, $search_data->sort_by, $search_data->keywords);

} else { // Search form was not submitted.

  // Get all content.
  $content = local_lor_get_content(null, null, null, null, null);

}

// Calculate the total number of pages.
$number_of_pages = ceil(count($content) / ITEMS_PER_PAGE);

?>



<!-- Modal template here -->
<!-- Content template here -->



</div>

<?php
echo $OUTPUT->footer();
?>
