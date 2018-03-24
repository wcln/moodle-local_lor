<?php

function local_lor_get_content($type, $platform, $categories, $grades, $order_by = "new", $keywords) {
  global $DB;

  $tables = "{lor_content}";
  $where_clause = '1=1';
  $params = array();

  // order by
  if ($order_by === "alphabetical") {
    $order_by = ' ORDER BY title ASC';
  } else if ($order_by === "new") {
    $order_by = ' ORDER BY date_created DESC';
  }

  // categories
  if(!is_null($categories)) {
    $tables .= ", {lor_content_categories}, {lor_category}";
    $where_clause .= " AND {lor_content}.id = {lor_content_categories}.content
                        AND {lor_content_categories}.category={lor_category}.id AND (";
    foreach ($categories as $cat) {
      $where_clause .= "{lor_category}.id = ? OR ";
      $params[] = $cat;
    }

    $where_clause = substr($where_clause, 0, -4) . ")";
  }

  // grades
  if(!is_null($grades)) {
    $tables .= ", {lor_content_grades}";
    $where_clause .= " AND {lor_content}.id = {lor_content_grades}.content AND (";
    foreach ($grades as $grade) {
      $where_clause .= "{lor_content_grades}.grade = ? OR ";
      $params[] = $grade;
    }

    $where_clause = substr($where_clause, 0, -4) . ")";
  }

  // type
  if(!is_null($type) && $type != -1) {
    $where_clause .= " AND {lor_content}.type = ?";
    $params[] = $type;
  }

  // platform
  if(!is_null($type) && $type == 1 && !is_null($platform)) {
    $where_clause .= ' AND {lor_content}.platform = ?';
    $params[] = $platform;
  }

  // keywords
  if (!is_null($keywords) && $keywords !== "") {
    $tables .= ", {lor_content_keywords}";
    // un comment when there are actually keywords in the database
    // $where_clause .= ' AND {lor_content_keywords}.content = {lor_content}.id AND (keyword LIKE ? OR title LIKE ?)';
    $where_clause .= ' AND title LIKE ?';
    $params[] = "%$keywords%";
    // un comment when there are actrually keywords in the database
    // $params[] = "%$keywords%";
  }

  // assemble query string
  $sql = "SELECT DISTINCT {lor_content}.id, type, title, image, link, date_created
          FROM $tables
          WHERE $where_clause $order_by";


  $content = $DB->get_records_sql($sql, $params);

  return $content;
}

function local_lor_get_content_from_id($id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_content}.id, type, {lor_type}.name, title, image, link, platform, date_created
          FROM {lor_content}, {lor_type}
          WHERE {lor_content}.id=? AND {lor_content}.type = {lor_type}.id";
  $item = $DB->get_record_sql($sql, array($id));
  return $item;
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

  return $keywords_str;
}

function local_lor_get_categories_string_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_category}.name
          FROM {lor_content}, {lor_content_categories}, {lor_category}
          WHERE {lor_content}.id = {lor_content_categories}.content
          AND {lor_content_categories}.category = {lor_category}.id
          AND {lor_content}.id = ?";

  $categories = $DB->get_records_sql($sql, array($content_id));

  $categories_str = "";
  foreach ($categories as $category) {
    $categories_str .= "$category->name, ";
  }

  if (strlen($categories_str) > 1) {
    $categories_str = substr($categories_str, 0, -2);
  }

  return $categories_str;
}

function local_lor_get_categories() {
  global $DB;

  $categories = $DB->get_records_sql('SELECT id, name FROM {lor_category}');
  return $categories;
}

function local_lor_get_platforms() {
  global $DB;

  $platforms = $DB->get_records_sql('SELECT id, name FROM {lor_platform}');
  return $platforms;
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

function local_projectspage_add_project($description, $categories, $topics, $contributors, $grades, &$form) {
  global $DB;
  global $CFG;

  date_default_timezone_set('America/Los_Angeles'); // PST

  $form->save_file('word', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('word'));
  $form->save_file('pdf', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('pdf'));
  $form->save_file('icon', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('icon'));


  $pid = str_replace(".pdf", "", $form->get_new_filename('pdf'));

  // insert into project table
  $DB->execute('INSERT INTO {lor_project} VALUES (?,?,?,?,?)', array($pid, $description, $topics, date("Ymd"), $contributors));

  // insert into project_categories table
  $categories = array_filter($categories);
  foreach ($categories as $category) {
    $record = new stdClass();
    $record->pid = $pid;
    $record->cid = (int)$category;
    $DB->insert_record('project_categories', $record);
  }

  // insert into project grades table
  $grades = array_filter($grades);
  foreach ($grades as $grade) {
    $record = new stdClass();
    $record->pid = $pid;
    $record->grade = (int)$grade;
    $DB->insert_record('project_grades', $record);
  }

  return $pid;
}
