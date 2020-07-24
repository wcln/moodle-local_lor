<?php

namespace local_lor\type\video;

use local_lor\item\data;
use local_lor\type\type;

class video
{
    use type;

    const PROPERTIES = ['videoid'];

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
        $item_form->addElement(
            'text',
            'videoid',
            get_string('videoid', 'lortype_video')
        );
        $item_form->setType('videoid', PARAM_TEXT);
        $item_form->addRule('videoid', get_string('required'), 'required');
        $item_form->addHelpButton('videoid', 'videoid', 'lortype_video');
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
            if ($existing_record = $DB->get_record_select(
                data::TABLE,
                "itemid = :itemid AND name LIKE :name",
                [
                    'itemid' => $itemid,
                    'name'   => $property,
                ]
            )
            ) {
                $record = [
                    'id' => $existing_record->id,
                    'itemid' => $itemid,
                    'name' => $property,
                    'value' => $data->{$property},
                ];

                $success = $success
                           && $DB->update_record(
                        data::TABLE,
                        (object)$record
                    );
            }
        }

        return $success;
    }

    public static function get_icon()
    {
        return 'video';
    }
}
