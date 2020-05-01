<?php

namespace local_lor\type\media;

use html_writer;
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
        return html_writer::tag('p', self::get_display_html($itemid), ['align' => 'center']);
    }

    public static function get_display_html($itemid)
    {
        $item_data = data::get_item_data($itemid);

        return html_writer::tag('iframe', null, [
            'width'  => $item_data['height'],
            'height' => $item_data['height'],
            'src'    => $item_data['url'],
        ]);
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
                    'id'     => $existing_record->id,
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
        }

        return $success;
    }


}
