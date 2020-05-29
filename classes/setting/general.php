<?php

namespace local_lor\setting;

use admin_setting_configtext;
use admin_setting_heading;
use admin_settingpage;
use local_lor\repository;
use theme_boost_admin_settingspage_tabs;

class general implements settings_tab
{

    public static function add_tab(theme_boost_admin_settingspage_tabs $settings
    ) {
        $tab = new admin_settingpage(
            'local_lor_general',
            get_string('general_settings', 'local_lor')
        );

        // File storage heading
        $tab->add(new admin_setting_heading(
            'file_storage_heading',
            get_string('file_storage_heading', 'local_lor'),
            get_string('file_storage_info', 'local_lor')
        ));

        // Repository name
        $tab->add(new admin_setting_configtext(
            'repository',
            get_string('repository_setting', 'local_lor'),
            get_string('repository_setting_desc', 'local_lor'),
            repository::get_default_repository(),
            PARAM_URL
        ));

        $settings->add($tab);
    }
}
