<?php

use local_lor\item\item;

define('CLI_SCRIPT', true);

require_once(__DIR__.'/../../../config.php');
require_once("$CFG->libdir/clilib.php");

list($options, $unrecognized) = cli_get_params(['type' => false, 'help' => false],
    ['t' => 'type', 'h' => 'help']);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help'] || empty($options['type'])) {
    $help
        = "Delete all learning resources of a certain type

Options:
-t [type], --type=[type]         The type of LOR resource to delete
-h, --help                       Print out this help

Example:
\$ php local/lor/delete_type.php --type=video
";

    echo $help;
    die;
}


// Delete all items of this type
$type = $options['type'];
$items = $DB->get_records_select(item::TABLE, "type LIKE :type", ['type' => $type], null,
    'id');
foreach ($items as $item) {
    item::delete($item->id);
}
echo "Deleted " . count($items) . " resources of type '$type'\n";

die;
