<?php

namespace local_lor\related;

use dml_exception;
use local_lor\item\item;
use local_lor\type\type;

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

    public static function get_related_items(int $itemid)
    {
        // Search course activities to find where this item is used
        $activities = self::search_activities($itemid);

        // For each course activity, find LTI activities which reference it
        $courseids = [];
        foreach ($activities as $cmid) {
            $courseids = array_merge($courseids, self::search_lti_courses($cmid));
        }

        return $courseids;

        // TODO Get a list of LTI provider course IDs this item is used in

        // TODO See which other resources are used in these courses

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

    private static function search_lti_courses(int $cmid)
    {
        global $DB;

        $courses = [];

        $lti_book_url = 'https://wcln.ca/local/lti/index.php?type=book';
        $lti_page_url = 'https://wcln.ca/local/lti/index.php?type=page';

        $lti_book_type = $DB->get_record_select('lti_types', "baseurl LIKE :url", ['url' => $lti_book_url]);
        $lti_page_type = $DB->get_record_select('lti_types', "baseurl LIKE :url", ['url' => $lti_page_url]);

        $sql    = "
                SELECT c.id
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

        // Find LTI books referncing this cmid
        if ($lti_book_type) {
            $params['typeid'] = $lti_book_type->id;
            if ($result = $DB->get_fieldset_sql($sql, $params)) {
                $courses = array_merge($courses, $result);
            }
        }

        // Find LTI pages referencing this cmid
        if ($lti_page_type) {
            $params['typeid'] = $lti_page_type->id;
            if ($result = $DB->get_fieldset_sql($sql, $params)) {
                $courses = array_merge($courses, $result);
            }
        }

        return $courses;
    }

}
