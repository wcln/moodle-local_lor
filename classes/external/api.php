<?php

use local_lor\item\item;

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
            'id' => new external_value(PARAM_INT, 'The ID of the item to retrieve')
        ]);
    }

    public static function get_resource($id)
    {
        $params = self::validate_parameters(self::get_resource_parameters(), compact('id'));

        return item::get($params['id']);
    }

    public static function get_resources_return()
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
                'categories' => new external_multiple_structure(
                    new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'Category ID'),
                        'name' => new external_value(PARAM_TEXT, 'Category name'),
                    ])
                ),
                'contributors' => new external_multiple_structure(
                    new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'Contributor user ID'),
                        'fullname' => new external_value(PARAM_TEXT, 'Contributor full name'),
                    ])
                ),
                'grades' => new external_multiple_structure(
                    new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'Grade ID'),
                        'name' => new external_value(PARAM_TEXT, 'Grade name'),
                    ])
                ),
                'topics' => new external_multiple_structure(
                    new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'Topic ID'),
                        'name' => new external_value(PARAM_TEXT, 'Topic name'),
                    ])
                ),
                'data' => new external_multiple_structure(
                    new external_single_structure([
                        'name' => new external_value(PARAM_INT, 'Item data name'),
                        'value' => new external_value(PARAM_TEXT, 'Item data value'),
                    ])
                ),
            ])
        );
    }

}
