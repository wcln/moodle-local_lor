<?php

use local_lor\setting\general;
use local_lor\setting\item_properties;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create the settings page
    $settings = new theme_boost_admin_settingspage_tabs(
        'local_lor',
        get_string(
            'pluginname',
            'local_lor'
        )
    );
    $ADMIN->add('localplugins', $settings);

    // General settings
    general::add_tab($settings);

    // Item property settings
    item_properties::add_tab($settings);
}
