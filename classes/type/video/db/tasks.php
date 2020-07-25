<?php

defined('MOODLE_INTERNAL') || die;

$tasks = [
    // Scrape YouTube every morning at 1AM
    [
        'classname' => 'lortype_video\task\scrape_youtube',
        'blocking' => 0,
        'minute' => 0,
        'hour' => 1,
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
        'disabled' => 0
    ]
];
