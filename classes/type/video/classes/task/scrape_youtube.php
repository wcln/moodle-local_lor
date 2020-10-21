<?php

namespace lortype_video\task;

use coding_exception;
use context_system;
use core\task\scheduled_task;
use dml_exception;
use file_exception;
use lang_string;
use local_lor\item\data;
use local_lor\item\item;
use local_lor\item\property\category;

defined('MOODLE_INTERNAL') || die();

class scrape_youtube extends scheduled_task
{
    const YOUTUBE_API_URL = 'https://www.googleapis.com/youtube/v3/';
    const MAX_RESULTS = 1000; // We will set this to 1000, even though YouTube limits the perpage results to 50

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
     *
     * We will query for a list of playlists in a specified channel
     * Then we will query for a list of videos in each playlist and add those to LOR categories
     * depending on the playlist name.
     *
     */
    public function execute()
    {
        // Access the plugin configuration,
        $config = get_config('lortype_video');

        // Construct the query URL using constants.
        // First we will query for playlists
        $playlist_query = self::YOUTUBE_API_URL
                          ."playlists"
                          ."?part=snippet,contentDetails"
                          ."&key=".$config->google_api_key
                          ."&channelId=".$config->youtube_channel_id
                          ."&maxResults=".self::MAX_RESULTS;
        $curl           = curl_init();

        mtrace('cURL initialized.');
        mtrace("The channel ID is: $config->youtube_channel_id");
        mtrace("Using API key: $config->google_api_key");
        mtrace("Querying URL for playlists: $playlist_query");

        // Set the URL.
        curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $playlist_query]);

        // Send the request & save response to $response after decoding from JSON.
        $playlists = json_decode(curl_exec($curl));

        mtrace('Retrieved response.');

        $num_videos_added     = 0;
        $num_videos_processed = 0;

        if (isset($playlists->items)) {
            mtrace("Found ".count($playlists->items)." playlists to scrape.");

            foreach ($playlists->items as $playlist) {
                if ( ! $categoryid = self::find_category($playlist->snippet->title)) {
                    mtrace("Could not find category for playlist: ".$playlist->snippet->title);
                    continue;
                }

                // Query for videos within this playlist
                mtrace("Querying for videos in playlist: ".$playlist->snippet->title);

                $videos = self::query_videos($playlist->id);
                if ( ! empty($videos)) {
                    mtrace("Found ".count($videos)." videos in playlist ".$playlist->snippet->title);

                    // Process videos in this playlist
                    foreach ($videos as $video) {
                        if (self::process_video($video, $categoryid)) {
                            $num_videos_added++;
                        }
                        $num_videos_processed++;
                    }
                } else {
                    mtrace("No videos found in playlist");
                }
            }
        } else {
            mtrace("No playlists found.");
        }

        mtrace("Done! Processed $num_videos_processed videos and added $num_videos_added videos to the database.");
        curl_close($curl);
    }

    /**
     * Determine which category to place the new LOR video in
     *
     * This is determined by matching the playlist title to the category name
     *
     * @param $playlist_title
     *
     * @return false|int
     * @throws dml_exception
     */
    private static function find_category($playlist_title)
    {
        $categories = category::get_all_menu();
        foreach ($categories as $id => $name) {
            // If the playlist title matches the category name.
            if (preg_match("/(?i)$name/", $playlist_title)) {
                mtrace("Found category: $name");

                return (int)$id;
            }
        }

        return false;
    }

    /**
     * Process the video record and add it to the LOR database
     *
     * @param $video
     * @param  int  $categoryid
     *
     * @return bool
     * @throws file_exception
     * @throws dml_exception
     */
    private static function process_video($video, int $categoryid)
    {
        global $DB;

        $video_id    = $video->snippet->resourceId->videoId;
        $title       = $video->snippet->title;
        $description = $video->snippet->description;

        if ($title === "Deleted video") {
            mtrace("Video is deleted, skipping.");

            return false;
        }

        mtrace("Found a new video: '$title'");

        // If video does not exist in LOR, proceed.
        if ( ! $DB->record_exists_select(data::TABLE, "name LIKE 'videoid' AND value LIKE :videoid",
            ['videoid' => $video_id])
        ) {
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
                'name'         => $title,
                'type'         => 'video',
                'description'  => $description,
                'videoid'      => $video_id,
                'categories'   => [$categoryid],
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
            mtrace("Done processing video, inserted new record with ID: $itemid");
        } else {
            mtrace("Video already exists in database... Skipping...");

            return false;
        }

        return true;
    }

    /**
     * Query for videos within a playlist
     *
     * This function uses pagination since the YouTube API limits page results to 50 items
     *
     * @param $playlist_id
     *
     * @return array
     * @throws dml_exception
     */
    private static function query_videos($playlist_id)
    {
        $config = get_config('lortype_video');
        $curl   = curl_init();

        $result     = null;
        $videos     = [];
        $base_query = self::YOUTUBE_API_URL
                      ."playlistItems"
                      ."?part=snippet"
                      ."&key=".$config->google_api_key
                      ."&channelId=".$config->youtube_channel_id
                      ."&maxResults=".self::MAX_RESULTS
                      ."&order=date"
                      ."&playlistId=".$playlist_id;

        $page = 0;
        while (isset($result->nextPageToken) || $page === 0) {
            if ($page !== 0) {
                $vidoes_query = "$base_query&pageToken=$result->nextPageToken";
            } else {
                $vidoes_query = $base_query;
            }

            curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $vidoes_query]);
            mtrace("Querying for videos (page $page of results): $vidoes_query");
            $result = json_decode(curl_exec($curl));
            if (isset($result->items)) {
                $videos = array_merge($videos, $result->items);
            } else {
                break;
            }

            $page++;
        }

        curl_close($curl);

        return $videos;
    }
}
