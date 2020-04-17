<?php

namespace local_lor\item\property;

use dml_exception;
use stdClass;

class contributor implements noneditable_property {

    const TABLE = 'local_lor_item_contributors';

    /**
     * Get an item's contributors (users)
     *
     * @param int $itemid
     * @return array of objects containing user properties: id, firstname, lastname
     * @throws dml_exception
     */
    public static function get_item_data(int $itemid) {
        global $DB;
        return $DB->get_records_sql(sprintf("
            SELECT u.id, u.firstname, u.lastname
            FROM {user} u
            JOIN {%s} ic ON ic.userid = u.id
            WHERE ic.itemid = :itemid
        ", self::TABLE), ['itemid' => $itemid]);
    }

    public static function save_item_form(int $itemid, stdClass $data) {
        // TODO: Implement save_item_form() method.
    }
}
