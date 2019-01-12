<?php
namespace local_lor\insert;

class insert_functions {

  /*
   * Insert a Game/Media.
   */
  public static function insert_1($data, &$form) {
    global $DB;
    global $CFG;

    date_default_timezone_set('America/Los_Angeles'); // PST

    // Insert into lor_content table.
    $record = new \stdClass();
    $record->type = 1;
    $record->title = $data->title;
    $record->image = ""; // Will be replaced below.
    $record->link = $data->link;
    $record->date_created = date("Ymd");
    $record->width = ($data->width == 0)? null : $data->width;
    $record->height = ($data->height == 0)? null : $data->height;
    $id = $DB->insert_record('lor_content', $record);

    // Save preview image to server.
    $form->save_file('image', "$CFG->dirroot/LOR/games/preview_images/$id.png", true);

    // Update image link in content table.
    $record->image = "$CFG->wwwroot/LOR/games/preview_images/$id.png";
    $record->id = $id;
    $DB->update_record('lor_content', $record);

    // Insert into categories table.
    $categories = array_filter($data->categories);
    foreach ($categories as $category) {
      $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($id, (int)$category));
    }

    // Insert into grades table.
    $grades = array_filter($data->grades);
    foreach ($grades as $grade) {
      $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
    }

    // insert into lor_keyword table and lor_content_keywords table
    $keywords = explode(',', $data->topics);
    foreach ($keywords as $word) {

      // check if keyword exists already, if not then insert
      $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
      if($existing_record) {
        $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
      } else {
        $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
        $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
      }
    }

    // insert into lor_contributor and lor_content_contributors
    $contributors = explode(',', $data->contributors);
    foreach ($contributors as $contributor) {

      // check if contributor exists already, if not then insert
      $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
      if($existing_record) {
        $cid = $existing_record->id;
      } else {
        $cid = $DB->insert_record_raw('lor_contributor', array('id' => null, 'name' => $contributor), true, false, false);
      }

      $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
    }

