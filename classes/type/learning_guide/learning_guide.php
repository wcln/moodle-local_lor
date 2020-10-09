<?php

namespace local_lor\type\learning_guide;

use local_lor\type\file_type;
use local_lor\type\type;
use moodle_url;


class learning_guide
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

    public static function get_image_url()
    {
        return (new moodle_url('/local/lor/classes/type/learning_guide/assets/images/default_preview.png'))->out();
    }
}
