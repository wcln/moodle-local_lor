<?php

namespace local_lor\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {

    public function render_insert_success($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/insert_success', $data);
    }

    public function render_update_success($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/update_success', $data);
    }

    public function render_delete_success($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/delete_success', $data);
    }

    public function render_content($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/content', $data);
    }

    public function render_insert_link($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/insert_link', $data);
    }

    public function render_not_allowed($page) {
        // No data is required to render this template.
        return parent::render_from_template('local_lor/not_allowed', null);
    }
}
