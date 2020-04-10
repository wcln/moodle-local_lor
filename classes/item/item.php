<?php

namespace local_lor\item;

class item {

    const TABLE = 'local_lor_item';

    public static function get_form(int $itemid) {

    }

    public static function get_type(int $itemid) {
        global $DB;
        $item = $DB->get_record(self::TABLE, ['id' => $itemid]);
        return $item->type;
    }
}
