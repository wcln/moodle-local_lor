<?php

namespace local_lor\item\property;

interface noneditable_property {

    public static function get_item_data(int $itemid);

    public static function save_item_form(int $itemid, \stdClass $data);

}
