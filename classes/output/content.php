<?php
namespace local_lor\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

require_once(__DIR__ . '/../../locallib.php');

class content implements renderable, templatable {

    // The ID of the item that was just inserted.
    var $items = null;

    public function __construct($items) {
        $this->items = $items;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      $data = new stdClass();
      $data->items = [];

      // Convert to format for mustache template.
      foreach ($this->items as $item) {
        $data->items[] = [
          'id' => $item->id,
          'title' => $item->title,
          'link' => $item->link,
          'image' => $item->image,
          'keywords' => local_lor_get_keywords_string_for_item($item->id)
        ];
      }

      $data->count = count($this->items);

      return $data;
    }
}
