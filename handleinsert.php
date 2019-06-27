<?php

use \local_lor\insert\handler;

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');

// Set up the page.
$title     = get_string('pluginname', 'local_lor');
$pagetitle = $title;
$url       = new moodle_url("/local/lor/handleinsert.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

// Navigation bar.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add(get_string('nav_insert_form', 'local_lor'), new moodle_url('/local/lor/insert.php'));

// Ensure the user is logged in to access this page.
require_login();

// Check if there is custom data from the game creator to send to a form.
$custom_data = null;
if (isset($_GET['gamecreator'])) {
    $custom_data = array('link' => $_GET['gamecreator']);
}

// Try to load and display the current form.
try {
    // Try to load current form.
    $current_form = handler::get_current_form($custom_data);
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

        // Output error string.
        echo get_string('error_missing_form', 'local_lor');

    }

} catch (Exception $e) {

    // Output the page header.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('heading', 'local_lor'));

    // Output exception.
    echo get_string('error_unexpected', 'local_lor') . $e;

}

// Output the footer.
echo $OUTPUT->footer();
