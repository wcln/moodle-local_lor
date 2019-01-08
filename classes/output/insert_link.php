<?php


namespace local_lor\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class insert_link implements renderable, templatable {

    public function __construct() {

    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      // Set the link of the page containing the insert form.
      $data = new stdClass();
      $data->link = new \moodle_url("/local/lor/insert.php");

      return $data;
    }
}
