<?php

namespace local_lor\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    /**
     * Defer to template.
     *
     * @param success_html $page
     *
     * @return string html for the page
     */
    public function render_success_html($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_lor/success_html', $data);
    }
}
