<?php

namespace local_lor\item;

use local_lor\form\item_form;

class item {

    const TABLE = 'local_lor_item';


    public static function get(int $id) {
        global $DB;
        return $DB->get_record(self::TABLE, ['id' => $id]);
    }

    /**
     * Get the moodle form used to create/edit a LOR item
     *
     * @param string $type
     * @param null $itemid
     * @return item_form
     * @throws \dml_exception
     */
    public static function get_form(string $type, $itemid = null) {

        $form = new item_form(null, ['type' => $type]);

        if (empty($itemid)) {
            return $form;
        } else {
            global $DB;
            $item = $DB->get_record(self::TABLE, ['id' => $itemid]);
            $data = data::get_item_data($itemid);
            $form->set_data((object) array_merge((array) $item, (array) $data));
            return $form;
        }
    }

    /**
     * Get the type of an item
     *
     * @param int $itemid
     * @return mixed
     * @throws \dml_exception
     */
    public static function get_type(int $itemid) {
        global $DB;
        $item = $DB->get_record(self::TABLE, ['id' => $itemid]);
        return $item->type;
    }

    /**
     * Get the path to the item type class
     *
     * @param string $type
     * @return string
     */
    public static function get_type_class(string $type) {
        return "local_lor\\type\\$type\\$type";
    }

    /**
     * Create a new LOR item
     *
     * @param $data
     * @return bool
     */
    public static function create($data) {
        return true;
    }

    /**
     * Update an existing LOR item
     *
     * @param int $itemid
     * @param $data
     * @return bool
     */
    public static function update(int $itemid, $data) {
        return true;
    }

    /**
     * Delete an existing LOR item
     *
     * @param int $itemid
     * @return bool
     */
    public static function delete(int $itemid) {
        return true;
    }
}
