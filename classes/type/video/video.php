<?php

namespace local_lor\type\video;

use local_lor\item\data;
use local_lor\type\type;

class video
{
    use type;

    public static function get_name()
    {
        return get_string('type_name', 'lortype_video');
    }

    public static function get_embed_html($itemid)
    {
        // TODO
    }

    public static function get_display_html($itemid)
    {
        // TODO: Implement get_display_html() method.
    }

    public static function add_to_form(&$item_form)
    {
        $item_form->addElement('text', 'test', 'Test video element');
        $item_form->setType('test', PARAM_TEXT);
    }
}
