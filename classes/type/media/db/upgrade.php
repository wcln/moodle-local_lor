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

    if ($oldversion < 2021012200) {

        // Define table lortype_media_size to be created.
        $table = new xmldb_table('lortype_media_size');

        // Adding fields to table lortype_media_size.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('match', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('width', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('height', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table lortype_media_size.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for lortype_media_size.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Media savepoint reached.
        upgrade_plugin_savepoint(true, 2021012200, 'lortype', 'media');
    }


    return true;
}
