<?php

namespace local_lor\item;

use cache;
use coding_exception;
use context_system;
use dml_exception;
use Exception;
use local_lor\form\item_form;
use local_lor\item\property\category;
use local_lor\item\property\contributor;
use local_lor\item\property\grade;
use local_lor\item\property\topic;
use local_lor\type\type;
use moodle_exception;
use moodle_url;

class item
{

    const TABLE = 'local_lor_item';

    const SORT_RECENT = 'recent';
    const SORT_ALPHABETICAL = 'alphabetical';

    const PROPERTIES
        = [
            category::class,
            contributor::class,
            grade::class,
            topic::class,
        ];

    /**
     * Get all item details
     *
     * @param  int  $id
     *
     * @return mixed
     * @throws dml_exception
     */
    public static function get(int $id)
    {
        global $DB;

        $item               = $DB->get_record(self::TABLE, ['id' => $id]);
        $item->image        = self::get_image_url($id);
        $item->categories   = category::get_item_data($id);
        $item->contributors = contributor::get_item_data($id);
        $item->grades       = grade::get_item_data($id);
        $item->topics       = topic::get_item_data($id);
        $item->data         = data::get_item_data($id);

        return $item;
    }

    /**
     * Get the moodle form used to create/edit a LOR item
     *
     * @param  string  $type
     * @param  null  $itemid
     *
     * @return item_form
     * @throws dml_exception
     */
    public static function get_form(string $type, $itemid = null)
    {
        $form = new item_form(null, ['type' => $type, 'id' => $itemid]);

        if (empty($itemid)) {
            return $form;
        } else {
            global $DB;
            $item = $DB->get_record(self::TABLE, ['id' => $itemid]);
            $data = data::get_item_data($itemid);
            $form->set_data((object)array_merge((array)$item, (array)$data));

            return $form;
        }
    }

    /**
     * Get the type of an item
     *
     * @param  int  $itemid
     *
     * @return mixed
     * @throws dml_exception
     */
    public static function get_type(int $itemid)
    {
        global $DB;
        $item = $DB->get_record(self::TABLE, ['id' => $itemid]);

        return $item->type;
    }

    /**
     * Create a new LOR item
     *
     * @param $data
     *
     * @param  null  $form  The Moodle form
     *
     * @return int The created item ID
     * @throws dml_exception
     */
    public static function create($data, $form = null)
    {
        global $DB;

        require_capability('local/lor:manage', context_system::instance());

        $item = [
            'name'         => substr($data->name, 0, item_form::NAME_MAX_LENGTH),
            'type'         => $data->type,
            'description'  => substr(is_array($data->description) ? $data->description['text'] : $data->description, 0,
                item_form::DESCRIPTION_MAX_LENGTH),
            'timecreated'  => isset($data->timecreated) ? $data->timecreated : time(),
            'timemodified' => isset($data->timecreated) ? $data->timecreated : time(),
        ];

        // Create the item, and call the type specific create func. as well as property funcs.
        if ($itemid = $DB->insert_record(self::TABLE, (object)$item)) {
            if (isset($data->image)) {
                self::save_image($itemid, $data->image);
            }
            self::save_properties($itemid, $data);

            $type_class = type::get_class($data->type);

            $type_class::create($itemid, $data, $form);
        }

        return $itemid;
    }

    /**
     * Update an existing LOR item
     *
     * @param  int  $itemid
     * @param     $data
     *
     * @param  null  $form  The Moodle form
     *
     * @return bool
     * @throws dml_exception
     */
    public static function update(int $itemid, $data, $form = null)
    {
        global $DB;

        require_capability('local/lor:manage', context_system::instance());

        self::save_image($itemid, $data->image);

        $item = [
            'id'           => $itemid,
            'name'         => substr($data->name, 0, item_form::NAME_MAX_LENGTH),
            'type'         => $data->type,
            'description'  => substr($data->description['text'], 0, item_form::DESCRIPTION_MAX_LENGTH),
            'timemodified' => time(),
        ];

        // Update the item, and call the type specific update func. as well
        if ($DB->update_record(self::TABLE, (object)$item)) {
            self::save_properties($itemid, $data);

            $type_class = type::get_class($data->type);

            return $type_class::update($itemid, $data, $form);
        }

        return false;
    }

