<?php

defined('MOODLE_INTERNAL') || die;

/**
 * Local LOR web services definitions
 */

$functions = [
    'local_lor_get_resources' => [
        'classname'     => 'api',
        'methodname'    => 'get_resources',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get all resources for the main search page',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_resource' => [
        'classname'     => 'api',
        'methodname'    => 'get_resource',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get a single resource to display',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_resource_types' => [
        'classname'     => 'api',
        'methodname'    => 'get_resource_types',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get the types of resources',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_categories' => [
        'classname'     => 'api',
        'methodname'    => 'get_categories',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get all of the possible resource categories',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_grades' => [
        'classname'     => 'api',
        'methodname'    => 'get_grades',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get all of the possible resource grades',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_user' => [
        'classname'     => 'api',
        'methodname'    => 'get_user',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get the current logged in user',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_related_items' => [
        'classname'     => 'api',
        'methodname'    => 'get_related_items',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get related resources',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'local_lor_get_courses_used' => [
        'classname'     => 'api',
        'methodname'    => 'get_courses_used',
        'classpath'     => 'local/lor/classes/external/api.php',
        'description'   => 'Get courses that this resource is used in',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
];
