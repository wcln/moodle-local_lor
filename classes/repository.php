<?php

namespace local_lor;

use coding_exception;
use context_system;
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
     * Save filemanager form elements to the file system repository
     *
     * @param  array  $elements
     *                  Should be in format: [ 'name' => 'the form name', 'filepath' => 'The path within the repo to store' ]
     *
     * @return array Results in format [ 'element_name' => 'saved_filepath' ]
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function save_to_repository(array $elements)
    {
        $context = context_system::instance();

        $results = [];

        foreach ($elements as $element) {
            $draftitemid = file_get_submitted_draft_itemid($element['name']);

            file_save_draft_area_files($draftitemid, $context->id, 'local_lor', 'temp', $draftitemid);

            // Temporarily store them in the database (we delete them in the for loop below)
            $fs    = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_lor', 'temp', $draftitemid);

            foreach ($files as $file) {
                $filepath = self::get_path_to_repository().self::format_filename($element['filepath']);

                $file->copy_content_to($filepath);
                $file->delete();

                $results[$element['name']] = basename($filepath);
            }
        }

        return $results;
    }

    /**
     * Clean and format a filename
     *
     * @param $filename string A filename, for example 'MyProject.pdf'
     *
     * @return string
     */
    public static function format_filename($filename)
    {
        // Separate filename into the basename and the file extension (.pdf, .jpg etc...)
        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
        $filename       = pathinfo($filename, PATHINFO_FILENAME);

        // Remove anything which isn't a word, whitespace, number
        // Adapted from: https://stackoverflow.com/a/2021729
        $filename = mb_ereg_replace("([^\w\s\d])", '', $filename);

        // Shorten the basename if needed
        $filename = substr($filename, 0, 255);

        // Convert to lowercase
        $filename = strtolower($filename);

        return "$filename.$file_extension";
    }
}
