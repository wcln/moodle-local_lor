<?php

namespace local_lor\item;

use context_system;
use dml_exception;
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
     * @return bool True on success, false on failure
     * @throws dml_exception
     */
    public static function create($data, $form = null)
    {
        global $DB;

        $item = [
            'name'         => $data->name,
            'type'         => $data->type,
            'description'  => $data->description['text'],
            'timecreated'  => time(),
            'timemodified' => time(),
        ];

        // Create the item, and call the type specific create func. as well as property funcs.
        if ($itemid = $DB->insert_record(self::TABLE, (object)$item)) {
            self::save_image($itemid, $data->image);
            self::save_properties($itemid, $data);

            $type_class = type::get_class($data->type);

            return $type_class::create($itemid, $data, $form);
        }

        return false;
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

        self::save_image($itemid, $data->image);

        $item = [
            'id'           => $itemid,
            'name'         => $data->name,
            'type'         => $data->type,
            'description'  => $data->description['text'],
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

        // Make sure we grab the type before the item is deleted
        $type = self::get_type($itemid);

        return $DB->delete_records(self::TABLE, ['id' => $itemid])
               && (type::get_class($type))::delete($itemid)
               && self::delete_properties($itemid);
    }

    /**
     * Search for items by filtering all items
     *
     * @param  string  $keywords
     * @param  string  $type
     * @param  array  $categories
     * @param  array  $grades
     * @param  string  $sort
     *
     * @return array
     * @throws moodle_exception
     * @throws dml_exception
     */
    public static function search(
        $keywords = '',
        $type = '',
        $categories = [],
        $grades = [],
        $sort = self::SORT_RECENT
    ) {
        global $DB;

        // Determine what sorting we are using
        $orderby = null;
        if ($sort === self::SORT_RECENT) {
            $orderby = 'timecreated ASC';
        } else {
            if ($sort === self::SORT_ALPHABETICAL) {
                $orderby = 'name ASC';
            } else {
                print_error('error_unknown_sort', 'local_lor');
            }
        }

        // Get the pre-sorted items
        $items = $DB->get_records(self::TABLE, null, $orderby);

        if ( ! empty($type) && $type !== 'all') {
            $items = self::filter_by_type($items, $type);
        }

        if ( ! empty($categories)) {
            $items = self::filter_by_category($items, $categories);
        }

        if ( ! empty($grades)) {
            $items = self::filter_by_grade($items, $grades);
        }

        if ( ! empty($keywords)) {
            $items = self::filter_by_keywords($items, $keywords);
        }

        return $items;
    }

    private static function filter_by_keywords($items, string $keywords) {
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
                    if (in_array($category, $categories)) {
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
                    if (in_array($grade, $grades)) {
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
