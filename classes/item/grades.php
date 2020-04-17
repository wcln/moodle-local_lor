<?php

namespace local_lor\item;

class grades implements editable_property {

    const TABLE = 'local_lor_grade';
    const LINKING_TABLE = 'local_lor_item_grades';

    /**
     * Get an item's grades
     *
     * @param int $itemid
     * @return array of objects containg containg properties: grade ID and grade
     * @throws \dml_exception
     */
    public static function get_item_data(int $itemid) {
        global $DB;
        return $DB->get_records_sql(sprintf("
            SELECT g.id, g.grade
            FROM {%s} g
            JOIN {%s} ig ON ig.gradeid = g.id
            WHERE ig.itemid = :itemid
        ", self::TABLE, self::LINKING_TABLE), ['itemid' => $itemid]);
    }

    public static function create(\stdClass $data) {
        // TODO: Implement create() method.
    }

    public static function update(int $id, \stdClass $data) {
        // TODO: Implement update() method.
    }

    public static function delete(int $id) {
        // TODO: Implement delete() method.
    }

    public static function save_item_form(int $itemid, \stdClass $data) {
        // TODO: Implement save_item_form() method.
    }
}
