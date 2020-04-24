<?php

namespace local_lor\type\media;

use local_lor\item\data;
use local_lor\type\type;

class media
{
    use type;

    const PROPERTIES = ['url', 'width', 'height'];

    public static function get_name()
    {
        return get_string('type_name', 'lortype_media');
    }

    public static function get_embed_html($itemid)
    {
        $data = data::get_item_data($itemid);
    }

    public static function get_display_html($itemid)
    {
        // TODO: Implement get_display_html() method.
    }

    public static function add_to_form(&$item_form)
    {
        // Media URL
        $item_form->addElement(
            'text',
            'url',
            get_string('media_url', 'lortype_media')
        );
        $item_form->setType('url', PARAM_RAW);
        $item_form->addRule('url', get_string('required'), 'required');

        // Width
        $item_form->addElement(
            'text',
            'width',
            get_string('width', 'lortype_media')
        );
        $item_form->setType('width', PARAM_INT);
        $item_form->addRule('width', get_string('required'), 'required');
        $item_form->addHelpButton('width', 'width', 'lortype_media');

        // Height
        $item_form->addElement(
            'text',
            'height',
            get_string('height', 'lortype_media')
        );
        $item_form->setType('height', PARAM_INT);
        $item_form->addRule('height', get_string('required'), 'required');
        $item_form->addHelpButton('height', 'height', 'lortype_media');
    }

    public static function create($itemid, $data)
    {
        global $DB;

        $success = true;

        foreach (self::PROPERTIES as $property) {
            $record = [
                'itemid' => $itemid,
                'name'   => $property,
                'value'  => $data->{$property},
            ];

            $success = $success
                       && $DB->insert_record(
                    data::TABLE,
                    (object)$record
                );
        }

        return $success;
    }

    public static function update($itemid, $data)
    {
        global $DB;

        $success = true;

        foreach (self::PROPERTIES as $property) {
            $record = [
                'itemid' => $itemid,
                'name'   => $property,
                'value'  => $data->{$property},
            ];

            $success = $success
                       && $DB->update_record(
                    data::TABLE,
                    (object)$record
                );
        }

        return $success;
    }


}
