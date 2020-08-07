<?php

require_once('../../config.php');
require_once('../../lib/filelib.php');

$filepath = required_param('filepath', PARAM_TEXT);

send_file(\local_lor\repository::get_path_to_repository() . $filepath, basename($filepath));
die;
