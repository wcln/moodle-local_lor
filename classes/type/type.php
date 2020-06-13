<?php

namespace local_lor\type;

use core_component;
use local_lor\form\item_form;
use local_lor\item\data;
use stdClass;

trait type
{

    /**
     * Get the name of this resource type
     *
     * Example: 'Media', 'YouTube video', etc...
     *
     * @return string
     */
    public abstract static function get_name();

    /**
     * Get the HTML code which one can copy & paste to embed this resource
     * into a course or webpage
     *
     * @param $itemid
     *
     * @return string HTML string built using a renderer or html_writer
     */
    public abstract static function get_embed_html($itemid);

    /**
     * Get the HTML code used to display this resource when it is
     * viewed within the Learning resources search/view pages
     *
     * @param $itemid
     *
     * @return string HTML string built using a renderer or html_writer
     */
    public abstract static function get_display_html($itemid);

    /**
     * Add elements to the item form
     *
     * These elements are added at the bottom of the form when
     * a LOR item is created or edited
     *
     * @param $item_form item_form A reference to the item moodle form
     */
    public abstract static function add_to_form(&$item_form);

    /**
     * Called after an item of this type is created
     *
     * Store any data in local_lor_item_data that is specific to this type
     *
     * @param $itemid int The ID of the LOR item that was just created
     * @param $data   stdClass Raw form data from the submitted item form and any custom type elements
     *
     * @param  null  $form A copy of the Moodle form in case we need to deal with files
     *
     * @return bool True on success, false on failure
     */
    public abstract static function create($itemid, $data, $form = null);

    /**
     * Called after an item of this type is updated
     *
     * Update any data which is stored in local_lor_item_data that is specific to this type and item
     *
     * @param $itemid int The ID of the LOR item that was just created
     * @param $data   stdClass Raw form data from the submitted item form containing any custom type elements added by this type
     *
     * @param  null  $form A copy of the Moodle form in case we need to deal with files
     *
     * @return bool True on success, false on failure
     */
    public abstract static function update($itemid, $data, $form = null);

    /**
     * Called after a LOR item of this type is deleted
     *
     * This function should delete all type specific records from local_lor_item_data that are associated with the deleted item
     *
     * You can override this if you want to perform some additional action when your type is deleted
     *
     * @param $itemid int The ID of the LOR item that was just deleted
     *
     * @return bool True on success, false on failure
     * @throws \dml_exception
     */
    public static function delete($itemid)
    {
        global $DB;

        return $DB->delete_records(data::TABLE, ['itemid' => $itemid]);
    }


    /**
     * Get an assoc. array of all installed item types
     *
     * @return array assoc. array 'type string identififer' => 'type name'
     */
    public static function get_all_types()
    {
        $types = [];

        foreach (core_component::get_plugin_list('lortype') as $name => $path) {
            $type_class   = self::get_class($name);
            $types[$name] = $type_class::get_name();
        }

        return $types;
    }

    /**
     * Get the path to the item type class
     *
     * @param string $type
     *
     * @return string
     */
    public static function get_class(string $type)
    {
        return "local_lor\\type\\$type\\$type";
    }

}
