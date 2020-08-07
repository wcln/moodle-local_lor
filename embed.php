<?php

use local_lor\item\item;
use local_lor\type\type;

require_once('../../config.php');

$id = required_param('id', PARAM_TEXT);
$type = optional_param('type', 'pdf', PARAM_TEXT);

if ($item = item::get($id)) {

    // Get the type class, which should give us the required filepath
    $type_class = type::get_class($item->type);
    $filepath = $type_class::get_embed_filepath($id, $type);

    redirect(new moodle_url('/local/lor/file.php', [
        'filepath' => $filepath
    ]));

} else {
    print_error('error:incorrect_embed_id', 'local_lor');
}
