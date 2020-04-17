<?php

// Starting with Moodle 3.8, the subplugins should be defined in db/subplugins.json.
// https://docs.moodle.org/dev/Subplugins#db.2Fsubplugins.json
$subplugins = (array) json_decode(file_get_contents(__DIR__ . '/subplugins.json'))->plugintypes;
