<?php

namespace local_lor\setting;

use theme_boost_admin_settingspage_tabs;

interface settings_tab {
    public static function add_tab(theme_boost_admin_settingspage_tabs $settings);
}
