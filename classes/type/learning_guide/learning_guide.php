<?php

namespace local_lor\type\learning_guide;

use local_lor\type\file_type;
use local_lor\type\type;


class learning_guide
{
    use file_type, type;

    /**
     * This is where the files will be stored in the filesystem
     *
     * @return string
     */
    public static function get_storage_directory()
    {
        return 'learning_guides';
    }

    public static function get_name()
    {
        return get_string('type_name', 'lortype_learning_guide');
    }

    public static function get_icon()
    {
        return 'book-reader';
    }
}
