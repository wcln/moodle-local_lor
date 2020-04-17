<?php

namespace local_lor\output;

defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {

    public function render_content($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/content', $data);
    }

    public function render_insert_link($page) {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/insert_link', $data);
    }

    /**
     * Render the item view page
     *
     * @param $page
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_item_view($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_lor/item_view', $data);
    }
}
