<?php

namespace local_lor\insert;

use Exception;
use stdClass;

class insert_functions {

    /*
     * Insert a Game/Media.
     */
    public static function insert_1($data, &$form) {
        global $DB;
        global $CFG;

        date_default_timezone_set('America/Los_Angeles'); // PST

        // Insert into lor_content table.
        $record               = new stdClass();
        $record->type         = 1;
        $record->title        = $data->title;
        $record->image        = ""; // Will be replaced below.
        $record->link         = $data->link;
        $record->date_created = date("Ymd");
        $record->width        = ($data->width == 0) ? null : $data->width;
        $record->height       = ($data->height == 0) ? null : $data->height;
        $id                   = $DB->insert_record('lor_content', $record);

        // Save preview image to server.
        $form->save_file('image', "$CFG->dirroot/_LOR/games/preview_images/$id.png", true);

        // Update image link in content table.
        $record->image = "$CFG->wwwroot/_LOR/games/preview_images/$id.png";
        $record->id    = $id;
        $DB->update_record('lor_content', $record);

        // Insert into categories table.
        $categories = array_filter($data->categories);
        foreach ($categories as $category) {
            $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array(
                $id,
                (int)$category,
            ));
        }

        // Insert into grades table.
        $grades = array_filter($data->grades);
        foreach ($grades as $grade) {
            $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
        }

        // insert into lor_topic table and lor_content_topics table
        $topics = preg_split('/,\s*/', $data->topics);
        foreach ($topics as $word) {

            // check if topic exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
            if ($existing_record) {
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            } else {
                $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            }
        }

