<?php

namespace local_lor\setting;

use admin_setting_heading;
use admin_settingpage;
use local_lor\output\category_list;
use local_lor\output\grade_list;
use local_lor\page;
use theme_boost_admin_settingspage_tabs;

class item_properties implements settings_tab
{

    public static function add_tab(theme_boost_admin_settingspage_tabs $settings
    ) {
        $tab = new admin_settingpage(
            'local_lor_item_properties',
            get_string(
                'item_properties_settings',
                'local_lor'
            )
        );

        $renderer = page::get_renderer();

        // Categories list
        $category_list = new category_list();
        $html          = $renderer->render($category_list);
        $tab->add(
            new admin_setting_heading(
                'local_lor/category_list',
                get_string('category_list_heading', 'local_lor'),
                $html
            )
        );

        // Grades list
        $grade_list = new grade_list();
        $html       = $renderer->render($grade_list);
        $tab->add(
            new admin_setting_heading(
                'local_lor/grade_list',
                get_string('grade_list_heading', 'local_lor'),
                $html
            )
        );

        $settings->add($tab);
    }
}
