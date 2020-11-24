<?php

namespace local_lor\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

class resource_deleted extends base
{
    protected function init()
    {
        $this->data['crud']        = 'd';
        $this->data['edulevel']    = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_lor_item';
    }

    public static function get_name()
    {
        return get_string('event:resource_deleted', 'local_lor');
    }

    public function get_description()
    {
        $itemid = $this->data['objectid'];
        $userid = $this->data['relateduserid'];

        return get_string('event:resource_deleted_desc', 'local_lor', [
            'itemid' => $itemid,
            'userid' => $userid,
        ]);
    }

    public function get_url()
    {
        return null;
    }
}
