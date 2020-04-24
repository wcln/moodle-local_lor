<?php

namespace local_lor\type;

interface type
{

    public static function get_name();

    public static function get_embed_html($itemid);

    public static function get_display_html($itemid);

    public static function add_to_form(&$item_form);

}