        // insert into lor_contributor and lor_content_contributors
        $contributors = preg_split('/,\s*/', $data->contributors);
        foreach ($contributors as $contributor) {

            // Remove white space from beginning and end of name.
            $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

            // If there is more than one space between first and last name, replace with one space.
            $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

            // check if contributor exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
            if ($existing_record) {
                $cid = $existing_record->id;
            } else {
                $cid = $DB->insert_record_raw('lor_contributor', array(
                    'id'   => null,
                    'name' => $contributor,
                ), true, false, false);
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

        // Insert into content table.
        $record               = new stdClass();
        $record->type         = 2;
        $record->title        = $data->title;
        $record->image        = "";
        $record->link         = "";
        $record->date_created = date("Ymd");
        $id                   = $DB->insert_record('lor_content', $record);
        $record->id           = $id;
        $record->image        = "$CFG->wwwroot/_LOR/projects/WCLN_Project_$id.png";
        $record->link         = "$CFG->wwwroot/_LOR/projects/WCLN_Project_$id.pdf";
        $DB->update_record('lor_content', $record);

        // Save files.
        $form->save_file('word', "$CFG->dirroot/_LOR/projects/WCLN_Project_$id.docx");
        $form->save_file('pdf', "$CFG->dirroot/_LOR/projects/WCLN_Project_$id.pdf");
        $form->save_file('icon', "$CFG->dirroot/_LOR/projects/WCLN_Project_$id.png");

        // insert into categories table
        $categories = array_filter($data->categories);
        foreach ($categories as $category) {
            $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array(
                $id,
                (int)$category,
            ));
        }

        // insert into grades table
        $grades = array_filter($data->grades);
        foreach ($grades as $grade) {
            $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
        }

        // insert into lor_topic table and lor_content_topics table
        $topics = preg_split('/,\s*/', $data->topics);
        foreach ($topics as $word) {

            // check if topic exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
            if ($existing_record) {
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            } else {
                $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            }

        }

        // insert into lor_contributor and lor_content_contributors
        $contributors = preg_split('/,\s*/', $data->contributors);
        foreach ($contributors as $contributor) {

            // Remove white space from beginning and end of name.
            $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

            // If there is more than one space between first and last name, replace with one space.
            $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

            // check if contributor exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
            if ($existing_record) {
                $cid = $existing_record->id;
            } else {
                $cid = $DB->insert_record_raw('lor_contributor', array(
                    'id'   => null,
                    'name' => $contributor,
                ), true, false, false);
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
            $date  = date("Y-m-d H:i:s", strtotime($response->items[0]->snippet->publishedAt));
            $image = $response->items[0]->snippet->thumbnails->medium->url;

            // Insert into lor_content table.
            $record               = new stdClass();
            $record->type         = 3; // Video.
            $record->title        = $title;
            $record->image        = $image;
            $record->date_created = $date;
            $id                   = $DB->insert_record('lor_content', $record);

            // Insert into lor_content_videos table.
            $DB->execute('INSERT INTO {lor_content_videos}(content, video_id) VALUES (?, ?)', array(
                $id,
                $data->video_id,
            ));

            // Insert into categories table.
            $categories = array_filter($data->categories);
            foreach ($categories as $category) {
                $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array(
                    $id,
                    (int)$category,
                ));
            }

            // Insert into grades table.
            $grades = array_filter($data->grades);
            foreach ($grades as $grade) {
                $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
            }

            // Insert into lor_topic table and lor_content_topics table.
            $topics = preg_split('/,\s*/', $data->topics);
            foreach ($topics as $word) {

                // check if topic exists already, if not then insert
                $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
                if ($existing_record) {
                    $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
                } else {
                    $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
                    $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
                }
            }

            // insert into lor_contributor and lor_content_contributors
            $contributors = preg_split('/,\s*/', $data->contributors);
            foreach ($contributors as $contributor) {

                // Remove white space from beginning and end of name.
                $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

                // If there is more than one space between first and last name, replace with one space.
                $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

                // check if contributor exists already, if not then insert
                $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
                if ($existing_record) {
                    $cid = $existing_record->id;
                } else {
                    $cid = $DB->insert_record_raw('lor_contributor', array(
                        'id'   => null,
                        'name' => $contributor,
                    ), true, false, false);
                }


                $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array(
                    $id,
                    $cid,
                ));
            }

            return $id;
        } else {
            throw new Exception(get_string('error_no_video', 'local_lor'));
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
        $record               = new stdClass();
        $record->type         = 5;
        $record->title        = $data->title;
        $record->image        = ""; // Will be set below.
        $record->link         = null;
        $record->date_created = date("Ymd");
        $record->width        = null;
        $record->height       = null;
        $id                   = $DB->insert_record('lor_content', $record);

        // Save preview image to server.
        $file_exists = $form->save_file('image', "$CFG->dirroot/_LOR/lessons/preview_images/$id.png", true);
        if ($file_exists) {
            // Use custom image.
            $record->image = "$CFG->wwwroot/_LOR/lessons/preview_images/$id.png";
        } else {
            // Use generic preview image.
            $record->image = "$CFG->wwwroot/local/lor/images/generic_preview_images/generic_lesson_preview.png";
        }

        // Update image link in content table.
        $record->image = "$CFG->wwwroot/_LOR/games/preview_images/$id.png";
        $record->id    = $id;
        $DB->update_record('lor_content', $record);

        // Insert into lor_content_lessons table.
        $DB->execute('INSERT INTO {lor_content_lessons}(content, book_id) VALUES (?, ?)', array($id, $data->book_id));

        // Insert into categories table.
        $categories = array_filter($data->categories);
        foreach ($categories as $category) {
            $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array(
                $id,
                (int)$category,
            ));
        }

        // Insert into grades table.
        $grades = array_filter($data->grades);
        foreach ($grades as $grade) {
            $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
        }

        // Insert into lor_topic table and lor_content_topics table.
        $topics = preg_split('/,\s*/', $data->topics);
        foreach ($topics as $word) {

            // check if topic exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
            if ($existing_record) {
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            } else {
                $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            }
        }

        // insert into lor_contributor and lor_content_contributors
        $contributors = preg_split('/,\s*/', $data->contributors);
        foreach ($contributors as $contributor) {

            // Remove white space from beginning and end of name.
            $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

            // If there is more than one space between first and last name, replace with one space.
            $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

            // check if contributor exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
            if ($existing_record) {
                $cid = $existing_record->id;
            } else {
                $cid = $DB->insert_record_raw('lor_contributor', array(
                    'id'   => null,
                    'name' => $contributor,
                ), true, false, false);
            }


            $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
        }

