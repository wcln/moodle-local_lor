<?php

namespace local_lor;

use dml_exception;
use moodle_exception;
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
        return 'LOR';
    }

    /**
     * Get the selected repository directory
     *
     * @return bool|mixed|object|string
     * @throws dml_exception
     */
    public static function get_repository()
    {
        return get_config('local_lor', 'repository') ?: self::get_default_repository();
    }

    /**
     * Get a moodle URL to a file stored in the repository
     *
     * @param $path string Path to the file (within the repository directory) including the filename
     * @param $filename string The filename (including file extension)
     *
     * @return moodle_url
     * @throws moodle_exception
     */
    public static function get_file_url(string $path, string $filename)
    {
        return new moodle_url(self::PATH_TO_FILE_FETCHER, [
            'path'     => $path,
            'filename' => $filename,
        ]);
    }

    /**
     * Get the full server path to the repository directory (located in moodledata)
     *
     * @return string
     * @throws dml_exception
     */
    public static function get_path_to_repository()
    {
        global $CFG;

        return $CFG->dataroot.'/repository/'.self::get_repository()."/";
    }

    /**
     * Clean and format a filename within a filepath
     *
     * @param $filepath string A filepath, for example 'projects/MyProject.pdf'
     *
     * @return string The same filepath, with the filename formatted
     */
    public static function format_filepath($filepath)
    {
        // Separate filename into the basename and the file extension (.pdf, .jpg etc...)
        $file_extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $filename       = pathinfo($filepath, PATHINFO_FILENAME);

        if (strpos($filepath, '/') !== false) {
            $dirname = pathinfo($filepath, PATHINFO_DIRNAME);
        } else {
            $dirname = false;
        }

        // Remove anything which isn't a word, whitespace, number
        // Adapted from: https://stackoverflow.com/a/2021729
        $filename = mb_ereg_replace("([^\w\s\d])", '', $filename);

        // Shorten the basename if needed
        $filename = substr($filename, 0, 255);

        // Convert to lowercase
        $filename = strtolower($filename);

        if ($dirname !== false) {
            return "$dirname/$filename.$file_extension";
        }

        return "$filename.$file_extension";
    }
}
