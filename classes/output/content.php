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

    // The total number of search results.
    var $count = null;

    // The number of LOR items to be displayed per page.
    var $items_per_page = null;

    // An array of integers.
    var $pages = null;

    // The current page integer.
    var $current_page = null;

    public function __construct($items, $current_page, $items_per_page) {
        $this->count = count($items);
        $this->items = array_slice($items, $current_page * $items_per_page, $items_per_page);
        $this->current_page = $current_page;
        $this->pages = range(1, ceil(count($items) / $items_per_page));
        $this->items_per_page = $items_per_page;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
      global $DB;

      $data = new stdClass();

      // LOR items to be displayed.
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

      // Array of integers representing page numbers.
      $data->pages = [];

      // Convert to format for mustache template.
      foreach ($this->pages as $page) {
        $data->pages[] = [
          'page' => $page,
          'is_current_page' => ($this->current_page + 1 == $page)? true : false // Determines if this page is the current page we are on.
        ];
      }

      // The total number of results.
      $data->count = $this->count;

      // The page the user is currently on. Used for next/back buttons.
      $data->current_page = $this->current_page + 1;

      // Are we are on the first or last page?
      // This determines if the back and next buttons are displayed or not.
      $data->is_last_page = ($this->current_page + 1 == ceil($this->count / $this->items_per_page))? true : false;
      $data->is_first_page = ($this->current_page == 0)? true : false;

      return $data;
    }
}
