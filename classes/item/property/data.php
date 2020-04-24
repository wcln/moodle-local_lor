<?php

namespace local_lor\item\property;

use dml_exception;
use stdClass;

class data implements noneditable_property
{

    const TABLE = 'local_lor_item_data';

    /**
     * Get all data records for an item
     *
     * @param int $itemid
     *
     * @return array assoc. array: data name => data value
     * @throws dml_exception
     */
    public static function get_item_data(int $itemid)
    {
        global $DB;

        return $DB->get_records_menu(
            self::TABLE,
            ['itemid' => $itemid],
            null,
            'name,value'
        );
    }

    public static function save_item_form(int $itemid, stdClass $data)
    {
        // TODO: Implement save_item_form() method.
    }
}
