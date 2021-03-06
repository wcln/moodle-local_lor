<?php

namespace local_lor\item\property;

use stdClass;

interface noneditable_property
{

    public static function get_item_data(int $itemid);

    public static function save_item_form(int $itemid, stdClass $data);

    public static function delete_for_item(int $itemid);

}
