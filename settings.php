<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
	$settings = new admin_externalpage('local_lor', get_string('pluginname', 'local_lor'), "$CFG->wwwroot/local/lor/index.php");
	$ADMIN->add('localplugins', $settings);
}
