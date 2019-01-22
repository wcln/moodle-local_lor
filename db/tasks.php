<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
  // Run the update videos task every morning at 1AM.
  [
      'classname' => 'local_lor\task\update_videos',
      'blocking' => 0,
      'minute' => '0',
      'hour' => '1',
      'day' => '*',
      'month' => '*',
      'dayofweek' => '*',
      'disabled' => 0
  ],
  // Run the delete items task every 1st of the month at 12AM.
  [
      'classname' => 'local_lor\task\delete_items',
      'blocking' => 0,
      'minute' => '0',
      'hour' => '0',
      'day' => '1',
      'month' => '*',
      'dayofweek' => '*',
      'disabled' => 0
  ],
];
