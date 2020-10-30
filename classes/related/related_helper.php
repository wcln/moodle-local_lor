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
        $courses       = self::get_courses_used($itemid, $lti_type_ids);

        if ( ! empty($courses)) {
            // For each resource, see if it is also used in any of these courses
            foreach (
                $DB->get_records_sql("SELECT id, name FROM {".item::TABLE."} WHERE id != :itemid",
                    ['itemid' => $itemid])
                as
                $another_item
            ) {
                $another_courses = self::get_courses_used($another_item->id, self::get_lti_type_ids());

                $courseids       = array_column($courses, 'id');
                $related_courses = [];
                foreach ($another_courses as $course) {
                    if (in_array($course->id, $courseids)) {
                        $related_courses[] = [
                            'id'        => $course->id,
                            'fullname'  => $course->fullname,
                            'shortname' => $course->shortname,
                            'url'       => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(),
                        ];
                    }
                }

                if ( ! empty($related_courses)) {
                    $related_items[] = [
                        'id'      => $another_item->id,
                        'name'    => $another_item->name,
                        'image'   => item::get_image_url($another_item->id),
                        'url'     => (new moodle_url('/local/lor/index.php/resources/view/'.$another_item->id))->out(),
                        'courses' => $related_courses,
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
    public static function get_courses_used(int $itemid, array $lti_type_ids)
    {
        $cache = cache::make('local_lor', 'courses_used');
        if (($courses = $cache->get($itemid)) !== false) {
            return $courses;
        }

        // Search course activities to find where this item is used
        $activities = self::search_activities($itemid);

        // Find which LTI courses are using this activity
        $courses = [];
        foreach ($activities as $cmid) {
            $courses += self::search_lti_courses($cmid, $lti_type_ids);
        }

        $cache->set($itemid, $courses);

        return $courses;
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

    private static function search_lti_courses(int $cmid, array $lti_type_ids)
    {
        global $DB;

        $courses = [];

        $sql    = "
                SELECT DISTINCT c.id, c.fullname, c.shortname
                FROM {lti} l
                JOIN {course} c ON l.course = c.id
                JOIN {course_categories} cc ON c.category = cc.id
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
                $courses += $result;
            }
        }

        return $courses;
    }

}
