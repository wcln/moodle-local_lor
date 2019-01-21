<?php


namespace local_lor\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class delete_success implements renderable, templatable {

    // The title of the item that was just deleted.
    var $title = null;

    public function __construct($title) {
        $this->title = $title;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      $data = new stdClass();
      $data->title = $this->title;
      $data->link = new \moodle_url("/local/lor/index.php");

      return $data;
    }
}
