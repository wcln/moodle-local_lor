<?php

namespace local_lor\type\media;

use html_writer;
use local_lor\item\data;
use local_lor\type\type;

class media
{
    use type;

    const PROPERTIES = ['url'];
    const MEDIA_HEIGHT = '900px';

    public static function get_name()
    {
        return get_string('type_name', 'lortype_media');
    }

    public static function get_embed_html($itemid)
    {
        return self::get_display_html($itemid);
    }

    public static function get_display_html($itemid)
    {
        $item_data = data::get_item_data($itemid);

        $html = html_writer::start_tag('div',
            ['align' => 'center', 'style' => 'height: '.self::MEDIA_HEIGHT, 'id' => "lor-$itemid"]);
        $html .= html_writer::tag('iframe', null, [
            'width'  => "100%",
            'height' => "100%",
            'src'    => $item_data['url'],
        ]);
        $html .= html_writer::end_tag('div');

        return $html;
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


    public static function get_icon()
    {
        return 'laptop';
    }

    public static function get_unique_identifier(int $itemid)
    {
        $data = data::get_item_data($itemid);

        return $data['url'];
    }

    public static function get_resource_url(int $itemid)
    {
        $data = data::get_item_data($itemid);

        return $data['url'];
    }
}
