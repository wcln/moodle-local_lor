<?php

namespace local_lor\type\group_activity;

use local_lor\type\file_type;
use local_lor\type\type;


class group_activity
{
    use file_type, type {
        file_type::delete insteadof type;
    }

    /**
     * This is where the files will be stored in the filesystem
     *
     * @return string
     */
    public static function get_storage_directory()
    {
        return 'group_activities';
    }

    public static function get_name()
    {
        return get_string('type_name', 'lortype_group_activity');
    }

    public static function get_icon()
    {
        return 'users';
    }
}
