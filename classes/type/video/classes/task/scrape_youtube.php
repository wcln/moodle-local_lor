<?php

namespace lortype_video\task;

use coding_exception;
use context_system;
use core\task\scheduled_task;
use lang_string;
use local_lor\item\data;
use local_lor\item\item;
use local_lor\item\property\category;

defined('MOODLE_INTERNAL') || die();

class scrape_youtube extends scheduled_task
{

    // Define search constants
    const ORDER = 'date';
    const PART = 'snippet';
    const TYPE = 'video';
    const YOUTUBE_API_URL = 'https://www.googleapis.com/youtube/v3/';

    /**
     * Get the name of this task
     *
     * @return lang_string|string
     * @throws coding_exception
     */
    public function get_name()
    {
        return get_string('scrape_youtube', 'lortype_video');
    }

    /**
     * Execute the task
     */
    public function execute()
    {
        global $DB;

        // Access the plugin configuration,
        $config = get_config('lortype_video');

        // Construct the query URL using constants.
        $query_url = self::YOUTUBE_API_URL
                     ."search"
                     ."?part=".self::PART
                     ."&key=".$config->google_api_key
                     ."&channelId=".$config->youtube_channel_id
                     ."&type=".self::TYPE
                     ."&maxResults=".$config->youtube_max_results
                     ."&order=".self::ORDER;

        // Get cURL resource.
        $curl = curl_init();

        mtrace('cURL initialized.');
        mtrace("Querying URL: $query_url");

        // Set the URL.
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $query_url));

        // Send the request & save response to $response after decoding from JSON.
        $response = json_decode(curl_exec($curl));

        mtrace('Retrieved response.');

        // If videos were found (should always occur).
        if (property_exists($response, 'items') && count($response->items) != 0) {
            // Get all categories in database, to be used within loop.
            $categories = category::get_all_menu();

            mtrace('Categories retrieved from database.');

            // Loop through each video.
            foreach ($response->items as $video) {
                // Extract the video id.
                $video_id = $video->id->videoId;

                // If video does not exist in LOR, proceed.
                if ( ! $DB->record_exists_select(data::TABLE, "name LIKE 'videoid' AND value LIKE :videoid",
                    ['videoid' => $video_id])
                ) {
                    // Extract the title for ease of use.
                    $title = $video->snippet->title;

                    mtrace("Found a new video: '$title'.");

                    // The category of this video, initialized to null.
                    $category_to_add = null;

                    // Check if video is in a playlist.
                    $query = self::YOUTUBE_API_URL
                             ."playlists"
                             ."?part=".self::PART
                             ."&maxResults=1"
                             ."&key=".$config->google_api_key
                             ."&channelId=".$config->youtube_channel_id
                             ."&q=".rawurlencode($title);
                    curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $query));

                    // Send the request & save response to $response.
                    $response = json_decode(curl_exec($curl));

                    // Check if it was found in a playlist.
                    // We will only add videos which are in playlists.
                    if (property_exists($response, 'items') && count($response->items) !== 0) {
                        // Retrive the title of the playlist that this video is in.
                        $playlist_title = $response->items[0]->snippet->title;

                        mtrace("Video in playlist: '$playlist_title'");

                        // For each of the possible categories (in the database).
                        foreach ($categories as $id => $name) {
                            // If the playlist title matches the category name.
                            if (preg_match("/(?i)$name/", $playlist_title)) {
                                // Retrieve the category ID.
                                $category_to_add = $id;

                                mtrace("Found a category for the video: '$name' with ID: '$id'");

                                // Ensure we only set one category.
                                break;
                            }
                        }

                        // If we found a category for this video, add it to LOR. Otherwise do nothing.
                        if ( ! is_null($category_to_add)) {
                            // Get video topics.
                            $topics  = [];
                            $connect = file_get_contents("https://www.youtube.com/watch?v=$video_id");
                            preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags,
                                PREG_SET_ORDER);

                            // For each video tag we found.
                            foreach ($tags as $tag) {
                                // Filter out redundant topics.
                                if ( ! preg_match("/(?i)WCLN|BCLN|math|unit.*|[0-9]+|western|canadian|learning|network|sawatzky/",
                                    $tag[1])
                                ) {
                                    // Ensure the tag is not already in the topics array.
                                    if ( ! in_array($tag[1], $topics)) {
                                        // Append the clean tag.
                                        $topics[] = $tag[1];
                                    }

                                    // Limit to 5 topics per video, just in case.
                                    if (count($topics) >= 5) {
                                        break;
                                    }
                                }
                            }

                            mtrace("Found ".count($topics)." topics.");

                            $itemid = item::create((object)[
                                'name'         => preg_replace('/^(?i)[B,W]CLN\s*-*\s*|OSBC\s*-*\s*|Math\s*-*\s*|Chemistry\s*-*\s*|Physics\s*-*\s*|English\s*-*\s*/',
                                    '', $title),
                                'type'         => 'video',
                                'description'  => '',
                                'videoid'      => $video_id,
                                'categories'   => [$category_to_add],
                                'topics'       => implode(',', $topics),
                                'grades'       => [],
                                'contributors' => [],
                                'timecreated'  => strtotime($video->snippet->publishedAt),
                            ]);

                            mtrace("Video added to local_lor_item table.");

                            $fs       = get_file_storage();
                            $fileinfo = [
                                'contextid' => context_system::instance()->id,
                                'component' => 'local_lor',
                                'filearea'  => 'preview_image',
                                'itemid'    => $itemid,
                                'filepath'  => '/',
                                'filename'  => "$video_id.jpg",
                            ];
                            $fs->create_file_from_url($fileinfo, $video->snippet->thumbnails->high->url);

                            mtrace("YouTube preview image saved to the database.");
                        } else {
                            mtrace("No category found. Skipping.");
                        }
                    } else {
                        mtrace("Video is not in a category playlist. Skipping.");
                    }
                }
            }
        }

        // Close request to clear up some resources
        curl_close($curl);

        mtrace("cURL closed. Update complete.");
    }
}