        return $id;
    }

    /*
     * Insert a Learning Guide.
     */
    public static function insert_6($data, &$form) {
        global $DB;
        global $CFG;

        date_default_timezone_set('America/Los_Angeles'); // PST

        $form->save_file('word', $CFG->dirroot . '/_LOR/learning_guides/' . $form->get_new_filename('word'));
        $form->save_file('pdf', $CFG->dirroot . '/_LOR/learning_guides/' . $form->get_new_filename('pdf'));

        // Insert into content table.
        $record               = new stdClass();
        $record->type         = 6;
        $record->title        = $data->title;
        $record->image        = "$CFG->wwwroot/local/lor/images/generic_preview_images/generic_learning_guide_preview.png";
        $record->link         = $CFG->wwwroot . '/_LOR/learning_guides/' . $form->get_new_filename('pdf');
        $record->date_created = date("Ymd");
        $id                   = $DB->insert_record('lor_content', $record);

        // insert into categories table
        $categories = array_filter($data->categories);
        foreach ($categories as $category) {
            $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array(
                $id,
                (int)$category,
            ));
        }

        // insert into grades table
        $grades = array_filter($data->grades);
        foreach ($grades as $grade) {
            $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
        }

        // insert into lor_topic table and lor_content_topics table
        $topics = preg_split('/,\s*/', $data->topics);
        foreach ($topics as $word) {

            // check if topic exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
            if ($existing_record) {
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            } else {
                $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            }

        }

        // insert into lor_contributor and lor_content_contributors
        $contributors = preg_split('/,\s*/', $data->contributors);
        foreach ($contributors as $contributor) {

            // Remove white space from beginning and end of name.
            $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

            // If there is more than one space between first and last name, replace with one space.
            $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

            // check if contributor exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
            if ($existing_record) {
                $cid = $existing_record->id;
            } else {
                $cid = $DB->insert_record_raw('lor_contributor', array(
                    'id'   => null,
                    'name' => $contributor,
                ), true, false, false);
            }


            $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
        }

        return $id;
    }

    /*
     * Insert a Group Activity.
     */
    public static function insert_7($data, &$form) {
        global $DB;
        global $CFG;

        date_default_timezone_set('America/Los_Angeles'); // PST

        // Insert into content table.
        $record               = new stdClass();
        $record->type         = 7;
        $record->title        = $data->title;
        $record->image        = "$CFG->wwwroot/local/lor/images/generic_preview_images/generic_group_activity_preview.png";
        $record->link         = ""; // Temp, will be updated below.
        $record->date_created = date("Ymd");
        $id                   = $DB->insert_record('lor_content', $record);
        $record->id           = $id;
        $record->link         = "$CFG->wwwroot/_LOR/group_activities/WCLN_Group_Activity_$id.pdf";
        $DB->update_record('lor_content', $record);

        // Save files.
        $form->save_file('word', "$CFG->dirroot/_LOR/group_activities/WCLN_Group_Activity_$id.docx");
        $form->save_file('pdf', "$CFG->dirroot/_LOR/group_activities/WCLN_Group_Activity_$id.pdf");

        // insert into categories table
        $categories = array_filter($data->categories);
        foreach ($categories as $category) {
            $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array(
                $id,
                (int)$category,
            ));
        }

        // insert into grades table
        $grades = array_filter($data->grades);
        foreach ($grades as $grade) {
            $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
        }

        // insert into lor_topic table and lor_content_topics table
        $topics = preg_split('/,\s*/', $data->topics);
        foreach ($topics as $word) {

            // check if topic exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
            if ($existing_record) {
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            } else {
                $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
                $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
            }

        }

        // insert into lor_contributor and lor_content_contributors
        $contributors = preg_split('/,\s*/', $data->contributors);
        foreach ($contributors as $contributor) {

            // Remove white space from beginning and end of name.
            $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

            // If there is more than one space between first and last name, replace with one space.
            $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

            // check if contributor exists already, if not then insert
            $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
            if ($existing_record) {
                $cid = $existing_record->id;
            } else {
                $cid = $DB->insert_record_raw('lor_contributor', array(
                    'id'   => null,
                    'name' => $contributor,
                ), true, false, false);
            }


            $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
        }

        return $id;
    }
}
