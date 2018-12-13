<?php


namespace local_lor\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class success_html implements renderable, templatable {

    // The ID of the item that was just inserted.
    var $id = null;

    public function __construct($id) {
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

      // Attempt to retrieve the title of the recently inserted item.
      $recently_inserted = $DB->get_record('lor_content', array('id' => $this->id), 'id, title');
      if ($recently_inserted !== false) {
        $data->title = $recently_inserted->title;
      } else {
        // Output an error if the title was not found.
        $data->title = get_string('error_title_not_found', 'local_lor');
      }

      // Create a link to the recently inserted item.
      $data->lor_link = new \moodle_url('/local/lor/index.php', array('id' => $this->id));
      return $data;
    }
}
