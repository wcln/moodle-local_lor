<?php

require_once('../../config.php');
require_once('../../lib/filelib.php');

$path = required_param('path', PARAM_TEXT);
$filename = required_param('filename', PARAM_TEXT);

send_file(\local_lor\repository::get_path_to_repository() . $path, $filename);
die;