    /**
     * Delete an existing LOR item
     *
     * @param  int  $itemid
     *
     * @return bool
     * @throws dml_exception
     */
    public static function delete(int $itemid)
    {
        global $DB;

        require_capability('local/lor:manage', context_system::instance());

        // Make sure we grab the type before the item is deleted
        $type = self::get_type($itemid);

        return $DB->delete_records(self::TABLE, ['id' => $itemid])
               && (type::get_class($type))::delete($itemid)
               && self::delete_properties($itemid);
    }

    /**
     * Search for items by filtering all items
     *
     * @param  int  $page
     * @param  string  $keywords
     * @param  string  $type
     * @param  array  $categories
     * @param  array  $grades
     * @param  string  $sort
     *
     * @param  int  $perpage
     *
     * @return array
     * @throws dml_exception
     * @throws moodle_exception
     */
    public static function search(
        $keywords = '',
        $type = '',
        $categories = [],
        $grades = [],
        $sort = self::SORT_RECENT,
        $page = 0,
        $perpage = 8
    ) {
        global $DB;

        list($sql, $params) = self::build_search_query($keywords, $type, $categories, $grades, $sort, $page, $perpage);

        // Execute the query
        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Count the number of resources matching a search
     *
     * This function utilizes the cache to enhance performance
     *
     * @param  string  $keywords
     * @param  string  $type
     * @param  array  $categories
     * @param  array  $grades
     *
     * @return int
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function count_search_results(
        $keywords = '',
        $type = 'all',
        $categories = [],
        $grades = []
    ) {
        global $DB;

        $cachekey = "$keywords-$type-".implode(',', $categories)."-".implode(',', $grades);
        $cache    = cache::make('local_lor', 'resource_count');
        if ($count = $cache->get($cachekey)) {
            return $count;
        }

        list($sql, $params) = self::build_search_query($keywords, $type, $categories, $grades, null, null, null, true);
        if ($count = $DB->count_records_sql($sql, $params)) {
            $cache->set($cachekey, $count);

            return $count;
        }

        return 0;
    }

    /**
     * Construct a resource search query
     *
     * @param  string  $keywords
     * @param  string  $type
     * @param  array  $categories
     * @param  array  $grades
     * @param  string  $sort
     * @param  int  $page
     * @param  int  $perpage
     * @param  false  $count
     *
     * @return array
     */
    private static function build_search_query(
        $keywords = '',
        $type = '',
        $categories = [],
        $grades = [],
        $sort = self::SORT_RECENT,
        $page = 0,
        $perpage = 8,
        $count = false
    ) {
        global $DB;

        // Define parts of the SQL query
        $select  = "i.*";
        $from    = "{".self::TABLE."} i";
        $joins   = "";
        $where   = "1 = 1";
        $orderby = "timecreated DESC";
        $limit   = $perpage;
        $offset  = $perpage * $page;
        $params  = [];

        // Determine what sorting we are using
        if ($sort === self::SORT_RECENT) {
            $orderby = 'i.timecreated DESC';
        } elseif ($sort === self::SORT_ALPHABETICAL) {
            $orderby = 'i.name ASC';
        }

        // Filter by type
        if ( ! empty($type) && $type !== 'all') {
            $where          .= " AND i.`type` LIKE :type";
            $params['type'] = $type;
        }

        // Filter by categories
        if ( ! empty($categories)) {
            $joins .= " JOIN {".category::LINKING_TABLE."} ic ON ic.itemid = i.id";

            list($categories_sql, $categories_params) = $DB->get_in_or_equal($categories, SQL_PARAMS_NAMED);
            $where  .= " AND ic.categoryid $categories_sql";
            $params += $categories_params;
        }

        // Filter by grades
        if ( ! empty($grades)) {
            $joins .= " JOIN {".grade::LINKING_TABLE."} ig ON ig.itemid = i.id";

            list($grades_sql, $grades_params) = $DB->get_in_or_equal($grades, SQL_PARAMS_NAMED);
            $where  .= " AND ig.gradeid $grades_sql";
            $params += $grades_params;
        }

        // Filter by keywords
        if ( ! empty($keywords)) {
            $joins .= " JOIN {".topic::LINKING_TABLE."} it ON it.itemid = i.id";
            $joins .= " JOIN {".topic::TABLE."} t ON t.id = it.topicid";

            $where .= " AND(
                (INSTR(:keywords_topic, t.name) > 0
                OR i.description LIKE CONCAT('%', :keywords_desc, '%')
	            OR i.name LIKE CONCAT('%', :keywords_name, '%'))
	            AND t.name NOT LIKE ' '
            )";

            $params['keywords_topic'] = $keywords;
            $params['keywords_desc']  = $keywords;
            $params['keywords_name']  = $keywords;
        }

        // Check if we are just counting the results, or performing a full search
        if ($count) {
            $sql = "SELECT COUNT(*) FROM (
                SELECT DISTINCT i.id
                FROM $from
                $joins
                WHERE $where) as countable
        ";
        } else {
            $sql = "
                SELECT DISTINCT $select
                FROM $from
                $joins
                WHERE $where
                ORDER BY $orderby
                LIMIT $limit OFFSET $offset
        ";
        }

