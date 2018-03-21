<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
	$settings = new admin_externalpage('local_projectspage', get_string('pluginname', 'local_projectspage'), "$CFG->wwwroot/local/projectspage/index.php");
	$ADMIN->add('localplugins', $settings);
}
