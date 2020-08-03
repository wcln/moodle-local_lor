<?php

use local_lor\item\data;
use local_lor\item\item;
use local_lor\item\property\category;
use local_lor\item\property\contributor;
use local_lor\item\property\topic;
use local_lor\type\project\project;

defined('MOODLE_INTERNAL') || die;

function xmldb_local_lor_upgrade($oldversion)
{
    global $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2020073100) {
        // If the old database exists, let's migrate over the data
        if ($dbman->table_exists('lor_content')) {

            $wcln_www_root = 'https://wcln.ca';

            // Migrate categories
            $oldrecords = $DB->get_records('lor_category');
            foreach ($oldrecords as $oldrecord) {
                $DB->insert_record(category::TABLE, $oldrecord);
            }

            // Migrate items
            $oldrecords = $DB->get_records('lor_content');
            foreach ($oldrecords as $oldrecord) {
                // Ignore video tutorials and lessons
                $type = get_type_from_id($oldrecord->type);
                if ($oldrecord->type !== 'video' && $oldrecord !== 'lesson') {
                    $newrecord = [
                        'type'         => $type,
                        'name'         => $oldrecord->title,
                        'description'  => '',
                        'timecreated'  => strtotime($oldrecord->date_create),
                        'timemodified' => strtotime($oldrecord->date_create),
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
                        \local_lor\repository::create_directory(project::get_storage_directory());

                        $pdf = $oldrecord->link;
                        $document = str_replace('.pdf', '.docx', $pdf);

                        $source = str_replace($wcln_www_root, \local_lor\repository::get_path_to_repository(), $pdf);
                        $destination = \local_lor\repository::get_path_to_repository() . "/" . project::get_storage_directory() . "/" . \local_lor\repository::format_filepath("$oldrecord->title.pdf");

                        copy($source, $destination);

                        $source = str_replace($wcln_www_root, \local_lor\repository::get_path_to_repository(), $document);
                        $destination = \local_lor\repository::get_path_to_repository() . "/" . project::get_storage_directory() . "/" . \local_lor\repository::format_filepath("$oldrecord->title.docx");

                        copy($source, $destination);

                    } elseif ($type === 'group_activity') {
                        // Save to repository from oldrecord link

                    } elseif ($type === 'learning_guide') {
                        // Save to repository from oldrecord link

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
                    $topics       = $DB->get_records('lor_content_topic', ['content' => $oldrecord->id]);
                    $topics_array = [];
                    foreach ($topics as $topic) {
                        $topics_array[] = $topic->topic;
                    }
                    topic::save_item_form($itemid,
                        (object)['topics' => implode(',', $topics_array)]);
                }
            }
        }

        upgrade_plugin_savepoint(true, 2020073100, 'local', 'lor');
    }

    return true;
}

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