    return $id;
  }

  /*
   * Insert an Inquiry Project.
   */
  public static function insert_2($data, &$form) {
    global $DB;
    global $CFG;

    date_default_timezone_set('America/Los_Angeles'); // PST

    $form->save_file('word', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('word'));
    $form->save_file('pdf', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('pdf'));
    $form->save_file('icon', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('icon'));

    // insert into content table
    $record = new \stdClass();
    $record->type = 2;
    $record->title = $data->title;
    $record->image = $CFG->wwwroot . '/LOR/projects/' . $form->get_new_filename('icon');
    $record->link = $CFG->wwwroot . '/LOR/projects/' . $form->get_new_filename('pdf');
    $record->date_created = date("Ymd");
    $id = $DB->insert_record('lor_content', $record);

    // insert into categories table
    $categories = array_filter($data->categories);
    foreach ($categories as $category) {
      $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($id, (int)$category));
    }

    // insert into grades table
    $grades = array_filter($data->grades);
    foreach ($grades as $grade) {
      $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
    }

    // insert into lor_keyword table and lor_content_keywords table
    $keywords = explode(',', $data->topics);
    foreach ($keywords as $word) {

      // check if keyword exists already, if not then insert
      $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
      if($existing_record) {
        $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
      } else {
        $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
        $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
      }

    }

    // insert into lor_contributor and lor_content_contributors
    $contributors = explode(',', $data->contributors);
    foreach ($contributors as $contributor) {

      // check if contributor exists already, if not then insert
      $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
      if($existing_record) {
        $cid = $existing_record->id;
      } else {
        $cid = $DB->insert_record_raw('lor_contributor', array('id' => null, 'name' => $contributor), true, false, false);
      }


      $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
    }

    return $id;
  }

  /*
   * Insert a Video.
   */
  public static function insert_3($data, &$form) {
    global $DB;
    global $CFG;

    date_default_timezone_set('America/Los_Angeles'); // PST

    $video_id = $data->video_id;

    // Get the plugin configuration. Used to retrieve API key.
    $config = get_config('local_lor');

    // Get cURL resource.
    $curl = curl_init();

    // Set the URL.
    $query_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=$video_id&key=" . $config->google_api_key;
    curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $query_url));

    // Send the request & save response to $response after decoding from JSON.
    $response = json_decode(curl_exec($curl));

    // Close request to clear up some resources
    curl_close($curl);

    // Check if a video with that ID was found.
    if (property_exists($response, 'items') && count($response->items) == 1) {

      // Retrieve title, date and image.
      $title = preg_replace('/^(?i)[B,W]CLN\s*-*\s*|OSBC\s*-*\s*|Math\s*-*\s*|Chemistry\s*-*\s*|Physics\s*-*\s*|English\s*-*\s*/', '', $response->items[0]->snippet->title);
      $date = date("Y-m-d H:i:s", strtotime($response->items[0]->snippet->publishedAt));
      $image = $response->items[0]->snippet->thumbnails->medium->url;

      // Insert into lor_content table.
      $record = new \stdClass();
      $record->type = 3; // Video.
      $record->title = $title;
      $record->image = $image;
      $record->date_created = $date;
      $id = $DB->insert_record('lor_content', $record);

      // Insert into lor_content_videos table.
      $DB->execute('INSERT INTO {lor_content_videos}(content, video_id) VALUES (?, ?)', array($id, $data->video_id));

      // Insert into categories table.
      $categories = array_filter($data->categories);
      foreach ($categories as $category) {
        $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($id, (int)$category));
      }

      // Insert into grades table.
      $grades = array_filter($data->grades);
      foreach ($grades as $grade) {
        $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
      }

      // Insert into lor_keyword table and lor_content_keywords table.
      $keywords = explode(',', $data->topics);
      foreach ($keywords as $word) {

        // check if keyword exists already, if not then insert
        $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
        if($existing_record) {
          $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
        } else {
          $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
          $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
        }
      }

      // insert into lor_contributor and lor_content_contributors
      $contributors = explode(',', $data->contributors);
      foreach ($contributors as $contributor) {

        // check if contributor exists already, if not then insert
        $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
        if($existing_record) {
          $cid = $existing_record->id;
        } else {
          $cid = $DB->insert_record_raw('lor_contributor', array('id' => null, 'name' => $contributor), true, false, false);
        }


        $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
      }

      return $id;
    } else {
      throw new \Exception(get_string('error_no_video', 'local_lor'));
    }
  }


  /*
   * Insert a Lesson.
   */
  public static function insert_5($data, &$form) {
    global $DB;
    global $CFG;

    date_default_timezone_set('America/Los_Angeles'); // PST

    // Insert into lor_content table.
    $record = new \stdClass();
    $record->type = 5;
    $record->title = $data->title;
    $record->image = ""; // Will be replaced below.
    $record->link = null;
    $record->date_created = date("Ymd");
    $record->width = null;
    $record->height = null;
    $id = $DB->insert_record('lor_content', $record);

    // Insert into lor_content_lessons table.
    $DB->execute('INSERT INTO {lor_content_lessons}(content, book_id) VALUES (?, ?)', array($id, $data->book_id));

    // Save preview image to server.
    $form->save_file('image', "$CFG->dirroot/LOR/games/preview_images/$id.png", true);

    // Update image link in content table.
    $record->image = "$CFG->wwwroot/LOR/games/preview_images/$id.png";
    $record->id = $id;
    $DB->update_record('lor_content', $record);

    // Insert into categories table.
    $categories = array_filter($data->categories);
    foreach ($categories as $category) {
      $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($id, (int)$category));
    }

    // Insert into grades table.
    $grades = array_filter($data->grades);
    foreach ($grades as $grade) {
      $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
    }

    // Insert into lor_keyword table and lor_content_keywords table.
    $keywords = explode(',', $data->topics);
    foreach ($keywords as $word) {

      // check if keyword exists already, if not then insert
      $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
      if($existing_record) {
        $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
      } else {
        $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
        $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($id, $word));
      }
    }

    // insert into lor_contributor and lor_content_contributors
    $contributors = explode(',', $data->contributors);
    foreach ($contributors as $contributor) {

      // check if contributor exists already, if not then insert
      $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
      if($existing_record) {
        $cid = $existing_record->id;
      } else {
        $cid = $DB->insert_record_raw('lor_contributor', array('id' => null, 'name' => $contributor), true, false, false);
      }


      $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
    }

    return $id;
  }
}
