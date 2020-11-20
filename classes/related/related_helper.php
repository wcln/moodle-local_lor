<?php

namespace local_lor\related;

use cache;
use dml_exception;
use local_lor\item\item;
use local_lor\type\type;
use moodle_exception;
use moodle_url;

/**
 * Class related_helper
 *
 * Custom build 'Related' functionality for WCLN
 * Integrates with their custom LTI provider code
 *
 * @package local_lor\related
 */
class related_helper
{

    private const LTI_BOOK_URL = 'https://wcln.ca/local/lti/index.php?type=book';
    private const LTI_PAGE_URL = 'https://wcln.ca/local/lti/index.php?type=page';

    /**
     * Get related resources
     *
     * @param  int  $itemid
     *
     * @return array With properties 'itemid' and 'courseid'
     * @throws dml_exception|moodle_exception
     */
    public static function get_related_items(int $itemid)
    {
        global $DB;

        $cache = cache::make('local_lor', 'related_items');

        $related_items = $cache->get($itemid);
        if ($related_items !== false) {
            return $related_items;
        }

        $related_items = [];
        $lti_type_ids  = self::get_lti_type_ids();
        $sections       = self::get_sections_used($itemid, $lti_type_ids);

        if ( ! empty($sections)) {
            // For each resource, see if it is also used in any of these courses
            $all_resources = $DB->get_records_sql("SELECT id, name, type FROM {".item::TABLE."} WHERE id != :itemid",
                ['itemid' => $itemid]);
            $total_resource_count = count($all_resources) + 1;
            $counter = 1;
            foreach (
                $all_resources
                as
                $another_item
            ) {
                $counter++;

                // Output script progress if we are running this in debug mode (from the scheduled task)
                if (defined('DEBUG')) {
                    echo "Processing item $another_item->id, which is item $counter / $total_resource_count.\n";
                }

                $another_sections = self::get_sections_used($another_item->id, self::get_lti_type_ids());

                $section_ids      = array_column($sections, 'id');
                $related_sections = [];
                foreach ($another_sections as $section) {
                    if (in_array($section->id, $section_ids)) {
                        $related_sections[] = [
                            'id'      => $section->id,
                            'section' => $section->section,
                            'name'    => $section->name,
                            'url'     => (new moodle_url('/course/view.php', ['id' => $section->courseid]))->out()
                                         ."#section-$section->section",
                            'course'  => [
                                'id'        => $section->courseid,
                                'fullname'  => $section->course_fullname,
                                'shortname' => $section->course_shortname,
                            ],
                        ];
                    }
                }

                if ( ! empty($related_sections)) {
                    $related_items[] = [
                        'id'       => $another_item->id,
                        'name'     => $another_item->name,
                        'type'     => $another_item->type,
                        'image'    => item::get_image_url($another_item->id),
                        'url'      => (new moodle_url('/local/lor/index.php/resources/view/'.$another_item->id))->out(),
                        'sections' => $related_sections,
                    ];
                }
            }
        }

        $cache->set($itemid, $related_items);

        return $related_items;
    }

    public static function get_lti_type_ids()
    {
        global $DB;

        $cache = cache::make('local_lor', 'lti_type_ids');
        if (($lti_type_ids = $cache->get('lti_type_ids')) !== false) {
            return $lti_type_ids;
        }

        $lti_book_type = $DB->get_record_select('lti_types', "baseurl LIKE :url", ['url' => self::LTI_BOOK_URL], 'id');
        $lti_page_type = $DB->get_record_select('lti_types', "baseurl LIKE :url", ['url' => self::LTI_PAGE_URL], 'id');

        if ($lti_page_type && $lti_book_type) {
            $lti_type_ids = [$lti_book_type->id, $lti_page_type->id];
        } else {
            throw new moodle_exception('error:unknown_lti_type', 'local_lor');
        }

        $cache->set('lti_type_ids', $lti_type_ids);

        return $lti_type_ids;
    }

    /**
     * Find which LTI courses use a LOR item
     *
     * @param  int  $itemid
     *
     * @param  array  $lti_type_ids
     *
     * @return array
     * @throws dml_exception
     */
    public static function get_sections_used(int $itemid, array $lti_type_ids)
    {
        $cache = cache::make('local_lor', 'sections_used');
        if (($sections = $cache->get($itemid)) !== false) {
            return $sections;
        }

        // Search course activities to find where this item is used
        $activities = self::search_activities($itemid);

        // Find which LTI course sections are using this activity
        $sections = [];
        foreach ($activities as $cmid) {
            $sections += self::search_lti_sections($cmid, $lti_type_ids);
        }

        $cache->set($itemid, $sections);

        return $sections;
    }

    /**
     * Search course activities to find where an item is used
     *
     * For now we only search in 'book' and 'page' activities.
     *
     * @param  int  $itemid
     *
     * @throws dml_exception
     */
    private static function search_activities(int $itemid)
    {
        global $DB;

        $item       = item::get($itemid);
        $type_class = type::get_class($item->type);
        if ($identifier = $type_class::get_unique_identifier($itemid)) {
            $sql    = "
                SELECT id AS cmid FROM (
                (
                    SELECT cm.id
                    FROM {course_modules} cm
                    JOIN {modules} m ON cm.module = m.id
                    JOIN {book} b ON b.id = cm.`instance`
                    JOIN {book_chapters} bc ON b.id = bc.bookid
                    WHERE m.name LIKE 'book'
                    AND bc.content LIKE CONCAT('%', :identifier1, '%')
                )
                UNION
                (
                    SELECT cm.id
                    FROM {course_modules} cm
                    JOIN {modules} m ON cm.module = m.id
                    JOIN {page} p ON p.id = cm.`instance`
                    WHERE m.name LIKE 'page'
                    AND p.content LIKE CONCAT('%', :identifier2, '%')
                )
            ) AS cmids
            ";
            $params = [
                'identifier1' => $identifier,
                'identifier2' => $identifier,
            ];


            return $DB->get_fieldset_sql($sql, $params);
        }

        return [];
    }

    private static function search_lti_sections(int $cmid, array $lti_type_ids)
    {
        global $DB;

        $sections = [];

        $sql    = "
                SELECT DISTINCT cs.id, cs.section, cs.name, c.id AS courseid, c.fullname AS course_fullname, c.shortname AS course_shortname
                FROM {lti} l
                JOIN {course} c ON l.course = c.id
                JOIN {course_categories} cc ON c.category = cc.id   
                JOIN {course_sections} cs ON cs.course = c.id
                JOIN {course_modules} cm ON cm.course = c.id AND cm.section = cs.id AND cm.instance = l.id
                WHERE typeid = :typeid
                AND instructorcustomparameters LIKE CONCAT('%=', :cmid)
                AND c.visible = 1
                AND cc.visible = 1
            ";
        $params = [
            'cmid' => $cmid,
        ];

        foreach ($lti_type_ids as $type_id) {
            $params['typeid'] = $type_id;
            if ($result = $DB->get_records_sql($sql, $params)) {
                $sections += $result;
            }
        }

        return $sections;
    }

}
