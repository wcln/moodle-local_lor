<?php

namespace local_lor\task;

// Require the local library. Will be used to retrieve categories.
require_once('../../locallib.php');

// Require the config file for DB calls.
require_once(__DIR__ . '/../../../../config.php');

class update_videos extends \core\task\scheduled_task {

  // Define search constants.
  const ORDER = 'date';
  const PART = 'snippet';
  const TYPE = 'video';
  const YOUTUBE_API_URL = 'https://www.googleapis.com/youtube/v3/search';

  /**
    * Return the task's name as shown in admin screens.
    *
    * @return string
    */
  public function get_name() {
    return get_string('updatevideos', 'local_lor');
  }

  /**
   * Execute the task.
   */
  public function execute() {

    // Access the plugin configuration,
    $config = get_config('local_lor');

    // Construct the query URL using constants.
    $query_url = YOUTUBE_API_URL
                . "?part=" . PART
                . "&key=" . $config->google_api_key
                . "&channelId=" . $config->youtube_channel_id
                . "&type=" . TYPE
                . "&maxResults=" . $config->youtube_max_results
                . "&order=" . ORDER;

    // Initialize empty array to store videos in.
    $videos = [];

    // Get cURL resource.
    $curl = curl_init();

    // Set the URL.
    curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $query_url));

    // Send the request & save response to $response after decoding from JSON.
    $response = json_decode(curl_exec($curl));

    // If videos were found (should always occur).
    if (property_exists($response, 'items') && count($response->items) != 0) {

      // Get all categories in database, to be used within loop.
      $categories = local_lor_get_categories();

      // Loop through each video.
      foreach ($response->items as $video) {

        // Extract the video id.
        $video_id = $video->id->videoId;

        // Check if video exists already in LOR.
        // Will return false if no record found.
        $already_added = $DB->get_record_sql('SELECT content, video_id FROM {lor_content_videos} WHERE video_id = ?', array($video_id), IGNORE_MISSING);

        // If video does not exist in LOR, proceed.
        if (!$already_added) {

          // Extract the title, description for ease of use.
          $title = $video->snippet->title;
          $description = $video->snippet->description;

          // The category of this video, initialized to null.
          $category_to_add = null;

          // Check if video is in a playlist.
          $query = "https://www.googleapis.com/youtube/v3/search?type=playlist&part=snippet&maxResults=1&key=".API_KEY."&channelId=UCcQwU9RgnPlF8FNqX4YoXlg&q=" . rawurlencode($title);
          curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $query));

          // Send the request & save response to $response.
          $response = json_decode(curl_exec($curl));

          // Check if it was found in a playlist.
          // We will only add videos which are in playlists.
          if (property_exists($response, 'items') && count($response->items) !== 0) {

            // Retrive the title of the playlist that this video is in.
            $playlist_title = $response->items[0]->snippet->title;

            // For each of the possible categories (in the database).
            foreach ($categories as $category) {

              // If the playlist title matches the category name.
              if (preg_match("/(?i)$category->name/", $playlist_title)) {

                // Retrieve the category ID.
                $category_to_add = $category->id;

                // Ensure we only set one category.
                break;
              }
            }

            // If we found a category for this video, add it to LOR. Otherwise do nothing.
            if (!is_null($category_to_add)) {

              // Get video keywords.
              $keywords = [];
              $connect = file_get_contents("https://www.youtube.com/watch?v=$video_id");
              preg_match_all('|<meta property="og\:video\:tag" content="(.+?)">|si', $connect, $tags, PREG_SET_ORDER);

              // For each video tag we found.
              foreach ($tags as $tag) {

                // Filter out redundant keywords.
                if (!preg_match("/(?i)WCLN|BCLN|math|unit.*|[0-9]+|western|canadian|learning|network|sawatzky/", $tag[1])) {

                    // Ensure the tag is not already in the keywords array.
                    if (!in_array($tag[1], $keywords)) {

                      // Append the clean tag.
                      $keywords[] = $tag[1];
                    }

                  // Limit to 5 keywords per video, just in case.
                  if (count($keywords) >= 5) {
                    break;
                  }
                }
              }

              // Create empty record to be inserted into lor_content.
              $record = new stdClass();

              // Video type.
              $record->type = 3;

              // Clean the title. Remove redundant sub-strings.
              $record->title = preg_replace('/^(?i)[B,W]CLN\s*-*\s*|OSBC\s*-*\s*|Math\s*-*\s*|Chemistry\s*-*\s*|Physics\s*-*\s*|English\s*-*\s*/', '', $title);

              // Store the medium sized thumbnail image.
              $record->image = $video->snippet->thumbnails->medium->url;

              // Convert to MySQL date format.
              $record->date_created = date("Y-m-d H:i:s", strtotime($video->snippet->publishedAt));

              // Insert the record, and retrieve the generated id.
              $id = $DB->insert_record('lor_content', $record);

              // Add the item category to the database.
              $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($id, (int)$category_to_add));

              // Insert into lor_keyword table and lor_content_keywords table.
              foreach ($keywords as $word) {

                // Check if keyword exists already, if not then insert.
                $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
                if($existing_record) {
                  $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
                } else {
                  $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
                  $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
                }
              }

              // Insert into lor_content_videos table.
              $DB->execute('INSERT INTO {lor_content_videos}(content, video_id) VALUES (?, ?)', array($id, $video_id));

            }
          }
        }
      }
    }

    // Close request to clear up some resources
    curl_close($curl);
  }
}
