<?php


namespace local_projectspage\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

class success_html implements renderable, templatable {

    var $pid = null;

    public function __construct($pid) {
        $this->pid = $pid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->pid = $this->pid;                                                                            
        return $data;
    }
}
