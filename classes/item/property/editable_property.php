<?php

namespace local_lor\item\property;

use stdClass;

interface editable_property
{

    public static function get_item_data(int $itemid);

    public static function create(stdClass $data);

    public static function update(int $id, stdClass $data);

    public static function delete(int $id);

    public static function save_item_form(int $itemid, stdClass $data);

    public static function delete_for_item(int $itemid);

    public static function get_all();

    public static function get(int $id);

}
