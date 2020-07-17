<?php

namespace local_lor\type\project;

use local_lor\type\file_type;
use local_lor\type\type;


class project
{
    use file_type, type;

    /**
     * This is where the project files will be stored in the filesystem
     *
     * @return string
     */
    private static function get_storage_directory()
    {
        return 'projects';
    }

    public static function get_name()
    {
        return get_string('type_name', 'lortype_project');
    }
}
