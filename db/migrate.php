<?php

use local_lor\item\data;
use local_lor\item\item;
use local_lor\item\property\category;
use local_lor\item\property\contributor;
use local_lor\item\property\grade;
use local_lor\item\property\topic;
use local_lor\repository;
use local_lor\type\group_activity\group_activity;
use local_lor\type\learning_guide\learning_guide;
use local_lor\type\project\project;
use local_lor\type\type;

define('CLI_SCRIPT', true);

require_once(__DIR__.'/../../../config.php');

$wcln_www_root = 'https://wcln.ca/';

// Migrate categories
$oldrecords = $DB->get_records('lor_category');
foreach ($oldrecords as $oldrecord) {
    $DB->insert_record(category::TABLE, $oldrecord);
}

// Migrate grades
for ($i = 1; $i <= 12; $i++) {
    $DB->insert_record(grade::TABLE, (object)['name' => $i]);
}

// Migrate items
$oldrecords = $DB->get_records('lor_content');
$fp         = fopen('links_to_replace.csv', 'w');
foreach ($oldrecords as $oldrecord) {
    // Ignore lessons
    $type = get_type_from_id($oldrecord->type);
    if ($type !== 'lesson') {
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
        } elseif ($type === 'video') {
            // Save the video ID
            if ($content_video_record = $DB->get_record('lor_content_videos', ['content' => $oldrecord->id])) {
                $DB->insert_record(data::TABLE, (object)[
                    'itemid' => $itemid,
                    'name'   => 'videoid',
                    'value'  => $content_video_record->video_id,
                ]);
            }

            // Save video preview image
            $fs       = get_file_storage();
            $fileinfo = [
                'contextid' => context_system::instance()->id,
                'component' => 'local_lor',
                'filearea'  => 'preview_image',
                'itemid'    => $itemid,
                'filepath'  => '/',
                'filename'  => "$content_video_record->video_id.jpg",
            ];
            try {
                $fs->create_file_from_url($fileinfo, $oldrecord->image);
            } catch (Exception $e) {
                mtrace("Could not save video image: $oldrecord->image for item with ID: $itemid");
            }
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
                ], 'id', IGNORE_MULTIPLE);

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

        // Migrate item categories
        $categories
            = $DB->get_records_sql("SELECT RAND(), category FROM {lor_content_categories} WHERE content = :content",
            ['content' => $oldrecord->id]);
        foreach ($categories as $category) {
            $category_record = $DB->get_record('lor_category', ['id' => $category->category]);
            if ($new_category_record = $DB->get_record_select(category::TABLE, "name LIKE :name",
                ['name' => $category_record->name])
            ) {
                $DB->insert_record(category::LINKING_TABLE,
                    (object)['itemid' => $itemid, 'categoryid' => $new_category_record->id]);
            }
        }

        // Migrate item grades
        $grades = $DB->get_records_sql("SELECT RAND(), grade FROM {lor_content_grades} WHERE content = :content",
            ['content' => $oldrecord->id]);
        foreach ($grades as $grade) {
            if ($new_grade_record = $DB->get_record_select(grade::TABLE, "name LIKE :name",
                ['name' => $grade->grade])
            ) {
                $DB->insert_record(grade::LINKING_TABLE,
                    (object)['itemid' => $itemid, 'gradeid' => $new_grade_record->id]);
            }
        }

        // Save the item's preview image
        if ($type !== 'video') {
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
                mtrace("Could not find item image $image_file for item with ID: $itemid");
            }
        }

        // Save list of old link and new link to CSV file so we can replace them site-wide
        if (in_array($type, ['project', 'learning_guide', 'group_activity'])) {
            $oldlink = $oldrecord->link;

            $type_class = type::get_class($type);
            $newlink    = repository::get_file_url($itemid);

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

    repository::create_directory($storage_dir);

    $pdf      = $oldrecord->link;
    $document = str_replace('.pdf', '.docx', $pdf);

    $filename    = repository::format_filepath("$oldrecord->title.pdf");
    $source      = $CFG->dirroot.'/'.str_replace($wcln_www_root, "", $pdf);
    $destination = repository::get_path_to_repository().$storage_dir."/"
                   .$filename;

    $DB->insert_record(data::TABLE, (object)[
        'itemid' => $itemid,
        'name'   => 'pdf',
        'value'  => $filename,
    ]);

    if (file_exists($source)) {
        copy($source, $destination);
    } else {
        mtrace("Missing file: $source for item with ID: $itemid");
    }

    $filename    = repository::format_filepath("$oldrecord->title.docx");
    $source      = $CFG->dirroot.'/'.str_replace($wcln_www_root, "", $document);
    $destination = repository::get_path_to_repository().$storage_dir."/"
                   .$filename;

    $DB->insert_record(data::TABLE, (object)[
        'itemid' => $itemid,
        'name'   => 'document',
        'value'  => $filename,
    ]);

    if (file_exists($source)) {
        copy($source, $destination);
    } else {
        mtrace("Missing file: $source for item with ID: $itemid");
    }
}
