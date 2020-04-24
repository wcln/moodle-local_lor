<?php

namespace local_lor\item\property;

use dml_exception;
use stdClass;

class grade implements editable_property
{

    const TABLE = 'local_lor_grade';
    const LINKING_TABLE = 'local_lor_item_grades';

    /**
     * Get an item's grades
     *
     * @param int $itemid
     *
     * @return array of objects containg containg properties: grade ID and grade name
     * @throws dml_exception
     */
    public static function get_item_data(int $itemid)
    {
        global $DB;

        return $DB->get_records_sql(
            sprintf(
                "
            SELECT g.id, g.name
            FROM {%s} g
            JOIN {%s} ig ON ig.gradeid = g.id
            WHERE ig.itemid = :itemid
        ",
                self::TABLE,
                self::LINKING_TABLE
            ),
            ['itemid' => $itemid]
        );
    }

    public static function create(stdClass $data)
    {
        global $DB;

        return $DB->insert_record(self::TABLE, (object)['name' => $data->name]);
    }

    public static function update(int $id, stdClass $data)
    {
        global $DB;
        $data->id = $id;

        return $DB->update_record(self::TABLE, $data);
    }

    public static function delete(int $id)
    {
        global $DB;

        return $DB->delete_records(self::TABLE, ['id' => $id])
               && $DB->delete_records(self::LINKING_TABLE, ['gradeid' => $id]);
    }

    public static function save_item_form(int $itemid, stdClass $data)
    {
        // TODO: Implement save_item_form() method.
    }

    /**
     * Get all grades
     *
     * @return array
     * @throws dml_exception
     */
    public static function get_all()
    {
        global $DB;

        return $DB->get_records(self::TABLE);
    }

    /**
     * Get all grades as assoc. array id => name
     *
     * @return array
     * @throws dml_exception
     */
    public static function get_all_menu()
    {
        global $DB;

        return $DB->get_records_menu(
            self::TABLE,
            null,
            'name + 0 ASC',
            'id,name'
        );
    }

    public static function get(int $id)
    {
        global $DB;

        return $DB->get_record(self::TABLE, ['id' => $id]);
    }
}
