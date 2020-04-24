<?php

namespace local_lor\output;

use coding_exception;
use dml_exception;
use local_lor\item\property\category;
use moodle_exception;
use moodle_url;
use pix_icon;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class category_list implements renderable, templatable
{

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     *
     * @return stdClass
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function export_for_template(renderer_base $output)
    {
        global $USER;

        $data = new stdClass();

        $data->categories = category::get_all();
        $data->add_url    = new moodle_url(
            '/local/lor/setting/category.php',
            ['id' => 0]
        );

        foreach ($data->categories as $category) {
            $edit_url              = new moodle_url(
                '/local/lor/setting/category.php',
                ['id' => $category->id]
            );
            $delete_url            = new moodle_url(
                '/local/lor/setting/category.php',
                [
                    'id'      => $category->id,
                    'delete'  => true,
                    'sesskey' => $USER->sesskey,
                ]
            );
            $category->edit_link   = $output->action_icon(
                $edit_url,
                new pix_icon(
                    't/edit',
                    get_string('edit')
                )
            );
            $category->delete_link = $output->action_icon(
                $delete_url,
                new pix_icon(
                    't/delete',
                    get_string(
                        'delete'
                    )
                )
            );
        }

        // Clean the array keys so that the Mustache template can render this correctly
        $data->categories = array_values($data->categories);

        return $data;
    }
}
