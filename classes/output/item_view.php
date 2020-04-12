<?php

namespace local_lor\output;

use local_lor\item\data;
use local_lor\item\item;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class item_view implements renderable, templatable {

    var $itemid = 0;

    public function __construct($itemid) {
        $this->itemid = $itemid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     * @throws \dml_exception
     */
    public function export_for_template(renderer_base $output) {
        $item = (array) item::get($this->itemid);
        $item_data = data::get_item_data($this->itemid);

        $data = array_merge($item, $item_data);

        return $data;
    }
}
