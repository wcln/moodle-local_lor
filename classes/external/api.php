<?php

use local_lor\helper;
use local_lor\item\item;
use local_lor\item\property\category;
use local_lor\item\property\grade;
use local_lor\related\related_helper;
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
            'perpage'    => new external_value(PARAM_INT, 'The number of items per page', VALUE_OPTIONAL),
        ]);
    }

    public static function get_resources(
        $page = 0,
        $keywords = null,
        $type = null,
        $categories = [],
        $grades = [],
        $sort = null,
        $perpage = 8 // Default is 8 resources per page
    )
    {
        $params = self::validate_parameters(self::get_resources_parameters(),
            compact('page', 'keywords', 'type', 'categories', 'grades', 'sort', 'perpage'));

        // Clean categories and grades (we only want the IDs for the search function)
        $params['categories'] = array_column($params['categories'], 'id');
        $params['grades']     = array_column($params['grades'], 'id');

        $items = item::search($params['keywords'], $params['type'], $params['categories'],
            $params['grades'], $params['sort'], $params['page'], $params['perpage']);

        $resource_count = item::count_search_results($params['keywords'], $params['type'], $params['categories'],
            $params['grades']);
        $num_pages      = ceil($resource_count / $perpage);

        // Include the image URLs as well
        foreach ($items as $item) {
            $item->image = item::get_image_url($item->id);
        }

        return [
            'resources'      => $items,
            'pages'          => $num_pages,
            'resource_count' => $resource_count,
        ];
    }

    public static function get_resources_returns()
    {
        return new external_single_structure([
            'pages'          => new external_value(PARAM_INT, 'The total number of pages'),
            'resource_count' => new external_value(PARAM_INT, 'The total number of resources matching this search'),
            'resources'      => new external_multiple_structure(
                new external_single_structure([
                    'id'           => new external_value(PARAM_INT, 'Item ID'),
                    'type'         => new external_value(PARAM_TEXT, 'Item type'),
                    'name'         => new external_value(PARAM_TEXT, 'Item name'),
                    'image'        => new external_value(PARAM_TEXT, 'Item image'),
                    'description'  => new external_value(PARAM_RAW, 'Item description'),
                    'timecreated'  => new external_value(PARAM_INT, 'Time created'),
                    'timemodified' => new external_value(PARAM_INT, 'Time modified'),
                ])
            ),
        ]);
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
            $type_class       = type::get_class($value);
            $resource_types[] = [
                'value' => $value,
                'name'  => $name,
                'icon'  => $type_class::get_icon(),
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
                'icon'  => new external_value(PARAM_TEXT, 'The resource font-awesome icon identifier'),
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

    /*
    |--------------------------------------------------------------------------
    | Get user
    |--------------------------------------------------------------------------
    |
    | Get the current logged in user. For now this just checks if they are an admin
    | so we can hide some controls on the front end.
    |
    */

    public static function get_user_parameters()
    {
        return new external_function_parameters([]);
    }

    public static function get_user()
    {
        return [
            'isAdmin' => has_capability('local/lor:manage', context_system::instance()),
        ];
    }

    public static function get_user_returns()
    {
        return new external_single_structure([
            'isAdmin' => new external_value(PARAM_BOOL,
                'True if the current logged in user is an admin, otherwise false'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get related items/resources
    |--------------------------------------------------------------------------
    |
    | Get resources which are used in the same courses as an item
    |
    */

    public static function get_related_items_parameters()
    {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'The item ID to find related items for'),
        ]);
    }

    public static function get_related_items(int $id)
    {
        $params = self::validate_parameters(self::get_related_items_parameters(), compact('id'));

        return related_helper::get_related_items($params['id']);
    }

    public static function get_related_items_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id'      => new external_value(PARAM_INT, 'ID of the related item'),
                'name'    => new external_value(PARAM_TEXT),
                'type'    => new external_value(PARAM_TEXT),
                'image'   => new external_value(PARAM_TEXT),
                'url'     => new external_value(PARAM_URL),
                'courses' => new external_multiple_structure(
                    new external_single_structure([
                        'id'        => new external_value(PARAM_INT, 'ID of the course this item is used in'),
                        'fullname'  => new external_value(PARAM_TEXT),
                        'shortname' => new external_value(PARAM_TEXT),
                        'url'       => new external_value(PARAM_URL),
                    ])
                ),
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get courses that this resource is used in
    |--------------------------------------------------------------------------
    |
    | Get courses that this resource is used in. This supports 'Related' functionality.
    |
    */

    public static function get_courses_used_parameters()
    {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'The item ID to find related items for'),
        ]);
    }

    public static function get_courses_used(int $id)
    {
        $params = self::validate_parameters(self::get_courses_used_parameters(), compact('id'));

        if ($courses = related_helper::get_courses_used($params['id'], related_helper::get_lti_type_ids())) {
            foreach ($courses as $course) {
                $course->url = (new moodle_url('/course/view.php', ['id' => $course->id]))->out();
            }
        }

        return $courses;
    }

    public static function get_courses_used_returns()
    {
        return new external_multiple_structure(
            new external_single_structure([
                'id'        => new external_value(PARAM_INT, 'Course ID'),
                'fullname'  => new external_value(PARAM_INT, 'Course full name'),
                'shortname' => new external_value(PARAM_INT, 'Course short name'),
                'url'       => new external_value(PARAM_INT, 'Link to course page'),
            ])
        );
    }


}
