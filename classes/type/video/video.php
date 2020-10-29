<?php

namespace local_lor\type\video;

use dml_exception;
use html_writer;
use local_lor\item\data;
use local_lor\type\type;

class video
{
    use type;

    const PROPERTIES = ['videoid'];
    const YOUTUBE_EMBED_URL = 'https://www.youtube.com/embed/';

    public static function get_name()
    {
        return get_string('type_name', 'lortype_video');
    }

    public static function get_embed_html($itemid)
    {
        // In this instance, the display function and embed function will return the same HTML
        return self::get_display_html($itemid);
    }

    public static function get_display_html($itemid)
    {
        $html = html_writer::start_tag('p', ['align' => 'center']);
        $html .= html_writer::tag('iframe', '', [
            'src'             => self::get_embed_url($itemid),
            'allowfullscreen' => true,
            'frameborder'     => 0,
            'height'          => 360,
            'width'           => 640,
        ]);
        $html .= html_writer::end_tag('p');

        return $html;
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
        return 'video';
    }

    /**
     * Build the Youtube video embed URL
     *
     * @param  int  $itemid
     *
     * @return string
     * @throws dml_exception
     */
    private static function get_embed_url(int $itemid)
    {
        $data = data::get_item_data($itemid);

        return self::YOUTUBE_EMBED_URL.$data['videoid'].'?rel=0';
    }

    public static function get_unique_identifier(int $itemid)
    {
        return self::get_embed_url($itemid);
    }
}
