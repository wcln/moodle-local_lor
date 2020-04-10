<?php

namespace local_lor\item;

class data {

    const TABLE = 'local_lor_item_data';

    /**
     * Get all data records for an item
     *
     * @param int $itemid
     * @return array assoc. array: data name => data value
     * @throws \dml_exception
     */
    public static function get_item_data(int $itemid) {
        global $DB;
        return $DB->get_records_menu(self::TABLE, ['itemid' => $itemid], null, 'name,value');
    }

}
