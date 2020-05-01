<?php

/**
 * Files support for this plugin
 *
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param  array  $options
 */
function local_lor_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options = [])
{
    $fs   = get_file_storage();
    $file = $fs->get_file($context->id, 'local_lor', $filearea, $args[0], '/', $args[1]);
    if ( ! $file) {
        send_file_not_found();
    }

    send_stored_file($file, 86400, 0, $forcedownload, $options);
}
