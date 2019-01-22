<?php


namespace local_lor\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class delete_success implements renderable, templatable {

    // The ID of the item that was just deleted.
    var $id = null;

    // The title of the item that was just deleted.
    var $title = null;

    public function __construct($id, $title) {
      $this->id = $id;
      $this->title = $title;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;
      global $CFG;

      $data = new stdClass();
      $data->title = $this->title;
      $data->done_link = new \moodle_url("/local/lor/index.php");
      $data->undo_link = "$CFG->wwwroot/local/lor/edit.php?id=$this->id&undo=1";

      return $data;
    }
}
