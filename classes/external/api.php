<?php

use local_lor\helper;
use local_lor\item\item;
use local_lor\type\type;

require_once("$CFG->libdir/externallib.php");

class api extends external_api
{

    public static function get_resources_parameters()
    {
        // TODO add search parameters
        return new external_function_parameters([]);
    }

    public static function get_resources()
    {
        $items = array_values(item::search());

        foreach ($items as $item) {
            $item->image = item::get_image_url($item->id);
        }

        return $items;
    }

    public static function get_resources_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id'           => new external_value(PARAM_INT, 'Item ID'),
                'type'         => new external_value(PARAM_TEXT, 'Item type'),
                'name'         => new external_value(PARAM_TEXT, 'Item name'),
                'image'        => new external_value(PARAM_TEXT, 'Item image'),
                'description'  => new external_value(PARAM_RAW, 'Item description'),
                'timecreated'  => new external_value(PARAM_INT, 'Time created'),
                'timemodified' => new external_value(PARAM_INT, 'Time modified'),
            ])
        );
    }

    public static function get_resource_parameters()
    {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'The ID of the item to retrieve'),
        ]);
    }

    public static function get_resource($id)
    {
        $params = self::validate_parameters(self::get_resource_parameters(), compact('id'));

        $item = item::get($params['id']);

        $data = [];
        foreach ($item->data as $data_key => $data_value) {
            $data[] = [
                'name'  => $data_key,
                'value' => $data_value,
            ];
        }
        $item->data = $data;

        $item->categories   = helper::implode_format($item->categories);
        $item->grades       = helper::implode_format($item->grades);
        $item->topics       = helper::implode_format($item->topics);
        $item->contributors = helper::implode_format($item->contributors, 'fullname');

        $item->timecreated = userdate(
            $item->timecreated,
            get_string('strftimedate', 'langconfig')
        );

        $type_class    = type::get_class(item::get_type($item->id));
        $item->display = $type_class::get_display_html($item->id);

        return $item;
    }

    public static function get_resource_returns()
    {
        return new external_single_structure([
            'id'           => new external_value(PARAM_INT, 'Item ID'),
            'type'         => new external_value(PARAM_TEXT, 'Item type'),
            'name'         => new external_value(PARAM_TEXT, 'Item name'),
            'image'        => new external_value(PARAM_TEXT, 'Item image'),
            'description'  => new external_value(PARAM_RAW, 'Item description'),
            'timecreated'  => new external_value(PARAM_TEXT, 'Time created'),
            'timemodified' => new external_value(PARAM_INT, 'Time modified'),
            'categories'   => new external_value(PARAM_TEXT, 'Item categories'),
            'contributors' => new external_value(PARAM_TEXT, 'Item contributors'),
            'grades'       => new external_value(PARAM_TEXT, 'Item grades'),
            'topics'       => new external_value(PARAM_TEXT, 'Item topics'),
            'display'       => new external_value(PARAM_RAW, 'Item display HTML'),
            'data'         => new external_multiple_structure(
                new external_single_structure([
                    'name'  => new external_value(PARAM_TEXT, 'Item data name'),
                    'value' => new external_value(PARAM_RAW, 'Item data value'),
                ])
            ),
        ]);
    }

}
