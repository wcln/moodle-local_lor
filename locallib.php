<?php

function local_lor_get_content($type, $categories, $grades, $order_by = "new", $topics) {
  global $DB;

  $tables = "{lor_content}";
  $where_clause = '{lor_content}.deleted=0';
  $params = array();

  // Order by.
  if ($order_by === "alphabetical") {
    $order_by = ' ORDER BY title ASC';
  } else if ($order_by === "new") {
    $order_by = ' ORDER BY date_created DESC';
  }

  // Categories.
  if(!is_null($categories)) {
    $tables .= ", {lor_content_categories}, {lor_category}";
    $where_clause .= " AND {lor_content}.id = {lor_content_categories}.content
                        AND {lor_content_categories}.category={lor_category}.id AND (";
    foreach ($categories as $category) {
      $where_clause .= "{lor_category}.id = ? OR ";
      $params[] = $category;
    }

    $where_clause = substr($where_clause, 0, -4) . ")";
  }

  // Grades.
  if(!is_null($grades) && count($grades) !== count(local_lor_get_grades())) {
    $tables .= ", {lor_content_grades}";
    $where_clause .= " AND {lor_content}.id = {lor_content_grades}.content AND (";
    foreach ($grades as $grade) {
      $where_clause .= "{lor_content_grades}.grade = ? OR ";
      $params[] = $grade;
    }

    $where_clause = substr($where_clause, 0, -4) . ")";
  }

  // Type.
  if(!is_null($type) && $type != -1) {
    $where_clause .= " AND {lor_content}.type = ?";
    $params[] = $type;
  }


  // topics.
  if (!is_null($topics) && $topics !== "") {
    $topics = explode(' ', $topics);
    if (strpos($tables, '{lor_category}') !== false) {
      $tables .= ", {lor_content_topics}, {lor_contributor}, {lor_content_contributors}";
    } else {
      $tables .= ", {lor_content_topics}, {lor_content_categories}, {lor_category}, {lor_contributor}, {lor_content_contributors}";
    }
    $where_clause .= ' AND {lor_content_topics}.content = {lor_content}.id
                       AND {lor_content}.id = {lor_content_categories}.content AND {lor_content_categories}.category={lor_category}.id
                       AND {lor_content}.id = {lor_content_contributors}.content AND {lor_content_contributors}.contributor={lor_contributor}.id';

    foreach ($topics as $topic) {
      $where_clause .= ' AND (LOWER(topic) LIKE ? OR LOWER(title) LIKE ? OR LOWER({lor_category}.name) LIKE ? OR LOWER({lor_contributor}.name LIKE ?))';
      $topic = strtolower($topic);
      $params[] = "%$topic%";
      $params[] = "%$topic%";
      $params[] = "%$topic%";
      $params[] = "%$topic%";
    }
  }

  // Assemble query string.
  $sql = "SELECT DISTINCT {lor_content}.id, type, title, image, link, date_created
          FROM $tables
          WHERE $where_clause $order_by";

  // Execute the query.
  $content = $DB->get_records_sql($sql, $params);

  return $content;
}

function local_lor_get_content_from_id($id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_content}.id, type, {lor_type}.name, title, image, link, date_created, width, height
          FROM {lor_content}, {lor_type}
          WHERE {lor_content}.id=? AND {lor_content}.type = {lor_type}.id";
  $item = $DB->get_record_sql($sql, array($id));
  return $item;
}

function local_lor_get_book_id_from_content_id($id) {
  global $DB;

  $sql = "SELECT DISTINCT book_id FROM {lor_content_lessons} WHERE content = ?";
  $record = $DB->get_record_sql($sql, array($id));
  return isset($record->book_id) ? $record->book_id : false;
}

function local_lor_get_video_id_from_content_id($id) {
  global $DB;

  $sql = "SELECT DISTINCT video_id FROM {lor_content_videos} WHERE content = ?";
  $record = $DB->get_record_sql($sql, array($id));
  return isset($record->video_id) ? $record->video_id : false;
}

function local_lor_get_topics_string_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT topic FROM {lor_content_topics} WHERE content = ?";

  $topics = $DB->get_records_sql($sql, array($content_id));

  $topics_str = "";
  foreach ($topics as $topic) {
    $topics_str .= "$topic->topic, ";
  }

  if (strlen($topics_str) > 1) {
    $topics_str = substr($topics_str, 0, -2);
  }

  // Return the string with the first character of each word in uppercase.
  return ucwords($topics_str);
}

function local_lor_get_categories_string_for_item($content_id) {

  $categories = local_lor_get_categories_for_item($content_id);

  $categories_str = "";
  foreach ($categories as $category) {
    $categories_str .= "$category->name, ";
  }

  if (strlen($categories_str) > 1) {
    $categories_str = substr($categories_str, 0, -2);
  }

  return $categories_str;
}

