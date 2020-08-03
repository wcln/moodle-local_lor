<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_local_lor_upgrade($oldversion)
{
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    // Nothing to do here yet...

    return true;
}
