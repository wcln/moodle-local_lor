<?php

namespace local_lor\task;

class cache_related extends \core\task\scheduled_task {

    public function get_name()
    {
        return get_string('task:cache_related', 'local_lor');
    }

    public function execute()
    {
        global $DB;

        // Ensure we get output from the get_related_items function below
        define('DEBUG', true);

        // We can use any item to build the cache, as all items in the DB will be processed
        $item = $DB->get_record('local_lor_item', [], 'id', IGNORE_MULTIPLE);
        \local_lor\related\related_helper::get_related_items($item->id);
    }
}
