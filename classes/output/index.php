<?php

namespace local_lor\output;

use coding_exception;
use dml_exception;
use moodle_exception;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class index implements renderable, templatable
{

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param  renderer_base  $output
     *
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function export_for_template(renderer_base $output)
    {
        // If there is every any data required for the index.mustache template include it here

        return new stdClass();
    }
}
