<?php

namespace local_lor\output;

use dml_exception;
use local_lor\item\item;
use moodle_exception;
use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class search_page implements renderable, templatable
{

    public function __construct()
    {
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     *
     * @return stdClass
     * @throws moodle_exception
     * @throws dml_exception
     */
    public function export_for_template(renderer_base $output)
    {
        $data = new stdClass();

        $data->items = array_values(item::search());

        foreach ($data->items as $item) {
            $item->view_url = new moodle_url(
                '/local/lor/item/view.php',
                ['id' => $item->id]
            );
        }

        return $data;
    }
}
