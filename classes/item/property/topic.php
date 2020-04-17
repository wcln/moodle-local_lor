<?php

namespace local_lor\item\property;

class topic implements noneditable_property {

    const TABLE = 'local_lor_topic';
    const LINKING_TABLE = 'local_lor_item_topics';

    /**
     * Get topics for an item
     *
     * @param int $itemid
     * @return array of objects containing properties: topic ID and topic name
     * @throws \dml_exception
     */
    public static function get_item_data(int $itemid) {
        global $DB;
        return $DB->get_records_sql(sprintf("
            SELECT t.id, t.name
            FROM {%s} t
            JOIN {%s} it ON it.topicid = t.id
            WHERE it.itemid = :itemid
        ", self::TABLE, self::LINKING_TABLE), ['itemid' => $itemid]);
    }

    public static function save_item_form(int $itemid, \stdClass $data) {
        // TODO: Implement save_item_form() method.
    }
}
