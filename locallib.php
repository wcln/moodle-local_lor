<?php

function local_lor_get_content($type, $categories, $grades, $order_by = "new", $keywords) {
  global $DB;

  $tables = "{lor_content}";
  $where_clause = '1=1';
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


  // Keywords.
  if (!is_null($keywords) && $keywords !== "") {
    $keywords = explode(' ', $keywords);
    if (strpos($tables, '{lor_category}') !== false) {
      $tables .= ", {lor_content_keywords}, {lor_contributor}, {lor_content_contributors}";
    } else {
      $tables .= ", {lor_content_keywords}, {lor_content_categories}, {lor_category}, {lor_contributor}, {lor_content_contributors}";
    }
    $where_clause .= ' AND {lor_content_keywords}.content = {lor_content}.id
                       AND {lor_content}.id = {lor_content_categories}.content AND {lor_content_categories}.category={lor_category}.id
                       AND {lor_content}.id = {lor_content_contributors}.content AND {lor_content_contributors}.contributor={lor_contributor}.id';

    foreach ($keywords as $keyword) {
      $where_clause .= ' AND (LOWER(keyword) LIKE ? OR LOWER(title) LIKE ? OR LOWER({lor_category}.name) LIKE ? OR LOWER({lor_contributor}.name LIKE ?))';
      $keyword = strtolower($keyword);
      $params[] = "%$keyword%";
      $params[] = "%$keyword%";
      $params[] = "%$keyword%";
      $params[] = "%$keyword%";
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
  return $DB->get_record_sql($sql, array($id))->book_id;
}

function local_lor_get_video_id_from_content_id($id) {
  global $DB;

  $sql = "SELECT DISTINCT video_id FROM {lor_content_videos} WHERE content = ?";
  return $DB->get_record_sql($sql, array($id))->video_id;
}

function local_lor_get_keywords_string_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT keyword FROM {lor_content_keywords} WHERE content = ?";

  $keywords = $DB->get_records_sql($sql, array($content_id));

  $keywords_str = "";
  foreach ($keywords as $keyword) {
    $keywords_str .= "$keyword->keyword, ";
  }

  if (strlen($keywords_str) > 1) {
    $keywords_str = substr($keywords_str, 0, -2);
  }

  // Return the string with the first character of each word in uppercase.
  return ucwords($keywords_str);
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
    $contributors_str .= "$contributor->name, ";
  }

  if (strlen($contributors_str) > 1) {
    $contributors_str = substr($contributors_str, 0, -2);
  }

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

function local_lor_get_grades() {
  global $DB;

  $grades = $DB->get_records_sql('SELECT grade FROM {lor_grade}');
  return $grades;
}

function local_lor_get_related_parameters($id) {
  global $DB;

  // Get keywords.
  $keywords = $DB->get_records_sql('SELECT {lor_content_keywords}.keyword FROM {lor_content_keywords} WHERE content=?', array($id));

  // Get grades.
  $grades = $DB->get_records_sql('SELECT {lor_content_grades}.grade FROM {lor_content_grades} WHERE content=?', array($id));

  // Get categories.
  $categories = $DB->get_records_sql('SELECT {lor_content_categories}.category FROM {lor_content_categories} WHERE content=?', array($id));

  $keywords_string = "&keywords=";
  $grades_string = "";
  $categories_string = "";

  // Currently not enabled see below.
  foreach ($keywords as $keyword) {
    $keywords_string .= $keyword->keyword . '+';
  }

  foreach ($grades as $grade) {
    $grades_string .= "&grades[]=$grade->grade";
  }

  foreach ($categories as $category) {
    $categories_string .= "&categories[]=$category->category";
  }

  // Currently not including keywords...
  return "?type=-1$grades_string$categories_string";
}

function local_lor_update_item($id, $title, $topics, $categories, $grades, $contributors) {
  global $DB;

  // Update lor_content record.
  $content_record = new stdCLass();
  $content_record->id = $id;
  $content_record->title = $title;
  $DB->update_record('lor_content', $content_record);

  // Delete all keywords for the item.
  $DB->delete_records('lor_content_keywords', array('content' => $id));

  // Re-insert keywords for the item.
  $keywords = preg_split('/,\s*/', $topics);
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

  $DB->delete_records('lor_content', array('id' => $id));
}
