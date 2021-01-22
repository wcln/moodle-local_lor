<?php

namespace local_lor\type\media;

use html_writer;
use local_lor\item\data;
use local_lor\type\type;

class media
{
    use type;

    const PROPERTIES = ['url', 'width', 'height'];

    const DEFAULT_WIDTH = '600px';
    const DEFAULT_HEIGHT = '900px';

    /** @var string Stores dimension information for common media resources */
    const DIMENSIONS_TABLE = 'lortype_media_size';

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

        $dimensions = self::get_item_dimensions($itemid);

        $html = html_writer::start_tag('div',
            ['align' => 'center', 'id' => "lor-$itemid"]);
        $html .= html_writer::tag('iframe', null, [
            'width'  => $dimensions['width'],
            'height' => $dimensions['height'],
            'src'    => $item_data['url'],
        ]);
        $html .= html_writer::end_tag('div');

        return $html;
    }

    public static function add_to_form(&$item_form, $itemid = 0)
    {
        // Media URL
        $item_form->addElement(
            'text',
            'url',
            get_string('media_url', 'lortype_media')
        );
        $item_form->setType('url', PARAM_RAW);
        $item_form->addRule('url', get_string('required'), 'required');

        // Dimensions static message
        $item_form->addElement('static', 'dimensions', get_string('dimensions_label', 'lortype_media'),
            get_string('dimensions_message', 'lortype_media'));

        // Width
        $item_form->addElement(
            'text',
            'width',
            get_string('width', 'lortype_media')
        );
        $item_form->setType('width', PARAM_INT);
        $item_form->addHelpButton('width', 'width', 'lortype_media');

        // Height
        $item_form->addElement(
            'text',
            'height',
            get_string('height', 'lortype_media')
        );
        $item_form->setType('height', PARAM_INT);
        $item_form->addHelpButton('height', 'height', 'lortype_media');
    }

    private static function get_item_dimensions($itemid)
    {
        $item_data = data::get_item_data($itemid);

        if ( ! empty($item_data['width']) && ! empty($item_data['height'])) {
            return [
                'width'  => $item_data['width'] . "px",
                'height' => $item_data['height'] . "px",
            ];
        }

        if ($sizing_record = self::get_template_dimensions($item_data['url'])) {
            return [
                'width'  => $sizing_record->width . "px",
                'height' => $sizing_record->height . "px",
            ];
        }

        return [
            'width'  => self::DEFAULT_WIDTH,
            'height' => self::DEFAULT_HEIGHT,
        ];
    }

    private static function get_template_dimensions(string $url)
    {
        global $DB;

        return $DB->get_record_select(self::DIMENSIONS_TABLE, "INSTR(:url, `match`)", ['url' => $url]);
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

            if ($existing_record = $DB->get_record_select(
                data::TABLE,
                "itemid = :itemid AND name LIKE :name",
                [
                    'itemid' => $itemid,
                    'name'   => $property,
                ]
            )
            ) {
                $record['id'] = $existing_record->id;

                $success = $success
                           && $DB->update_record(
                        data::TABLE,
                        (object)$record
                    );
            } else {
                $success = $success && $DB->insert_record(data::TABLE, (object)$record);
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
