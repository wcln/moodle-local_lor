<?php

namespace local_lor\task;

defined('MOODLE_INTERNAL') || die();

// Require the config file for DB calls.
require_once(__DIR__ . '/../../../../config.php');

class delete_items extends \core\task\scheduled_task {


    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('deleteitems', 'local_lor');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB;
        global $CFG;

        $count = $DB->count_records('lor_content', array('deleted' => 1));
        if ($count > 0) {
            mtrace("$count records marked for deletion.");
            $success = $DB->delete_records('lor_content', array('deleted' => 1));
            if ($success) {
                mtrace("Records successfully deleted.");
            } else {
                mtrace("Error deleting records.");
            }
        } else {
            mtrace("No records to delete.");
        }
    }
}
