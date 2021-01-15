<?php

define('CLI_SCRIPT', true);

require_once(__DIR__.'/../../../config.php');
require_once("$CFG->libdir/clilib.php");

define('OSBC_USERID', 7038);

$contributors = $DB->get_records_select('lor_contributor', "name LIKE :name1 OR name LIKE :name2",
    ['name1' => 'OSBC', 'name2' => 'Open School BC']);

foreach ($contributors as $contributor) {
    $items = $DB->get_records_sql("
        SELECT id, title FROM {lor_content} c
        JOIN {lor_content_contributors} cc
        ON cc.content = c.id AND cc.contributor = ?
    ", [$contributor->id]);

    mtrace("Found " . count($items) . " matching contributor $contributor->name");

    foreach ($items as $item) {
        $newItem = $DB->get_record_select('local_lor_item', "name LIKE :name", ['name' => $item->title], '*', IGNORE_MULTIPLE);
        if (! $DB->record_exists('local_lor_item_contributors', ['itemid' => $newItem->id])) {
            $DB->insert_record('local_lor_item_contributors', (object) ['itemid' => $newItem->id, 'userid' => OSBC_USERID]);
            mtrace("Adding OSBC as contributor for item $item->id");
        }
    }
}

mtrace("Done!");
