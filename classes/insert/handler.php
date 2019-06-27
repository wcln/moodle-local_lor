<?php

namespace local_lor\insert;

class handler {

    public static function get_all_types() {
        global $DB;

        return $DB->get_records('{lor_type}');
    }

    public static function set_current_type($type) {
        global $SESSION;
        $SESSION->current_type = $type;
    }

    public static function clear_current_type() {
        global $SESSION;
        unset($SESSION->current_type);
    }

    public static function get_current_type() {
        global $SESSION;

        return $SESSION->current_type;
    }

    public static function get_current_form($custom_data = null) {
        $class = "\\local_lor\\insert\\form\\form_" . handler::get_current_type();
        if (class_exists($class)) {
            return new $class(null, $custom_data);
        } else {
            return false;
        }
    }

    public static function get_navbar_string() {
        global $DB;
        $current_type = $DB->get_record('lor_type', array('id' => handler::get_current_type()));
        if ($current_type !== false) {
            return get_string('nav_insert', 'local_lor') . $current_type->name;
        } else {
            // If no type name is found, use the default type string.
            return get_string('nav_insert', 'local_lor') . get_string('nav_default_type', 'local_lor');
        }
    }

    public static function insert_item($data, &$form) {
        return call_user_func_array('\\' . __NAMESPACE__ . '\\insert_functions::insert_' . handler::get_current_type(), array(
            $data,
            &$form,
        ));
    }

}

?>
