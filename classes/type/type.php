<?php

namespace local_lor\type;

trait type
{

    public abstract static function get_name();

    public abstract static function get_embed_html($itemid);

    public abstract static function get_display_html($itemid);

    public abstract static function add_to_form(&$item_form);

    /**
     * Get an assoc. array of all installed item types
     *
     * @return array assoc. array 'type string identififer' => 'type name'
     */
    public static function get_all_types()
    {
        $types = [];

        foreach ( \core_component::get_plugin_list('lortype') as $name => $path) {
            $type_class = self::get_class($name);
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
