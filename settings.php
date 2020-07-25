<?php

use local_lor\setting\general;
use local_lor\setting\item_properties;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    // Create a settings category
    $ADMIN->add('localplugins', new admin_category('local_lor_category',
        get_string('pluginname', 'local_lor')));

    // Create the settings page
    $settings = new theme_boost_admin_settingspage_tabs(
        'local_lor',
        get_string(
            'pluginname',
            'local_lor'
        )
    );
    $ADMIN->add('local_lor_category', $settings);

    // General settings
    general::add_tab($settings);

    // Item property settings
    item_properties::add_tab($settings);

    // Load subplugin settings
    foreach (core_plugin_manager::instance()->get_plugins_of_type('lortype') as $plugin) {
        if (file_exists("$plugin->rootdir/settings.php")) {
            include("$plugin->rootdir/settings.php");
        }
    }

}
