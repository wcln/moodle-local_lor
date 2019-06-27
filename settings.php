<?php

defined('MOODLE_INTERNAL') || die();

$settings = null;

// Ensure the configurations for this site are set.
if ($hassiteconfig) {

    // Create new external page.
    // $lor_page = new admin_externalpage('local_lor', get_string('pluginname', 'local_lor'), "$CFG->wwwroot/local/lor/index.php");
    // $ADMIN->add('localplugins', $lor_page);

    // Create the new settings page.
    $settings = new admin_settingpage('local_lor', get_string('pluginname', 'local_lor'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext(
        'local_lor/google_api_key',
        'Google API Key',
        'The Google API key used to query the YouTube API to update LOR videos.',
        'No API Key Defined.',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'local_lor/youtube_channel_id',
        'YouTube Channel ID',
        'The ID of the YouTube channel to populate the LOR with videos.',
        'No Channel ID Defined.',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'local_lor/youtube_max_results',
        'YouTube Max Results',
        'The maximum number of videos you may post in 24 hours. If more than this amount are posted on YouTube, they will not be added to the LOR.',
        25,
        PARAM_INT
    ));
}
