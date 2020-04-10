<?php

namespace local_lor\type;

use local_lor\item\data;

class media implements type {

    public static function get_type_name($itemid) {
        return get_string('type:media', 'local_lor');
    }

    public static function get_embed_html($itemid) {
        $data = data::get_item_data($itemid);
    }

    public static function get_display_html($itemid) {
        // TODO: Implement get_display_html() method.
    }

    public static function add_to_form(\moodleform &$item_form) {
        $item_form->addElement('text', 'test', 'Test element');
    }
}
