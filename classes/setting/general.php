<?php

namespace local_lor\setting;

use admin_settingpage;
use theme_boost_admin_settingspage_tabs;

class general implements settings_tab
{

    public static function add_tab(theme_boost_admin_settingspage_tabs $settings
    ) {
        $tab = new admin_settingpage(
            'local_lor_general',
            get_string('general_settings', 'local_lor')
        );

        // TODO Add any general settings here

        $settings->add($tab);
    }
}
