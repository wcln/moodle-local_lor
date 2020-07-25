<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $settings = new admin_settingpage('lortype_video', get_string('pluginname', 'lortype_video'));

    // Google API key
    $settings->add(new admin_setting_configtext(
        'lortype_video/google_api_key',
        get_string('setting:google_api_key', 'lortype_video'),
        get_string('setting:google_api_key_desc', 'lortype_video'),
        '',
        PARAM_TEXT
    ));

    // YouTube channel ID
    $settings->add(new admin_setting_configtext(
        'lortype_video/youtube_channel_id',
        get_string('setting:youtube_channel_id', 'lortype_video'),
        get_string('setting:youtube_channel_id_desc', 'lortype_video'),
        '',
        PARAM_TEXT
    ));

    // YouTube max results
    $settings->add(new admin_setting_configtext(
        'lortype_video/youtube_max_results',
        get_string('setting:youtube_max_results', 'lortype_video'),
        get_string('setting:youtube_max_results_desc', 'lortype_video'),
        25,
        PARAM_INT
    ));

    $ADMIN->add('local_lor_category', $settings);

}