function local_lor_get_categories_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_category}.name, {lor_category}.id
          FROM {lor_content}, {lor_content_categories}, {lor_category}
          WHERE {lor_content}.id = {lor_content_categories}.content
          AND {lor_content_categories}.category = {lor_category}.id
          AND {lor_content}.id = ?";

  return $DB->get_records_sql($sql, array($content_id));
}

function local_lor_get_grades_string_for_item($content_id) {

  $grades = local_lor_get_grades_for_item($content_id);

  $grades_str = "";
  foreach ($grades as $grade) {
    $grades_str .= "$grade->grade, ";
  }

  if (strlen($grades_str) > 1) {
    $grades_str = substr($grades_str, 0, -2);
  }

  return $grades_str;
}

function local_lor_get_grades_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_grade}.grade
          FROM {lor_content}, {lor_content_grades}, {lor_grade}
          WHERE {lor_content}.id = {lor_content_grades}.content
          AND {lor_content_grades}.grade = {lor_grade}.grade
          AND {lor_content}.id = ?";

  return $DB->get_records_sql($sql, array($content_id));
}

function local_lor_get_contributors_string_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_contributor}.name
          FROM {lor_content}, {lor_content_contributors}, {lor_contributor}
          WHERE {lor_content}.id = {lor_content_contributors}.content
          AND {lor_content_contributors}.contributor = {lor_contributor}.id
          AND {lor_content}.id = ?";

  $contributors = $DB->get_records_sql($sql, array($content_id));

  $contributors_str = "";
  foreach ($contributors as $contributor) {

    // Remove white space from beginning and end of name.
    $contributor->name = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor->name);

    // If there is more than one space between first and last name, replace with one space.
    $contributor->name = preg_replace('/[ \t]{2,}/', ' ', $contributor->name);

    // Append the name to the string with a comma.
    $contributors_str .= "$contributor->name, ";
  }

  // Remove the trailing comma and space from the string.
  if (strlen($contributors_str) > 1) {
    $contributors_str = substr($contributors_str, 0, -2);
  }

  // Return the complete string.
  return $contributors_str;
}

function local_lor_get_categories() {
  global $DB;

  $categories = $DB->get_records_sql('SELECT id, name FROM {lor_category}');
  return $categories;
}

function local_lor_get_types() {
  global $DB;

  $types = $DB->get_records_sql('SELECT id, name FROM {lor_type}');
  return $types;
}

function local_lor_get_type_name_from_id($type_id) {
  global $DB;

  $name = $DB->get_record_sql('SELECT id, name FROM {lor_type} WHERE id=?', array($type_id));
  return $name->name;
}

function local_lor_get_grades() {
  global $DB;

  $grades = $DB->get_records_sql('SELECT grade FROM {lor_grade}');
  return $grades;
}

function local_lor_get_related_parameters($id) {
  global $DB;

  // Get topics.
  $topics = $DB->get_records_sql('SELECT {lor_content_topics}.topic FROM {lor_content_topics} WHERE content=?', array($id));

  // Get grades.
  $grades = $DB->get_records_sql('SELECT {lor_content_grades}.grade FROM {lor_content_grades} WHERE content=?', array($id));

  // Get categories.
  $categories = $DB->get_records_sql('SELECT {lor_content_categories}.category FROM {lor_content_categories} WHERE content=?', array($id));

  $topics_string = "&topics=";
  $grades_string = "";
  $categories_string = "";

  // Currently not enabled see below.
  foreach ($topics as $topic) {
    $topics_string .= $topic->topic . '+';
  }

  foreach ($grades as $grade) {
    $grades_string .= "&grades[]=$grade->grade";
  }

  foreach ($categories as $category) {
    $categories_string .= "&categories[]=$category->category";
  }

  // Currently not including topics...
  return "?type=-1$grades_string$categories_string";
}

