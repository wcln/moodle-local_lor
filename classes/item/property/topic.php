<?php

namespace local_lor\item\property;

use dml_exception;
use stdClass;

class topic implements noneditable_property
{

    const TABLE = 'local_lor_topic';
    const LINKING_TABLE = 'local_lor_item_topics';

    /**
     * Get topics for an item
     *
     * @param int $itemid
     *
     * @return array of objects containing properties: topic ID and topic name
     * @throws dml_exception
     */
    public static function get_item_data(int $itemid)
    {
        global $DB;

        return $DB->get_records_sql(
            sprintf(
                "
            SELECT t.id, t.name
            FROM {%s} t
            JOIN {%s} it ON it.topicid = t.id
            WHERE it.itemid = :itemid
        ",
                self::TABLE,
                self::LINKING_TABLE
            ),
            ['itemid' => $itemid]
        );
    }

    public static function save_item_form(int $itemid, stdClass $data)
    {
        global $DB;

        self::delete_for_item($itemid);

        $topics = explode(',', $data->topics);
        foreach ($topics as $topic) {
            if ($existing_topic = self::get_topic($topic)) {
                $DB->insert_record(
                    self::LINKING_TABLE,
                    (object)[
                        'itemid'  => $itemid,
                        'topicid' => $existing_topic->id,
                    ],
                    false
                );
            } else {
                if ($topicid = $DB->insert_record(
                    self::TABLE,
                    (object)['name' => $topic]
                )
                ) {
                    $DB->insert_record(
                        self::LINKING_TABLE,
                        (object)[
                            'itemid'  => $itemid,
                            'topicid' => $topicid,
                        ]
                    );
                }
            }
        }
    }

    public static function delete_for_item(int $itemid)
    {
        global $DB;

        return $DB->delete_records(self::LINKING_TABLE, ['itemid' => $itemid]);
    }

    private static function get_topic(string $topic)
    {
        global $DB;

        return $DB->get_record_select(
            self::TABLE,
            'name LIKE :topic',
            ['topic' => $topic]
        );
    }
}
