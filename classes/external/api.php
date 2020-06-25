<?php

use local_lor\helper;
use local_lor\item\item;
use local_lor\item\property\category;
use local_lor\item\property\grade;
use local_lor\type\type;

require_once("$CFG->libdir/externallib.php");

class api extends external_api
{

    /*
    |--------------------------------------------------------------------------
    | Get resources
    |--------------------------------------------------------------------------
    |
    | Search for resources
    |
    */

    public static function get_resources_parameters()
    {
        return new external_function_parameters([
            'page'       => new external_value(PARAM_INT, 'The current page number', VALUE_OPTIONAL),
            'keywords'   => new external_value(PARAM_TEXT, 'Keywords to search for', VALUE_OPTIONAL),
            'type'       => new external_value(PARAM_TEXT, 'Type of resources', VALUE_OPTIONAL),
            'categories' => new external_multiple_structure(
                new external_single_structure([
                    'id'   => new external_value(PARAM_INT, 'Category ID'),
                    'name' => new external_value(PARAM_TEXT, 'Category name'),
                ]),
                'List of categories', VALUE_OPTIONAL
            ),
            'grades'     => new external_multiple_structure(
                new external_single_structure([
                    'id'   => new external_value(PARAM_INT, 'Grade ID'),
                    'name' => new external_value(PARAM_TEXT, 'Grade name'),
                ]),
                'List of grades', VALUE_OPTIONAL
            ),
            'sort'       => new external_value(PARAM_TEXT, 'How to sort the results', VALUE_OPTIONAL),
        ]);
    }

    public static function get_resources(
        $page = null,
        $keywords = null,
        $type = null,
        $categories = [],
        $grades = [],
        $sort = null
    ) {
        $params = self::validate_parameters(self::get_resources_parameters(),
            compact('page', 'keywords', 'type', 'categories', 'grades', 'sort'));
        
        // Clean categories and grades (we only want the IDs for the search function)
        $params['categories'] = array_column($params['categories'], 'id');
        $params['grades']     = array_column($params['grades'], 'id');

        $items = array_values(item::search($params['keywords'], $params['type'], $params['categories'],
            $params['grades'], $params['sort']));

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

    /*
    |--------------------------------------------------------------------------
    | Get resource
    |--------------------------------------------------------------------------
    |
    | Get a single resource using the item ID
    |
    */

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
        $item->embed   = $type_class::get_embed_html($item->id);

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
            'display'      => new external_value(PARAM_RAW, 'Item display HTML'),
            'embed'        => new external_value(PARAM_RAW, 'Item embed HTML'),
            'data'         => new external_multiple_structure(
                new external_single_structure([
                    'name'  => new external_value(PARAM_TEXT, 'Item data name'),
                    'value' => new external_value(PARAM_RAW, 'Item data value'),
                ])
            ),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get resource types
    |--------------------------------------------------------------------------
    |
    | Get all of the configured resource types.
    | For example: "Games / media", "Projects", "Group activities" etc...
    |
    */

    public static function get_resource_types_parameters()
    {
        return new external_function_parameters([]);
    }

    public static function get_resource_types()
    {
        $resource_types = [];
        foreach (type::get_all_types() as $value => $name) {
            $resource_types[] = [
                'value' => $value,
                'name'  => $name,
            ];
        }

        return $resource_types;
    }

    public static function get_resource_types_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'value' => new external_value(PARAM_TEXT, 'The resource type value'),
                'name'  => new external_value(PARAM_TEXT, 'The resource type name to be displayed'),
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get categories
    |--------------------------------------------------------------------------
    |
    | Get all of the configured categories.
    | For example: "Math", "Science", "Physics"
    |
    */

    public static function get_categories_parameters()
    {
        return new external_function_parameters([]);
    }

    public static function get_categories()
    {
        return category::get_all();
    }

    public static function get_categories_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id'   => new external_value(PARAM_INT, 'The category ID'),
                'name' => new external_value(PARAM_TEXT, 'The category name'),
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get grades
    |--------------------------------------------------------------------------
    |
    | Get all of the configured grades.
    | For example: "1", "2", "10"
    |
    */

    public static function get_grades_parameters()
    {
        return new external_function_parameters([]);
    }

    public static function get_grades()
    {
        return grade::get_all();
    }

    public static function get_grades_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id'   => new external_value(PARAM_INT, 'The grade ID'),
                'name' => new external_value(PARAM_TEXT, 'The grade name'),
            ])
        );
    }

}
