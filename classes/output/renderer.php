<?php

namespace local_lor\output;

defined('MOODLE_INTERNAL') || die;

use moodle_exception;
use plugin_renderer_base;

class renderer extends plugin_renderer_base
{

    /**
     * Render the item view page
     *
     * @param $page
     *
     * @return bool|string
     * @throws moodle_exception
     */
    public function render_item_view($page)
    {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/item_view', $data);
    }

    /**
     * Render the list of categories on the settings page
     *
     * @param $page
     *
     * @return bool|string
     * @throws moodle_exception
     */
    public function render_category_list($page)
    {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/category_list', $data);
    }

    /**
     * Render the list of grades on the settings page
     *
     * @param $page
     *
     * @return bool|string
     * @throws moodle_exception
     */
    public function render_grade_list($page)
    {
        $data = $page->export_for_template($this);

        return parent::render_from_template('local_lor/grade_list', $data);
    }
}
