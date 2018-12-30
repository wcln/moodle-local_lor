<?php


namespace local_lor\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class content implements renderable, templatable {

    // The ID of the item that was just inserted.
    var $items = null;
    var $count = null;

    public function __construct($items) {
        $this->id = $id;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      $data = new stdClass();
      $data->items = $this->items;
      $data->count = count($this->items);

      return $data;
    }
}
