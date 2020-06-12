<?php

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
];
