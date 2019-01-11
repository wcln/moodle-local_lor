<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
	$settings = new admin_externalpage('local_lor', get_string('pluginname', 'local_lor'), "$CFG->wwwroot/local/lor/index.php");
	$ADMIN->add('localplugins', $settings);
}

$settings->add( new admin_setting_configtext(
	 'local_lor\google_api_key',
	 'Google API Key',
	 'The Google API key used to query the YouTube API to update LOR videos.',
	 PARAM_TEXT,
	 50
));

$settings->add( new admin_setting_configtext(
	 'local_lor\youtube_channel_id',
	 'YouTube Channel ID',
	 'The ID of the YouTube channel to populate the LOR with videos.',
	 PARAM_TEXT,
	 50
));

$settings->add( new admin_setting_configtext(
	 'local_lor\youtube_max_results',
	 'YouTube Max Results',
	 'The maximum number of videos you may post in 24 hours. If more than this amount are posted on YouTube, they will not be added to the LOR.',
	 PARAM_INT,
	 2
));
