<?php

use local_lor\item\data;

defined('MOODLE_INTERNAL') || die;

function xmldb_lortype_media_upgrade($oldversion)
{
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    // Delete all existing 'width' and 'height' item data
    if ($oldversion < 2020102300) {
        $DB->delete_records_select(data::TABLE, "name LIKE 'width' OR name LIKE 'height'");

        upgrade_plugin_savepoint(true, 2020102300, 'lortype', 'media');
    }

    return true;
}
