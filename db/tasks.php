<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
    // Run the update videos task every morning at 1AM.
    [
        'classname' => 'local_lor\task\update_videos',
        'blocking'  => 0,
        'minute'    => '0',
        'hour'      => '1',
        'day'       => '*',
        'month'     => '*',
        'dayofweek' => '*',
        'disabled'  => 0,
    ],
];
