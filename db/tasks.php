<?php

defined('MOODLE_INTERNAL') || die;

$tasks = [
    // Cache related items every night
    [
        'classname' => 'local_lor\task\cache_related',
        'blocking' => 0,
        'minute' => 0,
        'hour' => 3,
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
        'disabled' => 0
    ]
];