        return [$sql, $params];
    }

    private static function filter_by_keywords($items, string $keywords)
    {
        return array_filter(
            $items,
            function ($item) use ($keywords) {
                // Search item name
                if (strpos(strtolower($item->name), strtolower($keywords)) !== false) {
                    return true;
                }

                // Search item description
                if (strpos(strtolower($item->description), strtolower($keywords)) !== false) {
                    return true;
                }

                // Search item topics
                $topics = topic::get_item_data($item->id);
                foreach ($topics as $topic) {
                    if (strpos(strtolower($topic->name), strtolower($keywords)) !== false) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    /**
     * Filter an array of items by type
     *
     * @param  array  $items
     * @param  string  $type
     *
     * @return array
     */
    private static function filter_by_type(array $items, string $type)
    {
        return array_filter(
            $items,
            function ($item) use ($type) {
                return (string)$type === (string)$item->type;
            }
        );
    }

    /**
     * Filter an array of items by category
     *
     * @param  array  $items
     * @param  array  $categories
     *
     * @return array
     */
    private static function filter_by_category(array $items, array $categories)
    {
        return array_filter(
            $items,
            function ($item) use ($categories) {
                $item_categories = category::get_item_data($item->id);
                foreach ($item_categories as $category) {
                    if (in_array($category->id, $categories)) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    /**
     * Filter an array of items by grade
     *
     * @param  array  $items
     * @param  array  $grades
     *
     * @return array
     */
    private static function filter_by_grade(array $items, array $grades)
    {
        return array_filter(
            $items,
            function ($item) use ($grades) {
                $item_grades = grade::get_item_data($item->id);
                foreach ($item_grades as $grade) {
                    if (in_array($grade->id, $grades)) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    /**
     * Save all item properties
     *
     * @param  int  $itemid
     * @param     $data
     */
    private static function save_properties(int $itemid, $data)
    {
        foreach (self::PROPERTIES as $property) {
            $property::save_item_form($itemid, $data);
        }
    }

    /**
     * Delete all item properties
     *
     * @param  int  $itemid
     *
     * @return bool
     */
    private static function delete_properties(int $itemid)
    {
        $result = true;

        foreach (self::PROPERTIES as $property) {
            $result = $result && $property::delete_for_item($itemid);
        }

        return $result;
    }

    /**
     * Get the item image URL
     *
     * This is used for the item's preview image
     *
     * @param $itemid
     *
     * @param  string  $filearea
     *
     * @return bool|string
     * @throws dml_exception
     */
    public static function get_image_url($itemid, $filearea = 'preview_image')
    {
        global $DB;

        if ($item = $DB->get_record(self::TABLE, ['id' => $itemid], 'id,type')) {
            $type_class = type::get_class($item->type);
            if ($image_url = $type_class::get_image_url()) {
                return $image_url;
            }
        } else {
            throw new Exception("Item with ID $itemid does not exist!");
        }

        $file = $DB->get_record_select('files',
            "component = :component AND filearea = :filearea AND itemid = :itemid AND filesize > 0", [
                'component' => 'local_lor',
                'filearea'  => $filearea,
                'itemid'    => $itemid,
            ]);

        if ( ! $file) {
            return false;
        }

        return moodle_url::make_pluginfile_url(
            $file->contextid,
            $file->component,
            $file->filearea,
            $file->itemid,
            $file->filepath,
            $file->filename
        )->out();
    }

    /**
     * Save an image
     *
     * @param $itemid
     * @param $image
     *
     * @throws dml_exception
     */
    private static function save_image($itemid, $image, $filearea = 'preview_image')
    {
        $context = context_system::instance();
        file_save_draft_area_files($image, $context->id, 'local_lor', $filearea, $itemid);
    }

}
