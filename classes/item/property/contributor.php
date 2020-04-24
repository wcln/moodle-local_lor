<?php

namespace local_lor\item\property;

use dml_exception;
use stdClass;

class contributor implements noneditable_property
{

    const TABLE = 'local_lor_item_contributors';

    /**
     * Get an item's contributors (users)
     *
     * @param int $itemid
     *
     * @return array of objects containing user properties: id, firstname, lastname
     * @throws dml_exception
     */
    public static function get_item_data(int $itemid)
    {
        global $DB;

        return $DB->get_records_sql(
            sprintf(
                "
            SELECT u.id, u.firstname, u.lastname, u.lastnamephonetic, u.firstnamephonetic, u.middlename, u.alternatename
            FROM {user} u
            JOIN {%s} ic ON ic.userid = u.id
            WHERE ic.itemid = :itemid
        ",
                self::TABLE
            ),
            ['itemid' => $itemid]
        );
    }

    public static function save_item_form(int $itemid, stdClass $data)
    {
        global $DB;

        self::delete_for_item($itemid);

        foreach ($data->contributors as $userid) {
            $DB->insert_record(
                self::TABLE,
                (object)[
                    'itemid' => $itemid,
                    'userid' => $userid,
                ],
                false
            );
        }
    }

    public static function get_form_options()
    {
        global $DB;

        return $DB->get_records_menu(
            'user',
            null,
            'lastname ASC, firstname ASC',
            'id, CONCAT(firstname, \' \', lastname) as fullname'
        );
    }

    public static function delete_for_item(int $itemid)
    {
        global $DB;

        return $DB->delete_records(self::TABLE, ['itemid' => $itemid]);
    }
}
