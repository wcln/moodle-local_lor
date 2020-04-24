<?php

namespace local_lor\output;

use coding_exception;
use dml_exception;
use local_lor\item\item;
use moodle_exception;
use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class item_view implements renderable, templatable
{

    var $itemid = 0;

    public function __construct($itemid)
    {
        $this->itemid = $itemid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     *
     * @return stdClass
     * @throws dml_exception
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function export_for_template(renderer_base $output)
    {
        $item = item::get($this->itemid);

        $implode_format = function ($array) {
            return (empty($array))
                ?
                get_string('none', 'local_lor')
                : implode(
                    ',',
                    array_column(
                        $array,
                        'name'
                    )
                );
        };

        $item->categories = $implode_format($item->categories);
        $item->grades     = $implode_format($item->grades);
        $item->topics     = $implode_format($item->topics);

        array_map(
            function ($contributor) {
                $contributor->name = fullname($contributor);

                return $contributor;
            },
            $item->contributors
        );

        $item->contributors = $implode_format($item->contributors);

        $item->timecreated = userdate(
            $item->timecreated,
            get_string('strftimedate', 'langconfig')
        );

        $item->edit_url = new moodle_url(
            '/local/lor/item/edit.php',
            ['id' => $item->id]
        );
        $item->delete_url = new moodle_url(
            '/local/lor/item/delete.php',
            ['id' => $item->id]
        );

        return $item;
    }
}
