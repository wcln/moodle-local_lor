<?php

namespace local_lor\item;

class categories implements editable_property {

    const TABLE = 'local_lor_category';
    const LINKING_TABLE = 'local_lor_item_categories';

    /**
     * Get categories for this item
     *
     * @param int $itemid
     * @return array of objects containing category ID and name properties
     * @throws \dml_exception
     */
    public static function get_item_data(int $itemid) {
        global $DB;
        return $DB->get_records_sql(sprintf("
            SELECT c.id, c.name
            FROM {%s} c
            JOIN {%s} ic ON ic.categoryid = c.id
            WHERE ic.itemid = :itemid
        ", self::TABLE, self::LINKING_TABLE), ['itemid' => $itemid]);
    }

    /**
     * Create a category (from settings page)
     *
     * @param \stdClass $data
     * @return bool
     */
    public static function create(\stdClass $data) {
        return true;
    }

    /**
     * Update a category (from settings page)
     *
     * @param int $id
     * @param \stdClass $data
     * @return bool
     */
    public static function update(int $id, \stdClass $data) {
        return true;
    }

    /**
     * Delete a category (from settings page)
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id) {
        return true;
    }

    public static function save_item_form(int $itemid, \stdClass $data) {
        // TODO: Implement save_item_form() method.
    }
}