function local_lor_update_item($id, $type, $title, $topics, $categories, $grades, $contributors, $link, $width, $height, $video_id, $book_id, &$form) {
  global $DB;
  global $CFG;

  // Update lor_content record.
  $content_record = new stdCLass();
  $content_record->id = $id;
  $content_record->title = $title;
  if (!is_null($link)) {
    $content_record->link = $link;
  }
  $content_record->width = $width;
  $content_record->height = $height;
  $DB->update_record('lor_content', $content_record);

  // Check if video ID is set and needs to be updated.
  if (!is_null($video_id)) {

    // Update video ID.
    $DB->execute('UPDATE {lor_content_videos} SET video_id = ? WHERE content = ?', array($video_id, $id));

    // Update preview image.
    $DB->execute('UPDATE {lor_content} SET image = ? WHERE id = ?', array("https://i.ytimg.com/vi/$video_id/mqdefault.jpg", $id));
  }

  // Check if book ID is set and needs to be updated.
  if (!is_null($book_id)) {

    // Update book ID.
    $DB->execute('UPDATE {lor_content_lessons} SET book_id = ? WHERE content = ?', array($book_id, $id));
  }

  // Save preview image to server (if image exists).
  if ($type == 1) { // Game.

    if ($form->get_file_content('image') !== false) {
      $form->save_file('image', "$CFG->dirroot/_LOR/games/preview_images/$id.png", true);
    }

  } else if ($type == 5) { // Lesson.

    if ($form->get_file_content('image') !== false) {
      $form->save_file('image', "$CFG->dirroot/_LOR/lessons/preview_images/$id.png", true);
      $DB->execute('UPDATE {lor_content} SET image = ? WHERE id = ?', array("$CFG->wwwroot/_LOR/lessons/preview_images/$id.png", $id));
    }

  } else if ($type == 2 || $type == 7) { // Project or Group Activity.

    // Set directory and filename depending if Project or Group Activity.
    if ($type == 2) {
      $dir = 'projects';
      $filename = "WCLN_Project_$id";
    } else if ($type == 7) {
      $dir = 'group_activities';
      $filename = "WCLN_Group_Activity_$id";
    }

    // Save all three files.
    if ($form->get_file_content('word') !== false) {
      $form->save_file('word', "$CFG->dirroot/_LOR/$dir/$filename.docx", true);
    }
    if ($form->get_file_content('pdf') !== false) {
      $form->save_file('pdf', "$CFG->dirroot/_LOR/$dir/$filename.pdf", true);
    }
    if ($form->get_file_content('icon') !== false) {
      $form->save_file('icon', "$CFG->dirroot/_LOR/$dir/$filename.png", true);

      // Since by default Group Activities have
      if ($type == 7) {
        $record = new stdClass();
        $record->id = $id;
        $record->image = "$CFG->wwwroot/_LOR/$dir/$filename.png";
        $DB->update_record('lor_content', $record);
      }
    }
  }

  // Delete all topics for the item.
  $DB->delete_records('lor_content_topics', array('content' => $id));

  // Re-insert topics for the item.
  $topics = preg_split('/,\s*/', $topics);
  foreach ($topics as $word) {

    // check if topic exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT name FROM {lor_topic} WHERE name=?', array($word));
    if($existing_record) {
      $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
    } else {
      $DB->execute('INSERT INTO {lor_topic}(name) VALUES (?)', array($word));
      $DB->execute('INSERT INTO {lor_content_topics}(content, topic) VALUES (?,?)', array($id, $word));
    }
  }

  // Delete all categories for the item.
  $DB->delete_records('lor_content_categories', array('content' => $id));

  // Re-insert categories for the item.
  $categories = array_filter($categories);
  foreach ($categories as $category) {
    $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($id, (int)$category));
  }

  // Delete all grades for the item.
  $DB->delete_records('lor_content_grades', array('content' => $id));

  // Re-insert grades for the item.
  $grades = array_filter($grades);
  foreach ($grades as $grade) {
    $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($id, (int)$grade));
  }

  // Delete all contributors for the item.
  $DB->delete_records('lor_content_contributors', array('content' => $id));

  // Re-insert contributors for the item.
  $contributors = preg_split('/,\s*/', $contributors);
  foreach ($contributors as $contributor) {

    // Remove white space from beginning and end of name.
    $contributor = preg_replace('/^[ \t]+|[ \t]+$/', '', $contributor);

    // If there is more than one space between first and last name, replace with one space.
    $contributor = preg_replace('/[ \t]{2,}/', ' ', $contributor);

    // check if contributor exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
    if($existing_record) {
      $cid = $existing_record->id;
    } else {
      $cid = $DB->insert_record_raw('lor_contributor', array('id' => null, 'name' => $contributor), true, false, false);
    }

    $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($id, $cid));
  }

}

function local_lor_delete_item($id) {
  global $DB;

  $content_record = new stdClass();
  $content_record->id = $id;
  $content_record->deleted = 1;
  $DB->update_record('lor_content', $content_record);
}

function local_lor_undo_delete($id) {
  global $DB;

  $content_record = new stdClass();
  $content_record->id = $id;
  $content_record->deleted = 0;
  $DB->update_record('lor_content', $content_record);
}

/**
 * Checks if the current user is a designer.
 * If a user is in the designer cohort, they have access to the insert from and edit functionality.
 * @return boolean          True if the logged in user is part of the designer cohort (or is a site admin).
 */
function local_lor_is_designer() {
  global $DB, $USER;

  // Return true if the user is a site administrator.
  if (is_siteadmin()) {
    return true;
  }

  // Retrieve the ID of the 'Designer' cohort.
  $designer_cohort_id = $DB->get_record('cohort', array('idnumber' => 'Designer'), 'id');

  // If the cohort exists...
  if ($designer_cohort_id) {

    // Return if the current user is a member of the 'Designer' cohort.
    return $DB->record_exists('cohort_members', array('userid' => $USER->id, 'cohortid' => $designer_cohort_id->id));

  }

  // Default: return false.
  return false;
}
