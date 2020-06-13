<?php

namespace local_lor;

use moodle_url;

/**
 * Class repository
 *
 * This class contains functions to help access and store files
 * within the Moodle file system repository.
 *
 * @package local_lor
 */
class repository
{
    /** @var string Path to the file serving script in this plugin which will send files from the repository */
    private const PATH_TO_FILE_FETCHER = '/local/lor/file.php';

    /**
     * Get the default file system repository name
     *
     * @return string
     */
    public static function get_default_repository()
    {
        return 'learning_resources';
    }

    /**
     * Get the selected repository directory
     *
     * @return bool|mixed|object|string
     * @throws \dml_exception
     */
    public static function get_repository()
    {
        return get_config('local_lor', 'repository') ? : self::get_default_repository();
    }

    /**
     * Get a moodle URL to a file stored in the repository
     *
     * @param $path string Path to the file (within the repository directory) including the filename
     * @param $filename string The filename (including file extension)
     *
     * @return moodle_url
     * @throws \moodle_exception
     */
    public static function get_file_url(string $path, string $filename)
    {
        return new moodle_url(self::PATH_TO_FILE_FETCHER, [
            'path' => $path,
            'filename' => $filename
        ]);
    }

    /**
     * Get the full server path to the repository directory (located in moodledata)
     *
     * @return string
     * @throws \dml_exception
     */
    public static function get_path_to_repository()
    {
        global $CFG;

        return $CFG->dataroot.'/repository/'.self::get_repository() . "/";
    }
}
