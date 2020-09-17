<?php


use local_lor\item\data;
use local_lor\item\item;
use local_lor\item\property\category;
use local_lor\item\property\contributor;
use local_lor\item\property\topic;
use local_lor\type\group_activity\group_activity;
use local_lor\type\learning_guide\learning_guide;
use local_lor\type\project\project;

require_once(__DIR__.'/../../../config.php');

$wcln_www_root = 'https://wcln.ca/';

// Migrate categories
$oldrecords = $DB->get_records('lor_category');
foreach ($oldrecords as $oldrecord) {
    $DB->insert_record(category::TABLE, $oldrecord);
}

// Migrate items
$oldrecords = $DB->get_records('lor_content');
$fp = fopen('links_to_replace.csv', 'w');
fputcsv($fp, ['oldlink', 'newlink']);
foreach ($oldrecords as $oldrecord) {
    // Ignore video tutorials and lessons
    $type = get_type_from_id($oldrecord->type);
    if ($type !== 'video' && $type !== 'lesson') {
        $oldrecord->title = str_replace('/', '', $oldrecord->title);

        $newrecord = [
            'type'         => $type,
            'name'         => $oldrecord->title,
            'description'  => '',
            'timecreated'  => strtotime($oldrecord->date_created),
            'timemodified' => strtotime($oldrecord->date_created),
        ];

        $itemid = $DB->insert_record(item::TABLE, (object)$newrecord);

        if ($type === 'media') {
            $DB->insert_record(data::TABLE, (object)[
                'itemid' => $itemid,
                'name'   => 'width',
                'value'  => $oldrecord->width,
            ]);
            $DB->insert_record(data::TABLE, (object)[
                'itemid' => $itemid,
                'name'   => 'height',
                'value'  => $oldrecord->height,
            ]);
            $DB->insert_record(data::TABLE, (object)[
                'itemid' => $itemid,
                'name'   => 'url',
                'value'  => $oldrecord->link,
            ]);
        } elseif ($type === 'project') {
            process_files($oldrecord, project::get_storage_directory(), $itemid);
        } elseif ($type === 'group_activity') {
            process_files($oldrecord, group_activity::get_storage_directory(), $itemid);
        } elseif ($type === 'learning_guide') {
            process_files($oldrecord, learning_guide::get_storage_directory(), $itemid);
        }

        // Migrate contributors
        $contributors = $DB->get_records_sql("
                        SELECT DISTINCT c.name
                        FROM {lor_contributor} c
                        JOIN {lor_content_contributors} cc
                        ON c.id = cc.contributor
                        WHERE cc.content = :content_id
                    ", ['content_id' => $oldrecord->id]);
        foreach ($contributors as $contributor) {
            $names = explode(' ', $contributor->name);

            if ( ! (isset($names[0]) && isset($names[1]))) {
                continue;
            }

            $user = $DB->get_record_select('user', "firstname LIKE :firstname AND lastname LIKE :lastname",
                [
                    'firstname' => $names[0],
                    'lastname'  => $names[1],
                ]);

            if ($user) {
                $DB->insert_record(contributor::TABLE, (object)[
                    'itemid' => $itemid,
                    'userid' => $user->id,
                ]);
            }
        }

        // Migrate topics
        $topics       = $DB->get_records_sql("SELECT RAND(), t.* FROM {lor_content_topics} t WHERE content = :content",
            ['content' => $oldrecord->id]);
        $topics_array = [];
        foreach ($topics as $topic) {
            $topics_array[] = $topic->topic;
        }
        topic::save_item_form($itemid,
            (object)['topics' => implode(',', $topics_array)]);

        // Save the item's preview image
        $filename = basename($oldrecord->image);
        $fs       = get_file_storage();
        $fileinfo = [
            'contextid' => context_system::instance()->id,
            'component' => 'local_lor',
            'filearea'  => 'preview_image',
            'itemid'    => $itemid,
            'filepath'  => '/',
            'filename'  => "$filename",
        ];

        $image_file = rawurldecode($CFG->dirroot.'/'.str_replace($wcln_www_root, "", $oldrecord->image));

        try {
            $fs->create_file_from_pathname($fileinfo, $image_file);
        } catch (Exception $e) {
            echo "<p>Could not find item image $image_file</p>";
        }

        if (in_array($type, ['project', 'learning_guide', 'group_activity'])) {
            $oldlink = '';
            $newlink = '';

            fputcsv($fp, [$oldlink, $newlink]);
        }
    }
}

fclose($fp);

/**
 * Helper function for the upgrade script
 *
 * Map from the old type IDs to the new type strings
 *
 * @param $id
 *
 * @return false|string
 */
function get_type_from_id($id)
{
    switch ($id) {
        case 1:
            return 'media';
        case 2:
            return 'project';
        case 3:
            return 'video';
        case 5:
            return 'lesson';
        case 6:
            return 'learning_guide';
        case 7:
            return 'group_activity';
    }

    return false;
}

function process_files($oldrecord, $storage_dir, $itemid)
{
    global $CFG, $DB;

    $wcln_www_root = 'https://wcln.ca/';

    \local_lor\repository::create_directory($storage_dir);

    $pdf      = $oldrecord->link;
    $document = str_replace('.pdf', '.docx', $pdf);

    $filename    = \local_lor\repository::format_filepath("$oldrecord->title.pdf");
    $source      = $CFG->dirroot.'/'.str_replace($wcln_www_root, "", $pdf);
    $destination = \local_lor\repository::get_path_to_repository().$storage_dir."/"
                   .$filename;

    $DB->insert_record(data::TABLE, (object)[
        'itemid' => $itemid,
        'name'   => 'pdf',
        'value'  => $filename,
    ]);

    if (file_exists($source)) {
        copy($source, $destination);
    } else {
        echo "<p>Missing file: $source</p>";
    }

    $filename    = \local_lor\repository::format_filepath("$oldrecord->title.docx");
    $source      = $CFG->dirroot.'/'.str_replace($wcln_www_root, "", $document);
    $destination = \local_lor\repository::get_path_to_repository().$storage_dir."/"
                   .$filename;

        $DB->insert_record(data::TABLE, (object)[
            'itemid' => $itemid,
            'name'   => 'document',
            'value'  => $filename,
        ]);

    if (file_exists($source)) {
        copy($source, $destination);
    } else {
        echo "<p>Missing file: $source</p>";
    }
}
